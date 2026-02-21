<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenClawService
{
    protected $gatewayUrl;
    protected $gatewayToken;

    public function __construct()
    {
        $this->gatewayUrl = config('openclaw.gateway_url', 'http://127.0.0.1:18789');
        $this->gatewayToken = config('openclaw.token');
    }

    /**
     * Send a message to OpenClaw gateway and get a streaming response.
     *
     * @param string $message
     * @param array $context Array of message objects with role/content
     * @param string $sessionId
     * @param string $senderId
     * @return \Illuminate\Http\StreamedResponse|string
     */
    public function stream(string $message, array $context = [], string $sessionId = null, string $senderId = 'website-user')
    {
        if (!$this->gatewayToken) {
            return response()->stream(function () {
                echo "OpenClaw gateway token not configured. Please set OPENCLAW_GATEWAY_TOKEN in .env";
            }, 200, ['Content-Type' => 'text/event-stream']);
        }

        // Build payload
        $payload = [
            'message' => $message,
            'session_id' => $sessionId ?? uniqid('sess_', true),
            'sender_id' => $senderId,
            'channel' => 'webchat',
            'context' => $context,
            'priority' => 'normal'
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->gatewayToken,
                'Content-Type' => 'application/json',
                'Accept' => 'text/event-stream'
            ])->timeout(120)->withOptions([
                'stream' => true,
            ])->post($this->gatewayUrl . '/v1/messages/send', $payload);

            if (!$response->successful()) {
                Log::error('OpenClaw Gateway Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->stream(function () {
                    echo "Error: Could not connect to chat service. Status: " . $response->status();
                }, 200, ['Content-Type' => 'text/event-stream']);
            }

            // Stream the response directly to the client
            return response()->stream(function () use ($response) {
                $body = $response->toPsrResponse()->getBody();
                while (!$body->eof()) {
                    $line = $this->readLine($body);
                    if (empty($line)) {
                        continue;
                    }

                    // OpenClaw may send data in SSE format: data: {...}
                    // Or it might send raw text chunks. Adjust based on your gateway's response format.
                    if (str_starts_with($line, 'data: ')) {
                        $json = substr($line, 6);
                        if ($json === '[DONE]') {
                            break;
                        }
                        $data = json_decode($json, true);
                        if (isset($data['content'])) {
                            echo $data['content'];
                        } elseif (isset($data['text'])) {
                            echo $data['text'];
                        }
                    } else {
                        // If gateway sends raw text (not SSE), just output it
                        echo $line;
                    }

                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                }
            }, 200, [
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'text/event-stream',
                'X-Accel-Buffering' => 'no',
                'Connection' => 'keep-alive',
            ]);

        } catch (\Exception $e) {
            Log::error('OpenClaw Connection Exception: ' . $e->getMessage());
            return response()->stream(function () use ($e) {
                echo "An error occurred: " . $e->getMessage();
            }, 200, ['Content-Type' => 'text/event-stream']);
        }
    }

    /**
     * Send a message and get a complete response (non-streaming).
     *
     * @param string $message
     * @param array $context
     * @param string $sessionId
     * @param string $senderId
     * @return string
     */
    public function ask(string $message, array $context = [], string $sessionId = null, string $senderId = 'website-user'): string
    {
        if (!$this->gatewayToken) {
            return 'OpenClaw gateway token not configured.';
        }

        $payload = [
            'message' => $message,
            'session_id' => $sessionId ?? uniqid('sess_', true),
            'sender_id' => $senderId,
            'channel' => 'webchat',
            'context' => $context,
            'priority' => 'normal',
            'stream' => false
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->gatewayToken,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->gatewayUrl . '/v1/messages/send', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['content'] ?? $data['text'] ?? 'No response from chat service.';
            }

            Log::error('OpenClaw Gateway Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return 'Error: ' . $response->status() . ' - Could not get response.';

        } catch (\Exception $e) {
            Log::error('OpenClaw ask Exception: ' . $e->getMessage());
            return 'An unexpected error occurred.';
        }
    }

    /**
     * Read a line from a stream resource.
     */
    private function readLine($body): string
    {
        $line = '';
        while (!$body->eof()) {
            $char = $body->read(1);
            if ($char === "\n") {
                break;
            }
            $line .= $char;
        }
        return trim($line);
    }
}
