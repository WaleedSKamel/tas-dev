<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupervisorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onCreate(): array
    {
        return [
            'username' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'string', 'min:2', 'max:255', Rule::unique('supervisors', 'email')],
            'phone' => ['required', 'string', Rule::unique('supervisors', 'phone')],
            'password' => ['required', 'string', 'min:2', 'max:255'],
            'avatar' => ['required', validationImage()],

        ];
    }

    protected function onUpdate(): array
    {
        return [
            'id' => ['required', 'integer', Rule::exists('supervisors', 'id')],
            'username' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'string', 'min:2', 'max:255',
                Rule::unique('supervisors', 'email')->ignore($this->id, 'id')],
            'phone' => ['required', 'string',
                Rule::unique('supervisors', 'phone')->ignore($this->id, 'id')],
            'avatar' => ['sometimes', 'nullable', validationImage()],
        ];
    }

    protected function onChangePassword(): array
    {
        return [
            'id' => ['required', 'integer', Rule::exists('supervisors', 'id')],
            'password'=>'required|min:2',
        ];
    }

    protected function onMultiDelete(): array
    {
        return [
            'ids.*' => ['required', 'integer', Rule::exists('supervisors', 'id')],
        ];
    }


    public function rules(): array
    {
        if (request()->routeIs('admin.supervisor.store')) {
            return $this->onCreate();
        } elseif (request()->routeIs('admin.supervisor.update')) {
            return $this->onUpdate();
        }  elseif (request()->routeIs('admin.supervisor.change-password')) {
            return $this->onChangePassword();
        }elseif (request()->routeIs('admin.supervisor.multiple-delete')) {
            return $this->onMultiDelete();
        }else {
            return [];
        }
    }
}
