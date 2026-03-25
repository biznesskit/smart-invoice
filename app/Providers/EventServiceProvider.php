<?php

namespace App\Providers;

use App\Events\ProductExcelFileUpload\importTenantProductsFinishedEvent;
use App\Listeners\ProductExcelFileUpload\importTenantProductsFinishedListener;
use App\Events\Payment\MpesaB2CPay;
use App\Events\Payment\MpesaB2CRequest;

use App\Events\Tenant\CollectB2CCredentialsEvent;
use App\Listeners\Payment\MpesaB2CRequestListener;
use App\Listeners\Tenant\CollectB2CCredentialsListener;
use App\Events\Payment\MpesaSTKCallbackEvent;
use App\Events\Payment\MpesaSTKPushEvent;
use App\Events\StaffDestroyedEvent;
use App\Events\Tenant\CollectC2BCredentialsEvent;
use App\Listeners\Payment\B2CMpesaCallbackListener;
use App\Listeners\Payment\MpesaSTKCallbackListener;
use App\Listeners\Payment\MpesaSTKPushListener;
use App\Listeners\Staff\StaffDestroyedListener;
use App\Listeners\Tenant\CollectC2BCredentialsListener;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // //Tenant events
        // 'App\Events\Business\TenantCreatedEvent' => [
        //     'App\Listeners\Business\TenantCreatedListener',
        // ],
        // 'App\Events\Business\ForgotBusinessCodeEvent' => [
        //     'App\Listeners\Business\ForgotBusinessCodeListener',
        // ],

        // 'App\Events\Business\newBusinessCreatedEvent' => [
        //     'App\Listeners\Business\newBusinessCreatedListener',
        // ],

        // 'App\Events\Business\tenantDestroyedEvent' => [
        //     'App\Listeners\Business\tenantDestroyedListener',
        // ],

        // 'App\Events\Business\tenantParmanentlyDeleletedEvent' => [
        //     'App\Listeners\Business\tenantParmanentlyDeletedListener',
        // ],
        // 'App\Events\Business\tenantUpdatedEvent' => [
        //     'App\Listeners\Business\tenantUpdatedListener',
        // ],

        // 'App\Events\Tenant\TenantPasswordChangedEvent' => [
        //     'App\Listeners\Tenant\TenanPasswordChangedtListener',
        // ],
        // 'App\Events\Tenant\PaymentRecievedEvent' => [
        //     'App\Listeners\Tenant\PaymentRecievedListener',
        // ],

        // // user events
        // 'App\Events\User\newUserCreatedEvent' => [
        //     'App\Listeners\User\newUserCreatedListener',
        // ],
        // 'App\Events\User\UserUpdatedEvent' => [
        //     'App\Listeners\User\UserUpdatedListener',
        // ],
        // 'App\Events\User\UserDestroyedEvent' => [
        //     'App\Listeners\User\UserDestroyedListener',
        // ],

        // // Cashier events
        // 'App\Events\Cashier\CashierCreatedEvent' => [
        //     'App\Listeners\Cashier\CashierCreatedListener',
        // ],
        // 'App\Events\Cashier\CashierUpdatedEvent' => [
        //     'App\Listeners\Cashier\CashierUpdatedListener',
        // ],
        // 'App\Events\Cashier\CashierDestroyedEvent' => [
        //     'App\Listeners\Cashier\CashierDestroyedListener',
        // ],

        // // Customer events
        // 'App\Events\Customer\CustomerCreatedEvent' => [
        //     'App\Listeners\Customer\CustomerCreatedListener',
        // ],
        // 'App\Events\Customer\CustomerUpdatedEvent' => [
        //     'App\Listeners\Customer\CustomerUpdatedListener',
        // ],
        // 'App\Events\Customer\CustomerDestroyedEvent' => [
        //     'App\Listeners\Customer\CustomerDestroyedListener',
        // ],
        // 'App\Events\Customer\TopupAccountEvent' => [
        //     'App\Listeners\Customer\TopupAccountListener',
        // ],

        // // Supplier events
        // 'App\Events\Supplier\SupplierCreatedEvent' => [
        //     'App\Listeners\Supplier\SupplierCreatedListener',
        // ],
        // 'App\Events\Supplier\SupplierUpdatedEvent' => [
        //     'App\Listeners\Supplier\SupplierUpdatedListener',
        // ],
        // 'App\Events\Supplier\SupplierDestroyedEvent' => [
        //     'App\Listeners\Supplier\SupplierDestroyedListener',
        // ],
        // 'App\Events\Supplier\PaymentInitiatedEvent' => [
        //     'App\Listeners\Supplier\PaymentInitiatedListener',
        // ],
        // 'App\Events\Supplier\PaymentProcessedEvent' => [
        //     'App\Listeners\Supplier\PaymentProcessedListener',
        // ],

        // //staff events
        // StaffDestroyedEvent::class => [
        //     StaffDestroyedListener::class,
        // ],

        // //Order events

        // 'App\Events\Order\OrderCreatedEvent' => [
        //     'App\Listeners\Order\OrderCreatedListener',
        // ],

        // //Payment
        // 'App\Events\Payment\PaymentCreatedEvent' => [
        //     'App\Listeners\Payment\PaymentCreatedListener',
        // ],

        // MpesaB2CRequest::class => [
        //     MpesaB2CRequestListener::class
        // ],

        // MpesaB2CPay::class => [
        //     B2CMpesaCallbackListener::class,
        // ],
        // MpesaSTKPushEvent::class => [
        //     MpesaSTKPushListener::class
        // ],
        // MpesaSTKCallbackEvent::class => [
        //     MpesaSTKCallbackListener::class
        // ],


        // CollectB2CCredentialsEvent::class => [
        //     CollectB2CCredentialsListener::class,
        // ],

        // CollectC2BCredentialsEvent::class => [
        //     CollectC2BCredentialsListener::class,
        // ],


        // //Invoice
        // 'App\Events\Invoice\InvoiceCreatedEvent' => [
        //     'App\Listeners\Invoice\InvoiceCreatedListener',
        // ],

        // //Account
        // 'App\Events\Account\AccountCreatedEvent' => [
        //     'App\Listeners\Account\AccountCreatedListener',
        // ],

        // 'App\Events\Account\AccountUpdatedEvent' => [
        //     'App\Listeners\Account\AccountUpdatedListener',
        // ],

        // //Product
        // 'App\Events\Product\ProductCreatedEvent' => [
        //     'App\Listeners\Product\ProductCreatedListener',
        // ],

        // 'App\Events\Product\ProductUpdatedEvent' => [
        //     'App\Listeners\Product\ProductUpdatedListener',
        // ],
        // 'App\Events\Product\ProductDestroyedEvent' => [
        //     'App\Listeners\Product\ProductDestroyedListener',
        // ],

        // //Import Products
        // 'App\Events\ProductImport\ProductImportEvent' => [
        //     'App\Listeners\ProductImport\ProductImportListener',
        // ],

        // //File Uploads
        // 'App\Events\ProductExcelFileUpload\ProductExcelFileUploadEvent' => [
        //     'App\Listeners\ProductExcelFileUpload\ProductExcelFileUploadListener',
        // ],

        // // Payment Event
        // 'App\Events\Payment\PaymentReceived' => [
        //     'App\Listeners\Payment\PaymentReceived',
        // ],

        // // Invoice Event
        // 'App\Events\Invoice\InvoicePaid' => [
        //     'App\Listeners\Invoice\InvoicePaid',
        // ],


        // // Subscription notification
        // 'App\Events\Tenant\SubscriptionExpiryEvent' => [
        //     'App\Listeners\Tenant\SubscriptionExpiryListener',
        // ],

        // // Income notifications
        // 'App\Events\Income\IncomeCreatedEvent' => [
        //     'App\Listeners\Income\IncomeCreatedListener',
        // ],
        // 'App\Events\Income\IncomeUpdatedEvent' => [
        //     'App\Listeners\Income\IncomeUpdatedListener',
        // ],

        // // Expense notifications
        // 'App\Events\Expense\ExpenseCreatedEvent' => [
        //     'App\Listeners\Expense\ExpenseCreatedCListener',
        // ],
        // 'App\Events\Expense\ExpenseUpdatedEvent' => [
        //     'App\Listeners\Expense\ExpenseUpdatedListener',
        // ],
        // 'App\Events\Expense\ExpenseDestroyedEvent' => [
        //     'App\Listeners\Expense\ExpenseDestroyedListener',
        // ],

        // // File upload event
        // 'App\Events\Upload\UploadCreatedEvent' => [
        //     'App\Listeners\Upload\UploadCreatedCListener',
        // ],
        // 'App\Events\Upload\SendFileToEmailEvent' => [
        //     'App\Listeners\Upload\SendFileToEmailListener',
        // ],

        // // Stock recieved
        // 'App\Events\Stock\StockRecievedEvent' => [
        //     'App\Listeners\Stock\StockRecievedListener',
        // ],
        // // Low Stock alerts
        // 'App\Events\Stock\DailyLowStocktAlerEvent' => [
        //     'App\Listeners\Stock\dailyLowStockAlertListener',
        // ],

        // // Stock returned
        // 'App\Events\Stock\StockReturnedEvent' => [
        //     'App\Listeners\Stock\StockReturnedListener',
        // ],

        // // Stock Recount
        // 'App\Events\Stock\StockRecountEvent' => [
        //     'App\Listeners\Stock\StockRecountListener',
        // ],

        // // Company
        // 'App\Events\Company\CompanyCreatedEvent' => [
        //     'App\Listeners\Company\CompanyCreatedListener',
        // ],
        // 'App\Events\Company\CompanyUpdatedEvent' => [
        //     'App\Listeners\Company\CompanyUpdatedListener',
        // ],
        // 'App\Events\Company\CompanyDestroyedEvent' => [
        //     'App\Listeners\Company\CompanyDestroyedListener',
        // ],

        // // Low stock
        // 'App\Events\Stock\LowStockAlertEvent' => [
        //     'App\Listeners\Stock\LowStockAlertListener',
        // ],

        // // Excel upload Tenant products finished
        // importTenantProductsFinishedEvent::class => [
        //     importTenantProductsFinishedListener::class
        // ],

        // // Stock transfer events
        // 'App\Events\Transfer\TransferRequestEvent' => [
        //     'App\Listeners\Transfer\TransferRequestListener',
        // ],
        // 'App\Events\Transfer\TransferDispatchedEvent' => [
        //     'App\Listeners\Transfer\TransferDispatchedListener',
        // ],
        // 'App\Events\Transfer\TransferDispatchedRejetedEvent' => [
        //     'App\Listeners\Transfer\TransferDispatchedRejectedListener',
        // ],

        // 'App\Events\Transfer\TransferRequestRejectedEvent' => [
        //     'App\Listeners\Transfer\TransferRequestRejectedListener',
        // ],
        // 'App\Events\Transfer\TransferRequestItemRejectedEvent' => [
        //     'App\Listeners\Transfer\TransferRequestItemRejectedListener',
        // ],
        // 'App\Events\Transfer\DispatchedItemRejetedEvent' => [
        //     'App\Listeners\Transfer\DispatchedItemRejectedListener',
        // ],
        // 'App\Events\Transfer\TransferDispatchRejectedEvent' => [
        //     'App\Listeners\Transfer\TransferDispatchRejectedListener',
        // ],

        // 'App\Events\Transfer\TransferDispatchAccepetedEvent' => [
        //     'App\Listeners\Transfer\TransferDispatchAcceptedListener',
        // ],

        // 'App\Events\Etims\EtimsActivatedEvent' => [
        //     'App\Listeners\Etims\TEtimsActivatedListener',
        // ],

        // 'App\Events\Etims\EtimsDeactivateddEvent' => [
        //     'App\Listeners\Etims\EtimsDeactivatedListener',
        // ],
        // 'App\Events\Etims\EtimsPurchasedGoodsdEvent' => [
        //     'App\Listeners\Etims\EtimsPurchasedGoodsListener',
        // ],
        // 'App\Events\Etims\EtimsImportedGoodCreatedEvent' => [
        //     'App\Listeners\Etims\EtimsImportedGoodCreatedListener',
        // ],

        // // chart of accounts
        // // 'App\Events\ChartOfAccounts\ChartOfAccountCreatedEvent' => [
        // //     'App\Listeners\ChartOfAccounts\ChartOfAccountCreatedListener',
        // // ],
        // // 'App\Events\ChartOfAccounts\ChartOfAccounUpdatedEvent' => [
        // //     'App\Listeners\ChartOfAccounts\ChartOfAccountUpdatedListener',
        // // ],
        // // 'App\Events\ChartOfAccounts\ChartOfAccounDestroyedEvent' => [
        // //     'App\Listeners\ChartOfAccounts\ChartOfAccountDestroyedListener',
        // // ],

// --------------------------------- Web hooks-------------------------------------------------------------------------------------------------
        // 'App\Events\WebHooks\InvoiceTransmitedEvent' => [
        //     'App\Listeners\WebHooks\InvoiceTransmitedListener',
        // ],
        // 'App\Events\WebHooks\ItemTransmitedEvent' => [
        //     'App\Listeners\WebHooks\ItemTransmitedListener',
        // ],

        'App\Events\Invoice\InvoiceCreatedEvent' => [
            'App\Listeners\Invoice\InvoiceCreatedListener',
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
