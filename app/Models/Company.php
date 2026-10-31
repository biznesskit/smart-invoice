<?php

namespace App\Models;

use App\Models\Landlord\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Company extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = ['id'];
    // protected $with=['meta', 'tenant'];
    // protected $with=['tenant'];


    // Relations
    public function branches(){
        return $this->hasMany(Branch::class);
    }


    public function users(){
        return $this->hasMany(User::class);
    }


    public function tenant(){
        return $this->belongsTo(Tenant::class, 'business_code', 'business_code');
    }

    public function meta()
    {
        return $this->morphMany(Meta::class, 'metaable');
    }

    /**
     * Media
     */
    public function registerMediaConversions(Media $media = null): void
    {

        $height = 150;
        $width = 150;
        $sharpness = 10;

        $this->addMediaConversion('logo')
        ->width($width ? $width : 150)
            ->height($height ? $height : 150)
            ->sharpen($sharpness ? $sharpness : 10)
            ->nonQueued();
    }

}
