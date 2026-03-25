<?php

namespace App\Helpers;

use Carbon\Carbon;

class DatesRangeHelper
{
   
    /**
    *   Methods in this class returns start of day and end of day (dateTime)  objects
     */ 
  
    public static function getStartAndEndDatesToday()
    {
        return (object) [
            'start' => Carbon::now()->startOfDay(),
            'end' => Carbon::now()->endOfDay()
        ];
    }

    public static function getStartAndEndDatesThisWeek()
    {
        return (object) [
            'start' => Carbon::now()->startOfWeek()->startOfDay(),
            'end' => Carbon::now()->endOfWeek()->endOfDay()
        ];
    }

    public static function getStartAndEndDatesThisMonth()
    {
        return (object) [
            'start' => Carbon::now()->startOfMonth()->startOfDay(),
            'end' => Carbon::now()->endOfMonth()->endOfDay()
        ];
    }

    public static function getStartAndEndDatesLastMonth()
    {
        return (object) [
            'start' => Carbon::now()->subMonthsNoOverflow(1)->startOfMonth()->startOfDay(),
            'end' => Carbon::now()->subMonthsNoOverflow(1)->endOfMonth()->endOfDay(),
        ];
    }
    public static function getStartAndEndDatesLastThreeMonths()
    {
        return (object) [
            'start' => Carbon::now()->subMonthsNoOverflow(2)->startOfMonth()->startOfDay(),
            'end' => Carbon::now()->endOfDay(),
        ];
    }

    public static function getStartAndEndDatesLastSixMonths()
    {
        return (object) [
            'start' => Carbon::now()->subMonthsNoOverflow(5)->startOfMonth()->startOfDay(),
            'end' =>  Carbon::now()->endOfDay(),
        ];
    }

    public static function getStartAndEndDatesThisYear()
    {
        return (object) [
            'start' => Carbon::now()->startOfYear(),
            'end' => Carbon::now()
        ];
    }

    public static function getStartAndEndDatesCustom($start_date, $end_date)
    {
        return (object) [
            'start' => $start_date ? Carbon::parse($start_date)->startOfDay() : now()->startOfDay(),
            'end' => $end_date ? Carbon::parse($end_date)->endOfDay() : now()->endOfDay()
        ];
    }



    /**
     *---------------------------------Previous dates ranges ---------------------------- 
    */        

    public static function getStartAndEndDatesPreviousDay(String $date=null)
    {  
        $now = $date ? Carbon::parse($date) : now();

         return (object) [
            'start' => Carbon::parse($now)->subDay()->startOfDay(),
            'end' => Carbon::parse($now)->subDay()->endOfDay()
        ];
    }


    public static function getStartAndEndDatesSameDayPreviousWeek(String $date=null)
    {
        $day = $date ? $date : now();
        return (object) [
                'start' => Carbon::parse($day)->subDays(7)->startOfDay(),
                'end' =>  Carbon::parse($day)->subDays(7)->endOfDay(),
            ];
    }

    public static function getStartAndEndDatesPreviousWeek(String $date=null)
    {
        $day = $date ? $date : now();
        return (object) [ 
            'start' => Carbon::parse($day)->subWeek()->startOfWeek()->startOfDay(),
            'end' => Carbon::parse($day)->subWeek()->endOfWeek()->endOfDay()
        ];

    }

    public static function getStartAndEndDatesPreviousMonth(String $date=null)
    {
        $day = $date ? $date : now();
        return (object) [ 
            'start' => Carbon::parse($day)->subMonthNoOverflow()->startOfMonth()->startOfDay(),
            'end' => Carbon::parse($day)->subMonthNoOverflow()->endOfMonth()->endOfMonth()
        ];       
    }
    
    public static function getStartAndEndDatesPreviousThreeMonths(String $date=null)
    {
        $day = $date ? $date : now();
        return (object) [ 
            'start' => Carbon::parse($day)->subMonthsNoOverflow(3)->startOfMonth()->startOfDay(),
            'end' => Carbon::parse($day)->subMonthsNoOverflow(3)->endOfMonth()->endOfDay()
        ];
    }

    public static function getStartAndEndDatesPreviousSixMonths(String $date=null)
    {
        $day = $date ? $date : now();
        return (object) [ 
            'start' => Carbon::parse($day)->subMonthsNoOverflow(6)->startOfMonth()->startOfDay(),
            'end' => Carbon::parse($day)->subMonthsNoOverflow(6)->endOfMonth()->endOfDay()
        ];      
    }

    public static function getStartAndEndDatesPreviousYear(String $date=null)
    {
        $day = $date ? $date : now();

        return (object) [
            'start' => Carbon::parse($day)->subYear()->startOfYear()->startOfDay(),
            'end' => Carbon::parse($day)->subYear()->endOfYear()->endOfDay()
        ];
    }

}