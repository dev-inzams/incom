<?php

namespace App\Http\Controllers;
use App\Helper\ResponseHelper;
use App\Models\Category;

class CategoryController extends Controller {

    public function CategoryList() {
        $data = Category::all();
        return ResponseHelper::Out('success', $data, 200);
    }
}
