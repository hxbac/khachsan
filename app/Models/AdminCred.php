<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AdminCred extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $table = 'admin_cred';

    public $guarded = [];

    public $timestamps = false;
}
