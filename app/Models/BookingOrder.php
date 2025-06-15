<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingOrder extends Model
{
    use HasFactory;

    public $table = 'booking_order';

    public $guarded = [];

    public $timestamps = false;
}
