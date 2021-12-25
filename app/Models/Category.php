<?php

namespace App\Models;

use App\Scopes\SupervisorId;
use Cviebrock\EloquentSluggable\Sluggable;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Sluggable;

    protected $table = 'categories';

    protected $fillable = [
        'name', 'slug', 'icon', 'supervisor_id'
    ];

    protected $cascadeDeletes = ['products'];



    protected $appends = ['iconPath'];

    public function getIconPathAttribute(): string
    {
        return asset('storage/' . $this->icon);
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

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
