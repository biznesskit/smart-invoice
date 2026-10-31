<?php

namespace App\Helpers;

use App\Models\Branch;
use App\Models\Invoice;
use App\Models\Landlord\Tenant;
use Illuminate\Support\Collection;

class HouseKeep
{

    public static function run(Tenant $tenant)
    {

        if($tenant->business_code == 10015)
        {
            $tenant->configure()->use();

            $missingNumbers = [
                1808,1822,1844,1848,1897,1933,2013,2031,2073,2155,2279,2287,2307,2342,2344,
                2355,2420,2421,2460,2480,2488,2536,2544,2565,2625,2639,2662,2708,2792,2801,
                2854,2862,2866,2869,2880,2884,2888,2898,2903,2922,2932,2934,2937,2947,2956,
                2969,2970,2973,2997,3000,3001,3010,3049,3072,3074,3099,3130,3139,3168,3176,
                3196,3201,3229,3239,3252,3276,3277,3278,3317,3319,3323,3403,3433,3462,3562,
                3563,3575,3583,3613,3629,3646,3692,3701,3712,3716,3742,3752,3755,3793,3799,
                3804,3813,3833,3839,3842,3850,3863,3889,3901,3909,3943,3949,3985,3992,3997,
                4005,4022,4042,4044,4058,4067,4083,4088,4103,4124
            ];

            foreach( Invoice::whereIn('invoice_number', $missingNumbers)->get() as $inv )
            {
                echo "Invoice #{$inv->invoice_number} already exists locally. Skipping...\n";
            }



        }



    }


    public static function getSpareDuplicateInvoices(): Collection
    {
        $duplicateNumbers = Invoice::select('invoice_number')
            ->groupBy('invoice_number')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('invoice_number');

        $spares = collect();

        foreach ($duplicateNumbers as $number) {
            $invoices = Invoice::where('invoice_number', $number)
                ->orderBy('id') // keep the oldest untouched
                ->get();

            $extras = $invoices->slice(1); // all except the first
            $spares = $spares->merge($extras);
        }

        return $spares;
    }

    public static function canFillMissingInvoices(array $missingNumbers): bool
    {
        $spares = self::getSpareDuplicateInvoices();

        echo "Missing numbers count: " . count($missingNumbers) . "\n";
        echo "Spare duplicate invoices available: " . $spares->count() . "\n";

        if ($spares->count() < count($missingNumbers)) {
            echo "⚠️ Not enough duplicate invoices to fill the gaps.\n";
            return false;
        }

        echo "✅ Enough duplicate invoices available to repair the sequence.\n";
        return true;
    }

    public static function getMissingRemoteInvoices(int $start = 1, int $end = 4127): array
    {
        $missing = [];

        $branch = Branch::first();





        for ($number = $start; $number <= $end; $number++) {

            try {

                $data = [
                    "tin" => $branch->kra_pin,
                    "bhfId" => $branch->branch_code,
                    "invcNo" => $number
                ];

                $response = ETIMSHelper::sendGuzzleRequest($branch, '/selectInvoiceDetails', $data);

                $existsRemotely = isset($response->resultCd) && $response->resultCd == "000" ? true : false; // Your API call

                if (!$existsRemotely) {
                    echo "{$number}\n";
                    $missing[] = $number;
                }

            } catch (\Throwable $e) {
                echo "⚠️ Error checking Invoice #{$number}: {$e->getMessage()}\n";
            }

        }

        echo "\n==== SUMMARY ====\n";
        echo "Total missing remotely: " . count($missing) . "\n";

        return $missing;
    }

}
