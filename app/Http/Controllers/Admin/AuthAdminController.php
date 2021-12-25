<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthAdminRequest;
use App\Mail\Admin\AdminResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['guest'])
            ->only(['showLoginForm', 'login', 'resetPassword', 'postResetPassword', 'reset', 'postReset']);
    }

    public function showLoginForm()
    {
        try {
            return view('admin.auth.login');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    public function login(AuthAdminRequest $request)
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember_me)) {
                // if successful, then redirect to their intended location
                return redirect()->intended(route('admin.home'));
            }
            // if unsuccessful, then redirect back to the login with the form data
            return redirect()->back()->with('warning', __('Please Check your email and password is correct'))
                ->withInput($request->only('email', 'remember'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->only('email', 'remember'));
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return redirect()->route('admin.login');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    public function resetPassword()
    {
        try {
            return view('admin.auth.passwords.email');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    public function postResetPassword(AuthAdminRequest $request)
    {
        try {
            DB::beginTransaction();
            $admin = User::query()->where('email', '=', $request->email)->first();

            DB::table('password_resets')
                ->where('email', $request->email)->delete();

            $token = app('auth.password.broker')->createToken($admin);
            $data = DB::table('password_resets')->insert([
                'email' => $admin->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            if ($data) {
                Mail::to($admin->email)->send(new AdminResetPassword(['data' => $admin, 'token' => $token]));
                DB::commit();
                return redirect()->back()
                    ->with('success', __('Done Rest link is sent'))
                    ->withInput($request->all());
            }
            return redirect()->back();

        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())
                ->withInput($request->all());
        }
    }

    public function reset($token)
    {
        try {
            $data = DB::table('password_resets')
                ->where('token', '=', $token)
                ->where('created_at', '>', Carbon::now()->subHours(2))
                ->first();

            if (!empty($data)) {
                return view('admin.auth.passwords.reset', compact('data'));
            } else {
                return redirect()->route('admin.reset.password');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    public function postReset(AuthAdminRequest $request, $token)
    {
        try {
            DB::beginTransaction();
            $checkToken = DB::table('password_resets')
                ->where('token', '=', $token)
                ->where('created_at', '>', Carbon::now()->subHours(2))
                ->first();
            if (!empty($checkToken)) {

                User::query()->where('email', '=', $checkToken->email)->update([
                    'email' => $checkToken->email,
                    'password' => Hash::make($request->password)
                ]);

                DB::table('password_resets')
                    ->where('email', '=', $request->email)
                    ->delete();

                if (Auth::attempt(['email' => $checkToken->email, 'password' => $request->password], true)) {
                    DB::commit();
                    return redirect()->intended(route('admin.home'));
                }
                return redirect()->back()->with('warning', __('Please Check your email and password is correct'));
            }
            return redirect()->route('admin.reset.password');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }
}
