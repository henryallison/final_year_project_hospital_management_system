<?php

namespace App\Providers\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class EncryptionService
{
    protected string $method = 'aes-256-cbc';
    protected string $key;
    protected int $ivLength;

    public function __construct()
    {
        $this->key = $this->validateKey(config('app.encryption_key'));
        $this->ivLength = openssl_cipher_iv_length($this->method);

        if ($this->ivLength === false) {
            throw new RuntimeException('Invalid cipher method');
        }
    }

    protected function validateKey(string $key): string
    {
        if (strlen($key) !== 32) {
            throw new RuntimeException('Encryption key must be 32 characters long');
        }
        return $key;
    }

    public function encrypt($data): string
    {
        try {
            $iv = random_bytes($this->ivLength);
            $encrypted = openssl_encrypt(
                $data,
                $this->method,
                $this->key,
                OPENSSL_RAW_DATA,
                $iv
            );

            if ($encrypted === false) {
                throw new RuntimeException('Encryption failed: ' . openssl_error_string());
            }

            return base64_encode($iv . $encrypted);
        } catch (\Exception $e) {
            Log::error("Encryption failed", [
                'error' => $e->getMessage(),
                'data' => Str::limit($data, 50)
            ]);
            throw $e;
        }
    }

    public function decrypt(string $payload): string
    {
        try {
            $data = base64_decode($payload);
            $iv = substr($data, 0, $this->ivLength);
            $encrypted = substr($data, $this->ivLength);

            $decrypted = openssl_decrypt(
                $encrypted,
                $this->method,
                $this->key,
                OPENSSL_RAW_DATA,
                $iv
            );

            if ($decrypted === false) {
                throw new RuntimeException('Decryption failed: ' . openssl_error_string());
            }

            return $decrypted;
        } catch (\Exception $e) {
            Log::error("Decryption failed", [
                'error' => $e->getMessage(),
                'payload' => Str::limit($payload, 50)
            ]);
            throw $e;
        }
    }

    public function benchmark(int $size = 1024): array
    {
        $testData = Str::random($size);

        $start = microtime(true);
        $encrypted = $this->encrypt($testData);
        $encryptTime = microtime(true) - $start;

        $start = microtime(true);
        $decrypted = $this->decrypt($encrypted);
        $decryptTime = microtime(true) - $start;

        if ($decrypted !== $testData) {
            throw new RuntimeException('Benchmark validation failed');
        }

        return [
            'data_size' => $size,
            'encrypt_time' => $encryptTime,
            'decrypt_time' => $decryptTime,
            'encrypt_throughput' => $size / $encryptTime,
            'decrypt_throughput' => $size / $decryptTime,
            'cipher_method' => $this->method
        ];
    }

    public function generateKey(): string
    {
        return base64_encode(random_bytes(32));
    }
}
