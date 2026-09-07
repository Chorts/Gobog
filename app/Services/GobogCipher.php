<?php

namespace App\Services;

/**
 * Service untuk enkripsi dan dekripsi kode unik koin Gobog
 * menggunakan AES-256-CBC dengan APP_KEY sebagai basis key.
 *
 * Format simpan: base64(iv):base64(ciphertext)
 */
class GobogCipher
{
    private const CIPHER = 'aes-256-cbc';

    /**
     * Decode APP_KEY menjadi 32-byte binary key yang siap dipakai AES-256-CBC.
     * APP_KEY di .env disimpan dengan prefix "base64:", sehingga wajib di-decode dulu.
     */
    private function getKey(): string
    {
        return base64_decode(substr(config('app.key'), 7));
    }

    /**
     * Enkripsi plaintext menggunakan AES-256-CBC.
     *
     * @return string Format: base64(iv):base64(ciphertext)
     */
    public function encrypt(string $plaintext): string
    {
        $key = $this->getKey();
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::CIPHER));
        $ciphertext = openssl_encrypt($plaintext, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv).':'.base64_encode($ciphertext);
    }

    /**
     * Dekripsi string hasil enkripsi kembali ke plaintext (kode_unik).
     * Return false jika format tidak valid atau dekripsi gagal (QR palsu).
     */
    public function decrypt(string $encrypted): string|false
    {
        if (! str_contains($encrypted, ':')) {
            return false;
        }

        [$ivB64, $ctB64] = explode(':', $encrypted, 2);

        $iv = base64_decode($ivB64, true);
        $ciphertext = base64_decode($ctB64, true);

        if ($iv === false || $ciphertext === false) {
            return false;
        }

        $key = $this->getKey();

        return openssl_decrypt($ciphertext, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
    }
}
