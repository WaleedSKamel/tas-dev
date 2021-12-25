<?php

namespace App\Http\Requests\Supervisor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthSupervisorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function onLogin(): array
    {
        return [
            'email' => ['required', 'email', 'min:2', 'max:100', Rule::exists('supervisors', 'email')
                /*->where('blocked',0)*/],
            'password' => ['required', 'string', 'min:2', 'max:100'],
            'remember_me' => ['sometimes', 'nullable']
        ];
    }

    protected function onForgotPassword(): array
    {
        return [
            'email' => ['required', 'email', 'min:2', 'max:100', Rule::exists('supervisors', 'email')],
        ];
    }

    protected function onResetPassword(): array
    {
        return [
            'email' => ['required', 'email', 'string', Rule::exists('supervisors', 'email')],
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|same:password'
        ];
    }

    public function rules(): array
    {
        if (request()->routeIs('supervisors.login')) {
            return $this->onLogin();
        } elseif (request()->routeIs('supervisors.reset.password')) {
            return $this->onForgotPassword();
        } elseif (request()->routeIs('supervisors.reset')) {
            return $this->onResetPassword();
        } else {
            return [];
        }
    }
}
