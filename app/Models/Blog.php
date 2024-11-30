<?php

namespace App\Models;

use App\Traits\StorageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Blog extends Model
{
    use HasFactory, SoftDeletes, StorageTrait;
    protected $casts = [
        'id' => 'integer',
        'sequence' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $fillable = [
        'category_id',
        'slug',
        'title',
        'description',
        'media',
        'meta_title',
        'meta_description',
        'sequence',
        'status'
    ];

    protected $appends = ['media_full_url'];

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function getMediaFullUrlAttribute():string|null|array
    {
        $value = $this->media;
        return $this->storageLink('blog', $value, 'public');
    }

    protected static function boot(): void
    {
        parent::boot();
        static::saved(function ($model) {
            $file ='media';
            $storage = config('filesystems.disks.default') ?? 'public';
            if($model->isDirty($file)){
                $value = $storage;
                DB::table('storages')->updateOrInsert([
                    'data_type' => get_class($model),
                    'data_id' => $model->id,
                    'key' => $file,
                ], [
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
