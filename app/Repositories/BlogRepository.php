<?php

namespace App\Repositories;

use App\Contracts\Repositories\BlogRepositoryInterface;
use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogRepository implements BlogRepositoryInterface
{
    public function __construct(
        private readonly Blog $blog,
    )
    {
    }

    public function add(array $data): string|object
    {
        return $this->blog->create($data);
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->blog->where($params)->with($relations)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->blog->with($relations)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy), array_values($orderBy)[0]);
            });
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit);
    }

    public function getListWhere(array $orderBy = [], string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->blog->with($relations)
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }

    public function getListWhereIn(array $orderBy = [], string $searchValue = null, array $filters = [], array $whereInFilters = [], array $relations = [], array $nullFields = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $query = $this->blog
            ->with($relations)
            ->when(!empty($whereInFilters), function ($query) use ($whereInFilters) {
                foreach ($whereInFilters as $key => $filterIndex){
                    $query->whereIn($key , $filterIndex);
                }
            })
            ->when(!empty($nullFields), function ($query) use ($nullFields) {
                return $query->whereNull($nullFields);
            })
            ->when(!empty($orderBy), function ($query) use ($orderBy) {
                return $query->orderBy(array_key_first($orderBy),array_values($orderBy)[0]);
            });

        $filters += ['searchValue' =>$searchValue];
        return $dataLimit == 'all' ? $query->get() : $query->paginate($dataLimit)->appends($filters);
    }


    public function update(string $id, array $data): bool
    {
        $this->blog->find($id)->update($data);
        return true;
    }

    public function delete(array $params): bool
    {
        $this->blog->where($params)->delete();
        return true;
    }
}
