<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncToGoogleSheetJob implements ShouldQueue
{
    use Queueable;

    protected $sheetName;
    protected $data;
    protected $uniqueKey;
    protected $uniqueValue;

    /**
     * Create a new job instance.
     */
    public function __construct(string $sheetName, array $data, ?string $uniqueKey = null, ?string $uniqueValue = null)
    {
        $this->sheetName = $sheetName;
        $this->data = $data;
        $this->uniqueKey = $uniqueKey;
        $this->uniqueValue = $uniqueValue;
    }

    /**
     * Execute the job.
     */
    public function handle(\App\Services\GoogleSheetsService $sheetsService): void
    {
        $sheetsService->syncData($this->sheetName, $this->data, $this->uniqueKey, $this->uniqueValue);
    }
}
