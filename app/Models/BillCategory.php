<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'house_owner_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'house_owner_id');
    }
}