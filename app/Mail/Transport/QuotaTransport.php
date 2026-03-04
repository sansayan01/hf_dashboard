<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class QuotaTransport extends AbstractTransport
{
    protected $transport;
    protected $dailyLimit;

    public function __construct(TransportInterface $transport, int $dailyLimit = 100)
    {
        parent::__construct();
        $this->transport = $transport;
        $this->dailyLimit = $dailyLimit;
    }

    protected function doSend(SentMessage $message): void
    {
        $key = 'mail_daily_usage_' . date('Y-m-d');
        $currentUsage = Cache::get($key, 0);

        if ($currentUsage >= $this->dailyLimit) {
            Log::warning("Daily mail limit of {$this->dailyLimit} reached. Switching to failover/next transport.");
            // Throwing an exception triggers the failover mechanism in Laravel
            throw new \Exception("Daily mail limit reached ($currentUsage/$this->dailyLimit).");
        }

        // Delegate to the actual transport (e.g., SMTP)
        $this->transport->send($message);

        // Increment usage ONLY if send was successful
        Cache::increment($key);
        Log::info("Email sent via QuotaTransport. Usage: " . ($currentUsage + 1) . "/{$this->dailyLimit}");
    }

    public function __toString(): string
    {
        return 'quota://' . $this->transport->__toString();
    }
}
