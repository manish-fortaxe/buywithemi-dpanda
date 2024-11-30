<?php

namespace App\Http\Controllers\Web;

use App\Utils\CategoryManager;
use App\Utils\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlogListController extends Controller
{
    public function blogs(Request $request)
    {
        $themeName = theme_root_path();

        return match ($themeName) {
            'default' => self::default_theme($request)
        };
    }

    public function default_theme($request): View|JsonResponse|Redirector|RedirectResponse
    {
        $categories = CategoryManager::getCategoriesWithCountingAndPriorityWiseSorting();
        $blogsSortBy = $request->get('sort_by');

        $blogsListData = Blog::get();

        if ($blogsSortBy) {
            if ($blogsSortBy == 'latest') {
                $blogsListData = $blogsListData->sortByDesc('id');
            } elseif ($blogsSortBy == 'low-high') {
                $blogsListData = $blogsListData->sortBy('unit_price');
            } elseif ($blogsSortBy == 'high-low') {
                $blogsListData = $blogsListData->sortByDesc('unit_price');
            } elseif ($blogsSortBy == 'a-z') {
                $blogsListData = $blogsListData->sortBy('name');
            } elseif ($blogsSortBy == 'z-a') {
                $blogsListData = $blogsListData->sortByDesc('name');
            }
        }

        $blogs = $blogsListData->paginate(20);

        return view(VIEW_FILE_NAMES['blogs_list_page'], [
            'blogs' => $blogs,
            'categories' => $categories,
        ]);
    }

    function singleBlog($slug)
    {
        $blog = Blog::where('slug',$slug)->first();
        return view(VIEW_FILE_NAMES['blogs_view_page'], [
            'blog' => $blog
        ]);
    }

}
