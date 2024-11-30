<?php

namespace App\Http\Controllers\Admin\Promotion;

use App\Http\Controllers\Controller;
use App\Contracts\Repositories\BannerRepositoryInterface;
use App\Contracts\Repositories\BrandRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\ShopRepositoryInterface;
use App\Enums\ViewPaths\Admin\AbandonedCart;
use App\Models\Cart;
use App\Services\BannerService;
use App\Traits\FileManagerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AbandonedCartController extends Controller
{
    use FileManagerTrait {
        delete as deleteFile;
        update as updateFile;
    }

    public function __construct(
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, string $type = null): View
    {
        return $this->getListView($request);
    }

    public function getListView(Request $request): View
    {
        // Get all carts, grouped by customer_id
        $cartsGrouped = Cart::where('cart_group_id', 'not like', '%guest%')
            ->get()
            ->groupBy('customer_id');

        // Total number of unique customer groups
        $total_cart_count = $cartsGrouped->count();

        // Pagination variables
        $perPage = 20; // Number of items per page
        $currentPage = LengthAwarePaginator::resolveCurrentPage(); // Get current page

        // Slice the grouped data for the current page
        $currentPageItems = $cartsGrouped->slice(($currentPage - 1) * $perPage, $perPage);

        // Create a paginator instance
        $carts = new LengthAwarePaginator(
        $currentPageItems, // The items for the current page
        $total_cart_count, // Total items in the dataset
        $perPage,          // Items per page
        $currentPage,      // Current page number
        ['path' => LengthAwarePaginator::resolveCurrentPath()] // Path for pagination links
        );

        // Pass both the paginated data and the total cart count to the view
        return view(AbandonedCart::LIST[VIEW], compact('carts', 'total_cart_count'));
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $status = $request->get('status', 0);
        $this->bannerRepo->update(id:$request['id'], data:['published'=>$status]);
        return response()->json([
            'message' => $status == 1 ? translate("banner_published_successfully") : translate("banner_unpublished_successfully"),
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        $banner = $this->bannerRepo->getFirstWhere(params: ['id' => $request['id']]);
        $this->deleteFile(filePath: '/banner/' . $banner['photo']);
        $this->bannerRepo->delete(params: ['id' => $request['id']]);
        return response()->json(['message' => translate('banner_deleted_successfully')]);
    }
}
