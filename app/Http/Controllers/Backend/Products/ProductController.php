<?php

namespace App\Http\Controllers\Backend\Products;

use App\DataTables\CertificatesDataTable;
use App\DataTables\LanguagesDataTable;
use App\DataTables\ProductsDataTable;
use App\DataTables\PropertiesDataTable;
use App\DataTables\TeamsDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Brend;
use App\Models\Certificate;
use App\Models\Language;
use App\Models\ProductMedia;
use App\Models\Property;
use App\Models\SubProperty;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Yajra\DataTables\EloquentDataTable;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\Products\ProductSaveRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductProperty;
use App\Models\SubCategory;
use App\Models\Team;

class ProductController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Product::class;
    }

    public function index(ProductsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.products.index');
    }

    public function create()
    {
        $categories = Category::get();
        $subcategories = SubCategory::get();
        $brands = Brand::get();
        $properties = Property::get();

        return view('backend.pages.products.create', compact('categories', 'subcategories', 'brands', 'properties'));
    }
    public function store(ProductSaveRequest $request)
    {
        try {
            $item = new Product();
            DB::beginTransaction();
            $data = $request->except('_token', '_method', 'images');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "products", 'products_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);

            if ($request->images) {
                foreach ($request->images as $image) {
                    $imagePath = FileUploadHelper::uploadFile($image, "products_media", 'products_' . uniqid());
                    $item->media()->create([
                        "image" => $imagePath
                    ]);
                }
            }

            if ($request->has('properties') && $request->has('sub_properties')) {
                $subProperties = $request->input('sub_properties');
                $item->subProperties()->attach($subProperties);
            }
            
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.products.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }


    public function edit(Product $item)
    {
        $categories = Category::get();
        $subcategories = SubCategory::get();
        $brands = Brand::get();
        $properties = Property::get();
        $brends=Brend::get();
        $subcategories=SubCategory::get();

        return view('backend.pages.products.edit', compact('categories', 'subcategories','item', 'subcategories', 'brands', 'properties','brends'));
    }

    public function update(Request $request, Product $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "products", 'products_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);

            if ($request->images) {
                foreach ($request->images as $image) {
                    $imagePath = FileUploadHelper::uploadFile($image, "products_media", 'products_' . uniqid());
                    $item->media()->create([
                        "image" => $imagePath
                    ]);
                }
            }

            $item->subProperties()->detach();
            if ($request->has('properties') && $request->has('sub_properties')) {
                $subProperties = $request->input('sub_properties');
                $item->subProperties()->attach($subProperties);
            }

            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.products.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }


    public function delete(Product $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function properties(PropertiesDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.properties_old.index');
    }

    public function propertiesCreate()
    {
        return view('backend.pages.properties_old.create');
    }


    public function propertiesStore(Request $request)
    {
        try {
            $item = new ProductProperty();
            $item->product_id = $request->id;
            DB::beginTransaction();
            $data = $request->except('_token', '_method', 'id');

            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.products.properties_old.index', ['id' => $request->id]));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }


    public function propertiesEdit($id, $item_id)
    {
        $item = ProductProperty::find($item_id);
        return view('backend.pages.properties_old.edit', compact('item'));
    }


    public function propertiesUpdate(Request $request, $id, $item_id)
    {
        try {
            $item = ProductProperty::find($item_id);
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.products.properties_old.index', ['id' => $item->product_id]));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }


    public function propertiesDelete($id, $item_id)
    {

        try {
            DB::beginTransaction();
            $item = ProductProperty::find($item_id);
            $item->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }


    public function toggleSeasonal(Request $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);
            $product->is_seasonal = !$product->is_seasonal;
            $product->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Mövsüm təklifi statusu dəyişdirildi',
                'is_seasonal' => $product->is_seasonal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function toggleSpecialOffer(Request $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);
            $product->is_special_offer = !$product->is_special_offer;
            $product->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Sevindirən təklif statusu dəyişdirildi',
                'is_special_offer' => $product->is_special_offer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function toggleBundle(Request $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);
            $product->is_bundle = !$product->is_bundle;
            $product->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Birlikdə daha sərfəli statusu dəyişdirildi',
                'is_bundle' => $product->is_bundle
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function toggleWeeklyOffer(Request $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);
            $product->pick_of_status = !$product->pick_of_status;
            $product->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Həftənin təklifləri statusu dəyişdirildi',
                'pick_of_status' => $product->pick_of_status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getSubProperties(Request $request)
    {
        $propertyId = $request->input('property_id');
        $subProperties = SubProperty::where('property_id', $propertyId)->get()->map(function ($item) {
            return ['title'=>$item->title, 'id'=>$item->id];
        });



        return response()->json([
            'success' => true,
            'subProperties' => $subProperties
        ]);
    }


    public function deleteImage(Request $request, $image){
        try{
            $productImage = ProductMedia::findOrFail($image);
           if(file_exists(public_path('/storage/'.$productImage->image))){
             unlink(public_path('/storage/'.$productImage->image));
           }
           $productImage->delete();
            return $this->responseMessage('success', 'Successsfully Deleted', null,200, null);


        }catch(\Exception $e){
            return $this->responseMessage('error', $e->getMessage(),  null,200, null);
        }
    }
}
