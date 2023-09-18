<?php

namespace App\Http\Controllers;

use App\Models\ValidUsers;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use OTPHP\TOTP;


class GenerateTotpController extends Controller
{
    /**
     * Main method - write new user and return QR if ok
     */
    public function index(Request $request, $label, $username)
    {
        // Generate a random secret
        $totp = TOTP::create();
        $totp->setLabel($label);
        $totp->setIssuer($username);

        $secret = $totp->getSecret();

        // Write user and secret in DB
        if (self::newUserInsert($label, $username, $secret)) {
            // Create QR code and return image
            $uri = $totp->getProvisioningUri();
            return '<img src=' . self::createQR($uri) . ' alt="QR Code">';
        }
        return 'User already created!';
    }

    /**
     * Generates QR code and returns .png image preview
     */
    public static function createQR($uri): string
    {        
        $writer = new PngWriter();

        $qrCode = QrCode::create($uri)
                    ->setEncoding(new Encoding('UTF-8'))
                    ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                    ->setSize(300)
                    ->setMargin(10)
                    ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                    ->setForegroundColor(new Color(0, 0, 0))
                    ->setBackgroundColor(new Color(255, 255, 255));

        $result = $writer->write($qrCode);

        return $result->getDataUri();
    }

    /**
     * Insert new data into the db table
     */
    public static function newUserInsert(string $label, string $username, string $secret): bool
    {
        if (!self::usernameExist($username)) {
            ValidUsers::create([
                'label' => $label,
                'username' => $username,
                'secret' => $secret,
            ]);
            return true;  
        }
        return false;
    }
    
    /**
     * Check if category exists
     */
    public static function usernameExist(string $username): bool
    {
        return ValidUsers::where('username', $username)->exists();
    }   
}
