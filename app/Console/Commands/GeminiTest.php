<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiTest extends Command
{
    protected $signature = 'gemini:test';
    protected $description = 'Test Gemini API connection and configuration';

    public function handle()
    {
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-3.5-flash');
        $baseUrl = rtrim(config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'), '/');

        $this->info("====================================");
        $this->info("    Diagnostic: Gemini API Test     ");
        $this->info("====================================");

        if (empty($apiKey)) {
            $this->error('✗ API Key tidak ditemukan di file .env atau config.');
            return 1;
        } else {
            $this->info('✓ API Key ditemukan');
        }

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => 'Halo, ini adalah tes koneksi singkat.']]
                ]
            ]
        ];

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $apiKey,
                ])
                ->post("{$baseUrl}/models/{$model}:generateContent", $payload);
            
            // Checking HTTP Client success
            if ($response->successful()) {
                $this->info('✓ Endpoint dapat diakses');
                $this->info('✓ Model tersedia');
                $this->info('✓ Gemini membalas');
                $this->info("\n--- Detail Balasan ---");
                $reply = data_get($response->json(), 'candidates.0.content.parts.0.text', '(kosong)');
                $this->line($reply);
                return 0;
            } else {
                $status = $response->status();
                $body = $response->json();
                
                Log::error('Gemini CLI Test Error', [
                    'status' => $status,
                    'body' => $body
                ]);

                // Specifically catching error types based on standard Google API codes
                if ($status === 400) {
                    $this->error('✗ Permintaan tidak valid (HTTP 400)');
                    if (isset($body['error']['message']) && str_contains(strtolower($body['error']['message']), 'api key not valid')) {
                        $this->error('✗ API Key invalid (Ditolak oleh Google)');
                    }
                } elseif ($status === 401 || $status === 403) {
                    $this->error("✗ HTTP {$status} (Akses ditolak)");
                    $this->error('✗ API Key invalid atau tidak memiliki izin');
                } elseif ($status === 404) {
                    $this->error('✓ Endpoint dapat diakses (berhasil menghubungi server)');
                    $this->error("✗ HTTP 404");
                    $this->error("✗ Model '{$model}' tidak ditemukan atau salah ketik");
                } elseif ($status === 429) {
                    $this->error("✗ HTTP 429 (Terlalu banyak request / Quota habis)");
                } else {
                    $this->error("✗ Kegagalan API (HTTP {$status})");
                }

                $this->newLine();
                $this->error('Pesan Error Asli:');
                $this->line(json_encode($body, JSON_PRETTY_PRINT));
                return 1;
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->error('✗ Timeout atau Gagal terhubung ke Endpoint');
            $this->error('Error Message: ' . $e->getMessage());
            return 1;
        } catch (\Exception $e) {
            $this->error('✗ Terjadi Exception yang tidak terduga');
            $this->error('Error Message: ' . $e->getMessage());
            return 1;
        }
    }
}
