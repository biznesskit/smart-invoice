<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockInOut extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'validated_date' => 'datetime:d/m/Y H:i:s',
    ];

    public function items(){
        return $this->hasMany(StockInOutItem::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function stock_masters()
    {
        return $this->hasMany(StockMaster::class);
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
