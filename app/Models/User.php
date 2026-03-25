<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];
    // protected $with=['branch'];
    protected $guard = 'api';
    protected $guard_name = 'api';


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
        'password'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    //Relations
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function invoices(){
        return $this->hasMany(Invoice::class);
    }
    public function stock_IOs(){
        return $this->hasMany(StockInOut::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }


    public function meta()
    {
        return $this->morphMany(Meta::class, 'metaable');
    }







}
