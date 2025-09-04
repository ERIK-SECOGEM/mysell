<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id','title','make','model','year','mileage','vin','location','currency','price','slug','cover_image_path','status','description'
    ];


    protected static function booted()
    {
        static::creating(function ($vehicle) {
            if (empty($vehicle->slug)) {
                $vehicle->slug = Str::slug($vehicle->title.'-'.Str::random(6));
            }
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function images()
    {
        return $this->hasMany(VehicleImage::class)->orderBy('position');
    }


    public function getPublicUrlAttribute(): string
    {
        return route('vehicles.public.show', $this->slug);
    }


    public function scopeOwnedBy($query, $user)
    {
        return $query->where('user_id', $user->id);
    }
}
