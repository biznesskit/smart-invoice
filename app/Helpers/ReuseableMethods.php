<?php
namespace App\Helpers;

use App\Models\Landlord\Tenant;

class ReuseableMethods
{
    
public static function scheduleMigrationsToRunNextImmediateRequestForAllTenant()
    {
        Tenant::chunkById(1000, function ($records)  {
            foreach ($records as $tenant)         $tenant->update(['has_new_migrations'=>1]);
    });
    echo "Migrations sheduled next for all tenants".PHP_EOL;
    }


}