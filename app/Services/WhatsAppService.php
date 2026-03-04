<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message.
     * 
     * @param string $phoneNumber
     * @param string $message
     * @param string|null $mediaUrl Optional URL to an image or document
     * @return bool
     */
    public function sendMessage(string $phoneNumber, string $message, string $mediaUrl = null)
    {
        // Placeholder for WhatsApp API integration
        // You can integrate with Twilio, Interakt, or any other provider here.

        Log::info("WhatsApp Notification (SIMULATED):", [
            'to' => $phoneNumber,
            'message' => $message,
            'media' => $mediaUrl
        ]);

        /* 
        Example with a generic API:

        try {
            $response = Http::post('https://api.provider.com/send', [
                'apikey' => 'YOUR_API_KEY',
                'to' => $phoneNumber,
                'message' => $message,
                'media_url' => $mediaUrl
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("WhatsApp API Error: " . $e->getMessage());
            return false;
        }
        */

        return true;
    }

    /**
     * Send a notification to a user about a new downline registration.
     */
    public function notifyUplineNewRegistration($upline, $newbie, $mediaUrl = null)
    {
        $uplineName = $upline->profile->full_name ?? 'Manager';
        $newbieName = $newbie->profile->full_name ?? 'New Member';
        $phoneNumber = $upline->profile->phone_number ?? '';

        if (!$phoneNumber)
            return false;

        $message = "Hello {$uplineName},\n\nA new registered member has been added to your team:\nName: {$newbieName}\nID: {$newbie->employee_id}\n\nPlease check the offer letter sent to your email/whatsapp for verification and signature.";

        if ($mediaUrl) {
            $message .= "\n\nYou can also view/download the unsigned offer letter here: " . $mediaUrl;
        }

        return $this->sendMessage($phoneNumber, $message, $mediaUrl);
    }

    /**
     * Send a notification to the new user about their successful registration.
     */
    public function notifyNewbieRegistration($newbie)
    {
        $name = $newbie->profile->full_name ?? 'Member';
        $phoneNumber = $newbie->profile->phone_number ?? '';

        if (!$phoneNumber)
            return false;

        $message = "Hello {$name},\n\nYour registration with Humanity Foundation is successful! Your ID is {$newbie->employee_id}.\n\nYour profile is currently under review. Your upline will contact you for signature verification soon.";

        return $this->sendMessage($phoneNumber, $message);
    }

    /**
     * Send the official invitation and offer letter after approval.
     */
    public function notifyApprovedNewbie($newbie, $mediaUrl = null)
    {
        $name = $newbie->profile?->full_name ?? 'Member';
        $phoneNumber = $newbie->profile?->phone_number ?? '';

        if (!$phoneNumber)
            return false;

        $message = "Congratulations {$name}!\n\nYour profile has been verified and approved by Humanity Foundation. Welcome to the team!\n\nPlease find your official Joining Letter attached.\n\nBest Regards,\nHumanity Foundation";

        if ($mediaUrl) {
            $message .= "\n\nDownload Link: " . $mediaUrl;
        }

        return $this->sendMessage($phoneNumber, $message, $mediaUrl);
    }
}
