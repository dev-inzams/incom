<?php

namespace App\Http\Controllers;

use App\Helper\AmarPay;
use App\Helper\ResponseHelper;
use App\Helper\SSLCommerce;
use App\Models\AmarpayAccount;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\ProductCart;
use Illuminate\Http\Request;
use App\Models\CustomerProfile;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller {

    // invoice create
    public function CreateInvoice(Request $request) {
        DB::beginTransaction();
        try{

            $user_id = $request->header('id');
            $user_email = $request->header('email');

            $tran_id = uniqid();
            $delivery_status = 'Pending';
            $payment_status = 'Pending';

            $profile = CustomerProfile::where('user_id', $user_id)->first();
            $cus_details = "Name: $profile->cus_name, Address:$profile->cus_add, City:$profile->cus_city,Phone:$profile->cus_phone, Country:$profile->cus_country";
            $ship_details = "Name: $profile->ship_name, Address:$profile->ship_add, City:$profile->ship_city,Phone:$profile->ship_phone, Country:$profile->ship_country";

            // payable calculation
            $total = 0;
            $cartlist = ProductCart::where('user_id', $user_id)->get();
            foreach($cartlist as $item) {
                $total = $total + $item->price;
            }
            $vat = ($total * 3) / 100;
            $payable = $total + $vat;

            $invoice = Invoice::create([
                'total' => $total,
                'vat' => $vat,
                'payable' => $payable,
                'cus_details' => $cus_details,
                'ship_details' => $ship_details,
                'tran_id' => $tran_id,
                'val_id' => 0,
                'delivery_status' => $delivery_status,
                'payment_status' => $payment_status,
                'user_id' => $user_id
            ]);

            $invoice_id = $invoice->id;

            foreach($cartlist as $item) {
                InvoiceProduct::create([
                    'invoice_id' => $invoice_id,
                    'product_id' => $item['product_id'],
                    'user_id' => $user_id,
                    'qty' => $item['qty'],
                    'sale_price' => $item['price']
                ]);
            }

            // remove cart
            ProductCart::where('user_id', $user_id)->delete();

            $paymentMethod = SSLCommerce::InitiatePayment($profile, $payable, $tran_id, $user_email);
        DB::commit();
        return ResponseHelper::Out('success', array([
            'paymentMethod' => $paymentMethod,
            'payable' => $payable,
            'Vat' => $vat,
            'total' => $total
        ]), 200);

        }catch(\Exception $e) {
            DB::rollBack();
            return ResponseHelper::Out('error', $e->getMessage(), 200);
        }
    } // end of create invoice

} // end of class
