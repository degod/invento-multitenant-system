<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'month',
        'amount',
        'status',
        'notes',
        'bill_category_id',
        'flat_id',
        'house_owner_id',
    ];

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'house_owner_id');
    }

    public function category()
    {
        return $this->belongsTo(BillCategory::class, 'bill_category_id');
    }
}