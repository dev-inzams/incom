<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Helper\ResponseHelper;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller {

    public function userLogin( Request $request ) {
        try {
            $request->validate( [
                'email' => 'required|email',
            ] );

            $email = $request->input( 'email' );
            $OTP = rand( 1000, 9999 );
            Mail::to( $email )->send( new OTPMail( $OTP ) );

            User::updateOrCreate(
                ['email' => $email],
                ['email' => $email, 'otp' => $OTP]
            );

            return ResponseHelper::Out( 'success', '4 digit OTP sent', 200 );

        } catch ( \Exception $e ) {
            return ResponseHelper::Out( 'fail', $e->getMessage(), 200 );
        }
    }

    // verify login otp
    public function userLoginVerify( Request $request ) {
        try {
            $request->validate( [
                'email' => 'required|email',
                'otp'   => 'required|digits:4',
            ] );

            $email = $request->input( 'email' );
            $otp = $request->input( 'otp' );

            $user = User::where( 'email', $email )->where( 'otp', $otp )->first();

            if ( $user ) {
                $token = JWTToken::CreateToken( $email, $user->id );
                User::where( 'email', $email )->update( ['otp' => '0000'] );
                return ResponseHelper::Out( 'success', 'OTP verified', 200 )->cookie( 'token', $token, 3600 * 3600 );
            } else {
                return ResponseHelper::Out( 'fail', 'Invalid OTP', 200 );
            }
        } catch ( \Exception $e ) {
            return ResponseHelper::Out( 'fail', $e->getMessage(), 200 );
        }
    }

    // user logout
    public function userLogout() {
        return ResponseHelper::Out( 'success', 'User logged out', 200 )->cookie( 'token', '', -1 );
    }
}
