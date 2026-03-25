<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DateRangeFromStringHelper
{
    /**
     * Get date period range
     */
    public static function determineDatesRange( $period, $start_date=null, $end_date=null)
    {    
        switch ($period) {
            case 'today':
                return  DatesRangeHelper::getStartAndEndDatesToday();
                break;
            case 'yesterday':
                return  DatesRangeHelper::getStartAndEndDatesPreviousDay();
                break;
            case 'this_week':
                return  DatesRangeHelper::getStartAndEndDatesThisWeek();
                break;
            case 'this_month':
                return  DatesRangeHelper::getStartAndEndDatesThisMonth();
                break;
            case 'last_month':
                return  DatesRangeHelper::getStartAndEndDatesLastMonth();
                break;
            case 'three_months':
                return  DatesRangeHelper::getStartAndEndDatesLastThreeMonths();
                break;
            case 'six_months':
                return  DatesRangeHelper::getStartAndEndDatesLastSixMonths();
                break;
            case 'one_year':
                return  DatesRangeHelper::getStartAndEndDatesThisYear();
                break;
            case 'this_year':
                return  DatesRangeHelper::getStartAndEndDatesThisYear();
                break;
            case 'custom':
                return  DatesRangeHelper::getStartAndEndDatesCustom($start_date, $end_date);
                break;
            default:
                return  DatesRangeHelper::getStartAndEndDatesThisYear(); // Default to date today
                break;
        }      
    }

}