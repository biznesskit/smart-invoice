<?php

namespace App\Jobs\WebHooks;

use App\Helpers\WebHooksHelper;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InvoiceTransmitedWebHookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $invoice, $taxPIN;
    public $tries = 10; // Retry 5 times before failing

// retirlas in seconds
    public function backoff(): array
    {
        return [
            10,
            30,
            60,       
            120,    
            300,      
            600,     
            1800,     
            3600,     
            7200      
        ]; 
    }
    /**
     * Create a new event instance.
     */
    public function __construct(Invoice $invoice, string $taxPIN)
    {
        $this->invoice = $invoice;
        $this->taxPIN = $taxPIN;
    }


   
    public function failed(\Throwable $exception)
    {
        Log::error("Webhook Job permanently failed after $this->tries attempts: " . $exception->getMessage());
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            WebHooksHelper::sendInvoiceTransmitedSuccess($this->invoice, $this->taxPIN);
        } catch (\Exception $e) {
            // Log::error('Job failed: ' . $e->getMessage());
            // throw $e;
        }
        
    }



}
