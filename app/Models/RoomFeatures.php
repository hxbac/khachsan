<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomFeatures extends Model
{
    use HasFactory;

    public $table = 'room_features';

    public $guarded = [];

    public $timestamps = false;
}
