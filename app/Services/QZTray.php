<?php

namespace App\Services;

class QZTray
{
    /**
     * sign a printing request message with QZ Tray private key
     * 
     * @param string $message
     * @return string $signedMessage
     */
    public function signMessage($message)
    {
        $KEY_PATH = config('qzTray.private_key');

        // openssl_pkey_get_private()
        $privateKey = openssl_get_privatekey(file_get_contents($KEY_PATH) /*, $PASS */);

        $signature = null;
        openssl_sign($message, $signature, $privateKey, "sha512"); // Use "sha1" for QZ Tray 2.0 and older

        if ($signature) {
            return base64_encode($signature);
        }

        return null;
    }
}
