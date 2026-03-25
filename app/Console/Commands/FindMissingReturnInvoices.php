<?php

namespace App\Console\Commands;

use App\Helpers\ETIMSHelper;
use App\Models\Branch;
use App\Models\Landlord\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FindMissingReturnInvoices extends Command
{
    protected $signature = 'find:missing-invoices {tenant?}';
    protected $description = 'Find missing return invoices sequentially by invoice number';

    public function handle()
    {
        if ($tenantId = $this->argument('tenant')) {
            $tenant = Tenant::find($tenantId);
            if (!$tenant) {
                $this->error("Tenant #{$tenantId} not found.");
                return Command::FAILURE;
            }

            $this->process($tenant);
        } else {
            Tenant::chunk(100, function ($tenants) {
                foreach ($tenants as $tenant) {
                    $this->process($tenant);
                    $this->info("DONE with tenant #{$tenant->id}");
                }
            });
        }

        return Command::SUCCESS;
    }

    private function process(Tenant $tenant)
    {
        $tenant->configure()->use();

        $this->info("Processing Tenant #{$tenant->id} ({$tenant->name}) ...");

        $directory = storage_path($tenant->kra_pin);
        File::ensureDirectoryExists($directory);

        $missingFile = $directory . '/missing_return_invoices.txt';
        $progressFile = $directory . '/invoice_scan_progress.txt';

        // Determine starting invoice number from progress file
        $expected = 1;
        if (file_exists($progressFile)) {
            $last = trim(file_get_contents($progressFile));
            if (is_numeric($last)) {
                $expected = (int)$last + 1;
            }
        }

        $maxInvoice = 58827; // set your max invoice number
        $handle = fopen($missingFile, 'a');

        $branch = Branch::first(); // or loop branches if needed

        for ($invoiceNumber = $expected; $invoiceNumber <= $maxInvoice; $invoiceNumber++) {

            $attempts = 0;
            $success = false;

            while ($attempts < 5 && !$success) {
                try {
                    // Replace with your own helper
                    echo "Checking invoice #{$invoiceNumber}...\n";
                    $response = ETIMSHelper::getInvoiceListByNumber($branch, $invoiceNumber);

                    if (isset($response->resultCd) && ($response->resultCd === "000" || $response->resultMsg == "There is no search result")) {

                        $invoices = $response->data['salesList'] ?? [];
                        $found = false;

                        foreach ($invoices as $inv) {
                            if ((int)$inv['invcNo'] === $invoiceNumber) {
                                $found = true;
                                break;
                            }
                        }

                        if (!$found) {
                            fwrite($handle, $invoiceNumber . PHP_EOL);
                            echo "Found missing invoice #{$invoiceNumber}\n";
                        }

                        $success = true;

                        // Save progress after each invoice
                        file_put_contents($progressFile, $invoiceNumber);
                    }
                } catch (\Throwable $e) {
                    $this->warn("Attempt " . ($attempts + 1) . " failed for invoice #{$invoiceNumber}: " . $e->getMessage());
                }

                $attempts++;
                if (!$success) sleep(1);
            }

            if (!$success) {
                $this->error("Skipping invoice #{$invoiceNumber} after 5 failed attempts");
            }

            if ($invoiceNumber % 100 === 0) {
                $this->info("Processed invoice #{$invoiceNumber}");
            }
        }

        fclose($handle);
        $this->info("Done. Missing invoices saved to: {$missingFile}");
    }
}
