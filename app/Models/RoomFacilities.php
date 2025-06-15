<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomFacilities extends Model
{
    use HasFactory;

    public $table = 'room_facilities';

    public $guarded = [];

    public $timestamps = false;
}
