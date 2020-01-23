<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Admin extends Model
{
    use HasApiTokens, Notifiable;
    
    protected $table='admin';
    
    protected $hidden=['password','access_token','fcm_token'];
}
