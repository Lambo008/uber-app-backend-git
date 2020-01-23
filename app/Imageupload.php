<?php
namespace App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Provider;
use App\Useraddress;
use App\Location;
use App\Bookings;
use App\Timeslots;
use App\Category;
use App\Subcategory;
use App\Providerschedules;
use App\Providerservices;
use DB;
use Mail;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;

class Imageupload
{

    function imgupload($image)
{
               
                $filesize = filesize($image);
                $fileName = $image->getClientOriginalName();
                $fileExtension = $image->getClientOriginalExtension();
                $fileName = rand(11111, 99999) . '.' . $fileExtension;
                $destinationPath = 'images';
                $upload_success = $image->move($destinationPath, $fileName);
                if($upload_success)
                {
                 return $fileName;
                }
}
    
}

