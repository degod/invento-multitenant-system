<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_number',
        'owner_name',
        'building_id',
        'house_owner_id',
    ];

    /**
     * A flat belongs to a building.
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * A flat belongs to a house owner.
     */
    public function houseOwner()
    {
        return $this->belongsTo(User::class, 'house_owner_id');
    }

    /**
     * A flat can have many tenants.
     */
    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
