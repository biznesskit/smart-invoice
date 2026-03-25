<?php
namespace App\Helpers;

use App\Models\Landlord\TaxCode;

class StandardTaxationCodes 
{

    public static function getCreditNoteReasons()
    {
        return  [
            [
                'code' => '01',
                'reason' => 'missing_quantity',
                'name' => 'Missing Quantity'
            ],
            [
                'code' => '02',
                'reason'=>'accidental_charge',
                'name' => 'Missing data'
            ],
            [
                'code' => '03',
                'reason' => 'damaged_goods',
                'name' => 'damaged'
            ],
            [
                'code' => '04',
                'reason' => 'wasted',
                'name' => 'wasted'
            ],
            [
                'code' => '05',
                'reason' => 'raw_material_shortage',
                'name' => 'raw material shortage'
            ],
            [
                'code' => '06',
                'reason' => 'raw_material_shortage',
                'name' => 'raw material shortage'
            ],
            ["code"=>'07','reason'=>'wrong_customer','name'=>'wrong customer pin'],
            ["code"=>'08','reason'=>'wrong_customer','name'=>'wrong customer name'],
            ["code"=>'09','reason'=>'wrong_price','name'=>'wrong amount/price'],
            ["code"=>'10','reason'=>'wrong_quantity','name'=>'wrong quantity'],
            ["code"=>'11','reason'=>'wrong_items','name'=>'wrong items'],
            ["code"=>'12','reason' => 'wrong_tax_type', 'name'=>'wrong tax type'],
            ["code"=>'13','reason' => 'other_reason','name'=>'other reason'],
        ];
    }

    public static function getCreditNoteReasonCode($reason = 'damaged_goods')
    {
        if( $reason == 'expired' || $reason == 'other' ) $reason = 'damaged_goods';
        
        foreach (self::getCreditNoteReasons() as $type)
            if ($reason == $type['reason']) return $type['code'];


    }

    public static function getImportItemStatuses()
    {
        return  [
            [
                'code' => '1',
                'name' => 'unsent'
            ],
            [
                'code' => '2',
                'name' => 'waiting'
            ],
            [
                'code' => '3',
                'name' => 'approved'
            ],
            [
                'code' => '4',
                'name' => 'canceled'
            ],
        ];
    }

    public static function getStockInOutTypes()
    {
        return  [
            ['code' => '01', 'reason' => 'incoming_import',  'name' => 'Import', 'description' => 'Incoming -Import'],
            ['code' => '02', 'reason' => 'incoming_purchase',  'name' => 'Purchase', 'description' => 'Incoming -Purchase'],
            ['code' => '03', 'reason' => 'incoming_return', 'name' => 'Return', 'description' => 'Incoming -Return'],
            ['code' => '04', 'reason' => 'incoming_stock', 'name' => 'Stock Movement', 'description' => 'Incoming- Stock'],
            ['code' => '05', 'reason' => 'incoming_processing', 'name' => 'Processing', 'description' => 'Incoming -Processing'],
            ['code' => '06', 'reason' => 'incoming_adjustment','name' => 'Adjustment', 'description' => 'Incoming -Adjustment'],
            
            ['code' => '11', 'reason' => 'outgoing_sale', 'name' => 'Sale', 'description' => 'Outgoing -Sale'],
            ['code' => '12', 'reason' => 'outgoing_return','name' => 'Return', 'description' => 'Outgoing- Return'],
            ['code' => '13', 'reason' => 'outgoing_stock_movement', 'name' => 'Stock Movement', 'description' => 'Outgoing- Stock Movement'],
            ['code' => '14', 'reason' => 'outgoing_processing', 'name' => 'Processing', 'description' => 'Outgoing- Processing'],
            ['code' => '15', 'reason' => 'outgoing_discard', 'name' => 'Discarding', 'description' => 'Outgoing -Discarding'],
            ['code' => '16', 'reason' => 'outgoing_adjustment', 'name' => 'Adjustment', 'description' => 'Outgoing -Adjustment']
        ];
    }

    public static function getStockOutType($reason = 'outgoing_sale')
    {
        foreach (self::getStockInOutTypes() as $type)
            if ($reason == $type['reason']) return $type['code'];

        
    }

    public static function getPurchaseReceiptTypes()
    {
        return  [
            ['code' => 'P', 'name' => 'Purchase', 'description' => 'Purchase'],
            ['code' => 'R',  'name' => 'Credit Note after Purchase', 'description' => 'Credit Note after  Purchase']
        ];
    }

    public static function getRegistrationTypes()
    {
        return  [
            ['code' => 'A', 'name' => 'Automatic', 'description' => 'Automatic'],
            ['code' => 'M',  'name' => 'Manual', 'description' => 'Manual']
        ];
    }

    public static function getTransactionProgressStatuses()
    {
        return  [
            ['code' => '01',  'name' => 'waiting_for_approval', 'description' => 'waiting for approval'],
            ['code' => '02',  'name' => 'approved', 'description' => 'approved'],
            ['code' => '03',  'name' => 'cancel_requested', 'description' => 'cancel requested'],
            ['code' => '04',  'name' => 'canceled', 'description' => 'canceled'],
            ['code' => '05',  'name' => 'credit_note_generated', 'description' => 'credit note generated'],
            ['code' => '06',  'name' => 'transfered', 'description' => 'transfered'],
        ];
    }

    public static function getPaymentMethodTypes()
    {
        return  [
            ['code' => '01', 'name' => 'cash', 'description' => 'cash'],
            ['code' => '01', 'name' => 'split_payment_without_credit', 'description' => 'cash'],
            ['code' => '02', 'name' => 'on_credit', 'description' => 'credit'],
            ['code' => '02', 'name' => 'pay_later', 'description' => 'credit'],
            ['code' => '03', 'name' => 'split_payment_with_credit', 'description' => 'cash/credit'],
            ['code' => '04', 'name' => 'cheque', 'description' => 'bank check payment'],
            ['code' => '05', 'name' => 'card', 'description' => 'payment using card'],
            ['code' => '06', 'name' => 'mpesa', 'description' => 'any transaction using mobile money'],
            ['code' => '07', 'name' => 'insurance', 'description' => 'other transaction payment'],
            ['code' => '07', 'name' => 'other', 'description' => 'other transaction payment'],

        ];
    }

    public static function getSalesRecieptTypes()
    {
        return  [
            ['code' => 'S', 'name' => 'sale', 'description' => 'sale'],
            ['code' => 'R', 'name' => 'refund', 'description' => 'Credit Note after     Sale'],
        ];
    }

    public static function getTransactionTypes()
    {
        return  [
            ['code' => 'C', 'name' => 'copy', 'description' => 'Sales and purchase type : Copy'],
            ['code' => 'N', 'name' => 'normal', 'description' => 'Sales and purchase type : normal'],
            ['code' => 'P', 'name' => 'proforma', 'description' => 'Sales and purchase type : proforma'],
            ['code' => 'T', 'name' => 'training', 'description' => 'Sales and purchase type : training'],
        ];
    }

    public static function getCurrencyTypes()
    {
        return  [
            [
                "code" => "AFN",
                "name" => "Afghani",
                "country" => "AFGHANISTAN"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "ALAND ISLANDS"
            ],
            [
                "code" => "ALL",
                "name" => "Lek",
                "country" => "ALBANIA"
            ],
            [
                "code" => "DZD",
                "name" => "Algerian Dinar",
                "country" => "ALGERIA"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "AMERICAN SAMOA"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "ANDORRA"
            ],
            [
                "code" => "AOA",
                "name" => "Kwanza",
                "country" => "ANGOLA"
            ],
            [
                "code" => "XCD",
                "name" => "East Caribbean Dollar",
                "country" => "ANGUILLA"
            ],
            [
                "code" => "XCD",
                "name" => "East Caribbean Dollar",
                "country" => "ANTIGUA AND BARBUDA"
            ],
            [
                "code" => "ARS",
                "name" => "Argentine Peso",
                "country" => "ARGENTINA"
            ],
            [
                "code" => "AMD",
                "name" => "Armenian Dram",
                "country" => "ARMENIA"
            ],
            [
                "code" => "AWG",
                "name" => "Aruban Florin",
                "country" => "ARUBA"
            ],
            [
                "code" => "AUD",
                "name" => "Australian Dollar",
                "country" => "AUSTRALIA"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "AUSTRIA"
            ],
            [
                "code" => "AZN",
                "name" => "Azerbaijan Manat",
                "country" => "AZERBAIJAN"
            ],
            [
                "code" => "BSD",
                "name" => "Bahamian Dollar",
                "country" => "BAHAMAS (THE)"
            ],
            [
                "code" => "BHD",
                "name" => "Bahraini Dinar",
                "country" => "BAHRAIN"
            ],
            [
                "code" => "BDT",
                "name" => "Taka",
                "country" => "BANGLADESH"
            ],
            [
                "code" => "BBD",
                "name" => "Barbados Dollar",
                "country" => "BARBADOS"
            ],
            [
                "code" => "BYN",
                "name" => "Belarusian Ruble",
                "country" => "BELARUS"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "BELGIUM"
            ],
            [
                "code" => "BZD",
                "name" => "Belize Dollar",
                "country" => "BELIZE"
            ],
            [
                "code" => "XOF",
                "name" => "CFA Franc BCEAO",
                "country" => "BENIN"
            ],
            [
                "code" => "BMD",
                "name" => "Bermudian Dollar",
                "country" => "BERMUDA"
            ],
            [
                "code" => "BTN",
                "name" => "Ngultrum",
                "country" => "BHUTAN"
            ],
            [
                "code" => "INR",
                "name" => "Indian Rupee",
                "country" => "BHUTAN"
            ],
            [
                "code" => "BOB",
                "name" => "Boliviano",
                "country" => "BOLIVIA (PLURINATIONAL STATE OF)"
            ],
            [
                "code" => "BOV",
                "name" => "Mvdol",
                "country" => "BOLIVIA (PLURINATIONAL STATE OF)"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "BONAIRE, SINT EUSTATIUS AND SABA"
            ],
            [
                "code" => "BAM",
                "name" => "Convertible Mark",
                "country" => "BOSNIA AND HERZEGOVINA"
            ],
            [
                "code" => "BWP",
                "name" => "Pula",
                "country" => "BOTSWANA"
            ],
            [
                "code" => "NOK",
                "name" => "Norwegian Krone",
                "country" => "BOUVET ISLAND"
            ],
            [
                "code" => "BRL",
                "name" => "Brazilian Real",
                "country" => "BRAZIL"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "BRITISH INDIAN OCEAN TERRITORY (THE)"
            ],
            [
                "code" => "BND",
                "name" => "Brunei Dollar",
                "country" => "BRUNEI DARUSSALAM"
            ],
            [
                "code" => "BGN",
                "name" => "Bulgarian Lev",
                "country" => "BULGARIA"
            ],
            [
                "code" => "XOF",
                "name" => "CFA Franc BCEAO",
                "country" => "BURKINA FASO"
            ],
            [
                "code" => "BIF",
                "name" => "Burundi Franc",
                "country" => "BURUNDI"
            ],
            [
                "code" => "CVE",
                "name" => "Cabo Verde Escudo",
                "country" => "CABO VERDE"
            ],
            [
                "code" => "KHR",
                "name" => "Riel",
                "country" => "CAMBODIA"
            ],
            [
                "code" => "XAF",
                "name" => "CFA Franc BEAC",
                "country" => "CAMEROON"
            ],
            [
                "code" => "CAD",
                "name" => "Canadian Dollar",
                "country" => "CANADA"
            ],
            [
                "code" => "KYD",
                "name" => "Cayman Islands Dollar",
                "country" => "CAYMAN ISLANDS (THE)"
            ],
            [
                "code" => "XAF",
                "name" => "CFA Franc BEAC",
                "country" => "CENTRAL AFRICAN REPUBLIC (THE)"
            ],
            [
                "code" => "XAF",
                "name" => "CFA Franc BEAC",
                "country" => "CHAD"
            ],
            [
                "code" => "CLF",
                "name" => "Unidad de Fomento",
                "country" => "CHILE"
            ],
            [
                "code" => "CLP",
                "name" => "Chilean Peso",
                "country" => "CHILE"
            ],
            [
                "code" => "CNY",
                "name" => "Yuan Renminbi",
                "country" => "CHINA"
            ],
            [
                "code" => "AUD",
                "name" => "Australian Dollar",
                "country" => "CHRISTMAS ISLAND"
            ],
            [
                "code" => "AUD",
                "name" => "Australian Dollar",
                "country" => "COCOS (KEELING) ISLANDS (THE)"
            ],
            [
                "code" => "COP",
                "name" => "Colombian Peso",
                "country" => "COLOMBIA"
            ],
            [
                "code" => "COU",
                "name" => "Unidad de Valor Real",
                "country" => "COLOMBIA"
            ],
            [
                "code" => "KMF",
                "name" => "Comorian Franc ",
                "country" => "COMOROS (THE)"
            ],
            [
                "code" => "CDF",
                "name" => "Congolese Franc",
                "country" => "CONGO (THE DEMOCRATIC REPUBLIC OF THE)"
            ],
            [
                "code" => "XAF",
                "name" => "CFA Franc BEAC",
                "country" => "CONGO (THE)"
            ],
            [
                "code" => "NZD",
                "name" => "New Zealand Dollar",
                "country" => "COOK ISLANDS (THE)"
            ],
            [
                "code" => "CRC",
                "name" => "Costa Rican Colon",
                "country" => "COSTA RICA"
            ],
            [
                "code" => "XOF",
                "name" => "CFA Franc BCEAO",
                "country" => "CÔTE D'IVOIRE"
            ],
            [
                "code" => "HRK",
                "name" => "Kuna",
                "country" => "CROATIA"
            ],
            [
                "code" => "CUC",
                "name" => "Peso Convertible",
                "country" => "CUBA"
            ],
            [
                "code" => "CUP",
                "name" => "Cuban Peso",
                "country" => "CUBA"
            ],
            [
                "code" => "ANG",
                "name" => "Netherlands Antillean Guilder",
                "country" => "CURAÇAO"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "CYPRUS"
            ],
            [
                "code" => "CZK",
                "name" => "Czech Koruna",
                "country" => "CZECHIA"
            ],
            [
                "code" => "DKK",
                "name" => "Danish Krone",
                "country" => "DENMARK"
            ],
            [
                "code" => "DJF",
                "name" => "Djibouti Franc",
                "country" => "DJIBOUTI"
            ],
            [
                "code" => "XCD",
                "name" => "East Caribbean Dollar",
                "country" => "DOMINICA"
            ],
            [
                "code" => "DOP",
                "name" => "Dominican Peso",
                "country" => "DOMINICAN REPUBLIC (THE)"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "ECUADOR"
            ],
            [
                "code" => "EGP",
                "name" => "Egyptian Pound",
                "country" => "EGYPT"
            ],
            [
                "code" => "SVC",
                "name" => "El Salvador Colon",
                "country" => "EL SALVADOR"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "EL SALVADOR"
            ],
            [
                "code" => "XAF",
                "name" => "CFA Franc BEAC",
                "country" => "EQUATORIAL GUINEA"
            ],
            [
                "code" => "ERN",
                "name" => "Nakfa",
                "country" => "ERITREA"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "ESTONIA"
            ],
            [
                "code" => "SZL",
                "name" => "Lilangeni",
                "country" => "ESWATINI"
            ],
            [
                "code" => "ETB",
                "name" => "Ethiopian Birr",
                "country" => "ETHIOPIA"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "EUROPEAN UNION"
            ],
            [
                "code" => "FKP",
                "name" => "Falkland Islands Pound",
                "country" => "FALKLAND ISLANDS (THE) [MALVINAS]"
            ],
            [
                "code" => "DKK",
                "name" => "Danish Krone",
                "country" => "FAROE ISLANDS (THE)"
            ],
            [
                "code" => "FJD",
                "name" => "Fiji Dollar",
                "country" => "FIJI"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "FINLAND"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "FRANCE"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "FRENCH GUIANA"
            ],
            [
                "code" => "XPF",
                "name" => "CFP Franc",
                "country" => "FRENCH POLYNESIA"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "FRENCH SOUTHERN TERRITORIES (THE)"
            ],
            [
                "code" => "XAF",
                "name" => "CFA Franc BEAC",
                "country" => "GABON"
            ],
            [
                "code" => "GMD",
                "name" => "Dalasi",
                "country" => "GAMBIA (THE)"
            ],
            [
                "code" => "GEL",
                "name" => "Lari",
                "country" => "GEORGIA"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "GERMANY"
            ],
            [
                "code" => "GHS",
                "name" => "Ghana Cedi",
                "country" => "GHANA"
            ],
            [
                "code" => "GIP",
                "name" => "Gibraltar Pound",
                "country" => "GIBRALTAR"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "GREECE"
            ],
            [
                "code" => "DKK",
                "name" => "Danish Krone",
                "country" => "GREENLAND"
            ],
            [
                "code" => "XCD",
                "name" => "East Caribbean Dollar",
                "country" => "GRENADA"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "GUADELOUPE"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "GUAM"
            ],
            [
                "code" => "GTQ",
                "name" => "Quetzal",
                "country" => "GUATEMALA"
            ],
            [
                "code" => "GBP",
                "name" => "Pound Sterling",
                "country" => "GUERNSEY"
            ],
            [
                "code" => "GNF",
                "name" => "Guinean Franc",
                "country" => "GUINEA"
            ],
            [
                "code" => "XOF",
                "name" => "CFA Franc BCEAO",
                "country" => "GUINEA-BISSAU"
            ],
            [
                "code" => "GYD",
                "name" => "Guyana Dollar",
                "country" => "GUYANA"
            ],
            [
                "code" => "HTG",
                "name" => "Gourde",
                "country" => "HAITI"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "HAITI"
            ],
            [
                "code" => "AUD",
                "name" => "Australian Dollar",
                "country" => "HEARD ISLAND AND McDONALD ISLANDS"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "HOLY SEE (THE)"
            ],
            [
                "code" => "HNL",
                "name" => "Lempira",
                "country" => "HONDURAS"
            ],
            [
                "code" => "HKD",
                "name" => "Hong Kong Dollar",
                "country" => "HONG KONG"
            ],
            [
                "code" => "HUF",
                "name" => "Forint",
                "country" => "HUNGARY"
            ],
            [
                "code" => "ISK",
                "name" => "Iceland Krona",
                "country" => "ICELAND"
            ],
            [
                "code" => "INR",
                "name" => "Indian Rupee",
                "country" => "INDIA"
            ],
            [
                "code" => "IDR",
                "name" => "Rupiah",
                "country" => "INDONESIA"
            ],
            [
                "code" => "XDR",
                "name" => "SDR (Special Drawing Right)",
                "country" => "INTERNATIONAL MONETARY FUND (IMF) "
            ],
            [
                "code" => "IRR",
                "name" => "Iranian Rial",
                "country" => "IRAN (ISLAMIC REPUBLIC OF)"
            ],
            [
                "code" => "IQD",
                "name" => "Iraqi Dinar",
                "country" => "IRAQ"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "IRELAND"
            ],
            [
                "code" => "GBP",
                "name" => "Pound Sterling",
                "country" => "ISLE OF MAN"
            ],
            [
                "code" => "ILS",
                "name" => "New Israeli Sheqel",
                "country" => "ISRAEL"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "ITALY"
            ],
            [
                "code" => "JMD",
                "name" => "Jamaican Dollar",
                "country" => "JAMAICA"
            ],
            [
                "code" => "JPY",
                "name" => "Yen",
                "country" => "JAPAN"
            ],
            [
                "code" => "GBP",
                "name" => "Pound Sterling",
                "country" => "JERSEY"
            ],
            [
                "code" => "JOD",
                "name" => "Jordanian Dinar",
                "country" => "JORDAN"
            ],
            [
                "code" => "KZT",
                "name" => "Tenge",
                "country" => "KAZAKHSTAN"
            ],
            [
                "code" => "KES",
                "name" => "Kenyan Shilling",
                "country" => "KENYA"
            ],
            [
                "code" => "AUD",
                "name" => "Australian Dollar",
                "country" => "KIRIBATI"
            ],
            [
                "code" => "KPW",
                "name" => "North Korean Won",
                "country" => "KOREA (THE DEMOCRATIC PEOPLE’S REPUBLIC OF)"
            ],
            [
                "code" => "KRW",
                "name" => "Won",
                "country" => "KOREA (THE REPUBLIC OF)"
            ],
            [
                "code" => "KWD",
                "name" => "Kuwaiti Dinar",
                "country" => "KUWAIT"
            ],
            [
                "code" => "KGS",
                "name" => "Som",
                "country" => "KYRGYZSTAN"
            ],
            [
                "code" => "LAK",
                "name" => "Lao Kip",
                "country" => "LAO PEOPLE’S DEMOCRATIC REPUBLIC (THE)"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "LATVIA"
            ],
            [
                "code" => "LBP",
                "name" => "Lebanese Pound",
                "country" => "LEBANON"
            ],
            [
                "code" => "LSL",
                "name" => "Loti",
                "country" => "LESOTHO"
            ],
            [
                "code" => "ZAR",
                "name" => "Rand",
                "country" => "LESOTHO"
            ],
            [
                "code" => "LRD",
                "name" => "Liberian Dollar",
                "country" => "LIBERIA"
            ],
            [
                "code" => "LYD",
                "name" => "Libyan Dinar",
                "country" => "LIBYA"
            ],
            [
                "code" => "CHF",
                "name" => "Swiss Franc",
                "country" => "LIECHTENSTEIN"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "LITHUANIA"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "LUXEMBOURG"
            ],
            [
                "code" => "MOP",
                "name" => "Pataca",
                "country" => "MACAO"
            ],
            [
                "code" => "MKD",
                "name" => "Denar",
                "country" => "MACEDONIA (THE FORMER YUGOSLAV REPUBLIC OF)"
            ],
            [
                "code" => "MGA",
                "name" => "Malagasy Ariary",
                "country" => "MADAGASCAR"
            ],
            [
                "code" => "MWK",
                "name" => "Malawi Kwacha",
                "country" => "MALAWI"
            ],
            [
                "code" => "MYR",
                "name" => "Malaysian Ringgit",
                "country" => "MALAYSIA"
            ],
            [
                "code" => "MVR",
                "name" => "Rufiyaa",
                "country" => "MALDIVES"
            ],
            [
                "code" => "XOF",
                "name" => "CFA Franc BCEAO",
                "country" => "MALI"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "MALTA"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "MARSHALL ISLANDS (THE)"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "MARTINIQUE"
            ],
            [
                "code" => "MRU",
                "name" => "Ouguiya",
                "country" => "MAURITANIA"
            ],
            [
                "code" => "MUR",
                "name" => "Mauritius Rupee",
                "country" => "MAURITIUS"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "MAYOTTE"
            ],
            [
                "code" => "XUA",
                "name" => "ADB Unit of Account",
                "country" => "MEMBER COUNTRIES OF THE AFRICAN DEVELOPMENT BANK GROUP"
            ],
            [
                "code" => "MXN",
                "name" => "Mexican Peso",
                "country" => "MEXICO"
            ],
            [
                "code" => "MXV",
                "name" => "Mexican Unidad de Inversion (UDI)",
                "country" => "MEXICO"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "MICRONESIA (FEDERATED STATES OF)"
            ],
            [
                "code" => "MDL",
                "name" => "Moldovan Leu",
                "country" => "MOLDOVA (THE REPUBLIC OF)"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "MONACO"
            ],
            [
                "code" => "MNT",
                "name" => "Tugrik",
                "country" => "MONGOLIA"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "MONTENEGRO"
            ],
            [
                "code" => "XCD",
                "name" => "East Caribbean Dollar",
                "country" => "MONTSERRAT"
            ],
            [
                "code" => "MAD",
                "name" => "Moroccan Dirham",
                "country" => "MOROCCO"
            ],
            [
                "code" => "MZN",
                "name" => "Mozambique Metical",
                "country" => "MOZAMBIQUE"
            ],
            [
                "code" => "MMK",
                "name" => "Kyat",
                "country" => "MYANMAR"
            ],
            [
                "code" => "NAD",
                "name" => "Namibia Dollar",
                "country" => "NAMIBIA"
            ],
            [
                "code" => "ZAR",
                "name" => "Rand",
                "country" => "NAMIBIA"
            ],
            [
                "code" => "AUD",
                "name" => "Australian Dollar",
                "country" => "NAURU"
            ],
            [
                "code" => "NPR",
                "name" => "Nepalese Rupee",
                "country" => "NEPAL"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "NETHERLANDS (THE)"
            ],
            [
                "code" => "XPF",
                "name" => "CFP Franc",
                "country" => "NEW CALEDONIA"
            ],
            [
                "code" => "NZD",
                "name" => "New Zealand Dollar",
                "country" => "NEW ZEALAND"
            ],
            [
                "code" => "NIO",
                "name" => "Cordoba Oro",
                "country" => "NICARAGUA"
            ],
            [
                "code" => "XOF",
                "name" => "CFA Franc BCEAO",
                "country" => "NIGER (THE)"
            ],
            [
                "code" => "NGN",
                "name" => "Naira",
                "country" => "NIGERIA"
            ],
            [
                "code" => "NZD",
                "name" => "New Zealand Dollar",
                "country" => "NIUE"
            ],
            [
                "code" => "AUD",
                "name" => "Australian Dollar",
                "country" => "NORFOLK ISLAND"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "NORTHERN MARIANA ISLANDS (THE)"
            ],
            [
                "code" => "NOK",
                "name" => "Norwegian Krone",
                "country" => "NORWAY"
            ],
            [
                "code" => "OMR",
                "name" => "Rial Omani",
                "country" => "OMAN"
            ],
            [
                "code" => "PKR",
                "name" => "Pakistan Rupee",
                "country" => "PAKISTAN"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "PALAU"
            ],
            [
                "code" => "PAB",
                "name" => "Balboa",
                "country" => "PANAMA"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "PANAMA"
            ],
            [
                "code" => "PGK",
                "name" => "Kina",
                "country" => "PAPUA NEW GUINEA"
            ],
            [
                "code" => "PYG",
                "name" => "Guarani",
                "country" => "PARAGUAY"
            ],
            [
                "code" => "PEN",
                "name" => "Sol",
                "country" => "PERU"
            ],
            [
                "code" => "PHP",
                "name" => "Philippine Peso",
                "country" => "PHILIPPINES (THE)"
            ],
            [
                "code" => "NZD",
                "name" => "New Zealand Dollar",
                "country" => "PITCAIRN"
            ],
            [
                "code" => "PLN",
                "name" => "Zloty",
                "country" => "POLAND"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "PORTUGAL"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "PUERTO RICO"
            ],
            [
                "code" => "QAR",
                "name" => "Qatari Rial",
                "country" => "QATAR"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "RÉUNION"
            ],
            [
                "code" => "RON",
                "name" => "Romanian Leu",
                "country" => "ROMANIA"
            ],
            [
                "code" => "RUB",
                "name" => "Russian Ruble",
                "country" => "RUSSIAN FEDERATION (THE)"
            ],
            [
                "code" => "RWF",
                "name" => "Rwanda Franc",
                "country" => "RWANDA"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "SAINT BARTHÉLEMY"
            ],
            [
                "code" => "SHP",
                "name" => "Saint Helena Pound",
                "country" => "SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA"
            ],
            [
                "code" => "XCD",
                "name" => "East Caribbean Dollar",
                "country" => "SAINT KITTS AND NEVIS"
            ],
            [
                "code" => "XCD",
                "name" => "East Caribbean Dollar",
                "country" => "SAINT LUCIA"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "SAINT MARTIN (FRENCH PART)"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "SAINT PIERRE AND MIQUELON"
            ],
            [
                "code" => "XCD",
                "name" => "East Caribbean Dollar",
                "country" => "SAINT VINCENT AND THE GRENADINES"
            ],
            [
                "code" => "WST",
                "name" => "Tala",
                "country" => "SAMOA"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "SAN MARINO"
            ],
            [
                "code" => "STN",
                "name" => "Dobra",
                "country" => "SAO TOME AND PRINCIPE"
            ],
            [
                "code" => "SAR",
                "name" => "Saudi Riyal",
                "country" => "SAUDI ARABIA"
            ],
            [
                "code" => "XOF",
                "name" => "CFA Franc BCEAO",
                "country" => "SENEGAL"
            ],
            [
                "code" => "RSD",
                "name" => "Serbian Dinar",
                "country" => "SERBIA"
            ],
            [
                "code" => "SCR",
                "name" => "Seychelles Rupee",
                "country" => "SEYCHELLES"
            ],
            [
                "code" => "SLL",
                "name" => "Leone",
                "country" => "SIERRA LEONE"
            ],
            [
                "code" => "SGD",
                "name" => "Singapore Dollar",
                "country" => "SINGAPORE"
            ],
            [
                "code" => "ANG",
                "name" => "Netherlands Antillean Guilder",
                "country" => "SINT MAARTEN (DUTCH PART)"
            ],
            [
                "code" => "XSU",
                "name" => "Sucre",
                "country" => "SISTEMA UNITARIO DE COMPENSACION REGIONAL DE PAGOS \"SUCRE\""
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "SLOVAKIA"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "SLOVENIA"
            ],
            [
                "code" => "SBD",
                "name" => "Solomon Islands Dollar",
                "country" => "SOLOMON ISLANDS"
            ],
            [
                "code" => "SOS",
                "name" => "Somali Shilling",
                "country" => "SOMALIA"
            ],
            [
                "code" => "ZAR",
                "name" => "Rand",
                "country" => "SOUTH AFRICA"
            ],
            [
                "code" => "SSP",
                "name" => "South Sudanese Pound",
                "country" => "SOUTH SUDAN"
            ],
            [
                "code" => "EUR",
                "name" => "Euro",
                "country" => "SPAIN"
            ],
            [
                "code" => "LKR",
                "name" => "Sri Lanka Rupee",
                "country" => "SRI LANKA"
            ],
            [
                "code" => "SDG",
                "name" => "Sudanese Pound",
                "country" => "SUDAN (THE)"
            ],
            [
                "code" => "SRD",
                "name" => "Surinam Dollar",
                "country" => "SURINAME"
            ],
            [
                "code" => "NOK",
                "name" => "Norwegian Krone",
                "country" => "SVALBARD AND JAN MAYEN"
            ],
            [
                "code" => "SEK",
                "name" => "Swedish Krona",
                "country" => "SWEDEN"
            ],
            [
                "code" => "CHE",
                "name" => "WIR Euro",
                "country" => "SWITZERLAND"
            ],
            [
                "code" => "CHF",
                "name" => "Swiss Franc",
                "country" => "SWITZERLAND"
            ],
            [
                "code" => "CHW",
                "name" => "WIR Franc",
                "country" => "SWITZERLAND"
            ],
            [
                "code" => "SYP",
                "name" => "Syrian Pound",
                "country" => "SYRIAN ARAB REPUBLIC"
            ],
            [
                "code" => "TWD",
                "name" => "New Taiwan Dollar",
                "country" => "TAIWAN (PROVINCE OF CHINA)"
            ],
            [
                "code" => "TJS",
                "name" => "Somoni",
                "country" => "TAJIKISTAN"
            ],
            [
                "code" => "TZS",
                "name" => "Tanzanian Shilling",
                "country" => "TANZANIA, UNITED REPUBLIC OF"
            ],
            [
                "code" => "THB",
                "name" => "Baht",
                "country" => "THAILAND"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "TIMOR-LESTE"
            ],
            [
                "code" => "XOF",
                "name" => "CFA Franc BCEAO",
                "country" => "TOGO"
            ],
            [
                "code" => "NZD",
                "name" => "New Zealand Dollar",
                "country" => "TOKELAU"
            ],
            [
                "code" => "TOP",
                "name" => "Pa’anga",
                "country" => "TONGA"
            ],
            [
                "code" => "TTD",
                "name" => "Trinidad and Tobago Dollar",
                "country" => "TRINIDAD AND TOBAGO"
            ],
            [
                "code" => "TND",
                "name" => "Tunisian Dinar",
                "country" => "TUNISIA"
            ],
            [
                "code" => "TRY",
                "name" => "Turkish Lira",
                "country" => "TURKEY"
            ],
            [
                "code" => "TMT",
                "name" => "Turkmenistan New Manat",
                "country" => "TURKMENISTAN"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "TURKS AND CAICOS ISLANDS (THE)"
            ],
            [
                "code" => "AUD",
                "name" => "Australian Dollar",
                "country" => "TUVALU"
            ],
            [
                "code" => "UGX",
                "name" => "Uganda Shilling",
                "country" => "UGANDA"
            ],
            [
                "code" => "UAH",
                "name" => "Hryvnia",
                "country" => "UKRAINE"
            ],
            [
                "code" => "AED",
                "name" => "UAE Dirham",
                "country" => "UNITED ARAB EMIRATES (THE)"
            ],
            [
                "code" => "GBP",
                "name" => "Pound Sterling",
                "country" => "UNITED KINGDOM OF GREAT BRITAIN AND NORTHERN IRELAND (THE)"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "UNITED STATES MINOR OUTLYING ISLANDS (THE)"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "UNITED STATES OF AMERICA (THE)"
            ],
            [
                "code" => "USN",
                "name" => "US Dollar (Next day)",
                "country" => "UNITED STATES OF AMERICA (THE)"
            ],
            [
                "code" => "UYI",
                "name" => "Uruguay Peso en Unidades Indexadas (UI)",
                "country" => "URUGUAY"
            ],
            [
                "code" => "UYU",
                "name" => "Peso Uruguayo",
                "country" => "URUGUAY"
            ],
            [
                "code" => "UYW",
                "name" => "Unidad Previsional",
                "country" => "URUGUAY"
            ],
            [
                "code" => "UZS",
                "name" => "Uzbekistan Sum",
                "country" => "UZBEKISTAN"
            ],
            [
                "code" => "VUV",
                "name" => "Vatu",
                "country" => "VANUATU"
            ],
            [
                "code" => "VES",
                "name" => "Bolívar Soberano",
                "country" => "VENEZUELA (BOLIVARIAN REPUBLIC OF)"
            ],
            [
                "code" => "VND",
                "name" => "Dong",
                "country" => "VIET NAM"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "VIRGIN ISLANDS (BRITISH)"
            ],
            [
                "code" => "USD",
                "name" => "US Dollar",
                "country" => "VIRGIN ISLANDS (U.S.)"
            ],
            [
                "code" => "XPF",
                "name" => "CFP Franc",
                "country" => "WALLIS AND FUTUNA"
            ],
            [
                "code" => "MAD",
                "name" => "Moroccan Dirham",
                "country" => "WESTERN SAHARA"
            ],
            [
                "code" => "YER",
                "name" => "Yemeni Rial",
                "country" => "YEMEN"
            ],
            [
                "code" => "ZMW",
                "name" => "Zambian Kwacha",
                "country" => "ZAMBIA"
            ],
            [
                "code" => "ZWL",
                "name" => "Zimbabwe Dollar",
                "country" => "ZIMBABWE"
            ]
        ];
    }

    

    public static function getTaxTypes()
    {
        $taxTypes = TaxCode::select('name','rate','code')->get();
        return count($taxTypes) ? $taxTypes :[
            ["code"=>"A", "name"=> "A-Exempt","rate"=>"0"],	
            ["code"=>"B", "name"=> "B-16.00%","rate"=>"16"],	
            ["code"=>"C", "name" => "C-0%","rate"=>"0"],	
            ["code"=>"D", "name" => "D- Non-VAT","rate"=>"0"],	
            ["code"=>"E", "name" => "E-8%","rate"=>"0"],	
        ];
    }
    public static function getTaxTypeRate($taxCode)
    {
        foreach(self::getTaxTypes() as $taxType):
            if( empty($taxType['code']) ) continue;
            if( $taxType['code'] == $taxCode ) return $taxType['rate'] ? $taxType['rate'] : 0;
        endforeach;
        return 0;
    }
    public static function getTaxPayerStatuses()
    {
        return [
            ["code"=>"A", "name"=> "Active"],	
            ["code"=>"D", "name" => "Inactive"],	
        ];
    }
    public static function getProductTypes()
    {
        return [
            ["code"=>"1", "name"=> "Raw Material"],	
            ["code"=>"2", "name" => "Finished Product"],	
            ["code"=>"3", "name" => "Service"],	
        ];
    }
    public static function getCountries()
    {
        return [
            ['code'=>'AC', 'image'=>url('images/flags/png100px/ac.png'),'icon'=>'','calling_code'=>'247','name' => 'ASCENSION ISLAND'],
            ['code'=>'AF', 'image'=>url('images/flags/png100px/af.png'),'icon'=>'','calling_code'=>'93', 'name' => 'Afghanistan'],
            ['code'=>'AX', 'image'=>url('images/flags/png100px/ax.png'),'icon'=>'','calling_code'=>'358', 'name' => 'Aland Islands'],
            ['code'=>'AL', 'image'=>url('images/flags/png100px/al.png'),'icon'=>'','calling_code'=>'355', 'name' => 'Albania'],
            ['code'=>'DZ', 'image'=>url('images/flags/png100px/dz.png'),'icon'=>'','calling_code'=>'213', 'name' => 'Algeria'],
            ['code'=>'AS', 'image'=>url('images/flags/png100px/as.png'),'icon'=>'','calling_code'=>'1-684', 'name' => 'American Samoa'],
            ['code'=>'AD', 'image'=>url('images/flags/png100px/ad.png'),'icon'=>'','calling_code'=>'376', 'name' => 'Andorra'],
            ['code'=>'AO', 'image'=>url('images/flags/png100px/ao.png'),'icon'=>'','calling_code'=>'244', 'name' => 'Angola'],
            ['code'=>'AI', 'image'=>url('images/flags/png100px/ai.png'),'icon'=>'','calling_code'=>'1-264', 'name' => 'Anguilla'],
            ['code'=>'AQ', 'image'=>url('images/flags/png100px/aq.png'),'icon'=>'','calling_code'=>'672', 'name' => 'Antarctica'],
            ['code'=>'AG', 'image'=>url('images/flags/png100px/ag.png'),'icon'=>'','calling_code'=>'1-268', 'name' => 'Antigua And Barbuda'],
            ['code'=>'AR', 'image'=>url('images/flags/png100px/ar.png'),'icon'=>'','calling_code'=>'54', 'name' => 'Argentina'],
            ['code'=>'AM', 'image'=>url('images/flags/png100px/am.png'),'icon'=>'','calling_code'=>'374', 'name' => 'Armenia'],
            ['code'=>'AW', 'image'=>url('images/flags/png100px/aw.png'),'icon'=>'','calling_code'=>'297', 'name' => 'Aruba'],
            ['code'=>'AU', 'image'=>url('images/flags/png100px/au.png'),'icon'=>'','calling_code'=>'61', 'name' => 'Australia'],
            ['code'=>'AT', 'image'=>url('images/flags/png100px/at.png'),'icon'=>'','calling_code'=>'43', 'name' => 'Austria'],
            ['code'=>'AZ', 'image'=>url('images/flags/png100px/az.png'),'icon'=>'','calling_code'=>'994', 'name' => 'Azerbaijan'],
            ['code'=>'BS', 'image'=>url('images/flags/png100px/bs.png'),'icon'=>'','calling_code'=>'1-242', 'name' => 'Bahamas'],
            ['code'=>'BH', 'image'=>url('images/flags/png100px/bh.png'),'icon'=>'','calling_code'=>'973', 'name' => 'Bahrain'],
            ['code'=>'BD', 'image'=>url('images/flags/png100px/bd.png'),'icon'=>'','calling_code'=>'880', 'name' => 'Bangladesh'],
            ['code'=>'BB', 'image'=>url('images/flags/png100px/bb.png'),'icon'=>'','calling_code'=>'1-246', 'name' => 'Barbados'],
            ['code'=>'BY', 'image'=>url('images/flags/png100px/by.png'),'icon'=>'','calling_code'=>'375', 'name' => 'Belarus'],
            ['code'=>'BE', 'image'=>url('images/flags/png100px/be.png'),'icon'=>'','calling_code'=>'32', 'name' => 'Belgium'],
            ['code'=>'BZ', 'image'=>url('images/flags/png100px/bx.png'),'icon'=>'','calling_code'=>'501', 'name' => 'Belize'],
            ['code'=>'BJ', 'image'=>url('images/flags/png100px/bj.png'),'icon'=>'','calling_code'=>'229', 'name' => 'Benin'],
            ['code'=>'BM', 'image'=>url('images/flags/png100px/bm.png'),'icon'=>'','calling_code'=>'1-441', 'name' => 'Bermuda'],
            ['code'=>'BT', 'image'=>url('images/flags/png100px/bt.png'),'icon'=>'','calling_code'=>'975', 'name' => 'Bhutan'],
            ['code'=>'BO', 'image'=>url('images/flags/png100px/bo.png'),'icon'=>'','calling_code'=>'591', 'name' => 'Bolivia'],
            ['code'=>'BA', 'image'=>url('images/flags/png100px/ba.png'),'icon'=>'','calling_code'=>'378', 'name' => 'Bosnia And Herzegovina'],
            ['code'=>'BW', 'image'=>url('images/flags/png100px/bw.png'),'icon'=>'','calling_code'=>'267', 'name' => 'Botswana'],
            ['code'=>'BV', 'image'=>url('images/flags/png100px/bv.png'),'icon'=>'','calling_code'=>'47', 'name' => 'Bouvet Island'],
            ['code'=>'BR', 'image'=>url('images/flags/png100px/br.png'),'icon'=>'','calling_code'=>'55', 'name' => 'Brazil'],
            ['code'=>'IO', 'image'=>url('images/flags/png100px/io.png'),'icon'=>'','calling_code'=>'246', 'name' => 'British Indian Ocean Territory'],
            ['code'=>'BN', 'image'=>url('images/flags/png100px/bn.png'),'icon'=>'','calling_code'=>'673', 'name' => 'Brunei Darussalam'],
            ['code'=>'BG', 'image'=>url('images/flags/png100px/bg.png'),'icon'=>'','calling_code'=>'359', 'name' => 'Bulgaria'],
            ['code'=>'BF', 'image'=>url('images/flags/png100px/bf.png'),'icon'=>'','calling_code'=>'226', 'name' => 'Burkina Faso'],
            ['code'=>'BI', 'image'=>url('images/flags/png100px/bi.png'),'icon'=>'','calling_code'=>'257', 'name' => 'Burundi'],
            ['code'=>'KH', 'image'=>url('images/flags/png100px/kh.png'),'icon'=>'','calling_code'=>'855', 'name' => 'Cambodia'],
            ['code'=>'CM', 'image'=>url('images/flags/png100px/cm.png'),'icon'=>'','calling_code'=>'237', 'name' => 'Cameroon'],
            ['code'=>'CA', 'image'=>url('images/flags/png100px/ca.png'),'icon'=>'','calling_code'=>'1', 'name' => 'Canada'],
            ['code'=>'CV', 'image'=>url('images/flags/png100px/cv.png'),'icon'=>'','calling_code'=>'238', 'name' => 'Cape Verde'],
            ['code'=>'KY', 'image'=>url('images/flags/png100px/ky.png'),'icon'=>'','calling_code'=>'1-345', 'name' => 'Cayman Islands'],
            ['code'=>'CF', 'image'=>url('images/flags/png100px/cf.png'),'icon'=>'','calling_code'=>'236', 'name' => 'Central African Republic'],
            ['code'=>'TD', 'image'=>url('images/flags/png100px/td.png'),'icon'=>'','calling_code'=>'235', 'name' => 'Chad'],
            ['code'=>'CL', 'image'=>url('images/flags/png100px/cl.png'),'icon'=>'','calling_code'=>'56', 'name' => 'Chile'],
            ['code'=>'CN', 'image'=>url('images/flags/png100px/cn.png'),'icon'=>'','calling_code'=>'86', 'name' => 'China'],
            ['code'=>'CX', 'image'=>url('images/flags/png100px/cx.png'),'icon'=>'','calling_code'=>'61', 'name' => 'Christmas Island'],
            ['code'=>'CC', 'image'=>url('images/flags/png100px/cc.png'),'icon'=>'','calling_code'=>'61', 'name' => 'Cocos (Keeling) Islands'],
            ['code'=>'CO', 'image'=>url('images/flags/png100px/co.png'),'icon'=>'','calling_code'=>'57', 'name' => 'Colombia'],
            ['code'=>'KM', 'image'=>url('images/flags/png100px/km.png'),'icon'=>'','calling_code'=>'269', 'name' => 'Comoros'],
            ['code'=>'CG', 'image'=>url('images/flags/png100px/cg.png'),'icon'=>'','calling_code'=>'242', 'name' => 'Congo'],
            ['code'=>'CD', 'image'=>url('images/flags/png100px/cd.png'),'icon'=>'','calling_code'=>'243', 'name' => 'Congo, Democratic Republic'],
            ['code'=>'CK', 'image'=>url('images/flags/png100px/ck.png'),'icon'=>'','calling_code'=>'682', 'name' => 'Cook Islands'],
            ['code'=>'CR', 'image'=>url('images/flags/png100px/cr.png'),'icon'=>'','calling_code'=>'506', 'name' => 'Costa Rica'],
            ['code'=>'CI', 'image'=>url('images/flags/png100px/ci.png'),'icon'=>'','calling_code'=>'255', 'name' => 'Cote D\'Ivoire'],
            ['code'=>'HR', 'image'=>url('images/flags/png100px/hr.png'),'icon'=>'','calling_code'=>'385', 'name' => 'Croatia'],
            ['code'=>'CU', 'image'=>url('images/flags/png100px/cu.png'),'icon'=>'','calling_code'=>'53', 'name' => 'Cuba'],
            ['code'=>'CY', 'image'=>url('images/flags/png100px/cy.png'),'icon'=>'','calling_code'=>'357', 'name' => 'Cyprus'],
            ['code'=>'CZ', 'image'=>url('images/flags/png100px/cz.png'),'icon'=>'','calling_code'=>'420', 'name' => 'Czech Republic'],
            ['code'=>'DK', 'image'=>url('images/flags/png100px/dk.png'),'icon'=>'','calling_code'=>'45', 'name' => 'Denmark'],
            ['code'=>'DJ', 'image'=>url('images/flags/png100px/dj.png'),'icon'=>'','calling_code'=>'253', 'name' => 'Djibouti'],
            ['code'=>'DM', 'image'=>url('images/flags/png100px/dm.png'),'icon'=>'','calling_code'=>'1-767', 'name' => 'Dominica'],
            ['code'=>'DO', 'image'=>url('images/flags/png100px/do.png'),'icon'=>'','calling_code'=>'1-809', 'name' => 'Dominican Republic'],
            ['code'=>'EC', 'image'=>url('images/flags/png100px/ec.png'),'icon'=>'','calling_code'=>'593', 'name' => 'Ecuador'],
            ['code'=>'EG', 'image'=>url('images/flags/png100px/eg.png'),'icon'=>'','calling_code'=>'20', 'name' => 'Egypt'],
            ['code'=>'SV', 'image'=>url('images/flags/png100px/sv.png'),'icon'=>'','calling_code'=>'503', 'name' => 'El Salvador'],
            ['code'=>'GQ', 'image'=>url('images/flags/png100px/gq.png'),'icon'=>'','calling_code'=>'240', 'name' => 'Equatorial Guinea'],
            ['code'=>'ER', 'image'=>url('images/flags/png100px/er.png'),'icon'=>'','calling_code'=>'291', 'name' => 'Eritrea'],
            ['code'=>'EE', 'image'=>url('images/flags/png100px/ee.png'),'icon'=>'','calling_code'=>'372', 'name' => 'Estonia'],
            ['code'=>'ET', 'image'=>url('images/flags/png100px/et.png'),'icon'=>'','calling_code'=>'251', 'name' => 'Ethiopia'],
            ['code'=>'FK', 'image'=>url('images/flags/png100px/fk.png'),'icon'=>'','calling_code'=>'500', 'name' => 'Falkland Islands (Malvinas)'],
            ['code'=>'FO', 'image'=>url('images/flags/png100px/fo.png'),'icon'=>'','calling_code'=>'298', 'name' => 'Faroe Islands'],
            ['code'=>'FJ', 'image'=>url('images/flags/png100px/fj.png'),'icon'=>'','calling_code'=>'679', 'name' => 'Fiji'],
            ['code'=>'FI', 'image'=>url('images/flags/png100px/fi.png'),'icon'=>'','calling_code'=>'358', 'name' => 'Finland'],
            ['code'=>'FR', 'image'=>url('images/flags/png100px/fr.png'),'icon'=>'','calling_code'=>'33', 'name' => 'France'],
            ['code'=>'GF', 'image'=>url('images/flags/png100px/gf.png'),'icon'=>'','calling_code'=>'594', 'name' => 'French Guiana'],
            ['code'=>'PF', 'image'=>url('images/flags/png100px/pf.png'),'icon'=>'','calling_code'=>'689', 'name' => 'French Polynesia'],
            ['code'=>'TF', 'image'=>url('images/flags/png100px/tf.png'),'icon'=>'','calling_code'=>'262', 'name' => 'French Southern Territories'],
            ['code'=>'GA', 'image'=>url('images/flags/png100px/ga.png'),'icon'=>'','calling_code'=>'241', 'name' => 'Gabon'],
            ['code'=>'GM', 'image'=>url('images/flags/png100px/gm.png'),'icon'=>'','calling_code'=>'220', 'name' => 'Gambia'],
            ['code'=>'GE', 'image'=>url('images/flags/png100px/ge.png'),'icon'=>'','calling_code'=>'995', 'name' => 'Georgia'],
            ['code'=>'DE', 'image'=>url('images/flags/png100px/de.png'),'icon'=>'','calling_code'=>'49', 'name' => 'Germany'],
            ['code'=>'GH', 'image'=>url('images/flags/png100px/gh.png'),'icon'=>'','calling_code'=>'233', 'name' => 'Ghana'],
            ['code'=>'GI', 'image'=>url('images/flags/png100px/gi.png'),'icon'=>'','calling_code'=>'350', 'name' => 'Gibraltar'],
            ['code'=>'GR', 'image'=>url('images/flags/png100px/gr.png'),'icon'=>'','calling_code'=>'30', 'name' => 'Greece'],
            ['code'=>'GL', 'image'=>url('images/flags/png100px/gl.png'),'icon'=>'','calling_code'=>'299', 'name' => 'Greenland'],
            ['code'=>'GD', 'image'=>url('images/flags/png100px/gd.png'),'icon'=>'','calling_code'=>'1-473', 'name' => 'Grenada'],
            ['code'=>'GP', 'image'=>url('images/flags/png100px/gp.png'),'icon'=>'','calling_code'=>'590', 'name' => 'Guadeloupe'],
            ['code'=>'GU', 'image'=>url('images/flags/png100px/gu.png'),'icon'=>'','calling_code'=>'1-671', 'name' => 'Guam'],
            ['code'=>'GT', 'image'=>url('images/flags/png100px/gt.png'),'icon'=>'','calling_code'=>'502', 'name' => 'Guatemala'],
            ['code'=>'GG', 'image'=>url('images/flags/png100px/gg.png'),'icon'=>'','calling_code'=>'44', 'name' => 'Guernsey'],
            ['code'=>'GN', 'image'=>url('images/flags/png100px/gn.png'),'icon'=>'','calling_code'=>'224', 'name' => 'Guinea'],
            ['code'=>'GW', 'image'=>url('images/flags/png100px/gw.png'),'icon'=>'','calling_code'=>'245', 'name' => 'Guinea-Bissau'],
            ['code'=>'GY', 'image'=>url('images/flags/png100px/gy.png'),'icon'=>'','calling_code'=>'592', 'name' => 'Guyana'],
            ['code'=>'HT', 'image'=>url('images/flags/png100px/ht.png'),'icon'=>'','calling_code'=>'509', 'name' => 'Haiti'],
            ['code'=>'HM', 'image'=>url('images/flags/png100px/hm.png'),'icon'=>'','calling_code'=>'672', 'name' => 'Heard Island & Mcdonald Islands'],
            ['code'=>'VA', 'image'=>url('images/flags/png100px/va.png'),'icon'=>'','calling_code'=>'379', 'name' => 'Holy See (Vatican City State)'],
            ['code'=>'HN', 'image'=>url('images/flags/png100px/hn.png'),'icon'=>'','calling_code'=>'504', 'name' => 'Honduras'],
            ['code'=>'HK', 'image'=>url('images/flags/png100px/hk.png'),'icon'=>'','calling_code'=>'852', 'name' => 'Hong Kong'],
            ['code'=>'HU', 'image'=>url('images/flags/png100px/hu.png'),'icon'=>'','calling_code'=>'36', 'name' => 'Hungary'],
            ['code'=>'IS', 'image'=>url('images/flags/png100px/is.png'),'icon'=>'','calling_code'=>'354', 'name' => 'Iceland'],
            ['code'=>'IN', 'image'=>url('images/flags/png100px/in.png'),'icon'=>'','calling_code'=>'91', 'name' => 'India'],
            ['code'=>'ID', 'image'=>url('images/flags/png100px/id.png'),'icon'=>'','calling_code'=>'62', 'name' => 'Indonesia'],
            ['code'=>'IR', 'image'=>url('images/flags/png100px/ir.png'),'icon'=>'','calling_code'=>'98', 'name' => 'Iran, Islamic Republic Of'],
            ['code'=>'IQ', 'image'=>url('images/flags/png100px/iq.png'),'icon'=>'','calling_code'=>'964', 'name' => 'Iraq'],
            ['code'=>'IE', 'image'=>url('images/flags/png100px/ie.png'),'icon'=>'','calling_code'=>'353', 'name' => 'Ireland'],
            ['code'=>'IM', 'image'=>url('images/flags/png100px/im.png'),'icon'=>'','calling_code'=>'44-1624', 'name' => 'Isle Of Man'],
            ['code'=>'IL', 'image'=>url('images/flags/png100px/il.png'),'icon'=>'','calling_code'=>'972', 'name' => 'Israel'],
            ['code'=>'IT', 'image'=>url('images/flags/png100px/it.png'),'icon'=>'','calling_code'=>'39', 'name' => 'Italy'],
            ['code'=>'JM', 'image'=>url('images/flags/png100px/jm.png'),'icon'=>'','calling_code'=>'1-876', 'name' => 'Jamaica'],
            ['code'=>'JP', 'image'=>url('images/flags/png100px/jp.png'),'icon'=>'','calling_code'=>'81', 'name' => 'Japan'],
            ['code'=>'JE', 'image'=>url('images/flags/png100px/je.png'),'icon'=>'','calling_code'=>'44-1534', 'name' => 'Jersey'],
            ['code'=>'JO', 'image'=>url('images/flags/png100px/jo.png'),'icon'=>'','calling_code'=>'962', 'name' => 'Jordan'],
            ['code'=>'KZ', 'image'=>url('images/flags/png100px/kz.png'),'icon'=>'','calling_code'=>'7', 'name' => 'Kazakhstan'],
            ['code'=>'KE', 'image'=>url('images/flags/png100px/ke.png'),'icon'=>'','calling_code'=>'254', 'name' => 'Kenya'],
            ['code'=>'KI', 'image'=>url('images/flags/png100px/ki.png'),'icon'=>'','calling_code'=>'686', 'name' => 'Kiribati'],
            ['code'=>'KP', 'image'=>url('images/flags/png100px/kp.png'),'icon'=>'','calling_code'=>'850', 'name' => 'North Korea'],
            ['code'=>'KR', 'image'=>url('images/flags/png100px/kr.png'),'icon'=>'','calling_code'=>'82', 'name' => 'South Korea'],
            ['code'=>'KW', 'image'=>url('images/flags/png100px/kw.png'),'icon'=>'','calling_code'=>'965', 'name' => 'Kuwait'],
            ['code'=>'KG', 'image'=>url('images/flags/png100px/kg.png'),'icon'=>'','calling_code'=>'996', 'name' => 'Kyrgyzstan'],
            ['code'=>'XK', 'image'=>url('images/flags/png100px/xk.png'),'icon'=>'','calling_code'=>'383', 'name' => 'Kosovo'],
            ['code'=>'LA', 'image'=>url('images/flags/png100px/la.png'),'icon'=>'','calling_code'=>'856', 'name' => 'Lao People\'s Democratic Republic'],
            ['code'=>'LV', 'image'=>url('images/flags/png100px/lv.png'),'icon'=>'','calling_code'=>'371', 'name' => 'Latvia'],
            ['code'=>'LB', 'image'=>url('images/flags/png100px/lb.png'),'icon'=>'','calling_code'=>'961', 'name' => 'Lebanon'],
            ['code'=>'LS', 'image'=>url('images/flags/png100px/ls.png'),'icon'=>'','calling_code'=>'266', 'name' => 'Lesotho'],
            ['code'=>'LR', 'image'=>url('images/flags/png100px/lr.png'),'icon'=>'','calling_code'=>'231', 'name' => 'Liberia'],
            ['code'=>'LY', 'image'=>url('images/flags/png100px/ly.png'),'icon'=>'','calling_code'=>'218', 'name' => 'Libyan Arab Jamahiriya'],
            ['code'=>'LI', 'image'=>url('images/flags/png100px/li.png'),'icon'=>'','calling_code'=>'423', 'name' => 'Liechtenstein'],
            ['code'=>'LT', 'image'=>url('images/flags/png100px/lt.png'),'icon'=>'','calling_code'=>'370', 'name' => 'Lithuania'],
            ['code'=>'LU', 'image'=>url('images/flags/png100px/lu.png'),'icon'=>'','calling_code'=>'352', 'name' => 'Luxembourg'],
            ['code'=>'MO', 'image'=>url('images/flags/png100px/mo.png'),'icon'=>'','calling_code'=>'853', 'name' => 'Macau'],
            ['code'=>'MK', 'image'=>url('images/flags/png100px/mk.png'),'icon'=>'','calling_code'=>'389', 'name' => 'Macedonia'],
            ['code'=>'MG', 'image'=>url('images/flags/png100px/mg.png'),'icon'=>'','calling_code'=>'261', 'name' => 'Madagascar'],
            ['code'=>'MW', 'image'=>url('images/flags/png100px/mw.png'),'icon'=>'','calling_code'=>'265', 'name' => 'Malawi'],
            ['code'=>'MY', 'image'=>url('images/flags/png100px/my.png'),'icon'=>'','calling_code'=>'60', 'name' => 'Malaysia'],
            ['code'=>'MV', 'image'=>url('images/flags/png100px/mv.png'),'icon'=>'','calling_code'=>'960', 'name' => 'Maldives'],
            ['code'=>'ML', 'image'=>url('images/flags/png100px/ml.png'),'icon'=>'','calling_code'=>'223', 'name' => 'Mali'],
            ['code'=>'MT', 'image'=>url('images/flags/png100px/mt.png'),'icon'=>'','calling_code'=>'356', 'name' => 'Malta'],
            ['code'=>'MH', 'image'=>url('images/flags/png100px/mh.png'),'icon'=>'','calling_code'=>'692', 'name' => 'Marshall Islands'],
            ['code'=>'MQ', 'image'=>url('images/flags/png100px/mq.png'),'icon'=>'','calling_code'=>'596', 'name' => 'Martinique'],
            ['code'=>'MR', 'image'=>url('images/flags/png100px/mr.png'),'icon'=>'','calling_code'=>'222', 'name' => 'Mauritania'],
            ['code'=>'MU', 'image'=>url('images/flags/png100px/mu.png'),'icon'=>'','calling_code'=>'230', 'name' => 'Mauritius'],
            ['code'=>'YT', 'image'=>url('images/flags/png100px/yt.png'),'icon'=>'','calling_code'=>'262', 'name' => 'Mayotte'],
            ['code'=>'MX', 'image'=>url('images/flags/png100px/mx.png'),'icon'=>'','calling_code'=>'254', 'name' => 'Mexico'],
            ['code'=>'FM', 'image'=>url('images/flags/png100px/fm.png'),'icon'=>'','calling_code'=>'691', 'name' => 'Micronesia, Federated States Of'],
            ['code'=>'MD', 'image'=>url('images/flags/png100px/md.png'),'icon'=>'','calling_code'=>'373', 'name' => 'Moldova'],
            ['code'=>'MC', 'image'=>url('images/flags/png100px/mc.png'),'icon'=>'','calling_code'=>'377', 'name' => 'Monaco'],
            ['code'=>'MN', 'image'=>url('images/flags/png100px/mn.png'),'icon'=>'','calling_code'=>'976', 'name' => 'Mongolia'],
            ['code'=>'ME', 'image'=>url('images/flags/png100px/me.png'),'icon'=>'','calling_code'=>'382', 'name' => 'Montenegro'],
            ['code'=>'MS', 'image'=>url('images/flags/png100px/ms.png'),'icon'=>'','calling_code'=>'1-664', 'name' => 'Montserrat'],
            ['code'=>'MA', 'image'=>url('images/flags/png100px/ma.png'),'icon'=>'','calling_code'=>'212', 'name' => 'Morocco'],
            ['code'=>'MZ', 'image'=>url('images/flags/png100px/mz.png'),'icon'=>'','calling_code'=>'258', 'name' => 'Mozambique'],
            ['code'=>'MM', 'image'=>url('images/flags/png100px/mm.png'),'icon'=>'','calling_code'=>'95', 'name' => 'Myanmar'],
            ['code'=>'NA', 'image'=>url('images/flags/png100px/na.png'),'icon'=>'','calling_code'=>'264', 'name' => 'Namibia'],
            ['code'=>'NR', 'image'=>url('images/flags/png100px/nr.png'),'icon'=>'','calling_code'=>'674', 'name' => 'Nauru'],
            ['code'=>'NP', 'image'=>url('images/flags/png100px/np.png'),'icon'=>'','calling_code'=>'977', 'name' => 'Nepal'],
            ['code'=>'NL', 'image'=>url('images/flags/png100px/nl.png'),'icon'=>'','calling_code'=>'31', 'name' => 'Netherlands'],
            ['code'=>'AN', 'image'=>url('images/flags/png100px/an.png'),'icon'=>'','calling_code'=>'599', 'name' => 'Netherlands Antilles'],
            ['code'=>'NC', 'image'=>url('images/flags/png100px/nc.png'),'icon'=>'','calling_code'=>'687', 'name' => 'New Caledonia'],
            ['code'=>'NZ', 'image'=>url('images/flags/png100px/nz.png'),'icon'=>'','calling_code'=>'64', 'name' => 'New Zealand'],
            ['code'=>'NI', 'image'=>url('images/flags/png100px/ni.png'),'icon'=>'','calling_code'=>'505', 'name' => 'Nicaragua'],
            ['code'=>'NE', 'image'=>url('images/flags/png100px/ne.png'),'icon'=>'','calling_code'=>'227', 'name' => 'Niger'],
            ['code'=>'NG', 'image'=>url('images/flags/png100px/ng.png'),'icon'=>'','calling_code'=>'234', 'name' => 'Nigeria'],
            ['code'=>'NU', 'image'=>url('images/flags/png100px/nu.png'),'icon'=>'','calling_code'=>'683', 'name' => 'Niue'],
            ['code'=>'NF', 'image'=>url('images/flags/png100px/nf.png'),'icon'=>'','calling_code'=>'672', 'name' => 'Norfolk Island'],
            ['code'=>'MP', 'image'=>url('images/flags/png100px/mp.png'),'icon'=>'','calling_code'=>'1-670', 'name' => 'Northern Mariana Islands'],
            ['code'=>'NO', 'image'=>url('images/flags/png100px/no.png'),'icon'=>'','calling_code'=>'47', 'name' => 'Norway'],
            ['code'=>'OM', 'image'=>url('images/flags/png100px/om.png'),'icon'=>'','calling_code'=>'968', 'name' => 'Oman'],
            ['code'=>'PK', 'image'=>url('images/flags/png100px/pk.png'),'icon'=>'','calling_code'=>'92', 'name' => 'Pakistan'],
            ['code'=>'PW', 'image'=>url('images/flags/png100px/pw.png'),'icon'=>'','calling_code'=>'680', 'name' => 'Palau'],
            ['code'=>'PS', 'image'=>url('images/flags/png100px/ps.png'),'icon'=>'','calling_code'=>'970', 'name' => 'Palestinian Territory, Occupied'],
            ['code'=>'PA', 'image'=>url('images/flags/png100px/pa.png'),'icon'=>'','calling_code'=>'507', 'name' => 'Panama'],
            ['code'=>'PG', 'image'=>url('images/flags/png100px/pg.png'),'icon'=>'','calling_code'=>'675', 'name' => 'Papua New Guinea'],
            ['code'=>'PY', 'image'=>url('images/flags/png100px/py.png'),'icon'=>'','calling_code'=>'595', 'name' => 'Paraguay'],
            ['code'=>'PE', 'image'=>url('images/flags/png100px/pe.png'),'icon'=>'','calling_code'=>'51', 'name' => 'Peru'],
            ['code'=>'PH', 'image'=>url('images/flags/png100px/ph.png'),'icon'=>'','calling_code'=>'63', 'name' => 'Philippines'],
            ['code'=>'PN', 'image'=>url('images/flags/png100px/pn.png'),'icon'=>'','calling_code'=>'64', 'name' => 'Pitcairn'],
            ['code'=>'PL', 'image'=>url('images/flags/png100px/pl.png'),'icon'=>'','calling_code'=>'48', 'name' => 'Poland'],
            ['code'=>'PT', 'image'=>url('images/flags/png100px/pt.png'),'icon'=>'','calling_code'=>'351', 'name' => 'Portugal'],
            ['code'=>'PR', 'image'=>url('images/flags/png100px/pr.png'),'icon'=>'','calling_code'=>'1', 'name' => 'Puerto Rico'],
            ['code'=>'QA', 'image'=>url('images/flags/png100px/qa.png'),'icon'=>'','calling_code'=>'974', 'name' => 'Qatar'],
            ['code'=>'RE', 'image'=>url('images/flags/png100px/re.png'),'icon'=>'','calling_code'=>'262', 'name' => 'Reunion'],
            ['code'=>'RO', 'image'=>url('images/flags/png100px/ro.png'),'icon'=>'','calling_code'=>'40', 'name' => 'Romania'],
            ['code'=>'RU', 'image'=>url('images/flags/png100px/ru.png'),'icon'=>'','calling_code'=>'7', 'name' => 'Russian Federation'],
            ['code'=>'RW', 'image'=>url('images/flags/png100px/rw.png'),'icon'=>'','calling_code'=>'250', 'name' => 'Rwanda'],
            ['code'=>'BL', 'image'=>url('images/flags/png100px/bl.png'),'icon'=>'','calling_code'=>'590', 'name' => 'Saint Barthelemy'],
            ['code'=>'SH', 'image'=>url('images/flags/png100px/sh.png'),'icon'=>'','calling_code'=>'290', 'name' => 'Saint Helena'],
            ['code'=>'KN', 'image'=>url('images/flags/png100px/kn.png'),'icon'=>'','calling_code'=>'1-869', 'name' => 'Saint Kitts And Nevis'],
            ['code'=>'LC', 'image'=>url('images/flags/png100px/lc.png'),'icon'=>'','calling_code'=>'1-758', 'name' => 'Saint Lucia'],
            ['code'=>'MF', 'image'=>url('images/flags/png100px/mf.png'),'icon'=>'','calling_code'=>'590', 'name' => 'Saint Martin'],
            ['code'=>'PM', 'image'=>url('images/flags/png100px/pm.png'),'icon'=>'','calling_code'=>'508', 'name' => 'Saint Pierre And Miquelon'],
            ['code'=>'VC', 'image'=>url('images/flags/png100px/vc.png'),'icon'=>'','calling_code'=>'1-784', 'name' => 'Saint Vincent And Grenadines'],
            ['code'=>'WS', 'image'=>url('images/flags/png100px/ws.png'),'icon'=>'','calling_code'=>'685', 'name' => 'Samoa'],
            ['code'=>'SM', 'image'=>url('images/flags/png100px/sm.png'),'icon'=>'','calling_code'=>'378', 'name' => 'San Marino'],
            ['code'=>'ST', 'image'=>url('images/flags/png100px/st.png'),'icon'=>'','calling_code'=>'239', 'name' => 'Sao Tome And Principe'],
            ['code'=>'SA', 'image'=>url('images/flags/png100px/sa.png'),'icon'=>'','calling_code'=>'966', 'name' => 'Saudi Arabia'],
            ['code'=>'SN', 'image'=>url('images/flags/png100px/sn.png'),'icon'=>'','calling_code'=>'221', 'name' => 'Senegal'],
            ['code'=>'RS', 'image'=>url('images/flags/png100px/rs.png'),'icon'=>'','calling_code'=>'381', 'name' => 'Serbia'],
            ['code'=>'SC', 'image'=>url('images/flags/png100px/sc.png'),'icon'=>'','calling_code'=>'248', 'name' => 'Seychelles'],
            ['code'=>'SL', 'image'=>url('images/flags/png100px/sl.png'),'icon'=>'','calling_code'=>'232', 'name' => 'Sierra Leone'],
            ['code'=>'SG', 'image'=>url('images/flags/png100px/sg.png'),'icon'=>'','calling_code'=>'65', 'name' => 'Singapore'],
            ['code'=>'SK', 'image'=>url('images/flags/png100px/sk.png'),'icon'=>'','calling_code'=>'421', 'name' => 'Slovakia'],
            ['code'=>'SI', 'image'=>url('images/flags/png100px/si.png'),'icon'=>'','calling_code'=>'386', 'name' => 'Slovenia'],
            ['code'=>'SB', 'image'=>url('images/flags/png100px/sb.png'),'icon'=>'','calling_code'=>'677', 'name' => 'Solomon Islands'],
            ['code'=>'SO', 'image'=>url('images/flags/png100px/so.png'),'icon'=>'','calling_code'=>'252', 'name' => 'Somalia'],
            ['code'=>'ZA', 'image'=>url('images/flags/png100px/za.png'),'icon'=>'','calling_code'=>'27', 'name' => 'South Africa'],
            ['code'=>'SS', 'image'=>url('images/flags/png100px/ss.png'),'icon'=>'','calling_code'=>'211', 'name' => 'South Sudan'],
            ['code'=>'GS', 'image'=>url('images/flags/png100px/gs.png'),'icon'=>'','calling_code'=>'500', 'name' => 'South Georgia And Sandwich Isl.'],
            ['code'=>'ES', 'image'=>url('images/flags/png100px/es.png'),'icon'=>'','calling_code'=>'34', 'name' => 'Spain'],
            ['code'=>'LK', 'image'=>url('images/flags/png100px/lk.png'),'icon'=>'','calling_code'=>'94', 'name' => 'Sri Lanka'],
            ['code'=>'SD', 'image'=>url('images/flags/png100px/sd.png'),'icon'=>'','calling_code'=>'249', 'name' => 'Sudan'],
            ['code'=>'SR', 'image'=>url('images/flags/png100px/sr.png'),'icon'=>'','calling_code'=>'597', 'name' => 'Suriname'],
            ['code'=>'SJ', 'image'=>url('images/flags/png100px/sj.png'),'icon'=>'','calling_code'=>'47', 'name' => 'Svalbard And Jan Mayen'],
            ['code'=>'SZ', 'image'=>url('images/flags/png100px/sz.png'),'icon'=>'','calling_code'=>'268', 'name' => 'Swaziland'],
            ['code'=>'SE', 'image'=>url('images/flags/png100px/se.png'),'icon'=>'','calling_code'=>'46', 'name' => 'Sweden'],
            ['code'=>'CH', 'image'=>url('images/flags/png100px/ch.png'),'icon'=>'','calling_code'=>'41', 'name' => 'Switzerland'],
            ['code'=>'SY', 'image'=>url('images/flags/png100px/sy.png'),'icon'=>'','calling_code'=>'963', 'name' => 'Syrian Arab Republic'],
            ['code'=>'TW', 'image'=>url('images/flags/png100px/tw.png'),'icon'=>'','calling_code'=>'886', 'name' => 'Taiwan'],
            ['code'=>'TJ', 'image'=>url('images/flags/png100px/tj.png'),'icon'=>'','calling_code'=>'992', 'name' => 'Tajikistan'],
            ['code'=>'TZ', 'image'=>url('images/flags/png100px/tz.png'),'icon'=>'','calling_code'=>'255', 'name' => 'Tanzania'],
            ['code'=>'TH', 'image'=>url('images/flags/png100px/th.png'),'icon'=>'','calling_code'=>'66', 'name' => 'Thailand'],
            ['code'=>'TL', 'image'=>url('images/flags/png100px/tl.png'),'icon'=>'','calling_code'=>'670', 'name' => 'Timor-Leste'],
            ['code'=>'TG', 'image'=>url('images/flags/png100px/tg.png'),'icon'=>'','calling_code'=>'228', 'name' => 'Togo'],
            ['code'=>'TK', 'image'=>url('images/flags/png100px/tk.png'),'icon'=>'','calling_code'=>'690', 'name' => 'Tokelau'],
            ['code'=>'TO', 'image'=>url('images/flags/png100px/to.png'),'icon'=>'','calling_code'=>'676', 'name' => 'Tonga'],
            ['code'=>'TT', 'image'=>url('images/flags/png100px/tt.png'),'icon'=>'','calling_code'=>'1-868', 'name' => 'Trinidad And Tobago'],
            ['code'=>'TN', 'image'=>url('images/flags/png100px/tn.png'),'icon'=>'','calling_code'=>'216', 'name' => 'Tunisia'],
            ['code'=>'TR', 'image'=>url('images/flags/png100px/tr.png'),'icon'=>'','calling_code'=>'90', 'name' => 'Turkey'],
            ['code'=>'TM', 'image'=>url('images/flags/png100px/tm.png'),'icon'=>'','calling_code'=>'993', 'name' => 'Turkmenistan'],
            ['code'=>'TC', 'image'=>url('images/flags/png100px/tc.png'),'icon'=>'','calling_code'=>'1-649', 'name' => 'Turks And Caicos Islands'],
            ['code'=>'TV', 'image'=>url('images/flags/png100px/tv.png'),'icon'=>'','calling_code'=>'688', 'name' => 'Tuvalu'],
            ['code'=>'UG', 'image'=>url('images/flags/png100px/ug.png'),'icon'=>'','calling_code'=>'256', 'name' => 'Uganda'],
            ['code'=>'UA', 'image'=>url('images/flags/png100px/ua.png'),'icon'=>'','calling_code'=>'380', 'name' => 'Ukraine'],
            ['code'=>'AE', 'image'=>url('images/flags/png100px/ae.png'),'icon'=>'','calling_code'=>'971', 'name' => 'United Arab Emirates'],
            ['code'=>'GB', 'image'=>url('images/flags/png100px/gb.png'),'icon'=>'','calling_code'=>'44', 'name' => 'United Kingdom'],
            ['code'=>'US', 'image'=>url('images/flags/png100px/us.png'),'icon'=>'','calling_code'=>'1', 'name' => 'United States'],
            ['code'=>'UM', 'image'=>url('images/flags/png100px/um.png'),'icon'=>'','calling_code'=>'246', 'name' => 'United States Outlying Islands'],
            ['code'=>'UY', 'image'=>url('images/flags/png100px/uy.png'),'icon'=>'','calling_code'=>'598', 'name' => 'Uruguay'],
            ['code'=>'UZ', 'image'=>url('images/flags/png100px/uz.png'),'icon'=>'','calling_code'=>'998', 'name' => 'Uzbekistan'],
            ['code'=>'VU', 'image'=>url('images/flags/png100px/vu.png'),'icon'=>'','calling_code'=>'678', 'name' => 'Vanuatu'],
            ['code'=>'VE', 'image'=>url('images/flags/png100px/ve.png'),'icon'=>'','calling_code'=>'58', 'name' => 'Venezuela'],
            ['code'=>'VN', 'image'=>url('images/flags/png100px/vn.png'),'icon'=>'','calling_code'=>'84', 'name' => 'Viet Nam'],
            ['code'=>'VG', 'image'=>url('images/flags/png100px/vg.png'),'icon'=>'','calling_code'=>'1-284', 'name' => 'Virgin Islands, British'],
            ['code'=>'VI', 'image'=>url('images/flags/png100px/vi.png'),'icon'=>'','calling_code'=>'1-340', 'name' => 'Virgin Islands, U.S.'],
            ['code'=>'WF', 'image'=>url('images/flags/png100px/wf.png'),'icon'=>'','calling_code'=>'681', 'name' => 'Wallis And Futuna'],
            ['code'=>'EH', 'image'=>url('images/flags/png100px/eh.png'),'icon'=>'','calling_code'=>'212', 'name' => 'Western Sahara'],
            ['code'=>'YE', 'image'=>url('images/flags/png100px/ye.png'),'icon'=>'','calling_code'=>'967', 'name' => 'Yemen'],
            ['code'=>'ZM', 'image'=>url('images/flags/png100px/zm.png'),'icon'=>'','calling_code'=>'260', 'name' => 'Zambia'],
            ['code'=>'ZW', 'image'=>url('images/flags/png100px/zw.png'),'icon'=>'','calling_code'=>'263', 'name' => 'Zimbabwe'],
        ];
    }

    public static function getPackagingUnit()
    {
        $data = [
            ['code'=>'BE','name'=>'Bundle'],
            ['code'=>'DR','name'=>'Drum'],
            ['code'=>'RL','name'=>'Reel'],
            ['code'=>'RO','name'=>'Roll'],
            ['code'=>'BA','name'=>'Barrel'],
            ['code'=>'AM','name'=>'Ampoule'],
            ['code'=>'BC','name'=>'Bottlecrate'],
            ['code'=>'BF','name'=>'Balloon, non-protected'],
            ['code'=>'BG','name'=>'Bag'],
            ['code'=>'BJ','name'=>'Bucket'],
            ['code'=>'BL','name'=>'Bale'],
            ['code'=>'BQ','name'=>'Bottle, protected cylindrical'],
            ['code'=>'BR','name'=>'Bar'],
            ['code'=>'BV','name'=>'Bottle, bulbous'],

            ['code'=>'CA','name'=>'Can'],
            ['code'=>'CH','name'=>'Chest'],
            ['code'=>'CJ','name'=>'Coffin'],
            ['code'=>'CL','name'=>'Coil'],
            ['code'=>'CR','name'=>'Wooden Box, Wooden Case'],
            ['code'=>'CS','name'=>'Cassette'],
            ['code'=>'CT','name'=>'Carton'],
            ['code'=>'CTN','name'=>'Container'],
            ['code'=>'CY','name'=>'Cylinder'],
            ['code'=>'GT','name'=>'Extra Countable Item'],
            ['code'=>'HH','name'=>'Hand Baggage'],
            ['code'=>'IZ','name'=>'Ingots'],
            ['code'=>'JR','name'=>'Jar'],
            ['code'=>'JU','name'=>'Jug'],
            ['code'=>'JY','name'=>'Jerry CAN Cylindrical'],
            ['code'=>'KZ','name'=>'Canester'],
            ['code'=>'LZ','name'=>'Logs, in bundle/bunch/truss'],
            ['code'=>'NT','name'=>'Net'],
            ['code'=>'OU','name'=>'Non-Exterior Packaging Unit'],
            ['code'=>'PD','name'=>'Poddon'],
            ['code'=>'PG','name'=>'Plate'],
            ['code'=>'PI','name'=>'Pipe'],
            ['code'=>'PO','name'=>'Pilote'],
            ['code'=>'PU','name'=>'Traypack'],
            
            ['code'=>'RZ','name'=>'Rods, in bundle/bunch/truss'],
            ['code'=>'SK','name'=>'Skeletoncase'],
            ['code'=>'TY','name'=>'Tank, cylindrical'],
            ['code'=>'VG','name'=>'Bulk,gas(at 1031 mbar 15 oC)'],
            ['code'=>'VL','name'=>'Bulk,liquid(at normal temperature/pressure)'],
            ['code'=>'VO','name'=>'Bulk, solid, large particles("nodules")'],
            ['code'=>'VQ','name'=>'Bulk, gas(liquefied at abnormal temperature/pressure)'],
            ['code'=>'VR','name'=>'Bulk, solid, granular particles("grains")'],
            ['code'=>'VT','name'=>'Extra Bulk Item'],
            ['code'=>'VY','name'=>'Bulk, fine particles("powder")'],
            ['code'=>'ML','name'=>'cigarette Mills'],
            ['code'=>'TN','name'=>'1TAN REFER TO 20BAGS'],
            // [
            //     'name' => "session",
            //     'code' => 'sessions'
            // ],
            
            // [
            //     'name' => "hours",
            //     'code' => "hrs",
            // ],
            // [
            //     'name' => "minutes",
            //     'code' => "mins",
            // ],
            // [
            //     'name' => "days",
            //     'code' => "days",
            // ],
            // [
            //     'name' => "trips",
            //     'code' => "trips",
            // ],
           
            
            [
                'name' => "other",
                'code' => "other",
            ],
        ];
        $newData = [];
        foreach ($data as $item) {
            $newData[]= (object) $item;
        }

        return $newData;
    }

    public static function getUnitOfQuantity()
    {
        
       $data=    [
        ['code' => 'KG', 'name'=>  'Kilo-Gramme', 'description'=> 'Kilo-Gramme'],
        ['code' => 'LTR', 'name'=> 'Litre', 'description'=> 'Litre'],
           ['code' => '4B', 'name'=> 'Pair', 'description' => 'Pair'],
           ['code' => 'AV', 'name'=>  'Cap', 'description' => 'Cap'],
           ['code' => 'BA', 'name'=>  'Barrel', 'description' => 'Barrel'],
           ['code' => 'BE', 'name'=>  'bundle', 'description' => 'bundle'],
           ['code' => 'BG', 'name'=>  'bag', 'description' => 'bag'],
           ['code' => 'BL', 'name'=>  'block', 'description' => 'block'],
           ['code' => 'BLL', 'name'=> 'Barrel BLL', 'description' => 'Barrel (petroleum) (158,987 dm3)'],
           ['code' => 'BX', 'name'=>  'box', 'description' => 'box'],
           ['code' => 'CA', 'name'=>  'Can', 'description' => 'Can'],
           ['code' => 'CEL', 'name'=> 'Cell', 'description' => 'Cell'],
           ['code' => 'CMT', 'name'=> 'centimetre', 'description' => 'centimetre'],
           ['code' => 'CR', 'name'=>  'CARAT', 'description' => 'CARAT'],
           ['code' => 'DR', 'name'=>  'Drum', 'description' => 'Drum'],
           ['code' => 'DZ', 'name'=>  'Dozen', 'description' => 'Dozen'],
           ['code' => 'GLL', 'name'=> 'Gallon', 'description' => 'Gallon'],
           ['code' => 'GRM', 'name'=> 'Gram', 'description' => 'Gram'],
           ['code' => 'GRO', 'name'=> ' Gross', 'description' => 'Gross'],
           ['code' => 'KTM', 'name'=> 'kilometre', 'description'=> 'kilometre'],
           ['code' => 'KWT', 'name'=>' kilowatt', 'description'=> 'kilowatt'],
           ['code' => 'ML' , 'name'=> 'Millilitre', 'description'=> 'Millilitre'],
           ['code' => 'L' , 'name'=> 'Litre', 'description'=> 'Litre'],
           ['code' => 'LBR', 'name'=> 'pound', 'description'=> 'pound'],
           ['code' => 'LK', 'name'=>  'link', 'description'=> 'link'],
           ['code' => 'LTR', 'name'=> 'Litre', 'description'=> 'Litre'],
           ['code' => 'M' , 'name'=> 'Metre', 'description'=> 'Metre'],
           ['code' => 'M2', 'name'=>  'Square Metre', 'description'=> 'Square Metre'],
           ['code' => 'M3', 'name'=>  'Cubic Metre ', 'description'=>'Cubic Metre'],
           ['code' => 'MGM', 'name'=> 'milligram milligram'],
           ['code' => 'MTR', 'name'=>'metre', 'description'=> 'metre'],
           ['code' => 'MWT', 'name'=>' megawatt hour', 'description'=> '(1000 kW.h) megawatt hour (1000 kW.h)'],
           ['code' => 'NO', 'name'=>  'Number', 'description'=> 'Number'],
           ['code' => 'NX', 'name'=>  'part per thousand', 'description'=> 'part per thousand'],
           ['code' => 'PA', 'name'=>  'packet', 'description'=> 'packet'],
           ['code' => 'PG', 'name'=>  'plate', 'description'=> 'plate'],
           ['code' => 'PR', 'name'=>  'pair', 'description'=> 'pair'],
           ['code' => 'RL', 'name'=>  'reel', 'description'=> 'reel'],
           ['code' => 'RO', 'name'=>  'roll', 'description'=> 'roll'],
           ['code' => 'SET', 'name'=>'set', 'description'=> 'set'],
           ['code' => 'ST', 'name'=>  'sheet', 'description'=> 'sheet'],
           ['code' => 'TNE', 'name'=>'tonne', 'description'=> 'tonne (metric ton)'],
           ['code' => 'TU' , 'name'=> 'tube', 'description'=> 'tube'],
            ['code' => 'U',  'name' => 'pieces', 'description'=> 'Pieces'],
            ['code' => 'U',  'name' => 'capsules', 'description'=> 'Pieces'],
            ['code' => 'U',  'name' => 'tablets', 'description'=> 'Pieces'],
            ['code' => 'YRD', 'name' => 'yard', 'description'=> 'yard'],

            [
                'name' => "session",
                'code' => 'sessions'
            ],
            
            [
                'name' => "hours",
                'code' => "hrs",
            ],
            [
                'name' => "minutes",
                'code' => "mins",
            ],
            [
                'name' => "days",
                'code' => "days",
            ],
            [
                'name' => "trips",
                'code' => "trips",
            ],
           
            
            [
                'name' => "other",
                'code' => "other",
            ],
        ];


        $newData = [];
        foreach ($data as $item) {
            $newData[]= (object) $item;
        }

        return $newData;
    }

}