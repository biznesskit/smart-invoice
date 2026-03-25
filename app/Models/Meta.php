<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $dateFormat = 'Y-m-d H:i:s.u';
    protected $hidden = ['created_at', 'updated_at', 'metaable_type', 'metaable_id'];



    //Relations
    public function metaable()
    {
        return $this->morphTo();
    }
   

    
}
