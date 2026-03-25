<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundReason extends Model
{
    use HasFactory;
    protected $connection = 'landlord';
    protected $guarded = [];

}
