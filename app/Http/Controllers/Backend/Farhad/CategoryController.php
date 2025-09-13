<?php

namespace App\Http\Controllers\Backend\Farhad;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:category_view')->only(['index', 'show']);
        $this->middleware('can:category_create')->only(['create', 'store']);
        $this->middleware('can:category_edit')->only(['edit', 'update']);
        $this->middleware('can:category_delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //normal way
        // $categories = Category::all();
        // return view('backend.layouts.category.index', compact('categories'));

        // yajra datatable
        if ($request->ajax()) {
            $categories = Category::latest()->get();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if ($row->image) {
                        return '<img src="' . asset($row->image) . '" width="50" height="50" class="rounded">';
                    }
                    return '<span class="badge bg-secondary">No Image</span>';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status ? 'checked' : '';
                    $disabled = auth()->user()->can('category_edit') ? '' : 'disabled';

                    return '<div class="form-check form-switch form-switch-right form-switch-md">
                        <input class="form-check-input status-switch"
                               type="checkbox"
                               data-id="' . $row->id . '"
                               data-type="category"
                               ' . $checked . ' ' . $disabled . '>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $action = '';

                    // Edit button
                    if (auth()->user()->can('category_edit')) {
                        $action .= '<a href="' . route('admin.categories.edit', $row->id) . '"
                               class="btn btn-sm btn-primary me-1">
                               <i class="fa-regular fa-pen-to-square"></i>
                            </a>';
                    }

                    // Delete button
                    if (auth()->user()->can('category_delete')) {
                        $action .= '<form action="' . route('admin.categories.destroy', $row->id) . '"
                                method="POST" style="display:inline-block;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger delete-button">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>';
                    }

                    return $action;
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.layouts.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name|max:255',
            // 'slug' => 'required|string|unique:categories,slug|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:0,1',
        ]);

        // Handle image upload
        $imageUrl = null;

        if ($request->file('image')) {
            $image     = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $directory = 'uploads/categories-images/';
            // Create directory if it doesn't exist
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }
            // $image->move($directory, $imageName); //for normal image upload

            // Resize to 60x60 (image intervention)
            $resizedImage = Image::make($image)->resize(60, 60);
            $resizedImage->save(public_path($directory . $imageName));

            $imageUrl = $directory . $imageName;
        }

        $category                 = new Category();
        $category->name           = $request->name;
        // $category->slug           = Str::slug($request->name); //slug is handled in model
        $category->image          = $imageUrl;
        $category->status         = $request->status;
        $category->save();

        return back()->with('success', 'New Category information added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.layouts.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            // 'slug' => 'required|string|unique:categories,slug|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:0,1',
        ]);

        // Image update or keep old or remove
        $imageUrl = $category->image;
        if ($request->file('image')) {
            if ($category->image && file_exists($category->image)) {
                unlink($category->image);
            }

            // Upload new image
            $image     = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $directory = 'uploads/categories-images/';
            // $image->move($directory, $imageName);

            $resizedImage = Image::make($image)->resize(60, 60); // Resize to 60x60 (image intervention)
            $resizedImage->save(public_path($directory . $imageName));
            $imageUrl = $directory . $imageName;
        }
        // Check if removed the image
        elseif ($request->input('image') === null) {
            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }
            $imageUrl = null;
        }

        $category->name           = $request->name;
        // $category->slug           = Str::slug($request->name); //slug is handled in model
        $category->image          = $imageUrl;
        $category->status         = $request->status;
        $category->save();

        return back()->with('success', 'Category information updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->slug === 'uncategorized') {
            return redirect()->back()->with('error', 'Cannot delete Uncategorized category.');
        }

        $uncategorizedId = Category::where('slug', 'uncategorized')->first()->id;

        // Reassign products
        Product::where('category_id', $category->id)
            ->update(['category_id' => $uncategorizedId]);

        // Delete image if exists
        if ($category->image && file_exists($category->image)) {
            unlink($category->image);
        }

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted and products reassigned.');
    }
}
