<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportItem extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function import()
    {
        return $this->belongsTo(Import::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

}
