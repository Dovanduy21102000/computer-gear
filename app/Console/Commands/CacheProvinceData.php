<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheProvinceData extends Command
{
    protected $signature = 'cache:provinces';
    protected $description = 'Fetch and cache province and district data from provinces.open-api.vn';

    protected $maxRetries = 3;
    protected $retryDelay = 5; // seconds

    protected function fetchWithRetry($url, $retryCount = 0)
    {
        try {
            $response = Http::timeout(60) // Increased timeout to 60 seconds
                ->retry(3, 1000) // Retry 3 times with 1 second delay
                ->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception("HTTP request failed with status: " . $response->status());
        } catch (\Exception $e) {
            if ($retryCount < $this->maxRetries) {
                $this->warn("Attempt {$retryCount} failed for {$url}. Retrying in {$this->retryDelay} seconds...");
                sleep($this->retryDelay);
                return $this->fetchWithRetry($url, $retryCount + 1);
            }
            throw $e;
        }
    }

    public function handle()
    {
        try {
            $this->info('Fetching provinces data...');

            // Fetch provinces with retry logic
            $provinces = $this->fetchWithRetry('https://provinces.open-api.vn/api/');

            // Cache provinces
            Cache::put('provinces', $provinces, now()->addDays(30));
            $this->info('Provinces data cached successfully.');

            // Fetch and cache districts for each province
            $this->info('Fetching districts data...');
            $districtsByProvince = [];
            $totalProvinces = count($provinces);

            foreach ($provinces as $index => $province) {
                $this->info("Fetching districts for province " . ($index + 1) . "/{$totalProvinces}: {$province['name']}");

                try {
                    $districts = $this->fetchWithRetry("https://provinces.open-api.vn/api/p/{$province['code']}?depth=2");
                    $districtsByProvince[$province['code']] = $districts['districts'] ?? [];

                    // Cache individual province districts
                    Cache::put("districts_province_{$province['code']}", $districts['districts'] ?? [], now()->addDays(30));

                    // Add a small delay between requests to avoid overwhelming the API
                    usleep(500000); // 0.5 second delay
                } catch (\Exception $e) {
                    $this->error("Failed to fetch districts for province {$province['name']}: " . $e->getMessage());
                    $districtsByProvince[$province['code']] = [];
                    continue; // Continue with next province even if this one fails
                }
            }

            // Cache all districts data
            Cache::put('districts_by_province', $districtsByProvince, now()->addDays(30));

            $this->info('Districts data cached successfully.');
            return 0;
        } catch (\Exception $e) {
            $this->error('Error caching province data: ' . $e->getMessage());
            Log::error('Error caching province data:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}
