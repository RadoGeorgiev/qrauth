<?php

namespace App\Http\Controllers;

use App\Models\ValidUsers;
use Illuminate\Http\Request;
use OTPHP\TOTP;

class VerifyController extends Controller
{
    /**
     * Verify Authenticator code for user
     */
    public function verifyTOTP(Request $request, $username, $totpCode): string
    {
        // Retrieve the secret associated with the username
        $secret = self::getSecretForUsername($username);

        if (!$secret) {
            return 'User not registered!';
        }

        // Verify the TOTP code
        $otp = TOTP::createFromSecret($secret);

        $res = $otp->verify($totpCode);

        if ($res) {
            return "TOTP code is valid";
        } else {
            return "TOTP code is NOT valid";
        }
    }

    public static function getSecretForUsername(string $username): ?string
    {
        $model_instance = ValidUsers::where('username', $username)->get('secret');

        if ($model_instance->contains(ValidUsers::find($username))){
            return json_decode($model_instance)[0]->secret;
        }

        return null;
    }
}
