<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SupervisorRequest;
use App\Interfaces\UploadFile\UploadFileRepositoryInterface;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SupervisorController extends Controller
{

    /**
     * @var UploadFileRepositoryInterface
     */
    private $uploadFileRepositoryInterface;

    public function __construct(UploadFileRepositoryInterface $uploadFileRepositoryInterface)
    {
        $this->uploadFileRepositoryInterface = $uploadFileRepositoryInterface;
    }

    public function index()
    {
        try {
            $data = Supervisor::query()->orderByDesc('created_at')->get();

            return view('admin.supervisor.index', compact('data'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    public function create()
    {
        try {
            $edit = false;
            return view('admin.supervisor.form', compact('edit'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function store(SupervisorRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->except(['blocked', 'avatar']);
            if ($request->hasFile('avatar')) {
                $data['avatar'] = $this->uploadFileRepositoryInterface->upload([
                    'file' => 'avatar',
                    'path' => 'supervisor',
                    'upload_type' => 'single',
                    'delete_file' => ''
                ]);
            }
            $supervisor = Supervisor::query()->create($data);
            if ($supervisor) {
                DB::commit();
                return redirect()->route('admin.supervisor.index')->with('success', 'Done Save Data Successfully');
            }
            return redirect()->back()->with('warning', 'Some failed errors');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())
                ->withInput($request->all());
        }

    }


    public function show(Supervisor $supervisor)
    {
        try {
            return view('admin.supervisor.show', compact( 'supervisor'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    public function edit(Supervisor $supervisor)
    {
        try {
            $edit = true;
            return view('admin.supervisor.form', compact('edit', 'supervisor'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    public function update(SupervisorRequest $request, Supervisor $supervisor): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->except(['blocked', 'avatar', 'password']);
            if ($request->hasFile('avatar')) {
                $data['avatar'] = $this->uploadFileRepositoryInterface->upload([
                    'file' => 'avatar',
                    'path' => 'supervisor',
                    'upload_type' => 'single',
                    'delete_file' => $supervisor->avatar
                ]);
            }
            if ($supervisor->update($data)) {
                DB::commit();
                return redirect()->route('admin.supervisor.index')->with('success', 'Done Updated Data Successfully');
            }
            return redirect()->back()->with('warning', 'Some failed errors');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())
                ->withInput($request->all());
        }
    }


    public function destroy(Supervisor $supervisor): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $avatar = $supervisor->avatar;
            if ($supervisor->delete()) {
                Storage::delete($avatar);
                DB::commit();
                return redirect()->route('admin.supervisor.index')->with('success', 'Done Deleted Data Successfully');
            }
            return redirect()->back()->with('warning', 'Some failed errors');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function multipleDelete(SupervisorRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $supervisors = Supervisor::query()->whereIn('id',$request->ids);
            $avatars = $supervisors->pluck('avatar');
            if ($supervisors->delete()) {
                foreach ($avatars as $avatar){
                    Storage::delete($avatar);
                }
                DB::commit();
                return redirect()->route('admin.supervisor.index')->with('success', 'Done Deleted Data Successfully');
            }
            return redirect()->back()->with('warning', 'Some failed errors');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function updatedStatus($id): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $supervisor = Supervisor::query()->findOrFail($id);
            $blocked = !$supervisor->blocked;
            if ($supervisor->update(['blocked' => $blocked])) {
                DB::commit();
                return redirect()->route('admin.supervisor.index')->with('success', 'Done Updated' . $blocked ? 'Blocked' : 'Unblock' . ' Data Successfully');
            }
            return redirect()->back()->with('warning', 'Some failed errors');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function changePassword(SupervisorRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $supervisor = Supervisor::query()->findOrFail($request->id);

            if ($supervisor->update(['password' => $request->password])) {
                DB::commit();
                return redirect()->route('admin.supervisor.index')->with('success', 'Done Change Password Successfully');
            }
            return redirect()->back()->with('warning', 'Some failed errors');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())
                ->withInput($request->all());
        }

    }
}
