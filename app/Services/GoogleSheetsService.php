<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    /**
     * Send data to Google Sheets via Web App URL.
     *
     * @param string $sheetName The name of the sheet (tab) to append to.
     * @param array $data The data to append (key-value pairs).
     * @return bool
     */

    public function syncData(string $sheetName, array $data, ?string $uniqueKey = null, ?string $uniqueValue = null)
    {
        $url = env('GOOGLE_SHEET_WEB_APP_URL');

        if (!$url) {
            Log::warning("Google Sheets URL not set in .env");
            return false;
        }

        try {
            $payload = [
                'sheetName' => $sheetName,
                'timestamp' => now()->toDateTimeString(),
                'data' => $data,
                'uniqueKey' => $uniqueKey,
                'uniqueValue' => $uniqueValue
            ];

            // Use withoutVerifying() to prevent SSL certificate issues on local XAMPP/Windows
            $response = Http::withoutVerifying()
                ->timeout(15)
                ->asJson()
                ->post($url, $payload);


            if ($response->successful()) {
                return true;
            }

            Log::error("Google Sheets Sync Failed: " . $response->status() . " - " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("Google Sheets Sync Exception: " . $e->getMessage());
            return false;
        }
    }
}

