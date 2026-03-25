<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemComposition extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function composition_items()
    {
        return $this->hasMany(ItemCompositionItem::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    
}
