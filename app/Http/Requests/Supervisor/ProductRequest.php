<?php

namespace App\Http\Requests\Supervisor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onCreate(): array
    {
        return [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'name' => ['required', 'string', 'min:2', 'max:255', Rule::unique('products', 'name')
                ->whereNull('deleted_at')->where('supervisor_id', \Auth::guard('supervisor')->id())],
            'description' => ['required', 'string'],
            'image' => ['required', validationImage()],
            'images' => ['sometimes', 'nullable', 'array'],
            'images.*' => ['sometimes', 'nullable', validationImage()],

        ];
    }

    protected function onUpdate(): array
    {
        return [
            'id' => ['required', 'integer', Rule::exists('products', 'id')],
            'name' => ['required', 'string', 'min:2', 'max:255',
                Rule::unique('products', 'name')
                    ->whereNull('deleted_at')->where('supervisor_id', \Auth::guard('supervisor')->id())
                    ->ignore($this->id, 'id')],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'description' => ['required', 'string'],
            'image' => ['sometimes', 'nullable', validationImage()],
            'images' => ['sometimes', 'nullable', 'array'],
            'images.*' => ['sometimes', 'nullable', validationImage()],
        ];
    }


    protected function onMultiDelete(): array
    {
        return [
            'ids.*' => ['required', 'integer', Rule::exists('products', 'id')],
        ];
    }


    public function rules(): array
    {
        if (request()->routeIs('supervisor.product.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('supervisor.product.update')) {
            return $this->onUpdate();
        } elseif (request()->routeIs('supervisor.product.multiple-delete')) {
            return $this->onMultiDelete();
        } else {
            return [];
        }
    }
}
