<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Helper\SSLCommerce;
use App\Models\CustomerProfile;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller {

    // invoice create
    public function CreateInvoice( Request $request ) {
        DB::beginTransaction();
        try {

            $user_id = $request->header( 'id' );
            $user_email = $request->header( 'email' );

            $tran_id = uniqid();
            $delivery_status = 'Pending';
            $payment_status = 'Pending';

            $profile = CustomerProfile::where( 'user_id', $user_id )->first();
            $cus_details = "Name: $profile->cus_name, Address:$profile->cus_add, City:$profile->cus_city,Phone:$profile->cus_phone, Country:$profile->cus_country";
            $ship_details = "Name: $profile->ship_name, Address:$profile->ship_add, City:$profile->ship_city,Phone:$profile->ship_phone, Country:$profile->ship_country";

            // payable calculation
            $total = 0;
            $cartlist = ProductCart::where( 'user_id', $user_id )->get();
            foreach ( $cartlist as $item ) {
                $total = $total + $item->price;
            }
            $vat = ( $total * 3 ) / 100;
            $payable = $total + $vat;

            $invoice = Invoice::create( [
                'total'           => $total,
                'vat'             => $vat,
                'payable'         => $payable,
                'cus_details'     => $cus_details,
                'ship_details'    => $ship_details,
                'tran_id'         => $tran_id,
                'val_id'          => 0,
                'delivery_status' => $delivery_status,
                'payment_status'  => $payment_status,
                'user_id'         => $user_id,
            ] );

            $invoice_id = $invoice->id;

            foreach ( $cartlist as $item ) {
                InvoiceProduct::create( [
                    'invoice_id' => $invoice_id,
                    'product_id' => $item['product_id'],
                    'user_id'    => $user_id,
                    'qty'        => $item['qty'],
                    'sale_price' => $item['price'],
                ] );
            }

            // remove cart
            ProductCart::where( 'user_id', $user_id )->delete();

            $paymentMethod = SSLCommerce::InitiatePayment( $profile, $payable, $tran_id, $user_email );
            DB::commit();
            return ResponseHelper::Out( 'success', [ [
                'paymentMethod' => $paymentMethod,
                'payable'       => $payable,
                'Vat'           => $vat,
                'total'         => $total,
            ] ], 200 );

        } catch ( \Exception $e ) {
            DB::rollBack();
            return ResponseHelper::Out( 'error', $e->getMessage(), 200 );
        }
    } // end of create invoice

    // get invoice list
    function GetInvoiceList( Request $request ) {
        $user_id = $request->header( 'id' );
        $invoices = Invoice::where( 'user_id', $user_id )->get();
        return ResponseHelper::Out( 'success', $invoices, 200 );
    } // end of get invoice list

    // invoice product list
    function GetInvoiceProductList( Request $request ) {
        $user_id = $request->header( 'id' );
        $invoice_id = $request->input( 'invoice_id' );
        $products = InvoiceProduct::where( ['user_id' => $user_id, 'invoice_id' => $invoice_id] )->with( 'product' )->get();
        return ResponseHelper::Out( 'success', $products, 200 );
    } // end of invoice product list

    // payment success
    function PaymentSuccess( Request $request ) {
        return SSLCommerce::InitiateSuccess( $request->query( 'tran_id' ) );
    } // end of payment success

    // payment failed
    function PaymentFailed( Request $request ) {
        return SSLCommerce::InitiateFail( $request->query( 'tran_id' ) );
    } // end of payment failed

    // payment cancel
    public function PaymentCancel( Request $request ) {
        return SSLCommerce::InitiateCancel( $request->query( 'tran_id' ));
    }

    // payment ipn
    public function PaymentIPN(Request $request) {
        $tran_id = $request->input('tran_id');
        $status = $request->input('status');
        $val_id = $request->input('val_id');

        return SSLCommerce::InitiateIPN($tran_id, $status, $val_id);
    }
} // end of class
