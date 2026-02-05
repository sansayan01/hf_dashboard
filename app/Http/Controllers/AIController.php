<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Survey;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function chat(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string',
                'context' => 'nullable|array'
            ]);

            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Fetch data context for the user and their downline
            $dataContext = $this->getChatContext($user);

            // Updated system prompt with brevity constraint
            $systemPrompt = "You are the Humanity Foundation AI Assistant. You help staff manage patients, surveys, and appointments.
            RULES:
            1. ONLY answer based on the provided data context.
            2. If information is missing, say you don't know.
            3. BE EXTREMELY BRIEF. Maximum 1 or 2 short sentences per answer.
            4. Be professional and efficient.

            {$dataContext}";

            $context = $request->input('context', []);

            // Update or add system prompt to the context
            $found = false;
            foreach ($context as &$msg) {
                if (isset($msg['role']) && $msg['role'] === 'system') {
                    $msg['content'] = $systemPrompt;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                array_unshift($context, ['role' => 'system', 'content' => $systemPrompt]);
            }

            $apiKey = env('OPENROUTER_API_KEY');
            if (!$apiKey) {
                return response()->stream(function () {
                    echo "API Key missing in .env. Please configure OPENROUTER_API_KEY.";
                    if (ob_get_level() > 0)
                        ob_flush();
                    flush();
                }, 200, ['Content-Type' => 'text/event-stream']);
            }

            return response()->stream(function () use ($request, $context) {
                try {
                    // Disable output buffering if possible to ensure real-time streaming
                    if (function_exists('apache_setenv')) {
                        @apache_setenv('no-gzip', 1);
                    }
                    @ini_set('zlib.output_compression', 0);
                    @ini_set('implicit_flush', 1);

                    $response = $this->aiService->stream($request->message, $context);

                    if (!$response->successful()) {
                        Log::error("OpenRouter Stream Error: " . $response->status() . " - " . $response->body());
                        echo "Error: Could not connect to the AI model. Please try again later.";
                        return;
                    }

                    $body = $response->toPsrResponse()->getBody();

                    while (!$body->eof()) {
                        $line = $this->readLine($body);
                        if (empty($line))
                            continue;

                        if (str_starts_with($line, 'data: ')) {
                            $json = substr($line, 6);
                            if ($json === '[DONE]')
                                break;

                            $data = json_decode($json, true);
                            if (isset($data['choices'][0]['delta']['content'])) {
                                $content = $data['choices'][0]['delta']['content'];
                                echo $content;
                                if (ob_get_level() > 0)
                                    ob_flush();
                                flush();
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("AI Chat Stream Exception: " . $e->getMessage());
                    echo "An error occurred during communication.";
                }
            }, 200, [
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'text/event-stream',
                'X-Accel-Buffering' => 'no',
                'Connection' => 'keep-alive',
            ]);
        } catch (\Exception $e) {
            Log::error("AI Chat Controller Exception: " . $e->getMessage());
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }

    private function getChatContext($user)
    {
        try {
            // Get all downline IDs
            $downline = $user->getAllDownline();
            $downlineIds = $downline->pluck('id')->toArray();
            $relevantUserIds = array_merge([$user->id], $downlineIds);

            // Get Team Members
            $team = User::whereIn('id', $relevantUserIds)->with('profile')->take(40)->get()->map(function ($u) {
                /** @var \App\Models\User $u */
                $name = $u->profile->full_name ?? 'Unknown';
                return "- {$name} (#{$u->employee_id}, {$u->getDesignationLabel()})";
            })->implode("\n");

            // Get Patients
            $patients = Survey::whereIn('created_by', $relevantUserIds)->latest()->take(40)->get()->map(function ($p) {
                return "- {$p->full_name} ({$p->patient_id}) - Health: {$p->health_issues}";
            })->implode("\n");

            // Get Appointments
            $appointments = Appointment::whereIn('created_by', $relevantUserIds)
                ->with('survey')
                ->latest()
                ->take(20)
                ->get()->map(function ($a) {
                    $pName = $a->survey->full_name ?? 'Unknown';
                    $date = $a->appointment_date ? $a->appointment_date->format('d M') : 'N/A';
                    return "- {$a->appointment_id}: {$pName} on {$date} ({$a->status})";
                })->implode("\n");

            $today = now()->format('d M Y');

            return "CONTEXT AS OF {$today}:\n" .
                "USER: {$user->profile->full_name}\n" .
                "TEAM:\n{$team}\n" .
                "PATIENTS:\n{$patients}\n" .
                "APPOINTMENTS:\n{$appointments}";
        } catch (\Exception $e) {
            Log::error("Error generating chat context: " . $e->getMessage());
            return "No specific data context available due to an internal error.";
        }
    }

    private function readLine($body)
    {
        $line = '';
        while (!$body->eof()) {
            $char = $body->read(1);
            if ($char === "\n")
                break;
            $line .= $char;
        }
        return trim($line);
    }
}
