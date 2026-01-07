<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $apiKey;
    protected $baseUrl = 'https://openrouter.ai/api/v1';
    protected $model = 'mistralai/devstral-2512:free';

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
        ])->timeout(60)->withOptions([
                    'stream' => true,
                ])->post($this->baseUrl . '/chat/completions', [
                    'model' => $this->model,
                    'messages' => $messages,
                    'stream' => true,
                ]);
    }
}
