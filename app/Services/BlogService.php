<?php

namespace App\Services;

use App\Traits\FileManagerTrait;
use Illuminate\Support\Facades\DB;
use Str;

class BlogService
{
    use FileManagerTrait;

    public function getProcessedData(object $request, string $image = null): array
    {
        if ($image) {
            $imageName = $request->file('media') ? $this->update(dir:'blog/', oldImage:$image, format: 'webp', image: $request->file('media')) : $image;
        }else {
            $imageName = $this->upload(dir:'blog/', format: 'webp', image: $request->file('media'));
        }

        return [
            'title' => $request['title'],
            'slug' => $this->getSlug($request),
            'category_id' => $request['category_id'],
            'media' => $imageName,
            'description' => $request['description'],
            'meta_title' => $request['meta_title'],
            'meta_description' => $request['meta_description'],
        ];
    }

    public function getSlug(object $request): string
    {
        return Str::slug($request['title']) . '-' . Str::random(6);
    }

}
