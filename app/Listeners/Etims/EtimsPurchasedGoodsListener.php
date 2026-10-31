<?php

namespace App\Listeners\Etims;

use App\Events\Etims\EtimsPurchasedGoodsdEvent;
use App\Helpers\SupplyHelper;
use App\Helpers\Taxation\ETIMSHelper;
use App\Jobs\Etims\RecordNewProductJob;
use App\Jobs\Etims\RecordStockInOutJob;
use App\Models\Branch;
use App\Models\EtimsPurchase;
use App\Models\EtimsPurchaseItem;
use App\Models\Supplier;
use App\Models\Supply;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EtimsPurchasedGoodsListener
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
    public function handle(EtimsPurchasedGoodsdEvent $event): void
    {
        $purchaseItem = $event->etimsPurchaseItem;
        $etimsPurchase = $purchaseItem->purchase;
        $purchaseItem = $purchaseItem->fresh();
        $product = $event->product;
        $staff = $event->staff;
        $acceptedQuantity = $event->acceptedQuantity;
        $branch = $product->branch;

        
      //create a supply ==> update purchase, in/ out ,master

    //    $supplier = $this->createSupplier($purchaseItem->purchase, $branch);

       $suppliedItem = [
        [
            'item_id' => $product->id,
            'quantity_supplied' => $acceptedQuantity,
            'available_quantity' => $acceptedQuantity,
            'buying_price' => $purchaseItem->unit_price,
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

    RecordStockInOutJob::dispatch($etimsPurchase, [$purchaseItem], 0, $etimsPurchase->purchase_number, $branch, 'incoming_purchase');
    // SupplyHelper::createSupply($suppliedItem,$branch,$staff,$purchaseItem->item_total,null,$supplier,'new purchase','cash');
    
    $supply = Supply::where('etims_purchase_invoice_number', $etimsPurchase->supplier_invoice_number)->first();
    if(empty($supply)){
      $supply =  SupplyHelper::createSupply($suppliedItem,$branch,$staff,$purchaseItem->item_total,null,null,'incoming_purchase','cash');

     $supply->update(['etims_purchase_invoice_number'=>$etimsPurchase->supplier_invoice_number]);
     }
     else SupplyHelper::createSupplyItems($supply,$product,$acceptedQuantity,$purchaseItem->unit_price,$staff,'incoming_purchase',[],0,false,$product->selling_price);
    
}



    public function createSupplier(EtimsPurchase $purchase, Branch $branch)
    {
        // if(! $purchase->supplier_kra_pin) return;


        // $data=[
        //     'branch_id' => $branch->id,
        //     'full_name'=>$purchase->supplier_name,
        //     'first_name'=>$purchase->supplier_name,
        //     'trader_name'=>$purchase->supplier_name,
        //     'kra_pin'=>$purchase->supplier_kra_pin,
        //     'etims_branch_id'=>$purchase->supplier_branch_id,
        // ];

        //  $supplier = Supplier::updateOrCreate(['kra_pin'=>$purchase->supplier_kra_pin], $data);
        // return $supplier;
    }
}
