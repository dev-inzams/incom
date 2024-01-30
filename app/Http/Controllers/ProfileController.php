<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;

class ProfileController extends Controller {
    // create profile
    public function createProfile(Request $request) {
        try{
            $user_id = $request->header('id');
            $cus_name = $request->input('cus_name');
            $cus_add = $request->input('cus_add');
            $cus_city = $request->input('cus_city');
            $cus_state = $request->input('cus_state');
            $cus_postcode = $request->input('cus_postcode');
            $cus_country = $request->input('cus_country');
            $cus_phone = $request->input('cus_phone');
            $cus_fax = $request->input('cus_fax');

            $ship_name = $request->input('ship_name');
            $ship_add = $request->input('ship_add');
            $ship_city = $request->input('ship_city');
            $ship_state = $request->input('ship_state');
            $ship_postcode = $request->input('ship_postcode');
            $ship_country = $request->input('ship_country');
            $ship_phone = $request->input('ship_phone');

        CustomerProfile::create([
            'user_id' => $user_id,
            'cus_name' => $cus_name,
            'cus_add' => $cus_add,
            'cus_city' => $cus_city,
            'cus_state' => $cus_state,
            'cus_postcode' => $cus_postcode,
            'cus_country' => $cus_country,
            'cus_phone' => $cus_phone,
            'cus_fax' => $cus_fax,

            'ship_name' => $ship_name,
            'ship_add' => $ship_add,
            'ship_city' => $ship_city,
            'ship_state' => $ship_state,
            'ship_postcode' => $ship_postcode,
            'ship_country' => $ship_country,
            'ship_phone' => $ship_phone

        ]);
        return ResponseHelper::Out('success', 'Profile created successfully', 200);
        }catch(\Exception $e){
            return ResponseHelper::Out('error', $e->getMessage(), 200);
        }
    }
}
