<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $connection = 'landlord';
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
    ];


    //Relations
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function meta()
    {
        return $this->morphMany(Meta::class, 'metaable');
    }

}
