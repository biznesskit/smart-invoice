<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemClassification extends Model
{
    use HasFactory;
    protected $connection = 'landlord';
    protected $guarded = [];

}
