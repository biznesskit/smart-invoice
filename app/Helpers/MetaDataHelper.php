<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class MetaDataHelper 
{
    /**
     * Save specified model meta data
     * @param  $model Object
     * @param  $table_name String
     * @param  $data Array
     * @param  $category String
     */
    public static function createOrUpdate(Object $model, String $table_name, Array $data, String $category = null ){
        if(is_null($data) || !is_array($data) || is_null($model) || is_null($table_name) || !is_string($table_name)) return;
        $metas=[];
        foreach ($data as $key => $value) : 
            if($key=='media') continue; 
            $val =   is_string($value) || is_numeric($value) ? $value : json_encode($value);    
            $val =   $val == 'null' || $val =='undefined'  ? NULL : $val;    
            $values = [
                'key' => $key, 
                'value' => $val, 
                'category' => $category
            ];  
            if (!Schema::hasColumn($table_name, $key))  :
                $meta = $model->meta()->where('key', $key)->first();
                if($meta) $meta->update($values);
                else  $meta = $model->meta()->create($values);
                array_push($metas, $meta);
            endif;
        endforeach;
        return $metas;
    }

    /**
     * Retrieve whole meta dataobject
     */
    public static function retrieveMetaData(Object $model, $keyword){
        if(is_null($model) || is_null($keyword)) return false;

       return $model->meta()->where('key', $keyword)->first();
    }

    /**
     * Get the set value of the meta data object
     */
    public static function getMetaDataValue(Object $model, String $keyword){
        if(is_null($model) || is_null($keyword)) return NULL;

       if(!$meta = self::retrieveMetaData($model, $keyword)) return NULL;
       else return  $meta->value;
    }
       
}