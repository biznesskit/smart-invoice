<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $connection = 'landlord';
    protected $guarded = ['id'];


    //Relations
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }


    public function invoice()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function meta()
    {
        return $this->morphMany(Meta::class, 'metaable');
    }
}
