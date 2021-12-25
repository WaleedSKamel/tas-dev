<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class supervisor extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $table = 'supervisors';

    protected $guarded = 'supervisor';

    protected $fillable = [
        'username', 'phone', 'email', 'password', 'blocked', 'avatar'
    ];

    protected $hidden = [
        'password',
    ];

    protected $appends = ['avatarPath'];

    public function getAvatarPathAttribute()
    {
        return asset('storage/' . $this->avatar);
    }

    public function getUsernameAttribute($value)
    {
        return ucfirst($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

}
