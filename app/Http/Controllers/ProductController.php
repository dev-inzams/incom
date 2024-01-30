<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductWish;
use Illuminate\Http\Request;
use App\Models\ProductDetail;
use App\Models\ProductReview;
use App\Models\ProductSlider;
use App\Helper\ResponseHelper;
use App\Models\CustomerProfile;

class ProductController extends Controller {
    // list product by category
    public function ListProductByCategory( Request $request ) {
        $id = $request->id;
        $data = Product::where( 'category_id', '=', $id )->get();
        return ResponseHelper::Out( 'success', $data, 200 );
    }

    // list product by remark
    public function ListProductByRemark( Request $request ) {
        $data = Product::where( 'remark', '=', $request->remark )->with( 'category', 'brand' )->get();
        return ResponseHelper::Out( 'success', $data, 200 );
    }

    // list product by brand
    public function ListProductByBrand( Request $request ) {
        $data = Product::where( 'brand_id', '=', $request->id )->with( 'category', 'brand' )->get();
        return ResponseHelper::Out( 'success', $data, 200 );
    }

    // list product by slider
    public function ListProductBySlider() {
        $data = ProductSlider::all();
        return ResponseHelper::Out( 'success', $data, 200 );
    }

    // product details by id
    public function ProductDetailsById( Request $request ) {
        $id = $request->id;
        $data = ProductDetail::where( 'product_id', '=', $id )->with( 'product', 'product.category', 'product.brand' )->first();
        return ResponseHelper::Out( 'success', $data, 200 );

    }

    // list review by product
    public function ListReviewByProduct( Request $request ) {
        $data = ProductReview::where( 'product_id', '=', $request->product_id )
            ->with( ['profile' => function ( $query ) {$query->select( 'id', 'cus_name' );}] )
            ->get();
        return ResponseHelper::Out( 'success', $data, 200 );
    }






    // create review
    public function CreateOrupdateReview( Request $request ) {
        $user_id = $request->header('id');
        $profile = CustomerProfile::where( 'user_id', '=', $user_id )->first();
        if( $profile ) {
            $request->merge([ 'customer_id' => $profile->id ]);
            $data = ProductReview::updateOrCreate(
                ['customer_id' => $profile->id, 'product_id' => $request->input('product_id')],
                [
                    'customer_id' => $profile->id,
                    'product_id' => $request->input('product_id'),
                    'description' => $request->input('description'),
                    'rating' => $request->input('rating')
                ]
             );
        return ResponseHelper::Out( 'success', $data, 200 );
        }else{
            return ResponseHelper::Out( 'error', 'customer profile not found', 404 );
        }

    }





    // product wish list
    public function getWishList( Request $request ) {
        $user_id = $request->header('id' );
        $data = ProductWish::where( 'user_id', '=', $user_id )->with( 'product' )->get();
        return ResponseHelper::Out( 'success', $data, 200 );
    } // end getWishList

    // add to wish list
    public function addToWishList( Request $request ) {
        $user_id = $request->header('id');
        $data = ProductWish::updateOrCreate(
            ['user_id' => $user_id, 'product_id' => $request->input('product_id')],
            [
                'user_id' => $user_id,
                'product_id' => $request->input('product_id')
            ]
        );
        return ResponseHelper::Out( 'success', $data, 200 );
    } // end addToWishList

    // remove from wish list
    public function removeFromWishList( Request $request ) {
        $user_id = $request->header('id');
        $data = ProductWish::where( 'user_id', '=', $user_id )
            ->where( 'product_id', '=', $request->input('product_id') )
            ->delete();
        return ResponseHelper::Out( 'success', $data, 200 );
    } // end removeFromWishList




    // product cart
    public function getCart( Request $request ) {
        $user_id = $request->header('id');
        $data = ProductCart::where( 'user_id', '=', $user_id )->with( 'product' )->get();
        return ResponseHelper::Out( 'success', $data, 200 );
    } // end getCart

    // add to cart
    public function addToCart( Request $request ) {
        $user_id = $request->header('id');
        $product_id = $request->input('product_id');
        $color = $request->input('color');
        $size = $request->input('size');
        $qty = $request->input('qty');

        $UnitPrice = 0;
        $productDetails = Product::where( 'id', '=', $product_id )->first();
        if( $productDetails->discount == 1 ) {
            $UnitPrice = $productDetails->discount_price;
        }else{
            $UnitPrice = $productDetails->price;
        }

        $totalPrice = $UnitPrice * $qty;

        $data = ProductCart::updateOrCreate(
            ['user_id' => $user_id, 'product_id' => $product_id],
            [
                'user_id' => $user_id,
                'product_id' => $product_id,
                'color' => $color,
                'size' => $size,
                'qty' => $qty,
                'price' => $totalPrice
            ]
        );
        return ResponseHelper::Out( 'success', $data, 200 );
    } // end addToCart

    // remove from cart
    public function removeFromCart( Request $request ) {
        $user_id = $request->header('id');
        $product_id = $request->input('product_id');
        $data = ProductCart::where( 'user_id', '=', $user_id )
            ->where( 'product_id', '=', $product_id )
            ->delete();
        return ResponseHelper::Out( 'success', $data, 200 );
    } // end removeFromCart




} // end class
