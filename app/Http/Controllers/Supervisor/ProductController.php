<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supervisor\CategoryRequest;
use App\Http\Requests\Supervisor\ProductRequest;
use App\Interfaces\Category\CategoryRepositoryInterface;
use App\Interfaces\UploadFile\UploadFileRepositoryInterface;
use App\Models\File;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
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
            $data = Product::query()->with(['category'])->orderByDesc('created_at')->get();

            return view('supervisor.product.index', compact('data'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    public function create()
    {
        try {
            $edit = false;
            $categories = $this->categoryRepositoryInterface->all();
            return view('supervisor.product.form', compact('edit', 'categories'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->except(['image', 'slug', 'images', 'supervisor_id']);
            $data['supervisor_id'] = \Auth::guard('supervisor')->id();
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadFileRepositoryInterface->upload([
                    'file' => 'image',
                    'path' => 'product',
                    'upload_type' => 'single',
                    'delete_file' => ''
                ]);
            }
            $product = Product::query()->create($data);
            if ($product) {
                DB::commit();
                $this->uploadFileRepositoryInterface->upload([
                    'file' => 'images',
                    'path' => 'product-images/' . $product->id,
                    'upload_type' => 'files',
                    'delete_file' => '',
                    'multi_upload' => true,
                    'file_type' => 'product',
                    'relation_id' => $product->id,
                ]);

                return redirect()->route('supervisor.product.index')->with('success', 'Done Save Data Successfully');
            }
            return redirect()->back()->with('warning', 'Some failed errors');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())
                ->withInput($request->all());
        }
    }


    public function show($id)
    {
        return redirect()->back()->with('warning', 'Not Allow Access');
    }


    public function edit(Product $product)
    {
        try {
            $edit = true;
            $categories = $this->categoryRepositoryInterface->all();
            return view('supervisor.product.form', compact('edit', 'categories', 'product'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    public function update(ProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();
            $data = $request->except(['image', 'images', 'slug']);
            if ($request->hasFile('image')) {
                $data['image'] = $this->uploadFileRepositoryInterface->upload([
                    'file' => 'image',
                    'path' => 'product',
                    'upload_type' => 'single',
                    'delete_file' => $product->image
                ]);
            }
            if ($product->update($data)) {
                $this->uploadFileRepositoryInterface->upload([
                    'file' => 'images',
                    'path' => 'product-images/' . $product->id,
                    'upload_type' => 'files',
                    'delete_file' => '',
                    'multi_upload' => true,
                    'file_type' => 'product',
                    'relation_id' => $product->id,
                ]);
                DB::commit();
                return redirect()->route('supervisor.product.index')->with('success', 'Done Updated Data Successfully');
            }
            return redirect()->back()->with('warning', 'Some failed errors');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage())
                ->withInput($request->all());
        }
    }


    public function destroy(Product $product): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();
            if ($product->delete()) {
                DB::commit();
                return redirect()->route('supervisor.product.index')->with('success', 'Done Deleted Data Successfully');
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
            $products = Product::query()->whereIn('id', $request->ids);
            $dataProducts = $products;
            $images = $products->pluck('image');
            foreach ($dataProducts->get() as $product) {
                foreach ($product->images()->get() as $file) {
                    Storage::delete($file->full_file);
                }
                $product->images()->delete();
            }
            if ($products->delete()) {
                foreach ($images as $image) {
                    Storage::delete($image);
                }

                DB::commit();
                return redirect()->route('supervisor.product.index')->with('success', 'Done Deleted Data Successfully');
            }
            return redirect()->back()->with('warning', 'Some failed errors');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function deleteImage(Request $request)
    {
        try {
            DB::beginTransaction();
            $file = File::query()->fileType('product')->relationId($request->product_id)
                ->find($request->file_id);
            if ($file) {
                Storage::delete($file->full_file);
                $file->delete();
                DB::commit();
                return response()->json([
                    'data' => '',
                    'message' => 'Done Delete This Image',
                    'success' => true
                ]);
            }
            return response()->json([
                'data' => '',
                'message' => 'Not Found This Image',
                'success' => false
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'data' => '',
                'message' => $exception->getMessage(),
                'success' => false
            ]);
        }
    }
}
