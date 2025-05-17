<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\EncryptionService;

class SecurityTest extends TestCase
{
    public function test_encryption_performance()
    {
        $service = new EncryptionService();
        $results = $service->benchmark();

        $this->assertLessThan(0.1, $results['encrypt_1kb'],
            "Encryption should complete within 100ms for 1KB data");

        $this->assertLessThan(0.1, $results['decrypt_1kb'],
            "Decryption should complete within 100ms for 1KB data");
    }

    public function test_https_handshake()
    {
        $start = microtime(true);
        $response = $this->get('https://hospital.test/login');
        $handshakeTime = microtime(true) - $start;

        $response->assertOk();
        $this->assertLessThan(0.5, $handshakeTime,
            "TLS 1.3 handshake should complete within 500ms");
    }

    public function test_ai_access_control()
    {
        // Test different role combinations
        $testCases = [
            ['role' => 'doctor', 'resource' => 'patient_records', 'expected' => 200],
            ['role' => 'nurse', 'resource' => 'lab_results', 'expected' => 200],
            ['role' => 'reception', 'resource' => 'billing', 'expected' => 403],
        ];

        foreach ($testCases as $case) {
            $user = User::factory()->create(['role' => $case['role']]);
            $response = $this->actingAs($user)
                ->get("/api/{$case['resource']}");

            $response->assertStatus($case['expected']);
        }
    }
}
