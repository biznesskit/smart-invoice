<?php

namespace App\Listeners\Etims;

use App\Events\Etims\EtimsImportedGoodCreatedEvent;
use App\Helpers\SupplyHelper;
use App\Helpers\Taxation\ETIMSHelper;
use App\Jobs\Etims\RecordNewProductJob;
use App\Jobs\Etims\RecordStockInOutJob;
use App\Models\Supply;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EtimsImportedGoodCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EtimsImportedGoodCreatedEvent $event): void
    {
        $importedItem = $event->etimsImportedItem;
        $etimsImport = $importedItem->import;
        $importedItem = $importedItem->fresh();
        $product = $event->product;
        $staff = $event->staff;
        $acceptedQuantity = $event->acceptedQuantity;
        $branch = $product->branch;

        
      //create a supply ==> update purchase, in/ out ,master

    //    $supplier = $this->createSupplier($importedItem->purchase, $branch);

       $suppliedItem = [
        [
            'item_id' => $product->id,
            'quantity_supplied' => $acceptedQuantity,
            'available_quantity' => $acceptedQuantity,
            'buying_price' => $importedItem->unit_price,
        ],
    ];

    $product->update([
        "etims_product_code" =>   ETIMSHelper::getEtimsProductCode($product),
       ]);

       ///update item with selling price of  the product
        // set item_total = selling price * quantity
        // set vat amount from item total
        // set catering levy

    RecordNewProductJob::dispatch($product);
    RecordStockInOutJob::dispatch($etimsImport, [$importedItem], 0, $etimsImport->import_number, $branch, 'incoming_import');
    // SupplyHelper::createSupply($suppliedItem,$branch,$staff,$importedItem->item_total,null,$supplier,'new purchase','cash');
    
    $supply = Supply::where('declaration_number', $etimsImport->declaration_number)->first();
    if(empty($supply)){
      $supply =  SupplyHelper::createSupply($suppliedItem,$branch,$staff,$importedItem->item_total,null,null,'incoming_import','cash');

     $supply->update(['declaration_number'=>$etimsImport->declaration_number]);
     }
     else SupplyHelper::createSupplyItems($supply,$product,$acceptedQuantity,$importedItem->buying_price,$staff,'incoming_import',[],0,false,$product->selling_price);
 
    }
}
