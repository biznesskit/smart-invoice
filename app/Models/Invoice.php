<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function stock_in_out()
    {
        return $this->hasOne(StockInOut::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function staff()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function jobQueueSequence()
    {
        return $this->morphOne(JobQueueSequence::class, 'queueable');
    }


}
