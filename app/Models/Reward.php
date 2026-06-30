<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'points_required',
        'stock',
        'is_active',
    ];

    public function claims()
    {
        return $this->hasMany(RewardClaim::class);
    }
}