<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Meta extends Model
{
    use HasFactory;
    protected $connection = 'landlord';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'metaable_type', 'metaable_id'];

    //relationship

    public function metaable(): MorphTo
    {
        return $this->morphTo();
    }

}
