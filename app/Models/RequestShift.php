<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'requested_date',
        'new_clock_in',
        'new_clock_out',
        'is_requested_day_off'
    ];
}
