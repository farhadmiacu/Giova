<?php

namespace App\Http\Controllers\Backend\Farhad;

use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatusController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:category,brand,product,user',
            'id'   => 'required|integer',
            'status' => 'required|in:0,1',
        ]);

        $model = null;

        switch ($request->type) {
            case 'category':
                $model = Category::findOrFail($request->id);
                break;
            case 'brand':
                $model = Brand::findOrFail($request->id);
                break;
            case 'product':
                $model = Product::findOrFail($request->id);
                break;
            case 'user':
                $model = User::findOrFail($request->id);
                break;
        }

        $model->status = $request->status;
        $model->save();

        return response()->json([
            'success' => true,
            'message' => ucfirst($request->type) . ' status updated successfully!'
        ]);
    }

    public function updateSecond(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:category,brand,product,user',
            'id'   => 'required|integer',
            'status' => 'required|in:0,1',
        ]);

        // Determine the required permission dynamically
        $permission = match ($request->type) {
            'category' => 'category_edit',
            'brand'    => 'brand_edit',
            'product'  => 'product_edit',
            'user'     => 'user_edit',
            default    => null,
        };

        // Check if user has the required permission
        if (!auth()->user()->can($permission)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update this status.'
            ], 403);
        }

        // Find model
        $model = match ($request->type) {
            'category' => Category::findOrFail($request->id),
            'brand'    => Brand::findOrFail($request->id),
            'product'  => Product::findOrFail($request->id),
            'user'     => User::findOrFail($request->id),
        };

        $model->status = $request->status;
        $model->save();

        return response()->json([
            'success' => true,
            'message' => ucfirst($request->type) . ' status updated successfully!'
        ]);
    }
}
