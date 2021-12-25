<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supervisor\AuthSupervisorRequest;
use App\Mail\Supervisor\SupervisorResetPassword;
use App\Models\supervisor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthSupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['guest:supervisor'])
            ->only(['showLoginForm', 'login', 'resetPassword', 'postResetPassword', 'reset', 'postReset']);
    }

    public function showLoginForm()
    {
        try {
            return view('supervisor.auth.login');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    public function login(AuthSupervisorRequest $request)
    {
        try {
            if (Auth::guard('supervisor')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember_me)) {
                // if successful, then redirect to their intended location
                if (Auth::guard('supervisor')->user()->blocked == 1) {
                    Auth::guard('supervisor')->logout();
                    return redirect()->back()->with('warning', 'Your Account is blocked');
                }
                return redirect()->intended(route('supervisor.home'));
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
            Auth::guard('supervisor')->logout();
            return redirect()->route('supervisor.login');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    public function resetPassword()
    {
        try {
            return view('supervisor.auth.passwords.email');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    public function postResetPassword(AuthSupervisorRequest $request)
    {
        try {
            DB::beginTransaction();
            $supervisor = supervisor::query()->where('email', '=', $request->email)->first();

            DB::table('supervisor_password_resets')
                ->where('email', $request->email)->delete();

            $token = app('auth.password.broker')->createToken($supervisor);
            $data = DB::table('supervisor_password_resets')->insert([
                'email' => $supervisor->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            if ($data) {
                Mail::to($supervisor->email)->send(new SupervisorResetPassword(['data' => $supervisor, 'token' => $token]));
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
            $data = DB::table('supervisor_password_resets')
                ->where('token', '=', $token)
                ->where('created_at', '>', Carbon::now()->subHours(2))
                ->first();

            if (!empty($data)) {
                return view('supervisor.auth.passwords.reset', compact('data'));
            } else {
                return redirect()->route('supervisor.reset.password');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }

    public function postReset(AuthSupervisorRequest $request, $token)
    {
        try {
            DB::beginTransaction();
            $checkToken = DB::table('supervisor_password_resets')
                ->where('token', '=', $token)
                ->where('created_at', '>', Carbon::now()->subHours(2))
                ->first();
            if (!empty($checkToken)) {

                Supervisor::query()->where('email', '=', $checkToken->email)->update([
                    'email' => $checkToken->email,
                    'password' => Hash::make($request->password)
                ]);

                DB::table('supervisor_password_resets')
                    ->where('email', '=', $request->email)
                    ->delete();

                if (Auth::guard('supervisor')->attempt(['email' => $checkToken->email, 'password' => $request->password], true)) {
                    if (Auth::guard('supervisor')->user()->blocked == 1) {
                        Auth::guard('supervisor')->logout();
                        return redirect()->back()->with('warning', 'Your Account is blocked');
                    }
                    DB::commit();
                    return redirect()->intended(route('admin.home'));
                }
                return redirect()->back()->with('warning', 'Please Check your email and password is correct');
            }
            return redirect()->route('supervisor.reset.password');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())->withInput($request->all());
        }
    }
}
