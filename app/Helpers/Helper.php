<?php

use App\Models\Language;
use Carbon\Carbon;

# validateImage
if (!function_exists('validationImage')) {
    function validationImage($extension = null): array
    {
        if ($extension == null) {
            return ['image', 'mimes:jpg,jpeg,png,bmp'];
        } else {
            return ['image', 'mimes:' . $extension];
        }
    }
}


if (!function_exists('deleteSingleFile')) {
    function deleteSingleFile($file)
    {
        \Storage::delete($file);
    }
}

# format date
if (!function_exists('formatDate')) {
    function formatDate($format, $date)
    {
        return date($format, strtotime(Carbon::parse($date)));
    }
}

if (!function_exists('checkRoute')) {
    function checkRoute($route, $type = 'route', $tagType = 'tag'): string
    {
        if ($tagType == 'ul') {
            if ($type == 'route') {
                return \Route::currentRouteName() == $route ? 'active' : '';
            } elseif ($type == 'url') {
                return \Request::is($route) ? 'menu-open' : '';
            }

        } elseif ($tagType == 'all') {
            return \Request::is($route) ? 'active' : '';
        } else {
            if ($type == 'route') {
                return \Route::currentRouteName() == $route ? 'active' : '';
            } elseif ($type == 'url') {
                return \Request::is($route) ? 'menu-open' : '';
            }
        }

        return '';
    }
}

