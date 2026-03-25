<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCompositionItem extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function item_composition()
    {
        return $this->belongsTo(ItemComposition::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

}
