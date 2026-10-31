<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Landlord\Tenant;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
class TenancyProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRequests();

        $this->configureQueue();
    }
    /**
     *
     */
    public function configureRequests()
    {
        if (!$this->app->runningInConsole()) :

            $request = $this->app['request'];

            if ($request->is('/')) return null;

            if (Str::contains(strtolower($request->getRequestUri()), [
                'register',
                'create-account',
                'login',
                'authenticate',
                'signup',
                'sign-up',
                'is-tenant-email-registered',
                'tenants',
                'create-tenant-user',
                'forgot-business-code',
                'forgot-password',
                'reset-password',
                'open',
                'test-email-job',
                'handle-task'
            ])) return  null;

            $business_code =    $request->header('kra-pin');
            $business_code =  $business_code ? $business_code :  $request->header('company-kra-pin');
            $business_code =   $business_code ? $business_code : $request->header('business_code');

            $tenant = Tenant::where('kra_pin', $business_code)->first();
            $tenant = $tenant ? $tenant : Tenant::where('business_code', $business_code)->first();
            if (empty($tenant))
                return response()->json(['message' => 'tenant not found'], 404);

            $this->runDBMigrationsOnTheFly($tenant);
            return  $tenant->configure()->use();
        endif;
    }

    /**
     *
     */
    public function configureQueue()
    {
        if (!app()->bound('tenant')) $this->setLandlordConnection();
        $this->app['queue']->createPayloadUsing(function () {
            return app()->bound('tenant') ? ['tenant_id' => $this->app['tenant']->id] : [];
        });

        $this->app['events']->listen(JobProcessing::class, function ($event) {
            if (isset($event->job->payload()['tenant_id'])) {
                Tenant::find($event->job->payload()['tenant_id'])->configure()->use();
            }
        });
    }

    protected function setLandlordConnection()
    {
        $database = env('LANDLORD_DB_DATABASE', 'landlord');
        $host = env('LANDLORD_DB_HOST', 'localhost');

        config([
            'database.connections.tenant.database' => $database,
            'database.connections.tenant.host' => $host,
        ]);

        DB::purge($database);

        DB::reconnect($database);

        Schema::connection($database)->getConnection()->reconnect();
    }

    protected function runDBMigrationsOnTheFly(Tenant $tenant)
    {
        if ($tenant->has_new_migrations !== 1)   return;  // Return if no new migrations to run

        Artisan::call('tenants:migrate', [
            'tenant' => $tenant->id,
            '--fresh' => false,
            '--seed' => false
        ]);

        // $output = Artisan::output();
        // Log::info($output);

        $tenant->update(['has_new_migrations' => null]);
    }
}
