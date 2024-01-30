<?php

namespace App\Helper;
use App\Models\AmarpayAccount;
use App\Models\Invoice;
use Illuminate\Support\Facades\Http;

class AmarPay {

   static function InitiatePayment($profile, $payable, $tran_id, $user_email) {
        try{

            $amar = AmarpayAccount::first();

            $response = Http::asForm()->post($amar->init_url, [
                "store_id" => $amar->store_id,
                "signature_key" => $amar->secret_key,
                "tran_id" => $tran_id,
                "amount" => $payable,
                "currency" => "BDT",
                "desc" => "Payment",
                "cus_name" => $profile->cus_name,
                "cus_email" => $user_email,
                "cus_phone" => $profile->cus_phone,
                "success_url" => "$amar->success_url?tran_id= $tran_id",
                "fail_url" => "$amar->fail_url?tran_id= $tran_id",
                "cancel_url" => "$amar->cancel_url?tran_id= $tran_id",
                "type" => "json",
            ]);
            return $response;
        }catch(\Exception $e) {
            return $e->getMessage();
        }
    }


    static function InitiateFail($tran_id) {
        Invoice::where(['tran_id', $tran_id , 'val_id' => 0])->update(['payment_status' => 'Failed']);
        return true;
    }

    static function InitiateSuccess($tran_id) {
        Invoice::where(['tran_id', $tran_id , 'val_id' => 0])->update(['payment_status' => 'Success']);
        return true;
    }

    static function InitiateCancel($tran_id) {
        Invoice::where(['tran_id', $tran_id , 'val_id' => 0])->update(['payment_status' => 'Cancelled']);
        return true;
    }

    static function InitiateIPN($tran_id , $status, $val_id) {
        Invoice::where(['tran_id', $tran_id , 'val_id' => 0])->update(['payment_status' => $status, 'val_id' => $val_id]);
        return true;
    }


} // end of class

