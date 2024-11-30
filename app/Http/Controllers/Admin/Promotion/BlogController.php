<?php

namespace App\Http\Controllers\Admin\Promotion;

use App\Contracts\Repositories\BlogRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Enums\ViewPaths\Admin\Blog;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\BannerAddRequest;
use App\Http\Requests\Admin\BannerUpdateRequest;
use App\Http\Requests\Admin\BlogAddRequest;
use App\Http\Requests\Admin\BlogUpdateRequest;
use App\Services\BlogService;
use App\Traits\FileManagerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BlogController extends BaseController
{
    use FileManagerTrait {
        delete as deleteFile;
        update as updateFile;
    }

    public function __construct(
        private readonly BlogRepositoryInterface        $blogRepo,
        private readonly CategoryRepositoryInterface      $categoryRepo,
        private readonly BlogService       $blogService,
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
        $blogs = $this->blogRepo->getListWhereIn(
            orderBy: ['id'=>'desc'],
            searchValue: $request['searchValue'],
            filters: [],
            dataLimit: getWebConfig(name: 'pagination_limit'),
        );

        $categories = $this->categoryRepo->getListWhere(filters: ['position'=>0], dataLimit: 'all');
        return view(Blog::LIST[VIEW],  compact('blogs', 'categories'));
    }

    public function add(BlogAddRequest $request): RedirectResponse
    {
        $data = $this->blogService->getProcessedData(request: $request);
        $this->blogRepo->add(data:$data);
        Toastr::success(translate('blog_added_successfully'));
        return redirect()->route('admin.blog.list');
    }

    public function getUpdateView($id): View
    {
        $blog = $this->blogRepo->getFirstWhere(params: ['id'=>$id]);
        $categories = $this->categoryRepo->getListWhere(filters: ['position'=>0], dataLimit: 'all');
        return view(Blog::UPDATE[VIEW], compact('blog', 'categories'));
    }

    public function update(BlogUpdateRequest $request, $id): RedirectResponse
    {
        $blog = $this->blogRepo->getFirstWhere(params: ['id'=>$id]);
        $data = $this->blogService->getProcessedData(request: $request, image: $blog['media']);
        $this->blogRepo->update(id:$blog['id'], data:$data);
        Toastr::success(translate('blog_updated_successfully'));
        return redirect()->route(Blog::UPDATE[ROUTE]);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $status = $request->get('status', 0);
        $this->blogRepo->update(id:$request['id'], data:['status'=>$status]);
        return response()->json([
            'message' => $status == 1 ? translate("blog_published_successfully") : translate("blog_unpublished_successfully"),
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        $blog = $this->blogRepo->getFirstWhere(params: ['id' => $request['id']]);
        $this->deleteFile(filePath: '/blog/' . $blog['media']);
        $this->blogRepo->delete(params: ['id' => $request['id']]);
        return response()->json(['message' => translate('blog_deleted_successfully')]);
    }
}
