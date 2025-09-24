<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'contact',
        'flat_id',
        'house_owner_id',
    ];

    /**
     * A tenant belongs to a house owner and has a flat.
     */
    public function houseOwner()
    {
        return $this->belongsTo(User::class, 'house_owner_id');
    }

    /**
     * A tenant has a flat.
     */
    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }
}
