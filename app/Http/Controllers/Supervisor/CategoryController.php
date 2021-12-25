<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supervisor\CategoryRequest;
use App\Interfaces\Category\CategoryRepositoryInterface;
use App\Interfaces\UploadFile\UploadFileRepositoryInterface;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    /**
     * @var UploadFileRepositoryInterface
     */
    private $uploadFileRepositoryInterface;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepositoryInterface;

    public function __construct(UploadFileRepositoryInterface $uploadFileRepositoryInterface,
                                CategoryRepositoryInterface   $categoryRepositoryInterface)
    {
        $this->uploadFileRepositoryInterface = $uploadFileRepositoryInterface;
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
    }

    public function index()
    {
        try {
            $data = $this->categoryRepositoryInterface->all();

            return view('supervisor.category.index', compact('data'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function create()
    {
        try {
            $edit = false;
            return view('supervisor.category.form', compact('edit'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function store(CategoryRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->except(['icon', 'slug','supervisor_id']);
            $data['supervisor_id'] = \Auth::guard('supervisor')->id();
            if ($request->hasFile('icon')) {
                $data['icon'] = $this->uploadFileRepositoryInterface->upload([
                    'file' => 'icon',
                    'path' => 'category',
                    'upload_type' => 'single',
                    'delete_file' => ''
                ]);
            }
            $category = $this->categoryRepositoryInterface->create($data);
            if ($category) {
                DB::commit();
                return redirect()->route('supervisor.category.index')->with('success', 'Done Save Data Successfully');
            }
            return redirect()->back()->with('warning', 'Some failed errors');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())
                ->withInput($request->all());
        }
    }

    public function show($id): \Illuminate\Http\RedirectResponse
    {
        return redirect()->back()->with('warning', 'Not Allow Access');
    }

    public function edit(Category $category)
    {
        try {
            $edit = true;
            return view('supervisor.category.form', compact('edit', 'category'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function update(CategoryRequest $request, Category $category): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->except(['icon', 'slug']);
            if ($request->hasFile('icon')) {
                $data['icon'] = $this->uploadFileRepositoryInterface->upload([
                    'file' => 'icon',
                    'path' => 'category',
                    'upload_type' => 'single',
                    'delete_file' => $category->icon
                ]);
            }
            if ($category->update($data)) {
                DB::commit();
                return redirect()->route('supervisor.category.index')->with('success', 'Done Updated Data Successfully');
            }
            return redirect()->back()->with('warning', 'Some failed errors');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())
                ->withInput($request->all());
        }
    }

    public function destroy(Category $category): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $icon = $category->icon;
            if ($category->delete()) {
                Storage::delete($icon);
                DB::commit();
                return redirect()->route('supervisor.category.index')->with('success', 'Done Deleted Data Successfully');
            }
            return redirect()->back()->with('warning', 'Some failed errors');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function multipleDelete(CategoryRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            $categories = Category::query()->whereIn('id',$request->ids);
            $icons = $categories->pluck('icon');
            if ($categories->delete()) {
                foreach ($icons as $icon){
                    Storage::delete($icon);
                }
                DB::commit();
                return redirect()->route('supervisor.category.index')->with('success', 'Done Deleted Data Successfully');
            }
            return redirect()->back()->with('warning', 'Some failed errors');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
