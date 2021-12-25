<?php

namespace App\Http\Requests\Supervisor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onCreate(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255', Rule::unique('categories', 'name')
                ->whereNull('deleted_at')->where('supervisor_id', \Auth::guard('supervisor')->id())],
            'icon' => ['required', validationImage()],

        ];
    }

    protected function onUpdate(): array
    {
        return [
            'id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'name' => ['required', 'string', 'min:2', 'max:255',
                Rule::unique('categories', 'name')
                    ->whereNull('deleted_at')->where('supervisor_id', \Auth::guard('supervisor')->id())
                    ->ignore($this->id, 'id')],
            'icon' => ['sometimes', 'nullable', validationImage()],
        ];
    }


    protected function onMultiDelete(): array
    {
        return [
            'ids.*' => ['required', 'integer', Rule::exists('categories', 'id')],
        ];
    }


    public function rules(): array
    {
        if (request()->routeIs('supervisor.category.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('supervisor.category.update')) {
            return $this->onUpdate();
        } elseif (request()->routeIs('supervisor.category.multiple-delete')) {
            return $this->onMultiDelete();
        } else {
            return [];
        }
    }
}
