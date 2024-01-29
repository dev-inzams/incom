<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use App\Helper\ResponseHelper;

class BrandController extends Controller {

    public function BrandList() {
        $data = Brand::all();
        return ResponseHelper::Out('success', $data, 200);
    }
}
