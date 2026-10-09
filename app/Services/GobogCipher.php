<?php

namespace App\Services;

class GobogCipher
{
    private string $cipher = 'aes-256-cbc';

    public function encrypt(string $plainText): string
    {
        $key = $this->getKey();
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $encrypted = openssl_encrypt($plainText, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv.$encrypted);
    }

    public function decrypt(string $cipherText): string|false
    {
        $key = $this->getKey();
        $raw = base64_decode($cipherText, true);

        if ($raw === false) {
            return false;
        }

        $ivLength = openssl_cipher_iv_length($this->cipher);

        if (strlen($raw) <= $ivLength) {
            return false;
        }

        $iv = substr($raw, 0, $ivLength);
        $encrypted = substr($raw, $ivLength);

        return openssl_decrypt($encrypted, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);
    }

    private function getKey(): string
    {
        $appKey = config('app.key');

        if (str_starts_with($appKey, 'base64:')) {
            return base64_decode(substr($appKey, 7));
        }

        return $appKey;
    }
}
