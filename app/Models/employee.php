<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name', 
        'user_id',
        'organization_id',
        'role',
        'authority'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
