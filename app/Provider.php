<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
class Provider extends Model
{
     use HasApiTokens, Notifiable;
     
    protected $table='provider';

    protected $hidden=['password','access_token','fcm_token'];


    	public static  function addprovider($providerdata){




    	 $providerid = DB::table('provider')
                             ->insertGetId(['first_name' => $providerdata['firstname'], 'last_name'=> $providerdata['lastname'], 'image'=>$providerdata['image'], 'email' => $providerdata['email'], 'password' => $providerdata['password'], 'mobile' => $providerdata['mobile'], 'gender' => $providerdata['gender'], 'addressline1' => $providerdata['address1'], 'addressline2' => $providerdata['address2'], 'city' => $providerdata['city'], 'state' => $providerdata['state'] , 'dob' =>$providerdata['dob'], 'state' => $providerdata['state'], 'zipcode' => $providerdata['zipcode'], 'about' => $providerdata['about'],'workexperience' => $providerdata['workexperience']]);



                             return $providerid;


                         }




}
