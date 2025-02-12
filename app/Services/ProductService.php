<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Enums\ViewPaths\Admin\Product;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product as ModelsProduct;
use App\Models\ProductImportFail;
use App\Traits\FileManagerTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Boolean;
use Rap2hpoutre\FastExcel\FastExcel;
use function React\Promise\all;

class ProductService
{
    use FileManagerTrait;

    public function __construct(private readonly Color $color)
    {
    }

    public function getProcessedImages(object $request): array
    {
        $colorImageSerial = [];
        $imageNames = [];
        $storage = config('filesystems.disks.default') ?? 'public';
        if ($request->has('colors_active') && $request->has('colors') && count($request['colors']) > 0) {
            foreach ($request['colors'] as $color) {
                $color_ = Str::replace('#', '', $color);
                $img = 'color_image_' . $color_;
                if ($request->file($img)) {
                    $image = $this->upload(dir: 'product/', format: 'webp', image: $request->file($img));
                    $colorImageSerial[] = [
                        'color' => $color_,
                        'image_name' => $image,
                        'storage' => $storage,
                    ];
                    $imageNames[] = $image;
                } else if ($request->has($img)) {
                    $image = $request->$img[0];
                    $colorImageSerial[] = [
                        'color' => $color_,
                        'image_name' => $image,
                        'storage' => $storage,
                    ];
                    $imageNames[] = [
                        'image_name' => $image,
                        'storage' => $storage,
                    ];
                }
            }
        }
        if ($request->file('images')) {
            foreach ($request->file('images') as $image) {
                $images = $this->upload(dir: 'product/', format: 'webp', image: $image);
                $imageNames[] = [
                    'image_name' => $images,
                    'storage' => $storage,
                ];
                if ($request->has('colors_active') && $request->has('colors') && count($request['colors']) > 0) {
                    $colorImageSerial[] = [
                        'color' => null,
                        'image_name' => $images,
                        'storage' => $storage,
                    ];
                }
            }
        }
        if (!empty($request->existing_images)) {
            foreach ($request->existing_images as $image) {
                $colorImageSerial[] = [
                    'color' => null,
                    'image_name' => $image,
                    'storage' => $storage,
                ];
                $imageNames[] = $image;
            }
        }
        return [
            'image_names' => $imageNames ?? [],
            'colored_image_names' => $colorImageSerial ?? []
        ];

    }

    public function getProcessedUpdateImages(object $request, object $product): array
    {
        $productImages = json_decode($product->images);
        $colorImageArray = [];
        $storage = config('filesystems.disks.default') ?? 'public';
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $dbColorImage = $product->color_image ? json_decode($product->color_image, true) : [];
            if (!$dbColorImage) {
                foreach ($productImages as $image) {
                    $image = is_string($image) ? $image : (array)$image;
                    $dbColorImage[] = [
                        'color' => null,
                        'image_name' =>is_array($image) ? $image['image_name'] : $image,
                        'storage' => $image['storage'] ?? $storage,
                    ];
                }
            }

            $dbColorImageFinal = [];
            if ($dbColorImage) {
                foreach ($dbColorImage as $colorImage) {
                    if ($colorImage['color']) {
                        $dbColorImageFinal[] = $colorImage['color'];
                    }
                }
            }

            $inputColors = [];
            foreach ($request->colors as $color) {
                $inputColors[] = str_replace('#', '', $color);
            }
            $colorImageArray = $dbColorImage;

            foreach ($inputColors as $color) {
                if (!in_array($color, $dbColorImageFinal)) {
                    $image = 'color_image_' . $color;
                    if ($request->file($image)) {
                        $imageName = $this->upload(dir: 'product/', format: 'webp', image: $request->file($image));
                        $productImages[] = [
                            'image_name' => $imageName,
                            'storage' => $storage,
                        ];
                        $colorImages = [
                            'color' => $color,
                            'image_name' => $imageName,
                            'storage' => $storage,
                        ];
                        $colorImageArray[] = $colorImages;
                    }
                }
            }
        }

        if ($request->file('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $this->upload(dir: 'product/', format: 'webp', image: $image);
                $productImages[] = [
                    'image_name' => $imageName,
                    'storage' => $storage,
                ];
                if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                    $colorImageArray[] = [
                        'color' => null,
                        'image_name' => $imageName,
                        'storage' => $storage,
                    ];
                }
            }
        }
        return [
            'image_names' => $productImages ?? [],
            'colored_image_names' => $colorImageArray ?? []
        ];
    }

    public function getCategoriesArray(object $request): array
    {
        $category = [];
        if ($request['category_id'] != null) {
            $category[] = [
                'id' => $request['category_id'],
                'position' => 1,
            ];
        }
        if ($request['sub_category_id'] != null) {
            $category[] = [
                'id' => $request['sub_category_id'],
                'position' => 2,
            ];
        }
        if ($request['sub_sub_category_id'] != null) {
            $category[] = [
                'id' => $request['sub_sub_category_id'],
                'position' => 3,
            ];
        }
        return $category;
    }

    public function getColorsObject(object $request): bool|string
    {
        if ($request->has('colors_active') && $request->has('colors') && count($request['colors']) > 0) {
            $colors = $request['product_type'] == 'physical' ? json_encode($request['colors']) : json_encode([]);
        } else {
            $colors = json_encode([]);
        }
        return $colors;
    }

    public function getSlug(object $request): string
    {
        return Str::slug($request['name'][array_search('en', $request['lang'])], '-') . '-' . Str::random(6);
    }

    public function getChoiceOptions(object $request): array
    {
        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;
                $item['name'] = 'choice_' . $no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', $request[$str]));
                $choice_options[] = $item;
            }
        }
        return $choice_options;
    }

    public function getOptions(object $request): array
    {
        $options = [];
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $options[] = $request->colors;
        }
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $request[$name]);
                $options[] = explode(',', $my_str);
            }
        }
        return $options;
    }

    public function getCombinations(array $arrays): array
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public function getSkuCombinationView(object $request): string
    {
        $colorsActive = ($request->has('colors_active') && $request->has('colors') && count($request['colors']) > 0) ? 1 : 0;
        $unitPrice = $request['unit_price'];
        $productName = $request['name'][array_search('en', $request['lang'])];
        $options = $this->getOptions(request: $request);
        $combinations = $this->getCombinations(arrays: $options);

        return view(Product::SKU_COMBINATION[VIEW], compact('combinations', 'unitPrice', 'colorsActive', 'productName'))->render();
    }

    public function getVariations(object $request, array $combinations): array
    {
        $variations = [];
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request['colors']) > 0) {
                            $color_name = $this->color->where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = currencyConverter(abs($request['price_' . str_replace('.', '_', $str)]));
                $item['sku'] = $request['sku_' . str_replace('.', '_', $str)];
                $item['qty'] = abs($request['qty_' . str_replace('.', '_', $str)]);
                $variations[] = $item;
            }
        }

        return $variations;
    }

    public function getTotalQuantity(array $variations): int
    {
        $sum = 0;
        foreach ($variations as $item) {
            if (isset($item['qty'])) {
                $sum += $item['qty'];
            }
        }
        return $sum;
    }

    public function getCategoryDropdown(object $request, object $categories): string
    {
        $dropdown = '<option value="' . 0 . '" disabled selected>---' . translate("Select") . '---</option>';
        foreach ($categories as $row) {
            if ($row->id == $request['sub_category']) {
                $dropdown .= '<option value="' . $row->id . '" selected >' . $row->defaultName . '</option>';
            } else {
                $dropdown .= '<option value="' . $row->id . '">' . $row->defaultName . '</option>';
            }
        }

        return $dropdown;
    }

    public function deleteImages(object $product): bool
    {
        foreach (json_decode($product['images'], true) as $image) {
            $this->delete(filePath: '/product/'.(isset($image['image_name']) ? $image['image_name'] : $image));
        }
        $this->delete(filePath: '/product/thumbnail/' . $product['thumbnail']);

        return true;
    }

    public function deleteImage(object $request, object $product): array
    {
        $colors = json_decode($product['colors']);
        $color_image = json_decode($product['color_image']);
        $images = [];
        $color_images = [];
        if ($colors && $color_image) {
            foreach ($color_image as $img) {
                if ($img->color != $request['color'] && $img?->image_name != $request['name']) {
                    $color_images[] = [
                        'color' => $img->color != null ? $img->color : null,
                        'image_name' => $img->image_name,
                        'storage' => $img?->storage ?? 'public',
                    ];
                }
            }
        }

        foreach (json_decode($product['images']) as $image) {
            $imageName =  $image?->image_name ?? $image;
            if ($imageName != $request['name']) {
                $images[] = $image;
            }
        }

        return [
            'images' => $images,
            'color_images' => $color_images
        ];
    }

    public function getAddProductData(object $request, string $addedBy): array
    {
        $storage = config('filesystems.disks.default') ?? 'public';
        $processedImages = $this->getProcessedImages(request: $request); //once the images are processed do not call this function again just use the variable
        $combinations = $this->getCombinations($this->getOptions(request: $request));
        $variations = $this->getVariations(request: $request, combinations: $combinations);
        $stockCount = count($combinations[0]) > 0 ? $this->getTotalQuantity(variations: $variations) : (integer)$request['current_stock'];

        $digitalFile = '';
        if ($request['product_type'] == 'digital' && $request['digital_product_type'] == 'ready_product' && $request['digital_file_ready']) {
            $digitalFile = $this->fileUpload(dir: 'product/digital-product/', format: $request['digital_file_ready']->getClientOriginalExtension(), file: $request['digital_file_ready']);
        }

        $digitalFileOptions = $this->getDigitalVariationOptions(request: $request);
        $digitalFileCombinations = $this->getDigitalVariationCombinations(arrays: $digitalFileOptions);
        $feature_arr = [];
        //Feature process
        foreach ($request['features'] as $key=> $value) {
            $optionKey = 'feature-' . $key;
            if (!empty($value)) {
                $feature_arr[] = [$optionKey => $value];
            }
        }

        return [
            'added_by' => $addedBy,
            'user_id' => $addedBy == 'admin' ? auth('admin')->id() : auth('seller')->id(),
            'name' => $request['name'][array_search('en', $request['lang'])],
            'code' => $request['code'],
            'slug' => $this->getSlug($request),
            'category_ids' => json_encode($this->getCategoriesArray(request: $request)),
            'category_id' => $request['category_id'],
            'sub_category_id' => $request['sub_category_id'],
            'sub_sub_category_id' => $request['sub_sub_category_id'],
            'brand_id' => $request['brand_id'],
            'weight' => $request['weight'],
            'origin' => $request['origin'],
            'hsn_sac_code' => $request['hsn_sac_code'],
            'unit' => $request['product_type'] == 'physical' ? $request['unit'] : null,
            'digital_product_type' => $request['product_type'] == 'digital' ? $request['digital_product_type'] : null,
            'digital_file_ready' => $digitalFile,
            'digital_file_ready_storage_type' => $digitalFile ? $storage : null,
            'product_type' => $request['product_type'],
            'details' => $request['description'][array_search('en', $request['lang'])],
            'colors' => $this->getColorsObject(request: $request),
            'choice_options' => $request['product_type'] == 'physical' ? json_encode($this->getChoiceOptions(request: $request)) : json_encode([]),
            'variation' => $request['product_type'] == 'physical' ? json_encode($variations) : json_encode([]),
            'digital_product_file_types' => $request->has('extensions_type') ? $request->get('extensions_type') : [],
            'digital_product_extensions' => $digitalFileCombinations,
            'unit_price' => currencyConverter(amount: $request['unit_price']),
            'purchase_price' => 0,
            'features' => json_encode($feature_arr),
            'ean' => $request['ean_code'] ?? Null,
            'length' => $request['length'] ?? Null,
            'width' => $request['width'] ?? Null,
            'height' => $request['height'] ?? Null,
            'tax' => $request['tax_type'] == 'flat' ? currencyConverter(amount: $request['tax']) : $request['tax'],
            'tax_type' => $request->get('tax_type', 'percent'),
            'tax_model' => $request['tax_model'],
            'discount' => $request['discount_type'] == 'flat' ? currencyConverter(amount: $request['discount']) : $request['discount'],
            'discount_type' => $request['discount_type'],
            'attributes' => $request['product_type'] == 'physical' ? json_encode($request['choice_attributes']) : json_encode([]),
            'current_stock' => $request['product_type'] == 'physical' ? abs($stockCount) : 999999999,
            'minimum_order_qty' => $request['minimum_order_qty'],
            'video_provider' => 'youtube',
            'video_url' => $request['video_url'],
            'status' => $addedBy == 'admin' ? 1 : 0,
            'request_status' => $addedBy == 'admin' ? 1 : (getWebConfig(name: 'new_product_approval') == 1 ? 0 : 1),
            'shipping_cost' => $request['product_type'] == 'physical' ? currencyConverter(amount: $request['shipping_cost']) : 0,
            'multiply_qty' => ($request['product_type'] == 'physical') ? ($request['multiply_qty'] == 'on' ? 1 : 0) : 0, //to be changed in form multiply_qty
            'color_image' => json_encode($processedImages['colored_image_names']),
            'images' => json_encode($processedImages['image_names']),
            'thumbnail' => $request->has('image') ? $this->upload(dir: 'product/thumbnail/', format: 'webp', image: $request['image']) : $request->existing_thumbnail,
            'thumbnail_storage_type' => $request->has('image') ? $storage : null,
            'meta_title' => $request['meta_title'],
            'meta_description' => $request['meta_description'],
            'meta_image' => $request->has('meta_image') ? $this->upload(dir: 'product/meta/', format: 'webp', image: $request['meta_image']) : $request->existing_meta_image,
        ];
    }

    public function getUpdateProductData(object $request, object $product, string $updateBy): array
    {
        $storage = config('filesystems.disks.default') ?? 'public';
        $processedImages = $this->getProcessedUpdateImages(request: $request, product: $product);
        $combinations = $this->getCombinations($this->getOptions(request: $request));
        $variations = $this->getVariations(request: $request, combinations: $combinations);
        $stockCount = count($combinations[0]) > 0 ? $this->getTotalQuantity(variations: $variations) : (integer)$request['current_stock'];

        if ($request->has('extensions_type') && $request->has('digital_product_variant_key')) {
            $digitalFile = null;
        } else {
            $digitalFile = $product['digital_file_ready'];
        }
        if ($request['product_type'] == 'digital') {
            if ($request['digital_product_type'] == 'ready_product' && $request->hasFile('digital_file_ready')) {
                $digitalFile = $this->update(dir: 'product/digital-product/', oldImage: $product['digital_file_ready'], format: $request['digital_file_ready']->getClientOriginalExtension(), image: $request['digital_file_ready'], fileType: 'file');
            } elseif (($request['digital_product_type'] == 'ready_after_sell') && $product['digital_file_ready']) {
                $digitalFile = null;
                // $this->delete(filePath: 'product/digital-product/' . $product['digital_file_ready']);
            }
        } elseif ($request['product_type'] == 'physical' && $product['digital_file_ready']) {
            $digitalFile = null;
            // $this->delete(filePath: 'product/digital-product/' . $product['digital_file_ready']);
        }

        $digitalFileOptions = $this->getDigitalVariationOptions(request: $request);
        $digitalFileCombinations = $this->getDigitalVariationCombinations(arrays: $digitalFileOptions);
        $feature_arr = [];
        //Feature process
        foreach ($request['features'] as $key=> $value) {
            $optionKey = 'feature-' . $key;
            if (!empty($value)) {
                $feature_arr[] = [$optionKey => $value];
            }
        }
        $dataArray = [
            'name' => $request['name'][array_search('en', $request['lang'])],
            'code' => $request['code'],
            'product_type' => $request['product_type'],
            'category_ids' => json_encode($this->getCategoriesArray(request: $request)),
            'category_id' => $request['category_id'],
            'sub_category_id' => $request['sub_category_id'],
            'sub_sub_category_id' => $request['sub_sub_category_id'],
            'brand_id' => $request['brand_id'],
            'unit' => $request['product_type'] == 'physical' ? $request['unit'] : null,
            'digital_product_type' => $request['product_type'] == 'digital' ? $request['digital_product_type'] : null,
            'details' => $request['description'][array_search('en', $request['lang'])],
            'colors' => $this->getColorsObject(request: $request),
            'choice_options' => $request['product_type'] == 'physical' ? json_encode($this->getChoiceOptions(request: $request)) : json_encode([]),
            'variation' => $request['product_type'] == 'physical' ? json_encode($variations) : json_encode([]),
            'digital_product_file_types' => $request->has('extensions_type') ? $request->get('extensions_type') : [],
            'digital_product_extensions' => $digitalFileCombinations,
            'unit_price' => currencyConverter(amount: $request['unit_price']),
            'purchase_price' => 0,
            'tax' => $request['tax_type'] == 'flat' ? currencyConverter(amount: $request['tax']) : $request['tax'],
            'tax_type' => $request['tax_type'],
            'tax_model' => $request['tax_model'],
            'discount' => $request['discount_type'] == 'flat' ? currencyConverter(amount: $request['discount']) : $request['discount'],
            'discount_type' => $request['discount_type'],
            'attributes' => $request['product_type'] == 'physical' ? json_encode($request['choice_attributes']) : json_encode([]),
            'current_stock' => $request['product_type'] == 'physical' ? abs($stockCount) : 999999999,
            'minimum_order_qty' => $request['minimum_order_qty'],
            'video_provider' => 'youtube',
            'video_url' => $request['video_url'],
            'shipping_cost' => $request['product_type'] == 'physical' ? (getWebConfig(name: 'product_wise_shipping_cost_approval') == 1 && $product->shipping_cost == currencyConverter($request->shipping_cost) ? $product->shipping_cost : currencyConverter(amount: $request['shipping_cost'])) : 0,
            'multiply_qty' => ($request['product_type'] == 'physical') ? ($request['multiply_qty'] == 'on' ? 1 : 0) : 0,
            'color_image' => json_encode($processedImages['colored_image_names']),
            'images' => json_encode($processedImages['image_names']),
            'features' => json_encode($feature_arr),
            'ean' => $request['ean_code'] ?? Null,
            'length' => $request['length'] ?? Null,
            'width' => $request['width'] ?? Null,
            'height' => $request['height'] ?? Null,
            'digital_file_ready' => $digitalFile,
            'digital_file_ready_storage_type' => $request->has('digital_file_ready') ? $storage : $product['digital_file_ready_storage_type'],
            'meta_title' => $request['meta_title'],
            'meta_description' => $request['meta_description'],
            'meta_image' => $request->file('meta_image') ? $this->update(dir: 'product/meta/', oldImage: $product['meta_image'], format: 'png', image: $request['meta_image']) : $product['meta_image'],
        ];

        if ($request->file('image')) {
            $dataArray += [
                'thumbnail' => $this->update(dir: 'product/thumbnail/', oldImage: $product['thumbnail'], format: 'webp', image: $request['image'], fileType: 'image'),
                'thumbnail_storage_type' => $storage
            ];
        }

        if ($updateBy == 'seller' && getWebConfig(name: 'product_wise_shipping_cost_approval') == 1 && $product->shipping_cost != currencyConverter($request->shipping_cost)) {
            $dataArray += [
                'temp_shipping_cost' => currencyConverter($request->shipping_cost),
                'is_shipping_cost_updated' => 0
            ];
        }

        if ($updateBy == 'seller' && $product->request_status == 2) {
            $dataArray += [
                'request_status' => 0
            ];
        }

        if ($updateBy == 'admin' && $product->added_by == 'seller' && $product->request_status == 2) {
            $dataArray += [
                'request_status' => 1
            ];
        }

        return $dataArray;
    }

    public function getImportBulkProductData(object $request, string $addedBy): array
    {
        try {
            $collections = (new FastExcel)->import($request->file('products_file'));
        } catch (\Exception $exception) {
            return [
                'status' => false,
                'message' => translate('you_have_uploaded_a_wrong_format_file') . ',' . translate('please_upload_the_right_file'),
                'products' => []
            ];
        }

        $columnKey = [
            'name',
            'category_id',
            'sub_category_id',
            'sub_sub_category_id',
            'brand_id', 'unit',
            'minimum_order_qty',
            'refundable',
            'youtube_video_url',
            'unit_price',
//            'purchase_price',
            'tax',
            'discount',
            'discount_type',
            'current_stock',
            'details',
            'thumbnail'
        ];
        $skip = ['youtube_video_url', 'details', 'thumbnail'];

        if (count($collections) <= 0) {
            return [
                'status' => false,
                'message' => translate('you_need_to_upload_with_proper_data'),
                'products' => []
            ];
        }

        $products = [];
        foreach ($collections as $collection) {
            foreach ($collection as $key => $value) {
                if ($key != "" && !in_array($key, $columnKey)) {
                    return [
                        'status' => false,
                        'message' => translate('Please_upload_the_correct_format_file'),
                        'products' => []
                    ];
                }

                if ($key != "" && $value === "" && !in_array($key, $skip)) {
                    return [
                        'status' => false,
                        'message' => translate('Please fill ' . $key . ' fields'),
                        'products' => []
                    ];
                }
            }
            $thumbnail = explode('/', $collection['thumbnail']);

            $products[] = [
                'name' => $collection['name'],
                'slug' => Str::slug($collection['name'], '-') . '-' . Str::random(6),
                'category_ids' => json_encode([['id' => (string)$collection['category_id'], 'position' => 1], ['id' => (string)$collection['sub_category_id'], 'position' => 2], ['id' => (string)$collection['sub_sub_category_id'], 'position' => 3]]),
                'category_id' => $collection['category_id'],
                'sub_category_id' => $collection['sub_category_id'],
                'sub_sub_category_id' => $collection['sub_sub_category_id'],
                'brand_id' => $collection['brand_id'],
                'unit' => $collection['unit'],
                'minimum_order_qty' => $collection['minimum_order_qty'],
                'refundable' => $collection['refundable'],
                'unit_price' => $collection['unit_price'],
                'purchase_price' => 0,
                'tax' => $collection['tax'],
                'discount' => $collection['discount'],
                'discount_type' => $collection['discount_type'],
                'shipping_cost' => 0,
                'current_stock' => $collection['current_stock'],
                'details' => $collection['details'],
                'video_provider' => 'youtube',
                'video_url' => $collection['youtube_video_url'],
                'images' => json_encode(['def.png']),
                'thumbnail' => $thumbnail[1] ?? $thumbnail[0],
                'status' => 0,
                'request_status' => 1,
                'colors' => json_encode([]),
                'attributes' => json_encode([]),
                'choice_options' => json_encode([]),
                'variation' => json_encode([]),
                'featured_status' => 0,
                'added_by' => $addedBy,
                'user_id' => $addedBy == 'admin' ? auth('admin')->id() : auth('seller')->id(),
                'created_at' => now(),
            ];
        }

        return [
            'status' => true,
            'message' => count($products) . ' - ' . translate('products_imported_successfully'),
            'products' => $products
        ];
    }
    public function setStorageConnectionEnvironment(): void
    {
        $storageConnectionType = $this->getWebConfig(name: 'storage_connection_type') ?? 'public';
        Config::set('filesystems.disks.default', $storageConnectionType);
        $storageConnectionS3Credential = $this->getWebConfig(name: 'storage_connection_s3_credential');

        if ($storageConnectionType == 's3' && !empty($storageConnectionS3Credential)) {
            Config::set('filesystems.disks.' . $storageConnectionType, $storageConnectionS3Credential);
        }
    }

    function getWebConfig($name): string|object|array|null
    {
        $config = null;
        $check = ['currency_model', 'currency_symbol_position', 'system_default_currency', 'language', 'company_name', 'decimal_point_settings', 'product_brand', 'digital_product', 'company_email', 'business_mode', 'storage_connection_type', 'company_web_logo'];

        if (in_array($name, $check) == true && session()->has($name)) {
            $config = session($name);
        } else {
            $data = BusinessSetting::where(['type' => $name])->first();
            if (isset($data)) {
                $arrayOfCompaniesValue = ['company_web_logo', 'company_mobile_logo', 'company_footer_logo', 'company_fav_icon', 'loader_gif'];
                $arrayOfBanner = ['shop_banner', 'offer_banner', 'bottom_banner'];
                $mergeArray = array_merge($arrayOfCompaniesValue, $arrayOfBanner);
                $config = json_decode($data['value'], true);
                if (in_array($name, $mergeArray)) {
                    $folderName = in_array($name, $arrayOfCompaniesValue) ? 'company' : 'shop';
                    $value = isset($config['image_name']) ? $config : ['image_name' => $data['value'], 'storage' => 'public'];
                    $config = storageLink($folderName, $value['image_name'], $value['storage']);
                }
                if (is_null($config)) {
                    $config = $data['value'];
                }
            }
            if (in_array($name, $check) == true) {
                session()->put($name, $config);
            }
        }
        return $config;
    }
    public function getNewImportBulkProductData($row, $addedBy,$jobId=null)
    {
        $products = [];
        $this->setStorageConnectionEnvironment();
        try {
            $storage = config('filesystems.disks.default') ?? 'public';
            // Check if there are any collections
            if (count($row) <= 0) {
                throw new \Exception('No data Found In Row');
            }

            $choiceOptions = [];
            $attribute_ids = [];
            $variations = [];
            $images_arr = [];
            $feature_arr = [];
            $thumbnailFileName = null;

            // Validate required columns
            $requiredColumns = ['product_name', 'category_name', 'brand_name', 'product_price'];
            foreach ($requiredColumns as $column) {
                if (empty($row[$column])) {
                    throw new \Exception('Missed Required Field: ' . $column);
                }
            }

            //Feature process
            foreach (range(1, 10) as $i) {
                $optionKey = 'feature-' . $i;
                if (!empty($row[$optionKey])) {
                    $feature_arr[] = [$optionKey => $row[$optionKey]];
                }
            }
            Log::info('product_import');
            // Handle the thumbnail image
            if (isset($row['thumbnail']))
            {
                $img_url = $row['thumbnail'];
                $path = parse_url($img_url, PHP_URL_PATH);
                $thumbnailFileName = Null;

                $response = Http::get($img_url);
                if ($response->successful()) {
                    $imageContents = $response->body();
                    $thumbnailFileName = $this->upload(dir:'product/thumbnail/', format: 'webp', image: $imageContents, extension: 'jpg');
                } else {
                    throw new \Exception('Image Not Available: ' . $img_url);
                }
            }

            // Process options
            // for ($i = 1; $i <= 4; $i++) {
            //     $optionNameKey = 'option_name_' . $i;
            //     $optionValueKey = 'option_value_' . $i;

            //     if (isset($row[$optionNameKey]) && $row[$optionNameKey] !== "") {
            //         $options = array_map('trim', explode(',', $row[$optionValueKey]));
            //         $attr = Attribute::where('name', $row[$optionNameKey])->first();

            //         if ($attr) {
            //             $attribute_ids[] = $attr->id;
            //             $choiceOptions[] = [
            //                 'name' => 'choice_' . $i,
            //                 'title' => $row[$optionNameKey],
            //                 'options' => $options,
            //             ];
            //         }
            //     }
            // }

            // Handle additional images
            for ($i = 1; $i <= 10; $i++) {
                $optionNameKey = 'image_url_' . $i;

                if (isset($row[$optionNameKey]) && $row[$optionNameKey] !== "") {
                    $img_url = $row[$optionNameKey];
                    $path = parse_url($img_url, PHP_URL_PATH);
                    $imagesFileName = Null;

                    $response = Http::get($img_url);
                    if ($response->successful()) {
                        $imageContents = $response->body();
                        $imagesFileName = $this->upload(dir:'product/', format: 'webp', image: $imageContents, extension: 'jpg');

                        $images_arr[] = $imagesFileName;
                    } else {
                        throw new \Exception('Image fail download: ' . $img_url);
                    }
                }
            }

            // Find existing category, sub-category, and brand
            $category = Category::firstOrCreate([
                'name' => trim(ucfirst($row['category_name']))
            ],[
                'name' => trim(ucfirst($row['category_name'])),
                'slug' => Str::slug(trim(strtolower($row['category_name']))),
                'home_status' => 0
            ]);

            if (!$category) {
                throw new \Exception('Category Not Available: ' . $row['category_name']);
            }
            $categories = explode(",", $row['sub_category_name']);

            if (!empty($categories) && isset($categories[0])) {
                $sub_category = Category::firstOrCreate([
                        'name' => trim(ucfirst($categories[0])),
                        'parent_id' => $category->id,
                        'position' => 1
                    ],[
                        'name' => trim(ucfirst($categories[0])),
                        'slug' => Str::slug(trim(strtolower($categories[0]))),
                        'parent_id' => $category->id,
                        'position' => 1,
                        'home_status' => 0
                    ]);
                if (!$sub_category) {
                    throw new \Exception('Sub-Category Not Available: ' . $categories[0]);
                }

                // Check if sub-sub-category exists in the array before accessing it
                if (isset($categories[1])) {
                    $sub_sub_category = Category::firstOrCreate([
                        'name' => trim(ucfirst($categories[1])),
                        'parent_id' => $sub_category->id,
                        'position' => 2
                    ],[
                        'name' => trim(ucfirst($categories[1])),
                        'slug' => Str::slug(trim(strtolower($categories[1]))),
                        'parent_id' => $sub_category->id,
                        'position' => 2,
                        'home_status' => 0
                    ]);
                    if (!$sub_sub_category) {
                        throw new \Exception('Sub-Sub-Category Not Available: ' . $categories[1]);
                    }
                }
            }

            $brand = Brand::firstOrCreate([
                'name' => trim(ucfirst($row['brand_name']))
            ],[
                'name' => trim(ucfirst($row['brand_name']))
            ]);

            if (!$brand) {
                throw new \Exception('Brand Not Available: ' . $row['brand_name']);
            }

            $category_ids = [];

            if (isset($category)) {
                $category_ids[] = ['id' => (string)$category->id, 'position' => 1];
            }

            if (isset($sub_category)) {
                $category_ids[] = ['id' => (string)$sub_category->id, 'position' => 2];
            }

            if (isset($sub_sub_category)) {
                $category_ids[] = ['id' => (string)$sub_sub_category->id, 'position' => 3];
            }
            // Prepare product data for insertion
            $productData = [
                'name' => $row['product_name'],
                'slug' => Str::slug($row['product_name'], '-') . '-' . Str::random(6),
                'category_ids' => json_encode($category_ids),
                'category_id' => $category->id,
                'sub_category_id' => $sub_category->id ?? Null,
                'sub_sub_category_id' => $sub_sub_category->id ?? Null,
                'brand_id' => $brand->id,
                'code' => $row['sku'] ?? Null,
                'weight' => $row['weight'] ?? Null,
                'origin' => $row['origin'] ?? Null,
                'hsn_sac_code' => $row['hsn_sac_code'] ?? Null,
                'unit' => $row['unit'] ?? 'pc',
                'minimum_order_qty' => $row['minimum_order_qty'],
                'unit_price' => $row['product_price'],
                'purchase_price' => 0,
                'tax' => $row['tax'] ?? Null,
                'tax_type' => isset($row['tax']) ? 'percent' : Null,
                'discount' => $row['discount'],
                'discount_type' => strtolower($row['discount_type']),
                'shipping_cost' => 0,
                'current_stock' => $row['current_stock'] ?? 1,
                'details' => $row['product_description'],
                'ean' => $row['ean'] ?? Null,
                'length' => $row['length'] ?? Null,
                'width' => $row['width'] ?? Null,
                'height' => $row['height'] ?? Null,
                'features' => json_encode($feature_arr),
                'meta_title' => $row['meta_title'],
                'meta_description' => $row['meta_description'],
                'thumbnail_storage_type' => 's3',
                'video_provider' => 'youtube',
                'video_url' => $row['youtube_video_code1'],
                'images' => json_encode($images_arr),
                'thumbnail' => $thumbnailFileName,
                'status' => 1,
                'request_status' => 1,
                'colors' => json_encode([]),
                'attributes' => json_encode([]),
                'choice_options' => json_encode([]),
                'variation' => json_encode([]),
                'featured_status' => 0,
                'added_by' => $addedBy,
                'user_id' => $addedBy == 'admin' ? auth('admin')->id() : auth('seller')->id(),
                'created_at' => now(),
            ];

            // Save product to database
            $product = ModelsProduct::create($productData);
            $products[] = $product;

        } catch (\Exception $exception) {
            DB::table('product_import_fails')->insert(['error'=>$exception->getMessage(),'data_complete'=>json_encode($row),'product_name'=>$row['product_name']??'N/A','job_id'=>$jobId]);
        }
    }
    public function checkLimitedStock(object $products): bool
    {
        foreach ($products as $product) {
            if ($product['product_type'] == 'physical' && $product['current_stock'] < (int)getWebConfig('stock_limit')) {
                return true;
            }
        }
        return false;
    }

    public function getAddProductDigitalVariationData(object $request, object|array $product): array
    {
        $digitalFileOptions = $this->getDigitalVariationOptions(request: $request);
        $digitalFileCombinations = $this->getDigitalVariationCombinations(arrays: $digitalFileOptions);

        $digitalFiles = [];
        foreach ($digitalFileCombinations as $combinationKey => $combination) {
            foreach ($combination as $item) {
                $string = $combinationKey . '-' . str_replace(' ', '', $item);
                $uniqueKey = strtolower(str_replace('-', '_', $string));
                $fileItem = $request->file('digital_files.' . $uniqueKey);
                $uploadedFile = '';
                if ($fileItem) {
                    $uploadedFile = $this->fileUpload(dir: 'product/digital-product/', format: $fileItem->getClientOriginalExtension(), file: $fileItem);
                }
                $digitalFiles[] = [
                    'product_id' => $product->id,
                    'variant_key' => $request->input('digital_product_variant_key.' . $uniqueKey),
                    'sku' => $request->input('digital_product_sku.' . $uniqueKey),
                    'price' => currencyConverter(amount: $request->input('digital_product_price.' . $uniqueKey)),
                    'file' => $uploadedFile,
                ];
            }
        }
        return $digitalFiles;
    }

    public function getDigitalVariationCombinationView(object $request, object $product = null): string
    {
        $productName = $request['name'][array_search('en', $request['lang'])];
        $unitPrice = $request['unit_price'];
        $options = $this->getDigitalVariationOptions(request: $request);
        $combinations = $this->getDigitalVariationCombinations(arrays: $options);
        $digitalProductType = $request['digital_product_type'];
        $generateCombination = $this->generateDigitalVariationCombination(request: $request, combinations: $combinations, product: $product);
        return view(Product::DIGITAL_VARIATION_COMBINATION[VIEW], compact('generateCombination', 'unitPrice', 'productName', 'digitalProductType', 'request'))->render();
    }

    public function generateDigitalVariationCombination(object|array $request, object|array $combinations, object|array|null $product): array
    {
        $productName = $request['name'][array_search('en', $request['lang'])];
        $unitPrice = $request['unit_price'];

        $generateCombination = [];
        foreach ($combinations as $combinationKey => $combination) {
            foreach ($combination as $item) {
                $sku = '';
                foreach (explode(' ', $productName) as $value) {
                    $sku .= substr($value, 0, 1);
                }
                $string = $combinationKey . '-' . preg_replace('/\s+/', '-', $item);
                $sku .= '-' . $combinationKey . '-' . str_replace(' ', '', $item);
                $uniqueKey = strtolower(str_replace('-', '_', $string));
                if ($product && $product->digitalVariation && count($product->digitalVariation) > 0) {
                    $productDigitalVariationArray = [];
                    foreach ($product->digitalVariation->toArray() as $variationKey => $digitalVariation) {
                        $productDigitalVariationArray[$digitalVariation['variant_key']] = $digitalVariation;
                    }
                    if (key_exists($string, $productDigitalVariationArray)) {
                        $generateCombination[] = [
                            'product_id' => $product['id'],
                            'unique_key' => $uniqueKey,
                            'variant_key' => $productDigitalVariationArray[$string]['variant_key'],
                            'sku' => $productDigitalVariationArray[$string]['sku'],
                            'price' => $productDigitalVariationArray[$string]['price'],
                            'file' => $productDigitalVariationArray[$string]['file'],
                        ];
                    } else {
                        $generateCombination[] = [
                            'product_id' => $product['id'],
                            'unique_key' => $uniqueKey,
                            'variant_key' => $string,
                            'sku' => $sku,
                            'price' => $unitPrice,
                            'file' => '',
                        ];
                    }
                } else {
                    $generateCombination[] = [
                        'product_id' => '',
                        'unique_key' => $uniqueKey,
                        'variant_key' => $string,
                        'sku' => $sku,
                        'price' => $unitPrice,
                        'file' => '',
                    ];
                }
            }
        }
        return $generateCombination;
    }

    public function getDigitalVariationOptions(object $request): array
    {
        $options = [];
        if ($request->has('extensions_type')) {
            foreach ($request->extensions_type as $type) {
                $name = 'extensions_options_' . $type;
                $my_str = implode('|', $request[$name]);
                $options[$type] = explode(',', $my_str);
            }
        }
        return $options;
    }

    public function getDigitalVariationCombinations(array $arrays): array
    {
        $result = [];
        foreach ($arrays as $arrayKey => $array) {
            foreach ($array as $key => $value) {
                if ($value) {
                    $result[$arrayKey][] = $value;
                }
            }
        }
        return $result;
    }

    public function getProductSEOData(object $request, object|null $product = null, string $action = null): array
    {
        if ($product) {
            if ($request->file('meta_image')) {
                $metaImage = $this->update(dir: 'product/meta/', oldImage: $product['meta_image'], format: 'png', image: $request['meta_image']);
            } elseif (!$request->file('meta_image') && $request->file('image') && $action == 'add') {
                $metaImage = $this->upload(dir: 'product/meta/', format: 'webp', image: $request['image']);
            } else {
                $metaImage = $product?->seoInfo?->image ?? $product['meta_image'];            }
        } else {
            if ($request->file('meta_image')) {
                $metaImage = $this->upload(dir: 'product/meta/', format: 'webp', image: $request['meta_image']);
            } elseif (!$request->file('meta_image') && $request->file('image') && $action == 'add') {
                $metaImage = $this->upload(dir: 'product/meta/', format: 'webp', image: $request['image']);
            }
        }
        return [
            "product_id" => $product['id'],
            "title" => $request['meta_title'] ?? ($product ? $product['meta_title'] : null),
            "description" => $request['meta_description'] ?? ($product ? $product['meta_description'] : null),
            "index" => $request['meta_index'] ? '' : 'noindex',
            "no_follow" => $request['meta_no_follow'] ? 'nofollow' : '',
            "no_image_index" => $request['meta_no_image_index'] ? 'noimageindex' : '',
            "no_archive" => $request['meta_no_archive'] ? 'noarchive' : '',
            "no_snippet" => $request['meta_no_snippet'] ?? 0,
            "max_snippet" => $request['meta_max_snippet'] ?? 0,
            "max_snippet_value" => $request['meta_max_snippet_value'] ?? 0,
            "max_video_preview" => $request['meta_max_video_preview'] ?? 0,
            "max_video_preview_value" => $request['meta_max_video_preview_value'] ?? 0,
            "max_image_preview" => $request['meta_max_image_preview'] ?? 0,
            "max_image_preview_value" => $request['meta_max_image_preview_value'] ?? 0,
            "image" => $metaImage ?? ($product ? $product['meta_image'] : null),
            "created_at" => now(),
            "updated_at" => now(),
        ];
    }
}
