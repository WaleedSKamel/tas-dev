<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = ['name', 'size', 'file', 'path', 'full_file', 'mime_type', 'file_type', 'relation_id',];

    protected $dates = ['deleted_at'];

    protected $appends = ['fullFilePath'];

    public function getFullFilePathAttribute()
    {
        return asset('storage/' . $this->full_file);
    }

    // scopes local

    public function scopeFileType($query,$value)
    {
        return $query->where('file_type','=',$value);
    }

    public function scopeRelationId($query,$value)
    {
        return $query->where('relation_id','=',$value);
    }
}
