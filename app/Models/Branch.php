<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    public function stock_in_outs()
    {
        return $this->hasMany(StockInOut::class);
    }
    public function stock_masters()
    {
        return $this->hasMany(StockMaster::class);
    }
    public function insurances()
    {
        return $this->hasMany(Insurance::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function composite_items()
    {
        return $this->hasMany(ItemComposition::class);
    }

    public function imports()
    {
        return $this->hasMany(Import::class);
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
   
    public function meta()
    {
        return $this->morphMany(Meta::class, 'metaable');
    }
    
}
