<?php

namespace App\Http\Controllers\Backend\Farhad;

use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:dashboard_view')->only(['index']);
    }

    public function index()
    {
        $totalProducts = Product::count();
        $totalBrands = Brand::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();

        return view('backend.dashboard', compact('totalProducts', 'totalBrands', 'totalCategories', 'totalUsers'));
        // return view('backend.dashboard');
    }
}
