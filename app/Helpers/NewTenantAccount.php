<?php

namespace App\Helpers;

use App\Helpers\Accounting\ChartOfAccountsHelper;
use App\Jobs\Meta\MetaDataJob;
use App\Models\Company;
use App\Models\Branch;
use App\Models\ChartOfAccount;
use App\Models\Landlord\Addon;
use App\Models\Landlord\Package;
use App\Models\Landlord\Tenant;
use App\Models\Metric;
use App\Models\User;
use Dflydev\DotAccessData\Util;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Js;

use function PHPUnit\Framework\isNull;

class NewTenantAccount
{
    /**
     * Migrate tenant DB
     */
    public static function migrateTenantDB(Tenant $tenant)
    {
        // DB::statement("CREATE DATABASE IF NOT EXISTS $tenant->database");
        $tenant->configure()->use();
        $migration = Artisan::call("tenants:migrate $tenant->id");
        return $migration;
    }

    public static function subscribeTenantToDefaultServices(Tenant $tenant){
        // default services package, branches addon and users addon
        $expiryDate= now()->adddays(30);


    }

    public static function initializeTenantTransactionAccount(Tenant $tenant, Float $defaultCredits=null)
    {
        $credits = $defaultCredits ? $defaultCredits : env('DEFAULT_ACCOUNT_CREDITS', 500);
        $account = $tenant->account()->create([
            'debit'=>0,
            'credit'=>$credits,
            'balance'=>$credits,
            'reason'=>'initial account setup'
        ]);
        return $account;
    }



    /**
     * Create new Company
     */
    public static function createCompany( Array $data, Tenant $tenant)
    {
        if (! count($data) || !$tenant) return;

        if( ! Schema::hasTable('companies') ) self::migrateTenantDB($tenant);

        $data = [
            'tenant_id' => $tenant->id,
            'business_code' => $tenant->business_code,
            'domain' => $tenant->domain,
            'agent_code' => $tenant->agent_code,
            'name' => isset($data['name']) ? $data['name'] : null,
            'business_type' =>   isset($data['business_type']) ? $data['business_type'] : null,
            'business_type_name' =>  isset($data['business_type_name']) ? $data['business_type_name'] : null,
            'country' =>   isset($data['country']) ? $data['country'] : null,
            'country_name' =>   isset($data['country_name']) ? $data['country_name'] : null,
            'slug' => isset($data['name']) ? Utilities::getModelSlug($data['name'], 'company') : Utilities::getModelSlug($tenant->name, 'company'),
        ];
        // Log::info($data);

        $company = Company::firstOrCreate(['tenant_id' => $tenant->id],$data);


        return $company;
    }

    /***
     * Create new Branch
     */
    public static function createBranch(Array $data, Company $company)
    {
        if (! count($data) || is_null($company)) return;

        $tenant = app()->bound('tenant') ? app('tenant') : null;

        if( is_null($tenant) ) return null;

        if( ! Schema::hasTable('branches') ) self::migrateTenantDB($tenant);
// Log::info($data);
        $name = env('DEFAULT_BRANCH_NAME', 'Main Branch');
        $branchData = [
            'tracking_number' => '00', //$data['tracking_number'],
            'name' => $name,
            'slug' => Utilities::getModelSlug($name, 'branch'),
            'etims_branch_code' => '00',

        ];

        $branch = $company->branches()->firstOrCreate(['name'=>$name],$branchData);
        self::createDefaultMetrics($branch);

        return $branch;
    }

    public static function createDefaultCategories(Branch $branch, $business_type)
    {

    }

    /**
     * Create new User
     */
    public static function createUser($data, Branch $branch)
    {
        if (is_null($data) || is_null($branch)) return;
        $data['password']= Hash::make($data['password']);
        $data['cashier_id'] = rand(1000, 9999);
        $data['phone'] = Utilities::cleanPhoneNumber($data['phone']);
        $data['username']= Utilities::generateRandomLetters(5);

        $user = $branch->users()->create($data);
        return $user;
    }

    /**
     * Create user Token
     */
    public static function createToken(User $user)
    {
        // if (is_null($user)) return;

        // $token = $user->createToken('access_token');

        // return $token->plainTextToken;
    }

    /**
     * Assign user Roles
     */
    public static function assignRole(User $user, $role)
    {
        if (is_null($user) || is_null($role)) return;

        $role = AccessManagement::createRoleAssignUser($role, $user);

        return $role;
    }

    public static function tenantDBReady(Tenant $tenant)
    {
        $tenant->configure()->use();
        try {
            DB::connection()->getPdo();
            if(DB::connection()->getDatabaseName()) return true;
        } catch (\Exception $e) {
        }

        return false;

    }

    public static function createDefaultMetrics(Branch $branch)
    {

    }



}
