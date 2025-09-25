<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'house_owner_id',
    ];

    /**
     * A building belongs to a house owner.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'house_owner_id');
    }

    /**
     * A building has many flats.
     */
    public function flats()
    {
        return $this->hasMany(Flat::class);
    }
}
