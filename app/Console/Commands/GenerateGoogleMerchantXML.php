<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateGoogleMerchantXML extends Command
{
    // Command signature and description
    protected $signature = 'generate:merchant-xml';
    protected $description = 'Generate Google Merchant Center XML feed';

    public function handle()
    {
        $batchSize = 5000; // Number of products per batch
        $page = 1;
        $xmlFilePath = 'feeds/google_merchant_feed.xml';

        // Start XML structure
        $xmlHeader = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xmlHeader .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . PHP_EOL;
        $xmlHeader .= '<channel>' . PHP_EOL;
        $xmlHeader .= '<title>Your Store</title>' . PHP_EOL;
        $xmlHeader .= '<link>https://buywithemi.com</link>' . PHP_EOL;
        $xmlHeader .= '<description>Your Product Feed</description>' . PHP_EOL;

        // Save the XML header to a file
        Storage::disk('local')->put($xmlFilePath, $xmlHeader);

        // Paginate through products
        do {
            // Fetch products in batches
            $products = Product::paginate($batchSize, ['*'], 'page', $page);
            // Generate XML for each batch
            $xmlContent = '';
            foreach ($products as $product) {
            Log::info('products '.json_encode($product->thumbnail_full_url));

                $xmlContent .= $this->generateProductXML($product);
            }

            // Append the batch XML to the file
            if (!empty($xmlContent)) {
                Storage::disk('local')->append($xmlFilePath, $xmlContent);
            }

            $page++;
        } while ($products->hasMorePages());

        // Close XML structure
        $xmlFooter = '</channel>' . PHP_EOL . '</rss>';
        Storage::disk('local')->append($xmlFilePath, $xmlFooter);

        $this->info('Google Merchant XML feed generated successfully!');
    }

    /**
     * Generate XML for a single product
     */
    private function generateProductXML($product)
    {
        $availability = $product->current_stock > 0 ? 'in stock' : 'out of stock';

        // Ensure the output is properly escaped to prevent XML errors
        $productXML = '
        <item>
            <g:id>' . htmlspecialchars($product->id) . '</g:id>
            <g:title>' . htmlspecialchars($product->name) . '</g:title>
            <g:description>' . htmlspecialchars(url('product/'.$product->slug)) . '</g:description>
            <g:link>' . htmlspecialchars(url('/product/' . $product->slug)) . '</g:link>
            <g:image_link>' . htmlspecialchars('https://d2agtg6j5uplgz.cloudfront.net/product/thumbnail/'.$product->thumbnail) . '</g:image_link>
            <g:price>' . htmlspecialchars(setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product->unit_price), currencyCode: getCurrencyCode())) . '</g:price>
            <g:condition>new</g:condition>
            <g:availability>' . $availability . '</g:availability>
        </item>' . PHP_EOL;

        return $productXML;
    }
}
