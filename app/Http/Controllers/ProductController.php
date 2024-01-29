<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductReview;
use App\Models\ProductSlider;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;

class ProductController extends Controller
{
    // list product by category
    public function ListProductByCategory(Request $request) {
        $id = $request->id;
        $data = Product::where('category_id', '=', $id)->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    // list product by remark
    public function ListProductByRemark(Request $request) {
        $data = Product::where('remark', '=', $request->remark)->with('category', 'brand')->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    // list product by brand
    public function ListProductByBrand(Request $request) {
        $data = Product::where('brand_id', '=', $request->id)->with('category', 'brand')->get();
        return ResponseHelper::Out('success', $data, 200);
    }

    // list product by slider
    public function ListProductBySlider() {
        $data = ProductSlider::all();
        return ResponseHelper::Out('success', $data, 200);
    }

    // product details by id
    public function ProductDetailsById(Request $request) {
        $id = $request->id;
        $data = ProductDetail::where('product_id', '=', $id)->with('product','product.category', 'product.brand')->first();
        return ResponseHelper::Out('success', $data, 200);

    }

    // list review by product
    public function ListReviewByProduct(Request $request) {
        $data = ProductReview::where('product_id', '=', $request->product_id)
                ->with(['profile'=>function($query){$query->select('id','cus_name');}])
                ->get();
        return ResponseHelper::Out('success', $data, 200);
    }
}
