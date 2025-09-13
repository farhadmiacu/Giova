<?php

namespace App\Http\Controllers\Backend\Farhad;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductMultiImage;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:product_view')->only(['index', 'show']);
        $this->middleware('can:product_create')->only(['create', 'store']);
        $this->middleware('can:product_edit')->only(['edit', 'update']);
        $this->middleware('can:product_delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'brand'])->get();
        return view('backend.layouts.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('backend.layouts.product.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        $request->validate([
            'name' => 'required|string|unique:products,name|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'code' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'multi_images'       => 'nullable', // Ensure the array itself is not empty
            'multi_images.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif', //for table
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'regular_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0|lte:regular_price',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:0,1',
        ]);

        // Handle image upload
        $imageUrl = null;

        if ($request->file('image')) {
            $image     = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $directory = 'uploads/products-images/';
            // Create directory if it doesn't exist
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }
            $resizedImage = Image::make($image)->resize(300, 300);
            $resizedImage->save(public_path($directory . $imageName));
            $imageUrl = $directory . $imageName;
        }



        $product = new Product();
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->code = $request->code;
        $product->image = $imageUrl;
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->regular_price = $request->regular_price;
        $product->selling_price = $request->selling_price;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->save();

        $this->syncLongDescriptionImages($product, $request->long_description);

        // Multiple images
        if ($request->hasFile('multi_images')) {
            foreach ($request->file('multi_images') as $multiImage) {
                $multiName = time() . '_' . uniqid() . '.' . $multiImage->getClientOriginalExtension();
                $directory = 'uploads/products-images/multi/';
                $multiImage->move($directory, $multiName);

                $productImage = new ProductMultiImage();
                $productImage->product_id = $product->id;
                $productImage->image = $directory . $multiName;
                $productImage->save();
            }
        }

        return back()->with('success', 'New Product added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with(['category', 'brand'])->findOrFail($id);
        return view('backend.layouts.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        return view('backend.layouts.product.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'code' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'regular_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0|lte:regular_price',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:0,1',
        ]);

        // Image update or keep old or remove
        $imageUrl = $product->image; // default: keep old image
        if ($request->file('image')) {
            if ($product->image && file_exists($product->image)) {
                unlink($product->image);
            }

            // Upload new image
            $image     = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $directory = 'uploads/products-images/';

            $resizedImage = Image::make($image)->resize(300, 300);
            $resizedImage->save(public_path($directory . $imageName));
            $imageUrl = $directory . $imageName;
        }
        // Check if removed the image
        elseif ($request->input('image') === null) {
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $imageUrl = null;
        }

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->code = $request->code;
        $product->image = $imageUrl;
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->regular_price = $request->regular_price;
        $product->selling_price = $request->selling_price;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->save();

        $this->syncLongDescriptionImages($product, $request->long_description);


        /* ---------- Remove selected multi images ---------- */
        if ($request->filled('removed_images')) {
            // removed_images is coming as an array, not JSON
            $removedIds = is_array($request->removed_images) ? $request->removed_images : [$request->removed_images];
            foreach ($removedIds as $id) {
                $img = ProductMultiImage::find($id);
                if ($img) {
                    if (file_exists(public_path($img->image))) {
                        @unlink(public_path($img->image));
                    }
                    $img->delete();
                }
            }
        }

        /* ---------- Add new multi images (do not remove old ones) ---------- */
        // Check if new multiple images uploaded
        if ($request->hasFile('multi_images')) {
            // Delete old images (both from DB and storage)
            // $oldImages = ProductMultiImage::where('product_id', $product->id)->get();
            // foreach ($oldImages as $oldImage) {
            //     if (file_exists(public_path($oldImage->image))) {
            //         unlink(public_path($oldImage->image));
            //     }
            //     $oldImage->delete();
            // }

            // Save new images
            foreach ($request->file('multi_images') as $multiImage) {
                $multiName = time() . '_' . uniqid() . '.' . $multiImage->getClientOriginalExtension();
                $directory = 'uploads/products-images/multi/';
                $multiImage->move(public_path($directory), $multiName);

                $productImage = new ProductMultiImage();
                $productImage->product_id = $product->id;
                $productImage->image = $directory . $multiName;
                $productImage->save();
            }
        }

        return back()->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Remove image if exists
        if ($product->image && file_exists($product->image)) {
            unlink($product->image);
        }

        // ✅ Delete all multi images (physical + DB)
        $multiImages = ProductMultiImage::where('product_id', $id)->get();
        foreach ($multiImages as $multiImage) {
            if (file_exists($multiImage->image)) {
                unlink($multiImage->image);
            }
            $multiImage->delete();
        }

        // ✅ Delete all long description images (physical + DB)
        foreach ($product->longDescriptionImages as $img) {
            $absolutePath = public_path($img->image_path);
            if (file_exists($absolutePath)) {
                unlink($absolutePath);
            }
        }
        $product->longDescriptionImages()->delete();

        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully');
    }



    protected function syncLongDescriptionImages(Product $product, $html)
    {
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $html ?? '', $matches);
        $imageUrls = $matches[1] ?? [];

        $paths = [];

        foreach ($imageUrls as $url) {
            // If the URL contains your app URL, store relative path
            if (str_contains($url, url('/'))) {
                $relativePath = str_replace(url('/') . '/', '', $url);
                $paths[] = $relativePath;
            } else {
                // External URL, keep as-is
                $paths[] = $url;
            }
        }

        // Get currently stored
        $existing = $product->longDescriptionImages()->pluck('image_path')->toArray();

        // Find removed images
        $toDelete = array_diff($existing, $paths);

        foreach ($toDelete as $oldPath) {
            // Only delete local files
            if (!str_starts_with($oldPath, 'http')) {
                $absolutePath = public_path($oldPath);
                if (file_exists($absolutePath)) {
                    unlink($absolutePath);
                }
            }
        }

        // Remove from DB
        $product->longDescriptionImages()->whereIn('image_path', $toDelete)->delete();

        // Insert new
        foreach ($paths as $path) {
            $product->longDescriptionImages()->firstOrCreate([
                'image_path' => $path
            ]);
        }
    }




    public function uploadCkEditorImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            $destination = public_path('uploads/products-images/long-description-images/');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            $file->move($destination, $filename);

            $url = asset('uploads/products-images/long-description-images/' . $filename);

            return response()->json(['url' => $url]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function removeCkEditorImage(Request $request)
    {
        $request->validate([
            'image_url' => 'required|url'
        ]);

        $path = parse_url($request->image_url, PHP_URL_PATH);
        $absolutePath = public_path($path);

        if (file_exists($absolutePath)) {
            unlink($absolutePath);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}
