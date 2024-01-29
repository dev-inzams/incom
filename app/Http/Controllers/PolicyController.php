<?php

namespace App\Http\Controllers;
use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller {

    public function PolicyByType(Request $resquest) {
        return Policy::where('type', '=',$resquest->type)->first();
    }
}
