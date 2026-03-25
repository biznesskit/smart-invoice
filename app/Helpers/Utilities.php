<?php

namespace App\Helpers;

use App\Jobs\Etims\SyncNewSale;
use App\Jobs\Etims\SyncStockInOutJob;
use App\Jobs\Etims\SyncStockMaster;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Landlord\Tenant;
use App\Models\StockInOut;
use App\Models\StockInOutItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Cast\Object_;

class Utilities
{
  /**
   *Format phone numbers to country code first
   *@param number,string $phone $prefix
   *@return string $phone
   **/
  public static function cleanPhoneNumber($num, $prefix = '254')
  {
    $phone = strval($num);
    $prefix = strval($prefix);

    $phone = str_replace('+', '', $phone);
    $phone = str_replace(' ', '', $phone);
    $phone = str_replace($prefix, '0', $phone);

    $phone = preg_replace('/^0/', '', $phone);

    if (!is_numeric($phone)) return $num;

    $phone = $prefix . $phone;
    return $phone;
  }

  public static function sanitizeString(String $string){
    if(is_null($string)) return $string;
    return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string);
  }

  public static function createNamesFromFullName($validated = [])
  {
    $names = isset($validated['full_name']) ? explode(" ", $validated['full_name']) : null;
    $validated['first_name'] = isset($names[0]) ? $names[0] : 'user';
    $validated['middle_name'] = isset($names[1]) && isset($names[2]) ?  $names[1] : null;
    if (isset($names[1]) && isset($names[2])) $validated['last_name'] = $names[2];
    else if (isset($names[1]) && !isset($names[2])) $validated['last_name'] = $names[1];
    else if (!isset($names[1]) && isset($names[2])) $validated['last_name'] = $names[2];
    else $validated['last_name'] = $validated['first_name'];

    $other_names = '';

    for ($x = 0; $x < count($names); $x++) {
      if ($x <= 2) continue;

      $other_names .= $names[$x] . ' ';
    }

    if ($other_names) $validated['other_names'] = $other_names;

    return $validated;
  }


function randomString(int $length = 10): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $result = '';

    for ($i = 0; $i < $length; $i++) {
        $result .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $result;
}



  public static function getTenantDomain($name)
  {
    $slug = Str::slug($name, '-');
    if (Tenant::where('domain', $slug)->count())
      return self::suggestTenantDomains($slug)[0];
    return $slug;
  }

  public static function suggestTenantDomains($slug)
  {
    $suggestions = [];

    while (count($suggestions) < 3) {
      $randomNum = rand(10, 1000);
      $tenant = Tenant::where('domain', $slug . '-' . $randomNum)->first();
      if (!$tenant)
        $suggestions[] = $slug . '-' . $randomNum;
    }

    return $suggestions;
  }


  public static function getTenantBusinessCode()
  {
    $business_code = null;

    if ($lastEntry = Tenant::latest()->first()) $business_code = $lastEntry->business_code + 1;
    else $business_code = 10000; // default Db start position

    return $business_code;
  }


  public static function generateTenantAgentCode()
  {
    $str = strtoupper(self::generateRandomLetters(1)) . rand(100, 999) . strtoupper(self::generateRandomLetters(1));
    $tenant = Tenant::where('agent_code', $str)->first();
    if (is_null($tenant)) return $str;
    else return self::generateTenantAgentCode();
  }

  public static function generateRandomLetters($length = 10)
  {
    $letters = 'abcdefghijklmnpqrstuvwxyz';
    $string = '';

    for ($i = 0; $i < $length; $i++) {
      $index = rand(0, strlen($letters) - 1);
      $string .= $letters[$index];
    }
    return $string;
  }



  public static function getModelSlug($name, $model_name, $nameSpace = '\\App\\Models\\')
  {
    if (is_null($name) || is_null($model_name)) return $name;
    $model =  $nameSpace . ucfirst($model_name);
    $slug = Str::slug($name, '-');

    // Log::info(method_exists($model,'withTrashed'));

    if(method_exists($model,'withTrashed')){
      if ($model::withTrashed()->where('slug', $slug)->count())
        return self::suggestModelSlug($slug, $model_name);
    }
    else{
      if ($model::where('slug', $slug)->count())
      return self::suggestModelSlug($slug, $model_name);
    }
    return $slug;
  }

  public static function suggestModelSlug($name,  $model_name, $nameSpace = '\\App\\Models\\')
  {
    if (is_null($model_name) || is_null($name)) return false;
    $suggestions = [];
    $model =  $nameSpace . ucfirst($model_name);

    while (count($suggestions) < 3) {
      $randomNum = rand(100, 1000);
      $record = $model::where('slug', $name . $randomNum)->first();
      if (!$record)
        $suggestions[] = $name . $randomNum;
    }
    return $suggestions[0];
  }




  public static function  updateModelStoreAndReleaseNo($model,$original_store_and_release_no=null)
  {
    if( empty($model)) return time();
    $model_name = class_basename($model);
    $storeAndRelease = $model->storeAndReleaseNo()->create([
      'description' => "$model_name# $model->order_number"
    ]);

    if( empty($storeAndRelease) ) return;

    $data = ["store_and_release_no"=>$storeAndRelease->id,"original_store_and_release_no"=>$original_store_and_release_no];

    $model->update($data);

    if( $model_name == 'Order'){
      if(!$invoice = $model->invoice) return;
        $invoice->update($data);
    }
  }

  public static function truncateTableAndResetIDs(String $tableName)
    {
        if (!$tableName) return;

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            DB::table($tableName)->truncate();
        } catch (\Throwable $th) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            Log::error("Bulk delete error: Unable to truncate table $tableName");
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public static function validateItemsAvailableInStock(Array $items)
    {
        // $unavailableItems = [];

        // foreach($items as $item):
        //     $savedItem = null;
        //     if( ! isset($item['quantity']) ) {array_push($unavailableItems,$item);continue;}
        //     if( isset($item['id']) ) $savedItem = Item::find($item['id']);
        //     if( is_null($savedItem) && isset($item['item_id']) ) $savedItem = Item::find($item['item_id']);
        //     if( is_null($savedItem) && isset($item['item_code']) ) $savedItem = Item::where('item_code',$item['item_code'])->first();
        //     if( is_null($savedItem) ) {array_push($unavailableItems,$item);continue;}
        //     if($savedItem->type == 'service') continue;
        //     if( ! $inventory = $savedItem->latest_inventory ) {array_push($unavailableItems,$item);continue;}
        //     if( $inventory->quantity < $item['quantity'] ) {array_push($unavailableItems,$item);continue;}
        // endforeach;

        // return $unavailableItems;
    }

    public static function incrementStock(Item $item, FLoat $quantity,Float $taxable_amount, Float $tax_amount, Float $total_amount, $reason = null)
    {
        // if (is_null($quantity) || is_null($item) || !is_numeric($quantity)) return;
        // if ($item->type == 'service') return;

        // $item = $item->refresh();
        // $item_inventory = $item->latest_inventory;

        // $total = 0;
        // $previous_qty = $item_inventory ? ($item_inventory->quantity > 0 ? $item_inventory->quantity : 0) : 0;
        // $total =  $item_inventory ? ($item_inventory->quantity > 0 ? floatval($item_inventory->quantity) + $quantity : $quantity) : $quantity;

        // $data = [
        //     'increase_stock_by' => $quantity,
        //     'item_name' => $item->item_name,
        //     'default_unit_price' => $item->default_unit_price,
        //     'transacted_quantity' => $quantity,
        //     'reason' => $reason,
        //     'branch_id' => $item->branch_id,
        //     'previous_quantity' => $previous_qty,
        //     'quantity' => $total,
        //     'taxable_amount' => $taxable_amount,
        //     'tax_amount' => $tax_amount,
        //     'total_amount' => $total_amount,
        // ];

        // return $item->inventories()->create($data);
    }

    public static function decrementStock(Item $item, FLoat $quantity,Float $taxable_amount, Float $tax_amount, Float $total_amount, $reason = null)
    {
        // if (is_null($quantity) || is_null($item) || !is_numeric($quantity)) return;
        // if ($item->type == 'service') return;

        // $item = $item->refresh();
        // $item_inventory = $item->latest_inventory;
        // if( is_null($item_inventory) ) return;

        // $total = 0;
        // $previous_qty = $item_inventory->quantity > 0 ? $item_inventory->quantity : 0;
        // $total = $item_inventory->quantity > 0 ? floatval($item_inventory->quantity) - $quantity : $quantity;

        // $data = [
        //     'reduce_stock_by' => $quantity,
        //     'item_name' => $item->item_name,
        //     'default_unit_price' => $item->default_unit_price,
        //     'transacted_quantity' => $quantity,
        //     'reason' => $reason,
        //     'branch_id' => $item->branch_id,
        //     'previous_quantity' => $previous_qty,
        //     'quantity' => $total,
        //     'taxable_amount' => $taxable_amount,
        //     'tax_amount' => $tax_amount,
        //     'total_amount' => $total_amount,
        // ];

        // return $item->inventories()->create($data);
    }

    public static function recordStockIO(Branch $branch,String $released_type_code,String $original_stored_and_released_number,Float $total_taxable_amount,Float $total_tax_amount, Float $total_amount,Array $item_list,User $staff, String $tracking_number ,?Customer $customer=null, ?Invoice $invoice=null,$request=null)
    {
          $stockIOData = [
            "tracking_number" => $tracking_number,
            "stored_and_released_number" => $branch->stock_in_outs()->count() + 1,
            "original_stored_and_released_number" => $original_stored_and_released_number,
            "stored_and_released_type_code" => strval($released_type_code),
            "registration_type_code" => "A",//automatic
            "customer_kra_pin" => $customer ? $customer->kra_pin : null,
            "customer_name" => $customer ? $customer->name : null,
            "customer_branch_code" => $customer ? $customer->branch_code : null,
            "dispatching_branch_code" => $request ? $request->dispatching_branch_code : null,
            "dispatching_branch_name" => $request ? $request->dispatching_branch_name : null,
            "occured_date_time" => now()->format("Ymd"),
            "total_taxable_amount" => $total_taxable_amount,
            "total_tax_amount" => $total_tax_amount,
            "total_amount" => $total_amount,
            "created_by" => $staff->id,
            "invoice_id" => $invoice?$invoice->id:null
        ];

        $stockIO = $branch->stock_in_outs()->create($stockIOData);
        $stockIO->jobQueueSequence()->create(['created_by' => $staff->id]);
        $count=1;
        foreach($item_list as $list ):
          $item = isset($list["tracking_number"]) ? Item::where('tracking_number',$list["tracking_number"])->first() : null;
          // Log::alert($item);
          if( is_null($item) ) continue;

          if($item->type == 'service') continue;

          $unitPrice = isset($list["unit_price"]) ? ($list["unit_price"] ? $list["unit_price"] : $item->default_unit_price) : $list["default_unit_price"];
          $stockIOItemData = [
            'item_sequence_number' => $count,
            'tracking_number'=> $list['tracking_number'],
            'item_id' => $item->id,
            'item_code' => $item->item_code,
            'item_classification_code' => $item->item_classification_code,
            'item_name' => $item->item_name,
            'barcode' => $item->barcode,
            'packaging_unit' => $item->packaging_unit,//e.g 1 box, 1 crate etc
            'packaging_unit_code' => $item->packaging_unit_code,
            'quantity' => isset($list["quantity"]) ? $list["quantity"] : 0,
            'remaining_quantity' => isset($list["remaining_quantity"]) ? $list["remaining_quantity"] : 0,
            'quantity_unit_code' => $item->quantity_unit_code,
            'unit_price' => $unitPrice,
            'supply_price' => isset($list['total_amount']) ? $list['total_amount'] : 0,
            'discount_rate' => isset($list["discount_rate"]) ? $list["discount_rate"] : 0,
            'discount_amount' => isset($list["discount_amount"]) ? $list["discount_amount"] : 0,
            'total_discount_amount' => isset($list["discount_amount"]) ? $list["discount_amount"] : 0,
            'tax_type_code' => $item->tax_type_code,
            'taxable_amount' => isset($list["taxable_amount"]) ?  $list["taxable_amount"] : 0,
            'tax_amount' => isset($list["tax_amount"]) ? $list["tax_amount"] : 0,
            'total_amount' => isset($list["total_amount"]) ? $list["total_amount"] : 0,
        ];

        $stockInOutItem = $stockIO->items()->create($stockIOItemData);

        self::recordStockMaster($stockInOutItem,$branch,$staff);

        $count += 1;
    endforeach;


    $stockIO = $stockIO->fresh();
    if( ! $stockIO->items()->count() )  {
        $stockIO->jobQueueSequence()->forceDelete();
        $stockIO->forceDelete();
    }

    TransmissionQueueManager::processQueue();




        return $stockIO;
    }

    public static function recordNewSale(Invoice $invoice, Branch $branch, User $staff)
    {
      //dispatch this job if no pending job
      SyncNewSale::dispatch($invoice,$branch,$staff);
    }

    public static function pendingEtimsSyncCount()
    {
      $pendingSyncCount = 0;

      $pendingSyncCount += StockInOut::whereNull('synced_at')->count();
      $pendingSyncCount += Invoice::whereNull('synced_at')->count();

    }

    public static function recordStockMaster(StockInOutItem $stockInOutItem,Branch $branch, User $staff)
    {
      $stockMaster = $branch->stock_masters()->create([
        'item_id' => $stockInOutItem->item_id,
        'tracking_number'=>$stockInOutItem->tracking_number,
        'stock_in_out_id' => $stockInOutItem->stock_in_out_id,
        'item_code' => $stockInOutItem->item_code,
        'remaining_quantity' => $stockInOutItem->remaining_quantity,
        'registration_name' =>  ucwords($staff->first_name),
        'modifier_id' =>  $staff->id,
        'created_by' =>  $staff->id,
            'modifier_name' => ucwords($staff->first_name),
          ]);

          $stockMaster->jobQueueSequence()->create(['created_by' => $staff->id]);
          return   $stockMaster;
    }

        public static function getNumberSequence($sequenceNumber,$maxZeros=5)
  {
    return str_pad((string) $sequenceNumber, $maxZeros, '0', STR_PAD_LEFT);  

  }

}
