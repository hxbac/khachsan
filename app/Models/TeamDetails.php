<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamDetails extends Model
{
    use HasFactory;

    public $table = 'team_details';

    public $guarded = [];

    public $timestamps = false;
}
