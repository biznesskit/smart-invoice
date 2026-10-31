<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'validated_date' => 'datetime:d/m/Y H:i:s',
    ];

    public function items(){
        return $this->hasMany(PurchaseItem::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

}
