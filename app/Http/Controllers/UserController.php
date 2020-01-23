<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Provider;
use App\Imageupload;
use App\FCMPushNotification;
use App\Bookings;
use App\Useraddress;
use App\Location;
use App\Timeslots;
use App\Category;
use App\Subcategory;
use App\Providerservices;
use App\Providerschedules;
use App\Providerreviews;
use App\Userreports;
use App\Walletusers;
use App\Wallettransaction;
use App\Providerstripeaccount;

use Mail;
use DB;
use PDF;

// use PDF;

use Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Stripe\Error\Card;
use Authy;
use DateTime;

class UserController extends Controller
{

   public function getotp(request $request , $locale){

     app()->setLocale($locale);
     if($request->mobilenumber && $request->countrycode)
     {
        $mobile = $request->mobilenumber;
        $cc = $request->countrycode;
        $authy_api = new Authy\AuthyApi('EaeunZDQTodNUGSbFuf4XdYneAWe8o85');
        $verification =$authy_api->phoneVerificationStart($mobile, $cc , 'sms');
        if($verification->ok()){
             $response['error']='false';
             $response['error_message']=trans('lang.OTP Sent');
         
        }else{
             $response['error']="true";
             $response['error_message']=trans('lang.Issue in sending otp.');
        
        }  
     }else{
                $response['error']="true";
        $response['error_message']=trans('lang.Invalid mobilenumber or country code');
        
     }
     echo json_Encode($response);
  }



 public function verifyotp(request $request , $locale)
 {
     app()->setLocale($locale);
     if($request->mobilenumber && $request->countrycode && $request->otp)
     {
        $mobile = $request->mobilenumber;
        $cc = $request->countrycode;
        $otp = $request->otp;
        $authy_api = new Authy\AuthyApi('EaeunZDQTodNUGSbFuf4XdYneAWe8o85');

        $verification =$authy_api->phoneVerificationCheck($mobile, $cc, $otp);

        if($verification->ok()){
            $check_userlogin=User::where('mobile',$mobile)->update(['is_mobile_verify'=>'1']);
               $response['error']='false';
               $response['otp_message']="Verification code is correct.";
               $response['error_message']=trans('lang.Otp verified.');
        
        }else{
               $response['error']='true';
               $response['otp_message']="Verification code is incorrect.";
               $response['error_message']=trans('lang.Invalid Otp.');
         
        }
       
     }else{
                $response['error']="true";
        $response['error_message']=trans('lang.Mandatory Params are empty.');
        
     }
     echo json_Encode($response);
  }

 // public function verifyotp(request $request , $locale)
 // {
 //  app()->setLocale($locale);
 //     if($request->mobilenumber && $request->countrycode && $request->otp)
 //     {
 //        $mobile = $request->mobilenumber;
 //        $cc = $request->countrycode;
 //        $otp = $request->otp;
 //        $authy_api = new Authy\AuthyApi('EaeunZDQTodNUGSbFuf4XdYneAWe8o85');

 //        $twilio =$authy_api->phoneVerificationCheck($mobile, $cc, $otp);

 //        if(!empty($twilio))
 //        {     

 //          $check_userlogin=User::where('mobile',$mobile)->update(['is_mobile_verify'=>'1']);

 //          if($twilio->message() == 'Verification code is correct.')
 //          {
 //                $response['error']='false';
 //               $response['otp_message']="Verification code is correct.";
 //               $response['error_message']=trans('lang.Otp verified.');
 //          }else if($twilio->message() == 'No pending verifications for +91 784-584-5154 found.')
 //          {
 //                $response['error']='true';
 //               $response['otp_message']="No pending verifications for +91 784-584-5154 found.";
 //               $response['error_message']=trans('lang.Otp verified.');
 //          }else
 //          {
 //              $response['error']='false';
 //               $response['otp_message']=$twilio->message();
 //               $response['error_message']=trans('lang.Otp verified.');
 //          }

              
 //       }else{
 //                $response['error']="true";
 //        $response['error_message']=trans('lang.mobilenumber is not Registered with us.');
 //       }
 //     }else{
 //                $response['error']="true";
 //        $response['error_message']=trans('lang.Mandatory Params are empty.');
        
 //     }
 //     echo json_Encode($response);
 // }
    
    public function signup(request $request , $locale)
    {
      app()->setLocale($locale);
        if($request->first_name && $request->last_name && $request->password && $request->mobile && $request->countrycode)
        {
            $firstname=$request->first_name;
            $lastname=$request->last_name;
            $password=bcrypt($request->password);
            $mobile=$request->mobile;
            $image=$request->image;
            $countrycode=$request->countrycode;
            $email=$mobile;
            // $stripe = Stripe::make('DUMMY_KEY');
            // $customer = $stripe->customers()->create([
            //     'email' => $email,
            // ]);
            $customer_id=' ';//$customer['id'];
            
             $user_check=User::where('mobile',$mobile)->first();
             
             if($user_check)
                 {
                     if($user_check->login_type == 'google')
                {
                  $response['error']='true';
	          $response['error_message']=trans('lang.Already Signed using Google Login');  
                  
                }
                if($user_check->login_type == 'facebook')
                {
                  $response['error']='true';
	          $response['error_message']=trans('lang.Already Signed using Facebook Login');   
                }
                if($user_check->login_type == 'manual')
                {

                  $update=User::where('mobile',$mobile)->update([
                    'first_name'=>$firstname,
                    'last_name'=>$lastname,
                    'password'=>$password,
                    'image'=>$image,
                    'email'=>$email,
                    'countrycode'=>$countrycode
                ]);
              $response['is_user_exists']='true';
                  $response['error']='true';
	          $response['error_message']=trans('lang.User Already Registered');   
                }
                echo json_encode($response);die;
             }
             $client = new Client();
             
             try {
              $newuser=new User();
              $newuser->first_name=$firstname;
              $newuser->last_name=$lastname;
              $newuser->email = $email;
              $newuser->countrycode = $countrycode;
              $newuser->password=$password;
                $newuser->stripe_payment_account=' ';//$customer_id;
              $newuser->mobile=$mobile;
              if($request->image)
              {
             $newuser->image=$image;
              }else{
              $newuser->image=" ";
              }
              
              $newuser->login_type='manual';
              $newuser->save();
              
             } catch (\Illuminate\Database\QueryException  $ex) {
                 $response['error']='true';
$response['error_message']=trans('lang.Database Exception Error');
echo json_encode($response); 
die;
             }
                 	if($newuser)
    	{
                $get_user=User::where('id',$newuser->id)->first();            
                $token= $get_user->createToken('Token Name')->accessToken;            
                            
    		$response['error']="false";
              $response['is_user_exists']='false';

    		$response['error_message']=trans('lang.Inserted Successfully');
                $responsee['access_token']=$token;
    	}
    	else
    	{
            $response['error']="true";
    		$response['error_message']=trans('lang.Not Inserted.');
    	}

             
        }else{
                $response['error']="true";
    		$response['error_message']=trans('lang.Mandatory Parameters are Missing');
        }
        echo json_encode($response);
    }   
 
    public function userlogin(request $request , $locale)
    {
      app()->setLocale($locale);
        if($request->mobile && $request->password && $request->user_type && $request->countrycode)
        {
            $provider=$request->user_type;
            $email=$request->mobile;
            $countrycode=$request->countrycode;
            //$password=$request->password;

             $mobile=trim($request->mobile);
            //$email=$request->email;
            $password=trim($request->password);
            $check_userlogin=User::where('mobile',$mobile)->first();

            // if($check_userlogin['is_mobile_verify'] == '0')
            // {
            //   $response['error']='true';
            //   $response['error_message']=trans('lang.Mobile not yet verified');  
            //   // die; 
            // }else
            // {
            
            if($check_userlogin)
            {
                if($check_userlogin->login_type == 'manual')
                {
                 
                 if($check_userlogin->status == 'active')
                 {
         $client = new Client();
            
            try {
                 $res = $client->request('POST', 'http://18.218.132.174/uber_test/public/oauth/token', [
                'form_params' => [
                'client_id' => '2',
                'client_secret' => 'BpshD2f1NUhmeONox4YuDkibBMNOlSPdeKzR2zJI',
                'grant_type' => 'password',
                'username' => $email,
                'password' => $password,
                'scope' => '*',
                'provider'=>$provider
            ]
        ]);         
            } catch (\GuzzleHttp\Exception\BadResponseException $ex) {
                $jsonresp=$ex->getMessage();
                $response['error']='true';
              $response['is_user_exists']='true';

                $response['error_message']=trans('lang.User Credentials are incorrect.');
                echo json_encode($response); die;
            }
            $access_token = json_decode((string) $res->getBody(), true)['access_token'];
 
             $response['error']='false'; 
             $response['error_message']=trans('lang.Success'); 
             $response['access_token']=$access_token;
             $response['id']=$check_userlogin['id'];
             $response['first_name']=$check_userlogin['first_name'];
             $response['last_name']=$check_userlogin['last_name'];
             $response['email']=$check_userlogin['email'];
             $response['mobile']=$check_userlogin['mobile'];
             $response['image']=$check_userlogin['image'];
             $response['latitude']=$check_userlogin['latitude'];
             $response['longitude']=$check_userlogin['longitude'];
             $response['is_user_exists']='true';
             $response['is_mobile_verify']=$check_userlogin['is_mobile_verify'];
  
                 }else{

                  $response['error']='true';
                  $response['error_message']=trans('lang.User Blocked');
                 }
        
                }
                if($check_userlogin->login_type == 'google')
                {
                  $response['error']='true';
              $response['is_user_exists']='true';
	          $response['error_message']=trans('lang.Already Signed using Google Login');  
                }elseif($check_userlogin->login_type == 'facebook')
                {
                  $response['error']='true';
              $response['is_user_exists']='true';
	          $response['error_message']=trans('lang.Already Signed using Facebook Login');   
                }
            }else{
                  $response['error']='true';
              $response['is_user_exists']='false';
	          $response['error_message']=trans('lang.Email id not Registered with us.');
            }
          // }
        }else{
            $response['error']='true';
	    $response['error_message']=trans('lang.Mandatory Parameters are missing');
    }
    echo json_encode($response);
}





// public function  basic_email(request $request){

// $data = array('name'=>"Virat Gandhi");
   
// // Mail::send('errors.503',$data,function($message) use ($email,$otpnumber){
// //                       $message->To($email)->subject('FORGOT PASSWORD')->setBody($otpnumber);
// //                       });
// //                $resp



//       // Mail::send(['text'=>'mail'], $data, function($message) {
//       //    $message->to('ramkumars7395@gmail.com', 'Tutorials Point')->subject
//       //       ('Laravel Basic Testing Mail');
//       //    $message->from('jaaavvaaa@gmail.com','9551392249');
//       // });
//       // echo "Basic Email Sent. Check your inbox.";


//     $user="ramkumars7395@gmail.com";
//     $username="ram";

//     $link="welcome";
//   Mail::send( 'errors.503',['user' => $user], function ($m) use ($user,$link) {
//             $m->from('hello@app.com', 'welcome to laravel');

//             $m->to($user, 'ram')->subject('Your Reminder!')->setBody($link);
//         });



//   echo "welcome";

   





//    }


public function sociallogin(request $request , $locale)
{
  app()->setLocale($locale);
  if($request->socialtoken && $request->mobile && $request->firstname && $request->lastname && $request->social_type && $request->countrycode)
  {
      $socialtoken=$request->socialtoken;
      $type=$request->social_type;
      $mobile=$request->mobile;
      $countrycode=$request->countrycode;
      $firstname=$request->firstname;
      $lastname=$request->lastname;
      $check_userdetails=User::where(['mobile'=>$mobile])->first();
      
     if($check_userdetails)
      {
        if($type == 'google' || 'facebook')
          {
          if($check_userdetails['login_type'] == $type)
          {
          	  if($type == 'google')
          	  {
              if($check_userdetails->google_token == $socialtoken)
              {
               $token= $check_userdetails->createToken('Token Name')->accessToken;
               $response['error']="false";
    	       $response['error_message']=trans('lang.Login Successfully');
             $response['id']=$check_userdetails['id'];
             $response['is_mobile_verify']=$check_userdetails['is_mobile_verify'];
               $response['access_token']=$token; 
               
              }else{
                  $response['error']="true";
    		  $response['error_message']=trans('lang.Already Signed using Facebook Login');
              }
          	  }
          	  if($type == 'facebook')
          	  {
          	  if($check_userdetails->facebook_token == $socialtoken)
              {
               $token= $check_userdetails->createToken('Token Name')->accessToken;
                  $response['error']="false";
    		  $response['error_message']=trans('lang.Login Successfully');
             $response['is_mobile_verify']=$check_userdetails['is_mobile_verify'];
          $response['id']=$check_userdetails['id'];
                $response['access_token']=$token; 
              }else{
                  $response['error']="true";
    		  $response['error_message']=trans('lang.Already Signed using Google Login');
              }	
          	  }
              
                  
          }else{
                  $response['error']="true";
    		  $response['error_message']=trans('lang.Already Signed using') .trans('lang.login');
          }
        }else{
          $response['error']="true";
          $response['error_message']=trans('lang.Invalid Social type');
        }
      }
      else
          {
          // $stripe = Stripe::make('DUMMY_KEY');
          //   $customer = $stripe->customers()->create([
          //       'email' => $email,
          //   ]);
            $customer_id=' ';//$customer['id'];
          $client = new client();
          try {
              
              $new_socialuser= new User();
              $new_socialuser->mobile=$mobile;
              $new_socialuser->first_name=$firstname;
              $new_socialuser->last_name=$lastname;
              $new_socialuser->countrycode=$countrycode;
              $new_socialuser->login_type=$type;
               $new_socialuser->stripe_payment_account=' ';//$customer_id;
              if($request->image)
              {
                $new_socialuser->image=$request->image;
              }
              if($type == 'google')
              {
              $new_socialuser->google_token=$socialtoken;    
              }
              elseif($type == 'facebook')
              {
              $new_socialuser->facebook_token=$socialtoken;    
              }
              $new_socialuser->save();
              
          } catch (\Illuminate\Database\QueryException $ex) 
          {
              $jsonresp=$ex->getMessage();
                               $response['error']='true';
                               $response['message']=trans('lang.Database Exception Error');
                               echo json_encode($response); 
                               die;
          }
                       	if($new_socialuser)
    	{
                $get_user=User::where('id',$new_socialuser->id)->first();            
                $token= $get_user->createToken('Token Name')->accessToken;            
                            
    		$response['error']="false";
    		$response['error_message']=trans('lang.Inserted Successfully');
        $response['is_mobile_verify']=$get_user['is_mobile_verify'];
        $response['access_token']=$token;
        $response['id']=$new_socialuser->id;
                
    	}
    	else
    	{
            $response['error']="true";
    		$response['error_message']=trans('lang.Not Inserted.');
    	}
      }
  }else{
      $response['error']="true";
      $response['error_message']=trans('lang.Mandatory Parameters are Missing');
  }
  echo json_encode($response);
}

 public function forgot_password(request $request , $locale)
 {
  app()->setLocale($locale);
     if($request->mobilenumber && $request->countrycode)
     {
       $mobile = $request->mobilenumber;
        $cc = $request->countrycode;  
       $get_user=User::where('mobile',$mobile)->first();
       if($get_user)
       {
              
        $authy_api = new Authy\AuthyApi('EaeunZDQTodNUGSbFuf4XdYneAWe8o85');

        $twilio =$authy_api->phoneVerificationStart($mobile, $cc , 'sms');

        // print_r($response->message());

        if(!empty($twilio))
        {
               $response['error']='false';
               $response['otp_message']=$twilio->message();
               $response['error_message']=trans('lang.OTP Sent');
       
       }else{
                $response['error']="true";
    		$response['error_message']=trans('lang.Otp send failed.');
       }
     }else
     {
      $response['error']="true";
        $response['error_message']=trans('lang.Invalid mobilenumber');
     }
     }else{
                $response['error']="true";
    		$response['error_message']=trans('lang.Invalid Parameters');
    		
     }
     echo json_Encode($response);
 }
 
 public function otpcheck(request $request , $locale)
 {
  app()->setLocale($locale);
     if($request->mobilenumber && $request->countrycode && $request->otp)
     {
        $mobile = $request->mobilenumber;
        $cc = $request->countrycode;
        $otp = $request->otp;
        $authy_api = new Authy\AuthyApi('EaeunZDQTodNUGSbFuf4XdYneAWe8o85');

        $twilio =$authy_api->phoneVerificationCheck($mobile, $cc, $otp);
        if(!empty($twilio))
        {     

              if($twilio->message() == 'Verification code is correct.')
          {
                $response['error']='false';
               $response['otp_message']="Verification code is correct.";
               $response['error_message']=trans('lang.Otp verified.');
          }else if($twilio->message() == 'No pending verifications for +91 784-584-5154 found.')
          {
                $response['error']='true';
               $response['otp_message']="No pending verifications for +91 784-584-5154 found.";
               $response['error_message']=trans('lang.Otp verified.');
          }else
          {
              $response['error']='false';
               $response['otp_message']=$twilio->message();
               $response['error_message']=trans('lang.Otp verified.');
          }

       }else{
                $response['error']="true";
        $response['error_message']=trans('lang.mobilenumber is not Registered with us.');
       }
     }else{
                $response['error']="true";
        $response['error_message']=trans('lang.Mandatory Params are empty.');
        
     }
     echo json_Encode($response);
  }
         
public function resetpassword(request $request , $locale)
{
  app()->setLocale($locale);
 
    if($request->mobilenumber && $request->password && $request->confirmpassword)
    {
        $password=$request->password;
        $cnfpassword=$request->confirmpassword;
        $mobile=$request->mobilenumber;
        if($password == $cnfpassword)
        {
        $encryptpassword=bcrypt($password);    
        User::where('mobile',$mobile)->update(['password'=>$encryptpassword]);    
        $response['error']="false";
         $response['error_message']=trans('lang.Password Reset Successfully.'); 
        
        }else{
         $response['error']="true";
         $response['error_message']=trans('lang.Password & Confirm Password are not same.');   
        }
    }else{
        $response['error']="true";
         $response['error_message']=trans('lang.Mandatory Parameters are missing.'); 
    }
    
   echo json_encode($response); 
}
public function updatedevicetoken(request $request , $locale)
{
  app()->setLocale($locale);
    $userid=Auth::guard('api')->user()->id; 
    
    if($request->fcm_token && $request->os)
    {
     $fcmtoken=$request->fcm_token;    
     $os=$request->os;    
      User::where('id',$userid)->update(['fcm_token'=>$fcmtoken,'os_type'=>$os]);
         $response['error']="false";
         $response['error_message']=trans('lang.fcm token updated.'); 
    }else{
         $response['error']="true";
         $response['error_message']=trans('lang.fcm token is empty.'); 
    }
    echo json_encode($response);
}
public function addaddress(request $request , $locale)
{
  app()->setLocale($locale);
    if($request->address && $request->doorno && $request->landmark && $request->latitude && $request->longitude && $request->title && $request->city)
    {
      $address=$request->address;
      $doorno=$request->doorno;
      $landmark=$request->landmark;
      $latitude=$request->latitude;
      $longitude=$request->longitude;
      $city=$request->city;
      $title=$request->title;
      $userid=Auth::guard('api')->user()->id; 
      $client= new client();
      
      try {
          $addaddress=new Useraddress();
          $addaddress->user_id=$userid;
          $addaddress->address_line_1=$address;
          $addaddress->doorno=$doorno;
          $addaddress->landmark=$landmark;
          $addaddress->latitude=$latitude;
          $addaddress->longitude=$longitude;
          $addaddress->city=$city;
          $addaddress->title=$title;
          $addaddress->save();    
      } catch (\Illuminate\Database\QueryException $ex) {
          $jsonresp=$ex->getMessage();
                               $response['error']='true';
                               $response['message']=trans('lang.Database Exception Error');
                               echo json_encode($response); 
                               die;
      }
      if($addaddress)
      {
      $response['error']='false';
      $response['error_message']=trans('lang.address created.');
      	
  }else{
  	  $response['error']='true';
      $response['error_message']=trans('lang.No Address created.');
    
  }
    }else{
                               $response['error']='true';
                               $response['error_message']=trans('lang.Mandatory Params are empty.');                              
    }
    echo json_encode($response);
}


public function viewaddress(request $request , $locale)
{
  app()->setLocale($locale);
    $userid=Auth::guard('api')->user()->id; 
    $useraddress=Useraddress::where('user_id',$userid)->get();
    if($useraddress)
    {
    $response['error']='false';
    $response['error_message']=trans('lang.success');
    $response['list_address']=$useraddress;    
    }else{
    $response['error']='false';
    $response['error_message']=trans('lang.success');
    $response['list_address']=[];    
    }
    echo json_encode($response);
}

public function updateaddress(request $request , $locale)
{
  app()->setLocale($locale);
    if(($request->id))
    {
      $id=$request->id;  
      $address=$request->address;
      $doorno=$request->doorno;
      $landmark=$request->landmark;
      $latitude=$request->latitude;
      $title=$request->title;
      $longitude=$request->longitude;
      $userid=Auth::guard('api')->user()->id; 
      
      $update=Useraddress::where('id',$id)->update([
          'user_id'=>$userid,
          'address_line_1'=>$address,
          'doorno'=>$doorno,
          'landmark'=>$landmark,
          'latitude'=>$latitude,
          'title'=>$title,
          'longitude'=>$longitude
      ]);
      if($update){
          $response['error']='false';
          $response['error_message']=trans('lang.updated');
      }else{
          $response['error']='true';
          $response['error_message']=trans('lang.not updated');
      }
    }else{
        $response['error']='true';
          $response['error_message']=trans('lang.Id is empty');
    }
    echo json_encode($response);
}

public function deleteaddress(request $request , $locale)
{
app()->setLocale($locale);
  if($request->id)
  {
    $id=$request->id;
    $userid=Auth::guard('api')->user()->id;
    $delete=Useraddress::where(['id'=>$id,'user_id'=>$userid])->delete();
    if($delete)
    {
      $response['error']='false';
    $response['error_message']=trans('lang.Address Deleted');
    	
    }else{
	$response['error']='true';
  	$response['error_message']=trans('lang.Invalid User id');
    }
  }else{
  	$response['error']='true';
  	$response['error_message']=trans('lang.Invalid Address id.');
  }
  echo json_encode($response);
}

public function viewprofile(request $request , $locale)
        {
          app()->setLocale($locale);
         $userid=Auth::guard('api')->user()->id;
         $userdetails=User::select('first_name','last_name','mobile','image')->where('id',$userid)->first();
         if($userdetails)
         {
            $response['error'] ='false';
            $response['error_message'] =trans('lang.success');
            $response['user_details'] =$userdetails;
        }else{
            $response['error']='true';
            $response['error_message']=trans('lang.fail');
//            $response['error_message']='fail';
        }
echo json_encode($response);
        }

        public function updateprofile(request $request , $locale)
        {
          app()->setLocale($locale);
           $userid=Auth::guard('api')->user()->id;
            if($request->first_name && $request->last_name && $request->mobile && $request->image)
            {
                $first_name=$request->first_name;
                $last_name=$request->last_name;
                $mobile=$request->mobile;
                $image=$request->image;
               $update=User::where('id',$userid)->update(['first_name'=>$first_name,'last_name'=>$last_name,'mobile'=>$mobile,'image'=>$image]);
               if($update)
               {
                $response['error']='false';   
                $response['error_message']=trans('lang.success');   
               }else{
                $response['error']='true';   
                $response['error_message']=trans('lang.not updated.');    
               }
            }else{
                 $response['error']='true';   
                $response['error_message']=trans('lang.Mandatory Parameter are empty');
            }
            echo json_encode($response);
        }
        
        public function changepassword(request $request , $locale)
        {
          app()->setLocale($locale);
            if($request->oldpassword && $request->newpassword && $request->cnfpassword)
            {
                $oldpassword=$request->oldpassword;
                $email=Auth::guard('api')->user()->email;
                $userid=Auth::guard('api')->user()->id;
                $password=User::where('id',$userid)->value('password');
                if(password_verify($oldpassword,$password))
                {
                   if($request->newpassword == $request->cnfpassword)
                   {
                       $newpassword=$request->newpassword;
                       $updatepassword=bcrypt($newpassword);
                       User::where('id',$userid)->update(['password'=>$updatepassword]);
                       
                       $response['error']='false';
                       $response['error_message']=trans('lang.Password changed');
                   }else{
                       $response['error']='true';
                       $response['error_message']=trans('lang.Password and Confirm Password are not same');
                   }
                }else{
                     $response['error']='true';
                       $response['error_message']=trans('lang.The Entered Password is incorrect');
                }
            }else{
                       $response['error']='true';
                       $response['error_message']=trans('lang.Mandatory Parameters are empty');
            }
            echo json_encode($response);
        }
        
public function dashboard(request $request , $locale)
{
  app()->setLocale($locale);
    $location=Location::get();
    $get_category=Category::get();
    
    $list_types=Category::select('type')->where(['status'=>'active'])->distinct()->get();
   

    $all_banners=DB::table('banner_images')->get();
   
    
    if(count($list_types) > 0)
    {
         foreach($list_types as $types)
    {
         $maintype=$types->type;
          $type_category=Category::where(['type'=>$maintype,'status'=>'active'])->get();
//          foreach($type_category as $newcategory)
//          {
//                  $newcategory->image="http://18.218.132.174/UberDoo/public/images/".$newcategory->image;
//                  $newcategory->icon="http://18.218.132.174/UberDoo/public/images/".$newcategory->icon; 
//          }
// //         
         $all_categories[$maintype]=$type_category;
      } 
         $arraycategory[]=$all_categories;
//         $response['list_category']=$arraycategory;
    }else{
     $arraycategory=[];
    }
  
      $response['list_category']=$arraycategory;
    $response['error']='false';
    $response['error_message']=trans('lang.success');
    $response['banner_images']=$all_banners;
     if(Auth::guard('api')->check()){
             $userid=Auth::guard('api')->user()->id;
    $username=Auth::guard('api')->user()->first_name;
    $image=Auth::guard('api')->user()->image;
    $response['username']=$username;     
    $response['image']=$image;     
     }
    $response['location']=$location;
   
    echo json_encode($response);
}

public function list_subcategory(request $request , $locale)
{
  app()->setLocale($locale);
    if($request->id)
    {
        $categoryid=$request->id;
         $get_subcategory=Subcategory::where(['category_id'=>$categoryid,'status'=>'active'])->get();
       
       if($get_subcategory)
       {
           
       
    //     foreach($get_subcategory as $newsubcategory)
    // {
    //     $newsubcategory->image="http://18.218.132.174/UberDoo/public/images/".$newsubcategory->image;
    //     $newsubcategory->icon="http://18.218.132.174/UberDoo/public/images/".$newsubcategory->icon;
    // }
        $response['error']='false';
        $response['error_message']=trans('lang.success');
        $response['list_subcategory']=$get_subcategory;
        
    }else{
           $response['error']='false';
           $response['list_subcategory']=[];
           $response['error_message']=trans('lang.No Subcategories available');
       }
        
    }else{
        $response['error']='true';
        $response['error_message']=trans('lang.Invalid id');
    }
    echo json_encode($response);
}

/*
public function appsettings(request $request)
{   
           $location=Location::get();
        $timeslots=Timeslots::get();
        
        $response['error']='false';
        $response['error_message']='success';
        $response['location']=$location;
        $response['timeslots']=$timeslots;
 if(Auth::guard('api')->check())
 { 

     $userid=Auth::guard('api')->user()->id;
     
     //BLK = Is Blocked
     //DSP = is in Dispute
     //REP=review pending
     //PYP=Payment Pending
     //INP =Invoice Pending
     //PAID= Paid
     //PPCNF=Pending Payment Confirmation
     //PCNF =Payment Confirmation
     //Accept= Accepted Job 
     //Reject = Reject job
     //Start = Start Job
     //Complete = Complete Job

     $listofstatus=array('Blocked','Dispute','Reviewpending','Completedjob','Waitingforpaymentconfirmation');
     for($i=0;$i<=count($listofstatus)-1;$i++)
     {
//        $bookingdetails=Bookings::where(['user_id'=>$userid,'status'=>$listofstatus[$i]])->get();
        // $bookingdetails=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,CONCAT(users.first_name,users.last_name) AS username,CONCAT(provider.first_name,provider.last_name) AS providername,provider.id as provider_id,provider.image,service_sub_category.sub_category_name,service_sub_category.icon,time_slots.timing,bookings.booking_date,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,provider_schedules.days,bookings.job_start_time,bookings.job_end_time,bookings.cost,user_address.doorno,user_address.landmark,user_address.address_line_1,bookings.rating,bookings.service_category_id AS categoryId,bookings.status,bookings.feedback,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.worked_mins,bookings.created_at,bookings.updated_at"))
        //                ->join('user_address', 'bookings.address_id', '=', 'user_address.id')
        //                ->join('users', 'bookings.user_id', '=', 'users.id')
        //                ->join('provider', 'bookings.provider_id', '=', 'provider.id')
        //                ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
        //                ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
        //                ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
        //                ->where(['bookings.user_id'=>$userid,'bookings.status'=>$listofstatus[$i]])
        //                ->groupBy('bookings.id')
        //                ->orderBy('bookings.updated_at', 'desc')
        //                ->get();


           $bookingdetails=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,CONCAT(users.first_name,users.last_name) AS username,CONCAT(provider.first_name,provider.last_name) AS providername,provider.id as provider_id,provider.image,service_sub_category.sub_category_name,service_sub_category.icon,time_slots.timing,bookings.booking_date,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,provider_schedules.days,bookings.job_start_time,bookings.job_end_time,bookings.cost,user_address.doorno,user_address.landmark,user_address.address_line_1,service_category.baseamount,bookings.rating,bookings.status,bookings.feedback,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.worked_mins,bookings.created_at,bookings.updated_at"))
                       ->join('user_address', 'bookings.address_id', '=', 'user_address.id')
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('service_category','bookings.service_category_id','=','service_category.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                       //->join('tax_calculation','bookings.id', '=','tax_calculation.booking_id')
                       ->where(['bookings.user_id'=>$userid,'bookings.status'=>$listofstatus[$i]])
                       ->where('service_category.baseamount_status','=','active')
                       ->groupBy('bookings.id')
                       ->orderBy('bookings.updated_at', 'desc')
                       ->get();
                 

        if($bookingdetails)
        {
//             foreach($bookingdetails as $details)
//             {
            
//             $yesdata['booking_id']=$details->id;
//             $yesdata['booking_order_id']=$details->booking_order_id;
//             $yesdata['username']=$details->username;
//             $yesdata['providername']=$details->providername;
//             $yesdata['provider_id']=$details->provider_id;
//             $yesdata['sub_category_name']=$details->sub_category_name;
//             $yesdata['timing']=$details->timing;
//             $yesdata['booking_date']=$details->booking_date;
//             $yesdata['days']=$details->days;
//             $yesdata['job_start_time']=$details->job_start_time;
//             $yesdata['job_end_time']=$details->job_end_time;
            
//             if($details->cost == 0)
//             {
//               $yesdata['cost'] = 50;
//             }else
//             {
//               $yesdata['cost']=$details->cost;//android
//             }
            
//             $yesdata['tax_name']=$details->tax_name;
//             $yesdata['gst_percent']=$details->gst_percent;
//             $yesdata['gst_cost']=$details->gst_cost;
//             if($details->total_cost == 0){
//               $yesdata['total_cost']= 50;
//             }
//             else{
//               $yesdata['total_cost']=$details->total_cost;
//             }
//             $yesdata['doorno']=$details->doorno;
//             $yesdata['landmark']=$details->landmark;
//             $yesdata['address_line_1']=$details->address_line_1;
//             $yesdata['rating']=$details->rating;
//             $yesdata['worked_mins']=$details->worked_mins;
// //            $yesdata['created_at']=$details->created_at;
// //            $yesdata['updated_at']=$details->updated_at;
//             $yesdata['status']=$listofstatus[$i];
//             $newdata[]=$yesdata;
//             }

  foreach($bookingdetails as $details)
            {
          

            $yesdata['booking_id']=$details->id;
            $yesdata['booking_order_id']=$details->booking_order_id;
            $yesdata['username']=$details->username;
            $yesdata['providername']=$details->providername;
            $yesdata['provider_id']=$details->provider_id;
            $yesdata['sub_category_name']=$details->sub_category_name;
            $yesdata['timing']=$details->timing;
            $yesdata['booking_date']=$details->booking_date;
            $yesdata['days']=$details->days;
            $yesdata['job_start_time']=$details->job_start_time;
            $yesdata['job_end_time']=$details->job_end_time;
            
            $yesdata['tax_name']=$details->tax_name;
            $yesdata['gst_percent']=$details->gst_percent;
            $yesdata['gst_cost']=$details->gst_cost;
            //$yesdata['baseamount']=$details->charge;

            if($details->total_cost <= $details->baseamount){
             
              $yesdata['total_cost']= $details->baseamount;
            }
            else{
              $yesdata['total_cost']=$details->total_cost;
            }

             if($details->cost <= $details->baseamount){
             
             $yesdata['cost']=$details->baseamount;
            }
            else{
              $yesdata['cost']=$details->cost;
            }
            $yesdata['doorno']=$details->doorno;
            $yesdata['landmark']=$details->landmark;
            $yesdata['address_line_1']=$details->address_line_1;
            $yesdata['rating']=$details->rating;
            $yesdata['worked_mins']=$details->worked_mins;
//            $yesdata['created_at']=$details->created_at;
//            $yesdata['updated_at']=$details->updated_at;
            $yesdata['status']=$listofstatus[$i];



           // $yesdata[]=$yesdata;
            //$id=$details->id;
            }
               



           // $yesdata[]=$yesdata;
            //$id=$details->id;
           




        }
//        $lastdata=$newdata;
//        
//       
//                 $newdata[$listofstatus[$i]]=$yesdata;
//        }else{
////            $yesdata['booking_id']=$bookingdetails['id'];
////            $yesdata['status']="0";
////            $newdata[$listofstatus[$i]]=$yesdata;
//        }
//          
     }





      if(isset($yesdata))
     {



     $query_data = DB::table('tax_calculation')
                        ->where('booking_id', '=', $yesdata['booking_id'])
                        //->orWhere('ac_customer_id', '=', 13)
                        ->get();

                       // print_r($query_data);
                       // exit;
           
                     $yesdata['alltax']=array();
              foreach ($query_data as $tax) {

                     // $taxs['taxname']= $tax->taxname;
                        $taxs['tax_amount']= $tax->tax_amount;
                          $taxs['tax_totalamount']= $tax->tax_total_amount;
                      array_push($yesdata['alltax'],$taxs);
                     }       








      //$yesdata['alltax']=array();

      //array_push($yesdata['alltax'],$gettax);
      //$yesdata=$gettax;
      $response['status']=$yesdata;



     }else{
         $response['status']=[];
     }
      
//     $new[]=$yesdata;
    
 }
        echo json_encode($response);
}


*/





public function  pdfgenerator(request $request , $locale){

app()->setLocale($locale);
            $bookingid=$request->bookingid;

           $location=Location::get();
        $timeslots=Timeslots::get();
        
        $response['error']='false';
        $response['error_message']=trans('lang.success');
        // $response['location']=$location;
        // $response['timeslots']=$timeslots;
 
     //BLK = Is Blocked
     //DSP = is in Dispute
     //REP=review pending
     //PYP=Payment Pending
     //INP =Invoice Pending
     //PAID= Paid
     //PPCNF=Pending Payment Confirmation
     //PCNF =Payment Confirmation
     //Accept= Accepted Job 
     //Reject = Reject job
     //Start = Start Job
     //Complete = Complete Job

   
//        $bookingdetails=Bookings::where(['user_id'=>$userid,'status'=>$listofstatus[$i]])->get();
        $bookingdetails=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,CONCAT(users.first_name,' ',users.last_name) AS username,CONCAT(provider.first_name,' ',provider.last_name) AS providername,provider.id as provider_id,provider.image,provider.mobile,provider.email,service_sub_category.sub_category_name,service_sub_category.icon,time_slots.timing,bookings.booking_date,'bookings.payment_type',bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,bookings.coupon_applied,bookings.reduced_cost,provider_schedules.days,bookings.job_start_time,bookings.job_end_time,bookings.cost,user_address.doorno,user_address.landmark,user_address.address_line_1,bookings.rating,bookings.service_category_id AS categoryId,bookings.status,bookings.worked_mins,bookings.feedback,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.worked_mins,bookings.created_at,bookings.updated_at"))
                       ->join('user_address', 'bookings.address_id', '=', 'user_address.id')
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                       
                        ->where(['bookings.id'=>$bookingid])
                       ->groupBy('bookings.id')
                       ->orderBy('bookings.updated_at', 'desc')
                       ->get();


         
        if($bookingdetails)
        {
            foreach($bookingdetails as $details)
            {
            





            $yesdata['booking_id']=$details->id;
            $yesdata['booking_order_id']=$details->booking_order_id;
            $yesdata['username']=$details->username;
            $yesdata['providername']=$details->providername;

             $yesdata['mobile']=$details->mobile;
              $yesdata['email']=$details->email;
            $yesdata['provider_id']=$details->provider_id;
            $yesdata['sub_category_name']=$details->sub_category_name;
            $yesdata['timing']=$details->timing;
            $yesdata['booking_date']=$details->booking_date;
            $yesdata['days']=$details->days;
            $yesdata['job_start_time']=$details->job_start_time;
            $yesdata['job_end_time']=$details->job_end_time;
            $yesdata['payment_type']=$details->payment_type;
            $yesdata['status']=$details->status;
             $yesdata['worked_mins']=$details->worked_mins;
            $yesdata['doorno']=$details->doorno;
            $yesdata['landmark']=$details->landmark;
            $yesdata['address_line_1']=$details->address_line_1;
            if($details->coupon_applied == null)
            {
              $yesdata['coupon_applied']="";
            }else
            {
              $yesdata['coupon_applied']=$details->coupon_applied;
            }
            if($details->reduced_cost == null)
            {
              $yesdata['off']="";
            }else
            {
              $yesdata['off']=$details->reduced_cost;
            }
            
            if($details->cost == 0)
            {
              $yesdata['cost'] = 50;
            }else
            {
              $yesdata['cost']=$details->cost;//android
            }
            
            $yesdata['tax_name']=$details->tax_name;
            $yesdata['gst_percent']=$details->gst_percent;
            $yesdata['gst_cost']=$details->gst_cost;
            if($details->total_cost == 0){
              $yesdata['total_cost']= 50;
            }
            else{
              $yesdata['total_cost']=$details->total_cost;
            }
            $yesdata['doorno']=$details->doorno;
            $yesdata['landmark']=$details->landmark;
            $yesdata['address_line_1']=$details->address_line_1;
            $yesdata['rating']=$details->rating;
            $yesdata['worked_mins']=$details->worked_mins;
//            $yesdata['created_at']=$details->created_at;
//            $yesdata['updated_at']=$details->updated_at;
            //$yesdata['status']=;
            $newdata[]=$yesdata;
            }
        }
//        $lastdata=$newdata;
//        
//       
//                 $newdata[$listofstatus[$i]]=$yesdata;
//        }else{
////            $yesdata['booking_id']=$bookingdetails['id'];
////            $yesdata['status']="0";
////            $newdata[$listofstatus[$i]]=$yesdata;
//        }
//          
   
     if(isset($newdata))
     {
      $data['status']=$newdata;   
     }else{
         $data['status']=[];
     }
      
//     $new[]=$yesdata;



    
  // $pdf =\PDF::loadView('invoice', $data)->setPaper('a4');





$name =  $bookingid . '_' . date('Y-m-d H:i:s') . '.pdf';


$pdf = \PDF::loadView( 'invoice',$data, compact('invoice'))->save( '/var/www/html/uber_test/public/pdf/'.$name ); 
 


$path='http://18.218.132.174/uber_test/public/pdf/'.$name;
$result=DB::table('bookings')->where(['id'=>$bookingid])->update(['invoicelink'=>$path]);



if($result){

          $newpath=Bookings::select('invoicelink')->where(['id'=>$bookingid])->get();

        $response['error']='false';
        $response['error_message']=trans('lang.success');
        $response['invoicelink']=$newpath;



}else{

 $response['error']='false';
        $response['error_message']=trans('lang.no pdf aviable');


}


echo json_encode($response);


}









public function appsettings(request $request , $locale)
{   
  app()->setLocale($locale);
           $location=Location::get();
        $timeslots=Timeslots::get();
        
        $response['error']='false';
        $response['error_message']=trans('lang.success');
        $response['location']=$location;
        $response['timeslots']=$timeslots;
 if(Auth::guard('api')->check())
 { 

     $userid=Auth::guard('api')->user()->id;


     
     //BLK = Is Blocked
     //DSP = is in Dispute
     //REP=review pending
     //PYP=Payment Pending
     //INP =Invoice Pending
     //PAID= Paid
     //PPCNF=Pending Payment Confirmation
     //PCNF =Payment Confirmation
     //Accept= Accepted Job 
     //Reject = Reject job
     //Start = Start Job
     //Complete = Complete Job

     $listofstatus=array('Blocked','Dispute','Reviewpending','Completedjob','Waitingforpaymentconfirmation');
     for($i=0;$i<=count($listofstatus)-1;$i++)
     {
//        $bookingdetails=Bookings::where(['user_id'=>$userid,'status'=>$listofstatus[$i]])->get();
        $bookingdetails=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,CONCAT(users.first_name,' ',users.last_name) AS username,CONCAT(provider.first_name,' ',provider.last_name) AS providername,provider.id as provider_id,provider.image,service_sub_category.sub_category_name,service_sub_category.icon,time_slots.timing,bookings.booking_date,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,bookings.coupon_applied,bookings.reduced_cost,provider_schedules.days,bookings.job_start_time,bookings.job_end_time,bookings.cost,user_address.doorno,user_address.landmark,user_address.address_line_1,bookings.rating,bookings.service_category_id AS categoryId,bookings.status,bookings.feedback,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.worked_mins,bookings.created_at,bookings.updated_at"))
                       ->join('user_address', 'bookings.address_id', '=', 'user_address.id')
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                       
                       ->where(['bookings.user_id'=>$userid,'bookings.status'=>$listofstatus[$i]])
                       ->groupBy('bookings.id')
                       ->orderBy('bookings.updated_at', 'desc')
                       ->get();


        if($bookingdetails)
        {
            foreach($bookingdetails as $details)
            {
            

            $yesdata['booking_id']=$details->id;
            $yesdata['booking_order_id']=$details->booking_order_id;
            $yesdata['username']=$details->username;
            $yesdata['providername']=$details->providername;
            $yesdata['provider_id']=$details->provider_id;
            $yesdata['sub_category_name']=$details->sub_category_name;
            $yesdata['timing']=$details->timing;
            $yesdata['booking_date']=$details->booking_date;
            $yesdata['days']=$details->days;
            $yesdata['job_start_time']=$details->job_start_time;
            $yesdata['job_end_time']=$details->job_end_time;
          
            if($details->coupon_applied == null)
            {
              $yesdata['coupon_applied']="";
            }else
            {
              $yesdata['coupon_applied']=$details->coupon_applied;
            }
            if($details->reduced_cost == null)
            {
              $yesdata['off']="";
            }else
            {
              $yesdata['off']=$details->reduced_cost;
            }
            
            if($details->cost == 0)
            {
              $yesdata['cost'] = 50;
            }else
            {
              $yesdata['cost']=$details->cost;//android
            }
            
            $yesdata['tax_name']=$details->tax_name;
            $yesdata['gst_percent']=$details->gst_percent;
            $yesdata['gst_cost']=$details->gst_cost;
            if($details->total_cost == 0){
              $yesdata['total_cost']= 50;
            }
            else{
              $yesdata['total_cost']=$details->total_cost;
            }
            $yesdata['doorno']=$details->doorno;
            $yesdata['landmark']=$details->landmark;
            $yesdata['address_line_1']=$details->address_line_1;
            $yesdata['rating']=$details->rating;
            $yesdata['worked_mins']=$details->worked_mins;
//            $yesdata['created_at']=$details->created_at;
//            $yesdata['updated_at']=$details->updated_at;
            $yesdata['status']=$listofstatus[$i];

            Bookings::where(['id'=> $yesdata['booking_id']])->update(['total_cost'=> $yesdata['total_cost']]);
                 
                  $query_data = DB::table('tax_calculation')
                        ->where('booking_id', '=', $yesdata['booking_id'])
                        //->orWhere('ac_customer_id', '=', 13)
                        ->get();

                        
                     $yesdata['alltax']=array();
              foreach ($query_data as $tax) {

                      $taxs['taxname']= $tax->tax_name;
                        $taxs['tax_amount']= $tax->tax_amount;
                          $taxs['tax_totalamount']= $tax->tax_total_amount;
                          $total[]=$tax->tax_total_amount;
                      array_push($yesdata['alltax'],$taxs);
                     }



            $newdata[]=$yesdata;
            }
        }
//        $lastdata=$newdata;
//        
//       
//                 $newdata[$listofstatus[$i]]=$yesdata;
//        }else{
////            $yesdata['booking_id']=$bookingdetails['id'];
////            $yesdata['status']="0";
////            $newdata[$listofstatus[$i]]=$yesdata;
//        }
//          
     }
     if(isset($newdata))
     {


     	$wallet=DB::table('Walletusers')->where(['userid'=>$userid])->get();



      $response['status']=$newdata; 
      $response['wallet']=  $wallet;
     }else{
         $response['status']=[];
     }
      
//     $new[]=$yesdata;
    
 }
        echo json_encode($response);
}


public function listprovider(request $request , $locale)
        {
          app()->setLocale($locale);
    
if($request->service_sub_category_id && $request->time_slot_id && $request->date && $request->city && $request->lat && $request->lon)
{
 $subcategoryid=$request->service_sub_category_id;   
 $slotid=$request->time_slot_id;   
 $latitude=$request->lat;

 $longitude=$request->lon;   
 $city=$request->city;

$radius_data=DB::table('radius')->where('id','1')->first();

$radius=$radius_data->radius;



// $providerid=$request->provider_id;   
 $date=$request->date;
 $day=date('D', strtotime($date));

 // $day=$date;
 $allprovider=DB::select(DB::raw("select distinct(provider.id),CONCAT(first_name,' ',last_name) as name,email,image,mobile,latitude,longitude,about, (
      6371 * acos (
      cos ( radians('$latitude') )
      * cos( radians( provider.latitude ) )
      * cos( radians( provider.longitude ) - radians('$longitude') )
      + sin ( radians('$latitude') )
      * sin( radians( provider.latitude ) )
    )
) AS distance,addressline1,addressline2,city,state,zipcode from provider Inner join provider_schedules on provider_schedules.provider_id=provider.id Inner join provider_services on provider_services.provider_id=provider.id where provider_schedules.time_slots_id='$slotid' and provider_schedules.days='$day' and provider_schedules.status='1'and provider_services.service_sub_category_id='$subcategoryid' and provider.status = 'active' and provider.id Having distance < '$radius' ORDER by distance asc"));










// change by ram
 // $allprovider=DB::select(DB::raw("select distinct(provider.id),CONCAT(first_name,' ',last_name) as name,email,image,mobile,latitude,longitude,about, (
//       6371 * acos (
//       cos ( radians('$latitude') )
//       * cos( radians( provider.latitude ) )
//       * cos( radians( provider.longitude ) - radians('$longitude') )
//       + sin ( radians('$latitude') )
//       * sin( radians( provider.latitude ) )
//     )
// ) AS distance,addressline1,addressline2,city,state,zipcode from provider Inner join provider_schedules on provider_schedules.provider_id=provider.id Inner join provider_services on provider_services.provider_id=provider.id where provider_schedules.time_slots_id='$slotid' and provider.status = 'active' and provider_schedules.days='$day' and provider_schedules.status='1' and provider_services.service_sub_category_id='$subcategoryid' and provider.id Having distance < '$radius' ORDER by distance asc"));






// $allprovider=DB::select(DB::raw("select distinct(provider.id),CONCAT(first_name,' ',last_name) as name,email,image,mobile,latitude,longitude,about, (
//       6371 * acos (
//       cos ( radians('$latitude') )
//       * cos( radians( provider.latitude ) )
//       * cos( radians( provider.longitude ) - radians('$longitude') )
//       + sin ( radians('$latitude') )
//       * sin( radians( provider.latitude ) )
//     )
// ) AS distance from provider Inner join provider_schedules on provider_schedules.provider_id=provider.id Inner join provider_services on provider_services.provider_id=provider.id where provider_schedules.time_slots_id='$slotid' and provider_schedules.days='$day' and provider_services.service_sub_category_id='$subcategoryid' and provider.city='$city' and provider.id NOT IN (select distinct(provider.id) from provider inner join bookings on bookings.provider_id=provider.id inner join provider_schedules on provider_schedules.provider_id=provider.id where provider_schedules.time_slots_id='$slotid' and provider_schedules.days='$day' and bookings.service_category_type_id='$subcategoryid' and bookings.booking_date='$date' and provider.city='$city') ORDER BY distance ASC"));
 
// $allprovider = DB::select(DB::raw("select CONCAT(first_name,' ',last_name) as name,email,mobile,latitude,longitude from provider where provider.id NOT IN (select distinct(provider.id) from provider inner join bookings on bookings.provider_id=provider.id inner join provider_schedules on provider_schedules.provider_id=provider.id where provider_schedules.time_slots_id='$slotid' and provider_schedules.days='$day' and bookings.service_category_type_id='$subcategoryid' and bookings.status='Accepted' and bookings.booking_date='$date')"));
// $allprovider = DB::select(DB::raw("select distinct(provider.id),CONCAT(provider.first_name,' ',provider.last_name) as name,provider.email,provider.mobile,provider.latitude,provider.longitude, (
//      6371 * acos (
//      cos ( radians($latitude) )
//      * cos( radians( provider.latitude ) )
//      * cos( radians( provider.longitude ) - radians($longitude) )
//      + sin ( radians($longitude) )
//      * sin( radians( provider.latitude ) )
//    )
//) AS distance from provider Inner join provider_schedules on provider_schedules.id where provider.id NOT IN (select distinct(provider.id) from provider inner join bookings on bookings.provider_id=provider.id inner join provider_schedules on provider_schedules.provider_id=provider.id where provider_schedules.time_slots_id='$slotid' and provider_schedules.days='$day' and bookings.service_category_type_id='$subcategoryid' and bookings.booking_date='$date' and provider.city='$city')"));
//// echo json_encode($allprovider); die;

 
 
 foreach($allprovider as $newprovider)
 {



    $services_details=Providerservices::where(['provider_id'=>$newprovider->id,'service_sub_category_id'=>$subcategoryid])->first();    
    $provider_reviews=Providerreviews::select(DB::raw("CONCAT(provider.first_name,' ',provider.last_name) as providername,CONCAT(users.first_name,' ',users.last_name) as username,provider_reviews.feedback,provider_reviews.rating"))->leftjoin('provider','provider.id','=','provider_reviews.provider_id')->leftjoin('users','users.id','=','provider_reviews.user_id')->where(['provider_id'=>$newprovider->id])->get();
    $sumrating=Providerreviews::where(['provider_id'=>$newprovider->id])->sum('rating');
    $reviewcount=count($provider_reviews);


     if($sumrating == '0')
     {
      $provider_rating=0;   
     }else{

      

      if($reviewcount == '0'){

        $reviewcount=1;

      }



     $provider_rating=$sumrating/$reviewcount;    

     }

     $provider_services=Providerservices::select('provider_services.id','service_sub_category.sub_category_name','provider_services.quickpitch','provider_services.priceperhour','provider_services.experience')->join('service_sub_category','service_sub_category.id','=','provider_services.service_sub_category_id')->where(['provider_id'=>$newprovider->id])->get();
     // $newprovider->distance=floatval($newprovider->distance);
     
     
      
     
    $newprovider->quickpitch=$services_details['quickpitch'];
    $newprovider->priceperhour=$services_details['priceperhour'];
    $newprovider->experience=$services_details['experience'];
$newprovider->avg_rating=round($provider_rating,2);    
    $newprovider->reviews=$provider_reviews;
    $newprovider->distance=round($newprovider->distance,2); 
    $newprovider->provider_services=$provider_services;   
        
 }
// die;
// die;
 if($allprovider)
 {
     $response['error']='false';
     $response['error_message']=trans('lang.success');
     $response['all_providers']=$allprovider;
 }
 else
  {
     $response['error']='true';
     $response['error_message']=trans('lang.No Providers Available');
     $response['all_providers']=[];
 }
 
}else{
    $response['error']='true';
    $response['error_message']=trans('lang.mandatory params are empty');
}
echo json_encode($response);
        }
public function newbooking(request $request , $locale)
        {
          app()->setLocale($locale);

    if($request->time_slot_id && $request->provider_id && $request->date && $request->service_sub_category_id && $request->address_id)
    {
        $userid=Auth::guard('api')->user()->id;
        $timeslot=$request->time_slot_id;
        $providerid=$request->provider_id;
        $date=$request->date;
        $address_id=$request->address_id;
        $subcategoryid=$request->service_sub_category_id;
        $categoryid=Subcategory::where('id',$subcategoryid)->value('category_id');
        $day=date('D', strtotime($date));
        $provider_details=Provider::where('id',$providerid)->first();
        $provider_schedule_id=Providerschedules::where(['time_slots_id'=>$timeslot,'provider_id'=>$providerid,'days'=>$day])->value('id');
        $booking_order_id="UX".mt_rand(1000000, 9999999);

        $provider_status=Bookings::select('status')->where(['provider_schedules_id'=>$provider_schedule_id,'provider_id'=>$providerid,'booking_date'=>$date])->first();
        $sta = $provider_status['status'];
        if($sta == 'Accepted' || $sta == 'StarttoCustomerPlace' || $sta == 'Startedjob')
        {
          $response['error']='true';
            $response['error_message']=trans('lang.unable to book provider is busy at that time');
            echo json_encode($response);
          die;
        }else
        {
          // echo "booking made";
          // die;
        try {
        $newbooking= new Bookings();
        $newbooking->user_id=$userid;
        $newbooking->provider_id=$providerid;
        $newbooking->service_category_id=$categoryid;
        $newbooking->service_category_type_id=$subcategoryid;
        $newbooking->provider_schedules_id=$provider_schedule_id;
        $newbooking->booking_date=$date;
        $newbooking->address_id=$address_id;
        $newbooking->booking_order_id=$booking_order_id;
        $newbooking->status="Pending";
        $newbooking->Pending_time=date('Y-m-d H:i:s');
        $newbooking->save();
           
        } catch (\Illuminate\Database\QueryException  $ex) {
            $jsonresp=$ex->getMessage();
                               $response['error']='true';
                               $response['message']=trans('lang.Database Exception Error');
                               echo json_encode($response); 
                               die;
        }
        
   
        if($newbooking)
        {
          $service_name=Subcategory::where('id',$subcategoryid)->value('sub_category_name'); 

          $gcpm = new FCMPushNotification();
          $title = trans('lang.New Booking Request');
          $message =trans('lang.You have a new booking request for'). $service_name;
          // echo $message;
          $os=$provider_details['os_type'];
          $data = array('image' => "NULL",
                 'title' => $title,'notification_type'=>'newbooking');
          $gcpm->setDevices($provider_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KWAfy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
          $newresponse[] = $gcpm->send($message, $data,$os,$title, $message);



          // $gcpm = new FCMPushNotification();
          // $title = "Moip Account password link";
          // $message =$provider_details['moippaslink'];
          // $os=$provider_details['os_type'];
          // $data = array('image' => "NULL",
          //        'title' => $title,'notification_type'=>'link');
          // $gcpm->setDevices($provider_details['fcm_token']);
          // // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KWAfy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
          // $newresponse[] = $gcpm->send($message, $data,$os,$title, $message);




            $response['error']='false';
            $response['error_message']=trans('lang.Booked Successfully');
        }else{
            $response['error']='true';
            $response['error_message']=trans('lang.unable to book.');
        }
        
  }
    }else{
            $response['error']='true';
            $response['error_message']=trans('lang.Mandatory Parameters are missing');
    }
            echo json_encode($response);
}

public function view_bookings(request $request , $locale)
{
  app()->setLocale($locale);
 $userid=Auth::guard('api')->user()->id;
 
 $all_bookings=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,users.mobile as user_mobile,provider.mobile as provider_mobile,CONCAT(users.first_name,' ',users.last_name) AS username,CONCAT(provider.first_name,' ',provider.last_name) AS providername,provider.image,provider.id as provider_id,provider.latitude as provider_latitude,provider.longitude as provider_longitude,provider.Bearing as provider_bearing,service_sub_category.sub_category_name,time_slots.timing,bookings.booking_date,provider_schedules.days,bookings.Pending_time,bookings.Accepted_time,bookings.Rejected_time,bookings.Finished_time,bookings.startjob_timestamp,bookings.endjob_timestamp,bookings.CancelledbyUser_time,bookings.CancelledbyProvider_time,bookings.StarttoCustomerPlace_time,bookings.job_start_time,bookings.job_end_time,bookings.cost,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,service_sub_category.icon,user_address.doorno,user_address.landmark,user_address.address_line_1,user_address.latitude as boooking_latitude ,user_address.longitude as booking_longitude,bookings.rating,bookings.status,bookings.feedback,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.worked_mins,bookings.created_at,bookings.updated_at"))
                       ->join('user_address', 'bookings.address_id', '=', 'user_address.id')
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                       ->where('bookings.user_id',$userid)
                       ->groupBy('bookings.id')
                       ->orderBy('bookings.updated_at', 'desc')
                       ->get();

if($all_bookings)
{
    $response['error']='false';
    $response['error_message']=trans('lang.success');
    $response['all_bookings']=$all_bookings;
}else{
    
    $response['error']='true';
    $response['error_message']=trans('lang.No Bookings');
    $response['all_bookings']=[];
}
 echo json_encode($response);
}






public function getproviderlocation(request $request , $locale)
{
  app()->setLocale($locale);
    if($request->provider_id){
        $providerid=$request->provider_id;
        $provider_location=Provider::select('latitude','longitude','Bearing')->where('id',$providerid)->first();
        if($provider_location)
        {
            $response['error']='false';
            $response['error_message']=trans('lang.success');
            $response['latitude']=$provider_location['latitude'];
            $response['longitude']=$provider_location['longitude'];
            $response['Bearer']=$provider_location['Bearer'];
        }else{
            $response['error']='true';
            $response['error_message']=trans('lang.latitude & longitude empty');
        }
    }else{
        $response['error']='true';
            $response['error_message']=trans('lang.invalid provider_id');
    }
    echo json_encode($response);
}

public function cancel_request(request $request , $locale){
  app()->setLocale($locale);
   $userid=Auth::guard('api')->user()->id;
  $booking_request_id=$request->id;
  $rejecttime=date('Y-m-d H:i:s');
  $random_update_status=DB::table('provider_request')->where(['booking_id'=>$booking_request_id])->delete();
  $cancel_booking=DB::table('bookings')->where(['id'=>$booking_request_id])->delete();
  $response['error']='false';   
  $response['error_message']=trans('lang.Random Request Rejected');   
  
  echo json_encode($response);
}
public function paidstatus(request $request , $locale)
{
  app()->setLocale($locale);
              if($request->id)
            {
                $booking_request_id=$request->id;
                $accept=Bookings::where('id',$booking_request_id)->update(['status'=>'Paid']);
                if($accept){
                    $response['error']='false';
                    $response['error_message']=trans('lang.Updated.');
                }else{
                    $response['error']='true';
                    $response['error_message']=trans('lang.Not Updated.');
                }
            }else{
                $response['error']='true';
                    $response['error_message']=trans('lang.Invalid Booking id.');
            }
            echo json_encode($response);    
}

public function review_feedback(request $request , $locale)
{
  app()->setLocale($locale);
  if($request->id && $request->rating)
  {
      $providerid=$request->id;
      $userid=Auth::guard('api')->user()->id;
     $rating =$request->rating;
     $booking_id =$request->booking_id;
     $reviewinsert=new Providerreviews();
     $reviewinsert->provider_id=$providerid;
     $reviewinsert->user_id=$userid;
     if($request->feedback)
     {
         $feedback=$request->feedback;
     $reviewinsert->feedback=$feedback;    
     }
     $reviewinsert->rating=$rating;
     $reviewinsert->booking_id=$booking_id;
     $reviewinsert->save();
     
     if($reviewinsert)
     {
       Bookings::where('id',$booking_id)->update(['status'=>'Finished']);  
      $response['error']='false';   
      $response['error_message']=trans('lang.review Inserted');   
  }else{
      $response['error']='true';   
      $response['error_message']=trans('lang.review not updated.');   
      
  }
    
}else{
      $response['error']='true';   
      $response['error_message']=trans('lang.Review is empty.');   
}
echo json_encode($response);
}
public function payment_method(request $request , $locale)
{
  app()->setLocale($locale);
    if($request->method && $request->id)
    {
       $user_name=Auth::guard('api')->user()->first_name; 
       $method=$request->method;
       $bookingid=$request->id;
       if($method == 'cash')
       {
          Bookings::where('id',$bookingid)->update(['status'=>'Waitingforpaymentconfirmation','provider_owe_status'=>'completed','admin_owe_status'=>'pending','payment_type'=>'cash']);  

          $provider_id=Bookings::where('id',$bookingid)->value('provider_id');
          $provider_details=Provider::where('id',$provider_id)->first();
          // $provider_name=$provider_details['first_name'];
          $response['error']='false';   
          $response['error_message']='$provider_name';
          $gcpm = new FCMPushNotification();
          $title = trans('lang.Confirm Payment.');
          $message =$user_name .' '. trans('lang.has paid through cash.');
          $os=$provider_details['os_type'];
          $data = array('image' => "NULL",
                 'title' => $title,'notification_type'=>'Waitingforpaymentconfirmation');
          $gcpm->setDevices($provider_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KWAfy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
          $newresponse[] = $gcpm->send($message, $data,$os,$title, $message);   
       }else if($method == 'paypal')
       {
        $paypal_referenceId=$request->reference_id;
          Bookings::where('id',$bookingid)->update(['status'=>'Reviewpending','provider_owe_status'=>'pending','admin_owe_status'=>'completed','payment_type'=>'paypal','paypalreferenceid'=>$paypal_referenceId]);  

          $provider_id=Bookings::where('id',$bookingid)->value('provider_id');
          $provider_details=Provider::where('id',$provider_id)->first();
          // $provider_name=$provider_details['first_name'];
          $response['error']='false';   
          $response['error_message']='$provider_name';
          $gcpm = new FCMPushNotification();
          $title = trans('lang.Confirm Payment.');
          $message =$user_name .' '. trans('lang.has paid through paypal.');
          $os=$provider_details['os_type'];
          $data = array('image' => "NULL",
                 'title' => $title,'notification_type'=>'Reviewpending');
          $gcpm->setDevices($provider_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KWAfy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
          $newresponse[] = $gcpm->send($message, $data,$os,$title, $message);   
       }else{
        $response['error']='true';   
        $response['error_message']=trans('lang.invalid payment method');   
           
       }
    }else{
        $response['error']='true';   
        $response['error_message']=trans('lang.invalid bookingid');   
      
    }
    echo json_encode($response);
}

     public function cancelbyuser(request $request , $locale){
      app()->setLocale($locale);
            if($request->id)
            {
                $booking_request_id=$request->id;
                
                // $status=Bookings::where(['id'=>$booking_request_id])->value('status');
                // if($status == 'Accepted')
                // {

                $canceldate=date('Y-m-d H:i:s');
                $accept=Bookings::where('id',$booking_request_id)->update(['status'=>'CancelledbyUser','CancelledbyUser_time'=>$canceldate]);
                if($accept){
                    $response['error']='false';
                    $response['error_message']=trans('lang.Updated.');
                   
          $provider_id=Bookings::where('id',$booking_request_id)->value('provider_id');
          $provider_details=Provider::where('id',$provider_id)->first();
          $user_name=Auth::guard('api')->user()->first_name;
          $response['error']='false';   
          $response['error_message']=trans('lang.updated.');
          $gcpm = new FCMPushNotification();
          $title = trans('lang.Booking Cancelled.');
          $message =$user_name .' '. trans('lang.has Cancelled your booking.');
          $os=$provider_details['os_type'];
          $data = array('image' => "NULL",
                 'title' => $title,
                 'notification_type'=>'cancelbooking');
          $gcpm->setDevices($provider_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KWAfy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
          $newresponse[] = $gcpm->send($message, $data,$os,$title, $message); 
                }else{
                    $response['error']='true';
                    $response['error_message']=trans('lang.Not Updated.');
                }   
            }else{
                $response['error']='true';
                    $response['error_message']=trans('lang.Invalid Booking id.');
            }
            echo json_encode($response);
        }

public function list_payment_methods(request $request , $locale){
  app()->setLocale($locale);
  $payment_type=DB::table('payments_type')->get();
  if($payment_type)
  {
   $response['error']='false';
   $response['error_message']=trans('lang.success');
   $response['payment_types']=$payment_type;
  }else{
   $response['error']='true';
   $response['error_message']=trans('lang.No Payment');
 
  }
  echo json_encode($response);
}

public function logout(request $request , $locale)
{
  app()->setLocale($locale);
    $userid=Auth::guard('api')->user()->id;
    $update=User::where('id',$userid)->update(['fcm_token'=>" "]);
    
    if($update)
    {
        $response['error']='false';
        $response['error_message']=trans('lang.Logout Successfully.');
    }else{
        $response['error']='true';
        $response['error_message']=trans('lang.not logged out');
    }
    echo json_encode($response);
}

    
      public function ephemeral_keys(request $request , $locale){
        app()->setLocale($locale);
        if($request->api_version)
        {
            try {
                $userid=Auth::guard('api')->user()->id;
            $customer_id=Auth::guard('api')->user()->stripe_payment_account;
                //  $stripe = Stripe::make('DUMMY_KEY');
                // $setkey=\Stripe\Stripe::setApiKey('DUMMY_KEY');
               $key = \Stripe\EphemeralKey::create(
      array("customer" => $customer_id),
      array("stripe_version" => $request->api_version)
    );
    
    echo json_encode($key);
            }catch (Exception $e) {
    exit(http_response_code(500));
}
            
        }
    }

    public function postPaymentWithStripe(Request $request , $locale)
    {
      app()->setLocale($locale);
         $username=Auth::guard('api')->user()->firstname;
         $customer_id=Auth::guard('api')->user()->stripe_payment_account;
        $input = $request->all();
        $token=$request->token;
        $paypal_referenceId=$request->reference_id;
        $booking_id=$request->id;
        $booking_details=Bookings::where(['id'=>$booking_id])->first();
       if($request->id)
       {

         $provider_id=$booking_details->provider_id;
         $provider_details=Provider::where('id',$provider_id)->first();
     

      $amount=$request->amount;
    
       

        $converted_amount=$booking_details->total_cost/4.7;
       


           if($converted_amount > 0)
           {
            $amount=$converted_amount;   
           }else{
             $amount=50; //ste default amount
           }
           Bookings::where(['id'=>$booking_id])->update(['total_cost'=>$amount,'paypalreferenceid'=>$paypal_referenceId]);

        // if ($validator->passes()) {           
            // $input = array_except($input,array('_token'));            
            // $stripe = Stripe::make('DUMMY_KEY');


            try {

                // $token = $stripe->tokens()->create([
                //     'card' => [
                //         'number'    => $request->get('card_no'),
                //         'exp_month' => $request->get('ccExpiryMonth'),
                //         'exp_year'  => $request->get('ccExpiryYear'),
                //         'cvc'       => $request->get('cvvNumber'),
                //     ],
                // ]);
                // if ($token) {
                //     // \Session::put('error','The Stripe Token was not generated correctly');
                //     // return redirect()->route('stripform');
                // }


                $charge = $stripe->charges()->create([
                    'source' => $token,
                    'customer' => $customer_id,
                    'currency' => 'USD',
                    'amount'   => $amount,
                    'description' => 'Add in wallet',
                ]);

               // echo $charge;
              // echo "inside";
              // die;
                if($charge['status'] == 'succeeded') {
                    /**
                    * Write Here Your Database insert logic.
                    */
                    Bookings::where(['id'=>$booking_id,'provider_id'=>$provider_id])->update(['status'=>'Reviewpending','provider_owe_status'=>'pending','admin_owe_status'=>'completed','payment_type'=>'paypal']);
                    $response['error']='false';
                    $response['error_message']=trans('lang.success');
                    $response['order_details']=$charge;
                    $gcpm = new FCMPushNotification();
                      $title = trans('lang.Payment Completed');
                      $message =$username . trans('lang.have paid for your service');
                      $os=$provider_details['os_type'];
                      $data = array('image' => "NULL",
                 'title' => $title,'notification_type'=>'payment');
                      $gcpm->setDevices($provider_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KW            Afy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
                      $newresponse[] = $gcpm->send($message, $data,$os,$title, $message);
                    // echo 'payment successfull';
                    // return redirect()->route('stripform');
                } else {
                    $response['error']='true';
                    $response['error_message']=trans('lang.Money not added');
                }
            } catch (Exception $e) {
                $response['error']='true';
                $response['error_message']=$e->getMessage();
                // return redirect()->route('stripform');
            } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
                
                $response['error']='true';
                $response['error_message']=$e->getMessage();
                // return redirect()->route('stripform');
            } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                $response['error']='true';
                $response['error_message']=$e->getMessage();
                // return redirect()->route('stripform');
            }
          }else{
             $response['error']='true';
                $response['error_message']=trans('lang.Invalid Params');
          }
      
        // }
            echo json_encode($response);    

    }    









public function  viewbalance(request $request , $locale){

app()->setLocale($locale);
$userid=Auth::guard('api')->user()->id;

$walletusercheck=Walletusers::where('userid','=',$userid)->get();


if($walletusercheck->isEmpty()){

  $response['error']='true';
  $response['error_message']=trans('lang.This user is not  wallet user');


}else{


  $response['error']='false';
  $response['error_message']=trans('lang.successfully show the balance');
   $response['walletdetails']=$walletusercheck;



}

echo json_encode($response);


}






























  
       
        public function charge(Request $request , $locale)
    {
      app()->setLocale($locale);
         $username=Auth::guard('api')->user()->firstname;
         $customer_id=Auth::guard('api')->user()->stripe_payment_account;
        $input = $request->all();
        $token=$request->token;
        $booking_id=$request->id;
        $booking_details=Bookings::where(['id'=>$booking_id])->first();
       if($request->id && $request->amount)
       {
         $provider_id=$booking_details->provider_id;
         $provider_details=Provider::where('id',$provider_id)->first();
       $amount=$request->amount;

        // if ($validator->passes()) {           
            // $input = array_except($input,array('_token'));            
            // $stripe = Stripe::make('DUMMY_KEY');
            try {
                // $token = $stripe->tokens()->create([
                //     'card' => [
                //         'number'    => $request->get('card_no'),
                //         'exp_month' => $request->get('ccExpiryMonth'),
                //         'exp_year'  => $request->get('ccExpiryYear'),
                //         'cvc'       => $request->get('cvvNumber'),
                //     ],
                // ]);
                // if ($token) {
                //     // \Session::put('error','The Stripe Token was not generated correctly');
                //     // return redirect()->route('stripform');
                // }
                $charge = $stripe->charges()->create([
                    'source' => $token,
                    'customer' => $customer_id,
                    'currency' => 'USD',
                    'amount'   => $amount,
                    'description' => 'Add in wallet',
                ]);
                if($charge['status'] == 'succeeded') {
                    /**
                    * Write Here Your Database insert logic.
                    */
                    Bookings::where(['id'=>$booking_id,'provider_id'=>$provider_id])->update(['status'=>'Reviewpending']);
//                    $response['error']='false';
//                    $response['error_message']='success';
//                    $response['order_details']=$charge;
              
                    $gcpm = new FCMPushNotification();
                      $title = trans('lang.Payment Completed');
                      $message =$username . trans('lang.have paid for your service');
                      $os=$provider_details['os_type'];
                      $data = array('image' => "NULL",
                 'title' => $title,'notification_type'=>'payment');
                      $gcpm->setDevices($provider_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KW            Afy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
                      $newresponse[] = $gcpm->send($message, $data,$os,$title, $message);
                    // echo 'payment successfull';
                    // return redirect()->route('stripform');
                     
                } else {
                    $response['error']='true';
                    $response['error_message']=trans('lang.Money not added');
                }
                 echo json_encode($charge); die;
            } catch (Exception $e) {
                echo http_response_code(500); die;
                // return redirect()->route('stripform');
            }
          }else{
             echo http_response_code(500); die;
          }
      
        // }
           

    }

public function fcmtest(){
  $gcpm = new FCMPushNotification();
          $title = "New Booking Request";
          $message ="Request";
          $os='iOS';
          $data = array('image' => "NULL",
                 'title' => $title);
          // $gcpm->setDevices($devicetoken);
          $gcpm->setDevices("cneUKeW-eQQ:APA91bF2-1MsroxLIlS9jZ314OPGyt04sNEt66Pys0cHj2rpdMhonoD96YhNMO-c4LPH-l2dqZfKF7Rh6u9_J_RfkiA9aKPS8ASLB4swnoyq6QQx2GGs-etmTFJQzJymL96sLo_xUO8g");
          $newresponse[] = $gcpm->send($message, $data,$os,"zampii", $message);
          echo json_encode($newresponse);
}








public function listprovidertest(request $request , $locale)

        {
          app()->setLocale($locale);
    
if($request->service_sub_category_id && $request->time_slot_id && $request->date && $request->city && $request->lat && $request->lon)
{
 $subcategoryid=$request->service_sub_category_id;   
 $slotid=$request->time_slot_id;   
 $latitude=$request->lat;

 $longitude=$request->lon;   
 $city=$request->city;

$radius_data=DB::table('radius')->where('id','1')->first();

$radius=$radius_data->radius;

// $providerid=$request->provider_id;   
 $date=$request->date;
 $day=date('D', strtotime($date));
 $allprovider=DB::select(DB::raw("select distinct(provider.id),CONCAT(first_name,' ',last_name) as name,email,image,mobile,latitude,longitude,about, (
      6371 * acos (
      cos ( radians('$latitude') )
      * cos( radians( provider.latitude ) )
      * cos( radians( provider.longitude ) - radians('$longitude') )
      + sin ( radians('$latitude') )
      * sin( radians( provider.latitude ) )
    )
) AS distance,addressline1,addressline2,city,state,zipcode,premium from provider Inner join provider_schedules on provider_schedules.provider_id=provider.id Inner join provider_services on provider_services.provider_id=provider.id where provider_schedules.time_slots_id='$slotid' and provider_schedules.days='$day' and provider_services.service_sub_category_id='$subcategoryid' and provider.id Having distance < '$radius' ORDER by distance asc LIMIT 12"));

//echo json_encode($allprovider);die;
 foreach($allprovider as $newprovider)
 {
    $services_details=Providerservices::where(['provider_id'=>$newprovider->id,'service_sub_category_id'=>$subcategoryid])->first();    
    $provider_reviews=Providerreviews::select(DB::raw("CONCAT(provider.first_name,' ',provider.last_name) as providername,CONCAT(users.first_name,' ',users.last_name) as username,provider_reviews.feedback,provider_reviews.rating"))->join('provider','provider.id','=','provider_reviews.provider_id')->join('users','users.id','=','provider_reviews.user_id')->where(['provider_id'=>$newprovider->id])->get();
    $sumrating=Providerreviews::where(['provider_id'=>$newprovider->id])->sum('rating');
    $reviewcount=count($provider_reviews);
     if($sumrating == '0')
     {
      $provider_rating=0;   
     }else{
     $provider_rating=$sumrating/$reviewcount;    
     }

     $provider_services=Providerservices::select('provider_services.id','service_sub_category.sub_category_name','provider_services.quickpitch','provider_services.priceperhour','provider_services.experience')->join('service_sub_category','service_sub_category.id','=','provider_services.service_sub_category_id')->where(['provider_id'=>$newprovider->id])->get();
     // $newprovider->distance=floatval($newprovider->distance);
     
     
      
     
    $newprovider->quickpitch=$services_details['quickpitch'];
    $newprovider->priceperhour=$services_details['priceperhour'];
    $newprovider->experience=$services_details['experience'];
$newprovider->avg_rating=$provider_rating;    
    $newprovider->reviews=$provider_reviews;
    $newprovider->distance=round($newprovider->distance,2); 
    $newprovider->provider_services=$provider_services;   
        
 }
 
 $newlist  = json_encode($allprovider);

       $sortarray =  json_decode($newlist);
	    usort($sortarray,array("App\Http\Controllers\UserController", "premium"));
	  
 if($sortarray)
 {
     $response['error']='false';
     $response['error_message']=trans('lang.success');
     $response['all_providers']=$sortarray;
 }
 else
  {
     $response['error']='true';
     $response['error_message']=trans('lang.No Providers Available');
     $response['all_providers']=[];
 }
 
}else{
    $response['error']='true';
    $response['error_message']=trans('lang.mandatory params are empty');
}
echo json_encode($response);
        }
		
		
  
public function premium($a, $b){
		
    if ($a->premium == $b->premium) return 0;
    return ($a->premium > $b->premium) ? -1 : 1;
}


public function couponverify(request $request , $locale)
{
app()->setLocale($locale);
    if($request->couponname && $request->booking_order_id)
    {
      $booking_order_id=$request->booking_order_id;
      $customer_time=date('Y-m-d');

      $bookings = Bookings::where('booking_order_id',$booking_order_id)->first();
      $coupon = DB::table('coupons')->where('coupon_code',$request->couponname)->first();
      
      // echo($bookings->coupon_applied);
      if(empty($bookings->coupon_applied))
      {
      // die;

        if($bookings->coupon_applied == $request->couponname)
            { 
              $response['error']='true';
              $response['error_message']=trans('lang.Coupon Code Already Applied');
            }
            else
            { 
            if($coupon)
            {
              if($coupon->valid_from <= $customer_time && $coupon->valid_to >= $customer_time)
              {
                
                if($bookings->total_cost >= $coupon->discount_value)
                {
                  $response['error']='false';
                  $response['error_message']=trans('lang.Coupon Applied');
                  $response['off']=$coupon->discount_value;
                  $reduced = $bookings->total_cost - $coupon->discount_value;
                  $updateamount = Bookings::where(['booking_order_id'=>$booking_order_id])->update(['total_cost'=>$reduced,'coupon_applied'=>$request->couponname,'original_amount'=>$bookings->total_cost,'reduced_cost'=>$coupon->discount_value]);
                  $response['total_cost']= ($reduced);
                }else
                {
                  $response['error']='true';
                  $response['error_message']=trans('lang.Amount is Minimum');
                  $response['amount']= ($bookings->total_cost);
                }
              }else
              {
                $response['error']='true';
                $response['error_message']=trans('lang.Coupon Expired');
              }
            }else{
                $response['error']='true';
                $response['error_message']=trans('lang.Coupon Not Applied');
            }
          }
        }else
        {
          $response['error']='true';
          $response['error_message']=trans('lang.A Coupon Applied Already');
        }
            echo json_encode($response);

  }
}

public function couponremove(request $request , $locale)
{

app()->setLocale($locale);
    if($request->couponname && $request->booking_order_id)
    {
      $booking_order_id=$request->booking_order_id;

      $bookings = Bookings::where('booking_order_id',$booking_order_id)->first();
      $total_cost = $bookings->original_amount;
  if($bookings->coupon_applied == $request->couponname)
      { 
        $updateamount = Bookings::where(['booking_order_id'=>$booking_order_id])->update(['total_cost'=>$total_cost,'coupon_applied'=>NULL,'reduced_cost'=>NULL]);
        if($updateamount)
        {
          $response['error']='false';
          $response['error_message']=trans('lang.Coupon Removed');
        }else
        {
          $response['error']='true';
          $response['error_message']=trans('lang.Coupon Not Removed');
        }
      }
      else
      { 
          $response['error']='true';
          $response['error_message']=trans('lang.Coupon Not Applied');
    }
      echo json_encode($response);
  }
}


public function startjobendjobdetails(request $request , $locale)
{
  app()->setLocale($locale);
    
    if($request->booking_id)
    {   


       $coupon = DB::table('startendjobdetails')->where('booking_id',$request->booking_id)->first();
      
         $response['error']="false";
         $response['error_message']=trans('lang.details fetched'); 
         $response['data']=$coupon; 


            $bookingid=$request->booking_id;

           $location=Location::get();
        $timeslots=Timeslots::get();
        
        $response['error']='false';
        $response['error_message']=trans('lang.success');
        // $response['location']=$location;
        // $response['timeslots']=$timeslots;
 
     //BLK = Is Blocked
     //DSP = is in Dispute
     //REP=review pending
     //PYP=Payment Pending
     //INP =Invoice Pending
     //PAID= Paid
     //PPCNF=Pending Payment Confirmation
     //PCNF =Payment Confirmation
     //Accept= Accepted Job 
     //Reject = Reject job
     //Start = Start Job
     //Complete = Complete Job

   

  $bookingdetails=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,CONCAT(users.first_name,' ',users.last_name) AS username,CONCAT(provider.first_name,' ',provider.last_name) AS providername,provider.id as provider_id,provider.image,provider.mobile,provider.email,service_sub_category.sub_category_name,service_sub_category.icon,time_slots.timing,bookings.booking_date,'bookings.payment_type',bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,bookings.coupon_applied,bookings.reduced_cost,provider_schedules.days,bookings.job_start_time,bookings.job_end_time,bookings.cost,user_address.doorno,user_address.landmark,user_address.address_line_1,bookings.rating,bookings.service_category_id AS categoryId,bookings.status,bookings.worked_mins,bookings.feedback,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.worked_mins,bookings.created_at,bookings.updated_at"))
                       ->join('user_address', 'bookings.address_id', '=', 'user_address.id')
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                       
                        ->where(['bookings.id'=>$bookingid])
                       ->groupBy('bookings.id')
                       ->orderBy('bookings.updated_at', 'desc')
                       ->get();










//      


         
        if($bookingdetails)
        {
            foreach($bookingdetails as $details)
            {
            





           
            $yesdata['booking_id']=$details->id;
            $yesdata['booking_order_id']=$details->booking_order_id;
            $yesdata['username']=$details->username;
            $yesdata['providername']=$details->providername;

             $yesdata['mobile']=$details->mobile;
              $yesdata['email']=$details->email;
            $yesdata['provider_id']=$details->provider_id;
            $yesdata['sub_category_name']=$details->sub_category_name;
            $yesdata['timing']=$details->timing;
            $yesdata['booking_date']=$details->booking_date;
            $yesdata['days']=$details->days;
            $yesdata['job_start_time']=$details->job_start_time;
            $yesdata['job_end_time']=$details->job_end_time;
            $yesdata['payment_type']=$details->payment_type;
            $yesdata['status']=$details->status;
             $yesdata['worked_mins']=$details->worked_mins;
            $yesdata['doorno']=$details->doorno;
            $yesdata['landmark']=$details->landmark;
            $yesdata['address_line_1']=$details->address_line_1;
            if($details->coupon_applied == null)
            {
              $yesdata['coupon_applied']="";
            }else
            {
              $yesdata['coupon_applied']=$details->coupon_applied;
            }
            if($details->reduced_cost == null)
            {
              $yesdata['off']="";
            }else
            {
              $yesdata['off']=$details->reduced_cost;
            }
            
            if($details->cost == 0)
            {
              $yesdata['cost'] = 50;
            }else
            {
              $yesdata['cost']=$details->cost;//android
            }
            
            $yesdata['tax_name']=$details->tax_name;
            $yesdata['gst_percent']=$details->gst_percent;
            $yesdata['gst_cost']=$details->gst_cost;
            if($details->total_cost == 0){
              $yesdata['total_cost']= 50;
            }
            else{
              $yesdata['total_cost']=$details->total_cost;
            }
            $yesdata['doorno']=$details->doorno;
            $yesdata['landmark']=$details->landmark;
            $yesdata['address_line_1']=$details->address_line_1;
            $yesdata['rating']=$details->rating;
            $yesdata['worked_mins']=$details->worked_mins;
//            $yesdata['created_at']=$details->created_at;
//            $yesdata['updated_at']=$details->updated_at;
            //$yesdata['status']=;
            $newdata[]=$yesdata;
            }
        }
//        $lastdata=$newdata;
//        
//       
//                 $newdata[$listofstatus[$i]]=$yesdata;
//        }else{
////            $yesdata['booking_id']=$bookingdetails['id'];
////            $yesdata['status']="0";
////            $newdata[$listofstatus[$i]]=$yesdata;
//        }
//          
   
     if(isset($newdata))
     {
      $data['status']=$newdata;   
     }else{
         $data['status']=[];
     }
      
//     $new[]=$yesdata;



    
  // $pdf =\PDF::loadView('invoice', $data)->setPaper('a4');




$name =  rand(11111, 99999) . '.' . $bookingid. '.pdf';


$pdf = \PDF::loadView( 'invoice',$data, compact('invoice'))->save( '/var/www/html/uber_test/public/pdf/'.$name ); 
 


$path='http://18.218.132.174/uber_test/public/pdf/'.$name;
$result=DB::table('bookings')->where(['id'=>$bookingid])->update(['invoicelink'=>$path]);



if($result){

          $newpath=Bookings::select('invoicelink')->where(['id'=>$bookingid])->get();

        
        $response['invoicelink']=$newpath;



}



     
    }else{
         $response['error']="true";
         $response['error_message']=trans('lang.cannot fetch details'); 
         $response['data']=$coupon; 

    }
    echo json_encode($response);
}


	public function  searchproivder(request $request , $locale){

app()->setLocale($locale);


 $providersearchlist = provider::Select('id','proivder_name')
                                
                    ->orWhere('name', 'like', '%' . $name . '%')
                    ->get();

  }




public function  reportuser(request $request , $locale){
 app()->setLocale($locale);

 if($request->proivder_id && $request->user_id && $request->reportmessage && $request->reportimage){

      $proivder_id=$request->proivder_id;
      $user_id=$request->user_id;
      $reportmessage=$request->reportmessage;
      $reportimage=$request->reportimage;

        try{
          $reports= new Userreports();
          $reports->provider_id=$proivder_id;
          $addprovider->user_id=$user_id;
          $addprovider->reportmessage=$reportmessage;
          $reports->reportimage=$reportimage;
          $reports->save();   
        }catch (\Illuminate\Database\QueryException $ex) {
             $jsonresp=$ex->getMessage();
          $response['error']='true';
          $response['error_message']=trans('lang.Database Exception Error');
          echo json_encode($response); 
          die;
         }
              

         if($reports){

           $response['error']='false';
          $response['error_message']=trans('lang.user report update successfully'); 

         }



       }else{


          $response['error']='true';
          $response['error_message']=trans('lang.Mandatory parameter is missing');


         }


 echo json_encode($response); 



}




public function  addmoneywallet(request $request , $locale){
  app()->setLocale($locale);

 $userid=Auth::guard('api')->user()->id;
 $wallet_amount=$request->amount;
 $customer_id=Auth::guard('api')->user()->stripe_payment_account;
 $token=$request->token;

 
 // $stripe = Stripe::make('DUMMY_KEY');

  try{

        $charge = $stripe->charges()->create([
                    'source' => $token,
                    'customer' => $customer_id,
                    'currency' => 'USD',
                    'amount'   => $wallet_amount,
                    'description' => 'Add in wallet',
                ]);



 if($charge['status'] == 'succeeded') {


$walletusercheck=Walletusers::where('userid','=',$userid)->first();


if($walletusercheck){

$total_amount=$walletusercheck->balance +$wallet_amount;

$updatestatus=DB::table('Walletusers')->where('userid','=',$userid)->update(['balance'=>$total_amount]);

if($updatestatus){

   $response['error']='false';
$response['error_message']=trans('lang.successfully add the money in wallet');

}else{

 $response['error']='false';
$response['error_message']=trans('lang.something went wrong');

}


}else{


try{



     $walletuser=new Walletusers();
     $walletuser->userid=$userid;
     $walletuser->balance=$wallet_amount;
     $walletuser->save();

}catch (\Illuminate\Database\QueryException  $ex) {
                 $response['error']='true';
$response['error_message']=trans('lang.Database Exception Error');
echo json_encode($response); 
die;
             }



if($walletuser){

   $response['error']='false';
$response['error_message']=trans('lang.successfully add the money in wallet');

}else{

 $response['error']='true';
$response['error_message']=trans('lang.something went wrong');

}




}



$response['error']='true';
$response['error_message']=trans('lang.successfully add money Uberdoo wallet');

}else{

 $response['error']='true';
$response['error_message']=trans('lang.Not added in Uberdoo Wallet');



}



  }catch(Exception $e) {
                $response['error']='true';
                $response['error_message']=$e->getMessage();
                // return redirect()->route('stripform');
            } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
                
                $response['error']='true';
                $response['error_message']=$e->getMessage();
                // return redirect()->route('stripform');
            } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                $response['error']='true';
                $response['error_message']=$e->getMessage();
                // return redirect()->route('stripform');
            }





echo json_encode($response); 



}






 public function wallettransction(Request $request , $locale){
app()->setLocale($locale);
          $username=Auth::guard('api')->user()->firstname;
          $customer_id=Auth::guard('api')->user()->stripe_payment_account;
          $userid=Auth::guard('api')->user()->id;
          $input = $request->all();
          $token=$request->token;
          $booking_id=$request->id;
          $walletkey=$request->walletkey;
          $booking_details=Bookings::where(['id'=>$booking_id])->first();

           if($request->id)
 			 {


           
          

         $provider_id=$booking_details->provider_id;
         $provider_details=Provider::where('id',$provider_id)->first();
     
        $providerstripeaccount=Providerstripeaccount::where(['provider_id'=>$provider_id])->first();

         $provider_stripe_account=$providerstripeaccount->stripeaccount_number;

       
         // $amount=$request->amount;
         $provideramount= $booking_details->provider_share;
         $provider_share=round($provideramount);


         if($walletkey == 1){
             	$walletusercheck=Walletusers::where('userid','=',$userid)->first();
              	$walletamount=$walletusercheck->balance;
               	$booking_details=Bookings::where(['id'=>$booking_id])->first();
               	$bookingamount=$booking_details->total_cost;

                $bookingtotalamount=round($bookingamount);

                 $provideramount= $provider_share;
          $adminamount=$bookingtotalamount;




        
           if($provideramount >= 0 && $adminamount >=0)
           {



            $adminamount=1*100;
               $provideramount=5*10;
             
          }else{

             $adminamount=$adminamount*100;
               $provideramount=$provideramount*100;



          }

        
   // $stripe=Stripe::setApiKey("DUMMY_KEY");
   // $stripe = Stripe::make('DUMMY_KEY');

   // echo $provider_stripe_account; die;
               	if($walletamount == $adminamount){
				
           $transfer = $stripe->transfers()->create([
    'amount'    =>$provideramount,
    'currency'  => 'chf',
    'destination' => "{$provider_stripe_account}",
]);

try{

    $results= Wallettransaction::where(['userid'=>$userid])->update(['transactin_amount'=>$adminamount,'bookingid'=>$booking_id]);
    $res=Walletusers::where('userid','=',$userid)->update(['balance'=>0]);

 } catch (\Illuminate\Database\QueryException $ex) {
             $jsonresp=$ex->getMessage();
          $response['error']='true';
          $response['error_message']=trans('lang.Database Exception Error');
          echo json_encode($response); 
          die;
         }               


                  Bookings::where(['id'=>$booking_id,'provider_id'=>$provider_id])->update(['status'=>'Reviewpending','provider_owe_status'=>'pending','admin_owe_status'=>'completed','payment_type'=>'paypal']);
                    $response['error']='false';
                    $response['error_message']=trans('lang.success');
                   
                    $gcpm = new FCMPushNotification();
                      $title = trans('lang.Payment Completed');
                      $message =$username . trans('lang.have paid for your service');
                      $os=$provider_details['os_type'];
                      $data = array('image' => "NULL",
                 'title' => $title,'notification_type'=>'payment');
                      $gcpm->setDevices($provider_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KW            Afy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
                      $newresponse[] = $gcpm->send($message, $data,$os,$title, $message);

                        $response['error']="false";
        $response['error_message']=trans('lang.successfully Finished   wallet payment');

               					}else{


                            // $input = array_except($input,array('_token'));            
         


            try {

                // $token = $stripe->tokens()->create([
                //     'card' => [
                //         'number'    => $request->get('card_no'),
                //         'exp_month' => $request->get('ccExpiryMonth'),
                //         'exp_year'  => $request->get('ccExpiryYear'),
                //         'cvc'       => $request->get('cvvNumber'),
                //     ],
                // ]);
                // if ($token) {
                //     // \Session::put('error','The Stripe Token was not generated correctly');
                //     // return redirect()->route('stripform');
                // }


                $charge = $stripe->charges()->create([
                    'source' => $token,
                    'customer' => $customer_id,
                    'currency' => 'USD',
                    'amount'   => $adminamount,
                    'description' => 'Add in wallet',
                ]);

               // echo $charge;
              // echo "inside";
              // die;
                if($charge['status'] == 'succeeded') {
                    /**
                    * Write Here Your Database insert logic.
                    */

 $transfer = $stripe->transfers()->create([
    'amount'    =>$provideramount,
    'currency'  => 'chf',
    "source_transaction" =>$charge['id'],
    'destination' => "{$provider_stripe_account}",
]);

                  Bookings::where(['id'=>$booking_id,'provider_id'=>$provider_id])->update(['status'=>'Reviewpending','provider_owe_status'=>'pending','admin_owe_status'=>'completed','payment_type'=>'card']);
                    $response['error']='false';
                    $response['error_message']='success';
                    $response['order_details']=$charge;
                    $gcpm = new FCMPushNotification();
                      $title = trans('lang.Payment Completed');
                      $message =$username . trans('lang.have paid for your service');
                      $os=$provider_details['os_type'];
                      $data = array('image' => "NULL",
                 'title' => $title,'notification_type'=>'payment');
                      $gcpm->setDevices($provider_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KW            Afy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
                      $newresponse[] = $gcpm->send($message, $data,$os,$title, $message);
                    // echo 'payment successfull';
                    // return redirect()->route('stripform');
                } else {
                    $response['error']='true';
                    $response['error_message']=trans('lang.Money not added');
                }
            } catch (Exception $e) {
                $response['error']='true';
               
                $response['error_message']=$e->getMessage();
                // return redirect()->route('stripform');
            } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
                
                $response['error']='true';
                $response['error_message']=$e->getMessage();
                // return redirect()->route('stripform');
            } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                $response['error']='true';
                $response['error_message']=$e->getMessage();
                // return redirect()->route('stripform');
            }








                        }
















           				}



           	}






echo json_encode($response);




}








	}
