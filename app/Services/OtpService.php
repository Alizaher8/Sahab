<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Models\User;
use App\Notifications\SendOtpNotification;

class OtpService
{
    public function generateOtp($phoneNumber)
    {
        $otp = mt_rand(1000, 9999); // Generate a 4-digit OTP
        OtpCode::updateOrCreate(
            ['phone_number' => $phoneNumber],
            ['code' => $otp]
        );

        // Send the OTP via SMS
        $user = User::where('phone_number', $phoneNumber)->first();
        $user->notify(new SendOtpNotification($otp));
    }

    public function verifyOtp($phoneNumber, $otp)
    {
        $storedOtp = OtpCode::where('phone_number', $phoneNumber)->first();

        if ($storedOtp && $storedOtp->code == $otp) {
            $storedOtp->delete(); // Delete the OTP after successful verification
            return true;
        }

        return false;
    }
}
