<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    public $timestamps = false; 
    
    protected $fillable = [
        'city_id',
        'address_line',
        'postal_code',
        'addressable_id',  // Importante si usas relaciones polimórficas
        'addressable_type' // Importante si usas relaciones polimórficas
    ];

    //relacion with city
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function addressable()
    {
        return $this->morphTo();
    }
}