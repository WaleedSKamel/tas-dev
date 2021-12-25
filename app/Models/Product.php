<?php

namespace App\Models;

use App\Scopes\SupervisorId;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $table = 'products';

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'image', 'supervisor_id'
    ];


    protected $appends = ['imagePath'];

    public function getImagePathAttribute(): string
    {
        return asset('storage/' . $this->image);
    }

    public function getNameAttribute($value): string
    {
        return ucfirst($value);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    // scopes Global
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SupervisorId);
    }

    // scopes locals
    /*public function scopeSupervisorId($query)
    {
       return $query->where('supervisor_id', '=', \Auth::guard('supervisor')->id());
    }*/

    // relation

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(File::class, 'relation_id', 'id')
            ->where('file_type', '=', 'product');
    }
}
