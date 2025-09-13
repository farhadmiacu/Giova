<?php

namespace App\Http\Controllers\Backend\Farhad;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:brand_view')->only(['index', 'show']);
        $this->middleware('can:brand_create')->only(['create', 'store']);
        $this->middleware('can:brand_edit')->only(['edit', 'update']);
        $this->middleware('can:brand_delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();
        return view('backend.layouts.brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.layouts.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:brands,name|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:0,1',
        ]);

        // Handle image upload
        $imageUrl = null;

        if ($request->file('image')) {
            $image     = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $directory = 'uploads/brands-images/';
            // Create directory if it doesn't exist
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }
            $resizedImage = Image::make($image)->resize(60, 60);
            $resizedImage->save(public_path($directory . $imageName));

            $imageUrl = $directory . $imageName;
        }

        $brand = new Brand();
        $brand->name   = $request->name;
        $brand->image  = $imageUrl;
        $brand->status = $request->status;
        $brand->save();

        return back()->with('success', 'New Brand information added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('backend.layouts.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id . ',id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:0,1',
        ]);

        // Image update or keep old or remove
        $imageUrl = $brand->image;
        if ($request->file('image')) {
            if ($brand->image && file_exists($brand->image)) {
                unlink($brand->image);
            }

            // Upload new image
            $image     = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $directory = 'uploads/brands-images/';
            $resizedImage = Image::make($image)->resize(60, 60); // Resize to 60x60 (image intervention)
            $resizedImage->save(public_path($directory . $imageName));
            $imageUrl = $directory . $imageName;
        }
        // Check if removed the image
        elseif ($request->input('image') === null) {
            if ($brand->image && file_exists(public_path($brand->image))) {
                unlink(public_path($brand->image));
            }
            $imageUrl = null;
        }

        $brand->name   = $request->name;
        $brand->image  = $imageUrl;
        $brand->status = $request->status;
        $brand->save();

        return back()->with('success', 'Brand information updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->slug === 'unbranded') {
            return redirect()->back()->with('error', 'Cannot delete Unbranded brand.');
        }

        $unbrandedId = Brand::where('slug', 'unbranded')->first()->id;

        // Reassign products
        Product::where('brand_id', $brand->id)
            ->update(['brand_id' => $unbrandedId]);

        // Delete image if exists
        if ($brand->image && file_exists($brand->image)) {
            unlink($brand->image);
        }

        $brand->delete();

        // return redirect()->back()->with('success', 'Brand deleted and products reassigned.');
        return response()->json([
            'success' => true,
            'message' => 'Brand deleted and products reassigned.'
        ]);
    }
}
