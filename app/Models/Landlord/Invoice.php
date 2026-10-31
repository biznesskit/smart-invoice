<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $connection = 'landlord';
    protected $guarded = ['id'];
    protected $with =['tenant'];


    //Relations
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // public function subscription()
    // {
    //     return $this->belongsTo(Subscription::class);
    // }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    public function meta()
    {
        return $this->morphMany(Meta::class, 'metaable');
    }
}
