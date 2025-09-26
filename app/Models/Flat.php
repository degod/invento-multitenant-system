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
     * A flat can have one tenant.
     */
    public function tenant()
    {
        return $this->hasOne(Tenant::class, 'id', 'tenant_id');
    }
}
