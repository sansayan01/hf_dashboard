<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $apiKey;
    protected $baseUrl = 'https://openrouter.ai/api/v1';
    protected $model = 'arcee-ai/trinity-large-preview:free';

    public function __construct()
    {
        $this->apiKey = env('OPENROUTER_API_KEY');
    }

    /**
     * Send a prompt to the AI model.
     *
     * @param string $prompt
     * @param array $context Additional context (like previous messages)
     * @return string|null
     */
    public function ask(string $prompt, array $context = [])
    {
        if (!$this->apiKey) {
            Log::error('OpenRouter API Key not set in .env');
            return 'AI service is currently unavailable. Please check configuration.';
        }

        try {
            $messages = array_merge($context, [
                ['role' => 'user', 'content' => $prompt]
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => env('APP_URL', 'http://localhost'),
                'X-Title' => env('APP_NAME', 'Humanity Foundation'),
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/chat/completions', [
                        'model' => $this->model,
                        'messages' => $messages,
                        'stream' => false
                    ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'Sorry, I couldn\'t generate a response.';
            }

            Log::error('OpenRouter API Error: ' . $response->status() . ' - ' . $response->body());
            return 'Error communicating with the AI service.';

        } catch (\Exception $e) {
            Log::error('AI Service Exception: ' . $e->getMessage());
            return 'An unexpected error occurred while processing your request.';
        }
    }

    /**
     * Verify if the provided image is a successful UPI payment screenshot.
     * 
     * @param string $imagePath Local path to the image
     * @param float $expectedAmount The amount that should be in the screenshot
     * @return array ['success' => bool, 'message' => string, 'transaction_id' => string|null]
     */
    public function verifyPaymentScreenshot(string $imagePath, float $expectedAmount)
    {
        if (!$this->apiKey) {
            return ['success' => false, 'message' => 'AI Service not configured.'];
        }

        try {
            $imageData = base64_encode(file_get_contents($imagePath));
            $mimeType = mime_content_type($imagePath);

            $prompt = "Analyze this image and determine if it is a successful UPI payment screenshot.
            Expected Amount: ₹{$expectedAmount}
            
            Return a JSON object with the following fields:
            1. 'is_successful_upi_payment' (boolean): True if it's a clear success screen from apps like PhonePe, Google Pay, Paytm, etc.
            2. 'amount_matches' (boolean): True if the amount shown in the screenshot matches ₹{$expectedAmount}.
            3. 'transaction_id' (string|null): Extract the UTR or Transaction ID if visible.
            4. 'reason' (string): Brief explanation.
            
            ONLY return the JSON object, nothing else.";

            // Use a vision-capable model
            $visionModel = 'google/gemini-2.0-flash-exp:free';

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'HTTP-Referer' => env('APP_URL', 'http://localhost'),
                'X-Title' => env('APP_NAME', 'Humanity Foundation'),
                'Content-Type' => 'application/json',
            ])->timeout(45)->post($this->baseUrl . '/chat/completions', [
                        'model' => $visionModel,
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => [
                                    ['type' => 'text', 'text' => $prompt],
                                    [
                                        'type' => 'image_url',
                                        'image_url' => [
                                            'url' => "data:{$mimeType};base64,{$imageData}"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'] ?? '';
                // Clean markdown if AI included it
                $content = trim(str_replace(['```json', '```'], '', $content));
                $result = json_decode($content, true);

                if ($result) {
                    $isOk = ($result['is_successful_upi_payment'] ?? false) && ($result['amount_matches'] ?? false);
                    return [
                        'success' => $isOk,
                        'message' => $result['reason'] ?? 'Verification complete.',
                        'transaction_id' => $result['transaction_id'] ?? null
                    ];
                }
            }

            Log::error('Vision API Error: ' . $response->body());
            return ['success' => false, 'message' => 'Failed to analyze image.'];

        } catch (\Exception $e) {
            Log::error('Vision Service Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error processing image verification.'];
        }
    }

    public function stream(string $prompt, array $context = [])
    {
        $messages = array_merge($context, [
            ['role' => 'user', 'content' => $prompt]
        ]);

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'HTTP-Referer' => env('APP_URL', 'http://localhost'),
            'X-Title' => env('APP_NAME', 'Humanity Foundation'),
            'Content-Type' => 'application/json',
        ])->timeout(120)->withOptions([
                    'stream' => true,
                ])->post($this->baseUrl . '/chat/completions', [
                    'model' => $this->model,
                    'messages' => $messages,
                    'stream' => true,
                ]);
    }
}
