<?php
namespace App\Helpers;

use App\Models\Company;
use App\Models\Item;

class TaxationUtilities
{
    public static function is_etims_enabled(?Company $company =null)
    {
        return false;
        if( empty($company) ) $company = Company::first();
        if( empty($company) ) return false;
        return $company->kra_etims_active && $company->etims_credentials_validated;
    }

public static function prepareProductsForTransmission(Item $product)
{
if(!$product->country_of_origin_code || !$product->packaging_unit_code ||
   !$product->units_of_measure || !$product->unit_of_measure_code ||
   !$product->tax_classification_code || !$product->vat_rate )
        $product->update([
                'country_of_origin_code'=> $product->country_of_origin_code ?$product->country_of_origin_code: 'KE',
                'packaging_unit_code'=> $product->packaging_unit_code ?$product->packaging_unit_code: 'BE',
                'units_of_measure'=>$product->units_of_measure ?$product->units_of_measure: 'pieces',
                'unit_of_measure_code'=> $product->unit_of_measure_code ?$product->unit_of_measure_code: 'U',
                'tax_classification_code'=> $product->tax_classification_code ?$product->tax_classification_code: '49101600',
                'vat_rate' => $product->vat_rate ?$product->vat_rate: 16,
            ]);

        if(!$product->etims_product_code)           $product->update(['etims_product_code'=>  self::getEtimsProductCode($product)]);
}

    public static function getEtimsProductCode(Item $product)
    {
        return $product->country_of_origin_code . self::getItemTypeCodeStatic($product) . $product->packaging_unit_code . $product->unit_of_measure_code . self::getItemSequence($product);
    }

        private static function getItemTypeCodeStatic(Item $product)
    {
        if ($product->type == "raw_material")
            return "1";
        else if ($product->type == "product")
            return "2";
        else if ($product->type == "service")
            return "3";
        else return "1";
    }

        public static function getItemSequence($product)
    {
        $sequenceNumber = $product->id;
        $companyProductSequenceTracker = Item::whereNotNull('etims_product_code')->count();
            $sequenceNumber = $companyProductSequenceTracker + rand(10, 100);
        return Utilities::getNumberSequence($sequenceNumber, 5);
    }

     public static function getNumberSequence($sequenceNumber,$maxZeros=5)
  {
    return str_pad((string) $sequenceNumber, $maxZeros, '0', STR_PAD_LEFT);  // handles numbers padding better past 10,000
  }


}
