<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomNumber extends Model
{
    use HasFactory;

    public $table = 'room_number';

    public $guarded = [];

    public $timestamps = false;
}
