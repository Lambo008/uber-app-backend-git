<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Provider;
use App\Imageupload;
use App\Useraddress;
use App\FCMPushNotification;
use App\Location;
use App\Bookings;
use App\Timeslots;
use App\Category;
use App\Subcategory;
use App\Providerschedules;
use App\Providerservices;
use App\Servicetax;
use App\Userreviews;
use App\Userreports;
use App\Providerstripeaccount;

use DB;
use Mail;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Stripe\Error\Card;
use Authy;
use DateTime;



class ProviderController extends Controller
{
//     public function provider_signup(request $request)
//     {
//      if($request->first_name && $request->last_name  && $request->email && $request->password && $request->mobile && $request->dob && $request->gender && $request->address1 && $request->address2 && $request->city && $request->state && $request->zipcode && $request->about && $request->workexperience && $request->category)
//      {
//       if($request->image)
//       {
//         $image=$request->image;
//       }else{
//         $image="";
//       }
//          try {
//          // $imgupload=new Imageupload();
//          // $image=$request->file('image');
//          // $image_name=$imgupload->imgupload($image);
//          $addprovider= new Provider();
//          $addprovider->first_name=$request->first_name;
//          $addprovider->last_name=$request->last_name;
//          $addprovider->image=$image;
//          $addprovider->email=$request->email;
//          $addprovider->password=bcrypt($request->password);
//          $addprovider->gender=$request->gender;
//          $addprovider->addressline1=$request->address1;
//          $addprovider->addressline2=$request->address2;
//          $addprovider->city=$request->city;
//          $addprovider->mobile=$request->mobile;
//          $addprovider->dob=$request->dob;
//          $addprovider->state=$request->state;
//          $addprovider->zipcode=$request->zipcode;
//          $addprovider->about=$request->about;
//          $addprovider->workexperience=$request->workexperience;
//          $addprovider->save();    
         
//          } catch (\Illuminate\Database\QueryException $ex) {
//              $jsonresp=$ex->getMessage();
//           $response['error']='true';
//           $response['error_message']="Provider Details Already Exists"; 
//           echo json_encode($response); 
//           die;
//          }
//                        	if($addprovider)
//     	{
                            
//        $provider_categories=$request->category;
      
//        try {
                  
//        $jsoncatarray=json_decode($provider_categories);
       
//        foreach($jsoncatarray as $category)
//    {
//     $check_add_category=Providerservices::where(['provider_id'=>$addprovider->id,'service_category_id'=>$category->category_id,'service_sub_category_id'=>$category->sub_category_id])->get(); 
//     // print_r($check_add_category);

//     // $jsonarray=json_decode($check_add_category);
//     // print_r($jsonarray);
//     if(!$check_add_category->isEmpty())
//     {

      
//        $updateprovider=Providerservices::where(['provider_id'=>$addprovider->id,'service_category_id'=>$category->category_id,'service_sub_category_id'=>$category->sub_category_id])
//        ->update(['provider_id'=>$addprovider->id,'service_category_id'=>$category->category_id,'service_sub_category_id'=>$category->sub_category_id]);
// // print_r($updateprovider);
// // die;
//        // $response['error']='true';
//    // $response['error_message']='Category Already Selected';
//    // echo json_encode($response); 
//    // die;

//     }else{

// //        if($category->status == '1')
// //        {
//         try {
//         $addcategory=new Providerservices();
//         $addcategory->service_category_id=$category->category_id;
//         $addcategory->service_sub_category_id=$category->sub_category_id;
//         $addcategory->provider_id=$addprovider->id;
//         $addcategory->quickpitch=$category->quickpitch;
//         $addcategory->priceperhour=$category->priceperhour;
//         $addcategory->experience=$category->experience;
//         $addcategory->save();   
//          } catch (\Illuminate\Database\QueryException $ex) {
//              $jsonresp=$ex->getMessage();
//           $response['error']='true';
//           $response['error_message']=$jsonresp; 
//           echo json_encode($response); 
//           die;
//          } 
        
// //        }                       
//    }    
//       }
//        } catch (\Exception $ex) {
//               $jsonresp=$ex->getMessage();
//           $response['error']='true';
//           $response['error_message']=$jsonresp; 
//           echo json_encode($response); 
//           die;
//        }
       

       
//        try {

    
//       $newschedule= new Providerschedules();
//       $newschedule->provider_id=$addprovider->id;
//       $newschedule->time_Slots_id= 15;
//       $newschedule->days = "Everyday";
//       $newschedule->status= 1;
//       $newschedule->save();
//      } catch (\Illuminate\Database\QueryException $ex) {
//         $jsonresp=$ex->getMessage();
//          $response['error']='true';
//          $response['error_message']=$jsonresp;
//          echo json_encode($response);
//          die;
//      }
                
                      
          
//                 $get_provider=Provider::where('id',$addprovider->id)->first();            
//                 $token= $get_provider->createToken('Token Name')->accessToken;                        
//     		$response['error']="false";
//     		$response['error_message']="Inserted Successfully";
//                 $response['access_token']=$token;
//     	}
//     	else
//     	{
//             $response['error']="true";
//     		$response['error_message']="Not Inserted.";
//     	}
         
//      }else{
//          $response['error']='true';
//          $response['error_message']='Manadatory Parameters are empty.';
//      }
   
//      echo json_encode($response);
//     }


//     public function provider_signup(request $request)
//     {
//          if(empty($request->first_name))
//     {
//           $response['error']='true';
//           $response['error_message']="please enter the firstname";
//           echo json_encode($response);
//          die;
//         }
//     if(empty($request->last_name)){
//       $response['error']='true';
//      $response['error_message']="please enter the last_name";     
//       echo json_encode($response);
//          die;
//        }
//     if(empty($request->email)){   
//        $response['error']='true';

//        $response['error_message']="please enter the email";
//     echo json_encode($response);
//          die;
//     }
//     if(empty($request->password)){
//        $response['error']='true';
//      $response['error_message']="please enter the password";
//      echo json_encode($response);
//          die;
     
//     }
//     if(empty($request->mobile)){    
//        $response['error']='true';
//       $response['error_message']="please enter the mobile";
//     echo json_encode($response);
//          die;
//     }
//     if(empty($request->dob)){  
//        $response['error']='true';
//         $response['error_message']="please enter the date_of_birth";
//     echo json_encode($response);
//          die;
//     }
//     if(empty($request->gender)){  

//          $response['error']='true';
//         $response['error_message']="please enter the gender";
//     echo json_encode($response);
//          die;
//     }


// if(empty($request->address1) || ctype_space($request->address1) ){

//    $response['error']='true';
//    $response['error_message']="please enter the address1";
//    echo json_encode($response);
//          die;
     
//     } if(empty($request->address2) || ctype_space($request->address2) ){
//        $response['error']='true';
//  $response['error_message']="please enter the address2";
//  echo json_encode($response);
//          die;
     
//     }
//     if(empty($request->city) || ctype_space($request->city) ){
//        $response['error']='true';
//      $response['error_message']="please enter the city";
//      echo json_encode($response);
//          die;
     
//     }
//     if(empty($request->state) || ctype_space($request->state) ){   
//        $response['error']='true';
//        $response['error_message']="please enter the state";
//     echo json_encode($response);
//          die;
//     }
//     if(empty($request->zipcode) || ctype_space($request->zipcode)){

//        $response['error']='true';
//      $response['error_message']="please enter the zipcode";
//      echo json_encode($response);
//          die;
     
//     }
//     if(empty($request->about)){     
//        $response['error']='true';
//      $response['error_message']="please enter the about";
//     echo json_encode($response);
//          die;
//     }
//     if(empty($request->workexperience)){
//        $response['error']='true';
//      $response['error_message']="please enter the workexperience";
//      echo json_encode($response);
//          die;
     
//     }
//     if(empty($request->schedules)){
//        $response['error']='true';
//      $response['error_message']="please enter the schedules";
//      echo json_encode($response);
//          die;
     
//     }
//     if(empty($request->category)){
//        $response['error']='true';
//      $response['error_message']="please enter the category";
//      echo json_encode($response);
//          die;
     
//     }
//      if($request->first_name && $request->last_name  && $request->email && $request->password && $request->mobile && $request->dob && $request->gender && $request->address1 && $request->address2 && $request->city && $request->state && $request->zipcode && $request->about && $request->workexperience && $request->schedules && $request->category)
//      {
//       if($request->image)
//       {
//         $image=$request->image;
//       }else{
//         $image="";
//       }
//          try {
//          // $imgupload=new Imageupload();
//          // $image=$request->file('image');
//          // $image_name=$imgupload->imgupload($image);
//          $addprovider= new Provider();
//          $addprovider->first_name=$request->first_name;
//          $addprovider->last_name=$request->last_name;
//          $addprovider->image=$image;
//          $addprovider->email=$request->email;
//          $addprovider->password=bcrypt($request->password);
//          $addprovider->gender=$request->gender;
//          $addprovider->addressline1=$request->address1;
//          $addprovider->addressline2=$request->address2;
//          $addprovider->city=$request->city;
//          $addprovider->mobile=$request->mobile;
//          $addprovider->dob=$request->dob;
//          $addprovider->state=$request->state;
//          $addprovider->zipcode=$request->zipcode;
//          $addprovider->about=$request->about;
//          $addprovider->workexperience=$request->workexperience;
         
//          $addprovider->save();    
//          } catch (\Illuminate\Database\QueryException $ex) {
//              $jsonresp=$ex->getMessage();
//           $response['error']='true';
//           $response['error_message']="Provider Details Already Exists"; 
//           echo json_encode($response); 
//           die;
//          }
//                         if($addprovider)
//       {
                            
//        $provider_categories=$request->category;
      
//        try {
                  
//        $jsoncatarray=json_decode($provider_categories);
       
//        foreach($jsoncatarray as $category)
//    {
// //        if($category->status == '1')
// //        {
//         try {
//         $addcategory=new Providerservices();
//         $addcategory->service_category_id=$category->category_id;
//         $addcategory->service_sub_category_id=$category->sub_category_id;
//         $addcategory->provider_id=$addprovider->id;
//         $addcategory->quickpitch=$category->quickpitch;
//         $addcategory->priceperhour=$category->priceperhour;
//         $addcategory->experience=$category->experience;
//         $addcategory->save();   
//          } catch (\Illuminate\Database\QueryException $ex) {
//              $jsonresp=$ex->getMessage();
//           $response['error']='true';
//           $response['error_message']=$jsonresp; 
//           echo json_encode($response); 
//           die;
//          } 
        
// //        }                       
//    }    
//        } catch (\Exception $ex) {
//               $jsonresp=$ex->getMessage();
//           $response['error']='true';
//           $response['error_message']=$jsonresp; 
//           echo json_encode($response); 
//           die;
//        }
       
                   
//                 $provider_schedules=$request->schedules;  
                
//                 try {
//                       $jsonarray=json_decode($provider_schedules);
   
//    foreach($jsonarray as $schedules)
//    {
//       try {
//            $newschedule= new Providerschedules();
//        $newschedule->provider_id=$addprovider->id;
//        $newschedule->time_Slots_id= $schedules->time_Slots_id;
//        $newschedule->days = $schedules->days;
//        $newschedule->status= $schedules->status;
//        $newschedule->save(); 
//       } catch (\Illuminate\Database\QueryException $ex) {
//          $jsonresp=$ex->getMessage();
//           $response['error']='true';
//           $response['error_message']=$jsonresp; 
//           echo json_encode($response); 
//           die;
//       }
        
//    }   
//                 } catch (\Exception $ex) {
//                     $jsonresp=$ex->getMessage();
//           $response['error']='true';
//           $response['error_message']=$jsonresp; 
//           echo json_encode($response); 
//           die;
//                 }      
          
//                 $get_provider=Provider::where('id',$addprovider->id)->first();            
//                 $token= $get_provider->createToken('Token Name')->accessToken;                        
//         $response['error']="false";
//         $response['error_message']="Inserted Successfully";
//                 $response['access_token']=$token;
//       }
//       else
//       {
//             $response['error']="true";
//         $response['error_message']="Not Inserted.";
//       }
         
//      }else{
//          $response['error']='true';
//          $response['error_message']='Manadatory Parameters are empty.';
//      }
//      echo json_encode($response);
//     }


public function provider_signupss(request $request , $locale)
    {
         if(empty($request->first_name))
    {
          $response['error']='true';
          $response['error_message']=trans('lang.please enter the firstname');
          echo json_encode($response);
         die;
        }
    if(empty($request->last_name)){
      $response['error']='true';
     $response['error_message']="please enter the last_name";     
      echo json_encode($response);
         die;
       }
    if(empty($request->email)){   
       $response['error']='true';

       $response['error_message']="please enter the email";
    echo json_encode($response);
         die;
    }
    if(empty($request->password)){
       $response['error']='true';
     $response['error_message']="please enter the password";
     echo json_encode($response);
         die;
     
    }
    if(empty($request->mobile)){    
       $response['error']='true';
      $response['error_message']="please enter the mobile";
    echo json_encode($response);
         die;
    }
    if(empty($request->dob)){  
       $response['error']='true';
        $response['error_message']="please enter the date_of_birth";
    echo json_encode($response);
         die;
    }
    if(empty($request->gender)){  

         $response['error']='true';
        $response['error_message']="please enter the gender";
    echo json_encode($response);
         die;
    }


if(empty($request->address1) || ctype_space($request->address1) ){

   $response['error']='true';
   $response['error_message']="please enter the address1";
   echo json_encode($response);
         die;
     
    } if(empty($request->address2) || ctype_space($request->address2) ){
       $response['error']='true';
 $response['error_message']="please enter the address2";
 echo json_encode($response);
         die;
     
    }
    if(empty($request->city) || ctype_space($request->city) ){
       $response['error']='true';
     $response['error_message']="please enter the city";
     echo json_encode($response);
         die;
     
    }
    if(empty($request->state) || ctype_space($request->state) ){   
       $response['error']='true';
       $response['error_message']="please enter the state";
    echo json_encode($response);
         die;
    }
    if(empty($request->zipcode) || ctype_space($request->zipcode)){

       $response['error']='true';
     $response['error_message']="please enter the zipcode";
     echo json_encode($response);
         die;
     
    }
    if(empty($request->about)){     
       $response['error']='true';
     $response['error_message']="please enter the about";
    echo json_encode($response);
         die;
    }
    if(empty($request->workexperience)){
       $response['error']='true';
     $response['error_message']="please enter the workexperience";
     echo json_encode($response);
         die;
     
    }

    $schedules=json_decode($request->schedules );


    if(empty($schedules) ){
       
     
  $response['error']='true';
     $response['error_message']="please enter the Schedules";
     echo json_encode($response);
         die;



    }



    $category=json_decode($request->category );


    if(empty($category)){
       $response['error']='true';
     $response['error_message']="please enter the category";
     echo json_encode($response);
         die;
     
    }





    $email_existing=Provider::where('email',$request->email)->first();


        $mobile_existing=Provider::where('mobile',$request->mobile)->first();

        if($email_existing){

         $response['error']='true';
                  $response['error_message']='Email already registered'; 
                  echo json_encode($response); 
                  die;

        }

      if($mobile_existing){

       $response['error']='true';
                $response['error_message']='Mobile Number already registered'; 
                echo json_encode($response); 
                die;

      }
     if($request->first_name && $request->last_name  && $request->email && $request->password && $request->mobile && $request->dob && $request->gender && $request->address1 && $request->address2 && $request->city && $request->state && $request->zipcode && $request->about && $request->workexperience && $request->schedules && $request->category)
     {
      if($request->image)
      {
        $image=$request->image;
      }else{
        $image="";
      }
         try {
         // $imgupload=new Imageupload();
         // $image=$request->file('image');
         // $image_name=$imgupload->imgupload($image);
         $addprovider= new Provider();
         $addprovider->first_name=$request->first_name;
         $addprovider->last_name=$request->last_name;
         $addprovider->image=$image;
         $addprovider->email=$request->email;
         $addprovider->password=bcrypt($request->password);
         $addprovider->gender=$request->gender;
         $addprovider->addressline1=$request->address1;
         $addprovider->addressline2=$request->address2;
         $addprovider->city=$request->city;
         $addprovider->mobile=$request->mobile;
         $addprovider->dob=$request->dob;
         $addprovider->state=$request->state;
         $addprovider->zipcode=$request->zipcode;
         $addprovider->about=$request->about;
         $addprovider->workexperience=$request->workexperience;
         
         $addprovider->save();    
         } catch (\Illuminate\Database\QueryException $ex) {
             $jsonresp=$ex->getMessage();
          $response['error']='true';
          $response['error_message']="Provider Details Already Exists"; 
          echo json_encode($response); 
          die;
         }
                        if($addprovider)
      {
                            
       $provider_categories=$request->category;
      
       try {
                  
       $jsoncatarray=json_decode($provider_categories);
       
       foreach($jsoncatarray as $category)
   {
//        if($category->status == '1')
//        {
        try {
        $addcategory=new Providerservices();
        $addcategory->service_category_id=$category->category_id;
        $addcategory->service_sub_category_id=$category->sub_category_id;
        $addcategory->provider_id=$addprovider->id;
        $addcategory->quickpitch=$category->quickpitch;
        $addcategory->priceperhour=$category->priceperhour;
        $addcategory->experience=$category->experience;
        $addcategory->save();   
         } catch (\Illuminate\Database\QueryException $ex) {
             $jsonresp=$ex->getMessage();
          $response['error']='true';
          $response['error_message']=$jsonresp; 
          echo json_encode($response); 
          die;
         } 
        
//        }                       
   }    
       } catch (\Exception $ex) {
              $jsonresp=$ex->getMessage();
          $response['error']='true';
          $response['error_message']=$jsonresp; 
          echo json_encode($response); 
          die;
       }
       
                   
                $provider_schedules=$request->schedules;  
                
                try {
                      $jsonarray=json_decode($provider_schedules);

   
   foreach($jsonarray as $schedules)
   {
      try {
           $newschedule= new Providerschedules();
       $newschedule->provider_id=$addprovider->id;
       $newschedule->time_Slots_id= $schedules->time_Slots_id;
       $newschedule->days = $schedules->days;
       $newschedule->status= $schedules->status;
       $newschedule->save(); 
      } catch (\Illuminate\Database\QueryException $ex) {
         $jsonresp=$ex->getMessage();
          $response['error']='true';
          $response['error_message']=$jsonresp; 
          echo json_encode($response); 
          die;
      }
        
   }   
                } catch (\Exception $ex) {
                    $jsonresp=$ex->getMessage();
          $response['error']='true';
          $response['error_message']=$jsonresp; 
          echo json_encode($response); 
          die;
                }      






   // $stripe=Stripe::setApiKey("DUMMY_KEY");
   //                 $setkey=\Stripe\Stripe::setApiKey('DUMMY_KEY');
                


  // try {
    // $status =\Stripe\Account::create(array(
    //               "country" => "CH",
    //               "type" => "custom",
    //               "email" => $request->email,
    //              "statement_descriptor" => "Custom descriptor",
    //             ));




          $accountnumber=' ';//$status->id;
              $stripe_email=$request->email;
              $providerid = $addprovider->id;
              //use App\Providerstripeaccount;

      $provider_account= new Providerstripeaccount();
      $provider_account->provider_id=$providerid;
      $provider_account->stripeaccount_number=' ';//$accountnumber;
      $provider_account->stripe_email=$stripe_email;
      $provider_account->save();

          
                $get_provider=Provider::where('id',$addprovider->id)->first();            
                $token= $get_provider->createToken('Token Name')->accessToken;                        
        $response['error']="false";
        $response['error_message']="Inserted Successfully";
                $response['access_token']=$token;
      }
      else
      {
            $response['error']="true";
        $response['error_message']="Not Inserted.";
      }
         
     }else{
         $response['error']='true';
         $response['error_message']='Manadatory Parameters are empty.';
     }
     echo json_encode($response);
    }




public function  providernewlogin(request $request){

				 if($request->first_name && $request->last_name  && $request->email && $request->password && $request->mobile && $request->dob && $request->gender && $request->address1 && $request->address2  && $request->state && $request->zipcode && $request->about && $request->workexperience )
     	{
     


				      if($request->image)
				      {
				        $image=$request->image;
				      }else{
				        $image="";
				      }

				$email_existing=Provider::where('email',$request->email)->first();
				$mobile_existing=Provider::where('mobile',$request->mobile)->first();

				if($email_existing){

				 $response['error']='true';
				          $response['error_message']='Email already registered'; 
				          echo json_encode($response); 
				          die;

				}

			if($mobile_existing){

			 $response['error']='true';
			          $response['error_message']='Mobile Number already registered'; 
			          echo json_encode($response); 
			          die;

			}

				$providerdata=array(
				'firstname'=>$request->first_name,
				'lastname'=>$request->last_name,
				'email'=>$request->email,
				'gender'=>$request->gender,
				'image'=>$image,
				'dob'=>$request->dob,
				'mobile'=>$request->mobile,
				'password'=>bcrypt($request->password),
				'address1'=>$request->address1,
				'address2'=>$request->address2,
				'city'=>$request->city,
				'state'=>$request->state,
				'zipcode'=>$request->zipcode,
				'about'=>$request->about,
				'workexperience'=>$request->workexperience);

				if($providerdata){

				$provider_id = Provider::addprovider($providerdata);

				 $response['error']='true';
				 $response['providerid']=$provider_id;
				 $response['error_message']='success'; 
				          
				}


				     }else{



				         $response['error']='true';
				         $response['error_message']='Manadatory Parameters are empty.';
				   
				     }


     echo json_encode($response);
}





public function  providerschedules(request $request){


if($request->schedules){




 $newschedule->provider_id=$addprovider->id;
       $newschedule->time_Slots_id= $schedules->time_Slots_id;
       $newschedule->days = $schedules->days;
       $newschedule->status= $schedules->status;
       $newschedule->save(); 
}






}




public function  provider_signup(request $request , $locale ){
  app()->setLocale($locale);


   if(empty($request->first_name))
    {
          $response['error']='true';

          $response['error_message']=trans('lang.please enter the firstname');
          echo json_encode($response);
         die;
        }
    if(empty($request->last_name)){
      $response['error']='true';
     $response['error_message']=trans('lang.please enter the last_name');     
      echo json_encode($response);
         die;
       }
    if(empty($request->countrycode)){   
       $response['error']='true';

       $response['error_message']=trans('lang.please enter the countrycode');
    echo json_encode($response);
         die;
    }
    if(empty($request->password)){
       $response['error']='true';
     $response['error_message']=trans('lang.please enter the password');
     echo json_encode($response);
         die;
     
    }
    if(empty($request->mobile)){    
       $response['error']='true';
      $response['error_message']=trans('lang.please enter the mobile');
    echo json_encode($response);
         die;
    }
    if(empty($request->dob)){  
       $response['error']='true';
        $response['error_message']=trans('lang.please enter the date_of_birth');
    echo json_encode($response);
         die;
    }
    if(empty($request->gender)){  

         $response['error']='true';
        $response['error_message']=trans('lang.please enter the gender');
    echo json_encode($response);
         die;
    }

     


if(empty($request->address1) || ctype_space($request->address1) ){

   $response['error']='true';
   $response['error_message']=trans('lang.please enter the address1');
   echo json_encode($response);
         die;
     
    } if(empty($request->address2) || ctype_space($request->address2) ){
       $response['error']='true';
 $response['error_message']=trans('lang.please enter the address2');
 echo json_encode($response);
         die;
     
    }
    if(empty($request->city) || ctype_space($request->city) ){
       $response['error']='true';
     $response['error_message']=trans('lang.please enter the city');
     echo json_encode($response);
         die;
     
    }
    if(empty($request->state) || ctype_space($request->state) ){   
       $response['error']='true';
       $response['error_message']=trans('lang.please enter the state');
    echo json_encode($response);
         die;
    }
    if(empty($request->zipcode) || ctype_space($request->zipcode)){

       $response['error']='true';
     $response['error_message']=trans('lang.please enter the zipcode');
     echo json_encode($response);
         die;
     
    }
    if(empty($request->about)){     
       $response['error']='true';
     $response['error_message']=trans('lang.please enter the about');
    echo json_encode($response);
         die;
    }
    if(empty($request->workexperience)){
       $response['error']='true';
     $response['error_message']=trans('lang.please enter the workexperience');
     echo json_encode($response);
         die;
     
    }

    if(empty($request->document_1)){
       $response['error']='true';
     $response['error_message']=trans('lang.please enter the document_1');
     echo json_encode($response);
         die;
     
    }

    if(empty($request->document_2)){
       $response['error']='true';
     $response['error_message']=trans('lang.please enter the document_2');
     echo json_encode($response);
         die;
     
    }

    if(empty($request->document_3)){
       $response['error']='true';
     $response['error_message']=trans('lang.please enter the document_3');
     echo json_encode($response);
         die;
     
    }

    if(empty($request->document_4)){
       $response['error']='true';
     $response['error_message']=trans('lang.please enter the document_4');
     echo json_encode($response);
         die;
     
    }

    $schedules=json_decode($request->schedules );


    if(empty($schedules) ){
       
     
  $response['error']='true';
     $response['error_message']=trans('lang.please enter the Schedules');
     echo json_encode($response);
         die;



    }



    $category=json_decode($request->category );


    if(empty($category)){
       $response['error']='true';
     $response['error_message']=trans('lang.please enter the category');
     echo json_encode($response);
         die;
     
    }

    // $email_existing=Provider::where('email',$request->email)->first();
      $mobile_existing=Provider::where('mobile',$request->mobile)->first();

        // if($email_existing){

        //  $response['error']='true';
        //           $response['error_message']=trans('lang.Email already  registered'); 
        //           echo json_encode($response); 
        //           die;

        // }

      if($mobile_existing){

       $response['error']='true';
       $response['is_user_exists']='true';
                $response['error_message']=trans('lang.Mobile Number already registered'); 
                echo json_encode($response); 
                die;

      }

 if($request->first_name && $request->last_name  && $request->password && $request->mobile && $request->dob && $request->gender && $request->address1 && $request->address2 && $request->city && $request->state && $request->zipcode && $request->about && $request->workexperience && $request->schedules && $request->category && $request->countrycode && $request->document_1 && $request->document_2 && $request->document_3 && $request->document_4)
     {
      if($request->image)
      {
        $image=$request->image;
      }else{
        $image="";
      }

DB::beginTransaction();


try{
     $addprovider= new Provider();
         $addprovider->first_name=$request->first_name;
         $addprovider->last_name=$request->last_name;
         $addprovider->image=$image;
         $addprovider->password=bcrypt($request->password);
         $addprovider->email=$request->mobile;
         $addprovider->gender=$request->gender;
         $addprovider->countrycode=$request->countrycode;
         $addprovider->addressline1=$request->address1;
         $addprovider->addressline2=$request->address2;
         $addprovider->city=$request->city;
         $addprovider->mobile=$request->mobile;
         $addprovider->dob=$request->dob;
         $addprovider->state=$request->state;
         $addprovider->zipcode=$request->zipcode;
         $addprovider->about=$request->about;
         $addprovider->workexperience=$request->workexperience;
         $addprovider->document_1=$request->document_1;
         $addprovider->document_2=$request->document_2;
         $addprovider->document_3=$request->document_3;
         $addprovider->document_4=$request->document_4;
         $addprovider->save();    

}catch (\Illuminate\Database\QueryException $ex) {
             $jsonresp=$ex->getMessage();
          $response['error']='true';
          $response['is_user_exists']='true';
          $response['error_message']=trans('lang.Provider Details Already Exists'); 
          echo json_encode($response); 
          die;
         }



if($addprovider){
   $provider_categories=$request->category;
      
       try {
                  
       $jsoncatarray=json_decode($provider_categories);
       
       foreach($jsoncatarray as $category)
   {
//        if($category->status == '1')
//        {
        try {
        $addcategory=new Providerservices();
        $addcategory->service_category_id=$category->category_id;
        $addcategory->service_sub_category_id=$category->sub_category_id;
        $addcategory->provider_id=$addprovider->id;
        $addcategory->quickpitch=$category->quickpitch;
        $addcategory->priceperhour=$category->priceperhour;
        $addcategory->experience=$category->experience;
        $addcategory->save();   
         } catch (\Illuminate\Database\QueryException $ex) {
             $jsonresp=$ex->getMessage();
              DB::rollBack();
          $response['error']='true';
          $response['error_message']=$jsonresp; 
          echo json_encode($response); 
          die;
         } 
        
//        }                       
   }    
       } catch (\Exception $ex) {
              $jsonresp=$ex->getMessage();
               DB::rollBack();
          $response['error']='true';
          $response['error_message']=$jsonresp; 
          echo json_encode($response); 
          die;
       }

       if($addcategory){
          $provider_schedules=$request->schedules;  
                
                try {
                      $jsonarray=json_decode($provider_schedules);

   
   foreach($jsonarray as $schedules)
   {
      try {
           $newschedule= new Providerschedules();
       $newschedule->provider_id=$addprovider->id;
       $newschedule->time_Slots_id= $schedules->time_Slots_id;
       $newschedule->days = $schedules->days;
       $newschedule->status= $schedules->status;
       $newschedule->save(); 
      } catch (\Illuminate\Database\QueryException $ex) {
         $jsonresp=$ex->getMessage();
          DB::rollBack();
          $response['error']='true';
          $response['error_message']=$jsonresp; 
          echo json_encode($response); 
          die;
      }
        
   }   
                } catch (\Exception $ex) {
                    $jsonresp=$ex->getMessage();
                     DB::rollBack();
          $response['error']='true';
          $response['error_message']=$jsonresp; 
          echo json_encode($response); 
          die;
                }   

                if($newschedule){
                   DB::commit();
    //         $stripe=Stripe::setApiKey("DUMMY_KEY");
    //                $setkey=\Stripe\Stripe::setApiKey('DUMMY_KEY');
                
  
    // $status =\Stripe\Account::create(array(
    //               "country" => "CH",
    //               "type" => "custom",
    //               "email" => $request->email,
    //              "statement_descriptor" => "Custom descriptor",
    //             ));


          $accountnumber=' ';//$status->id;
              $stripe_email=$request->email;
              $providerid = $addprovider->id;
              //use App\Providerstripeaccount;

      $provider_account= new Providerstripeaccount();
      $provider_account->provider_id=$providerid;
      $provider_account->stripeaccount_number=' ';//$accountnumber;
      $provider_account->stripe_email=$stripe_email;
      $provider_account->save();

          
                $get_provider=Provider::where('id',$addprovider->id)->first();            
                $token= $get_provider->createToken('Token Name')->accessToken;                        
        $response['error']="false";
       $response['is_user_exists']='false';
        $response['error_message']=trans('lang.Inserted Successfully');
        $response['access_token']=$token;

                }else{
                   DB::rollBack();
                  $get_provider1=Provider::where('id',$addprovider->id)->delete();
              $get_provider2=Providerservices::where('provider_id',$addprovider->id)->delete();
                  $get_provider3=Providerschedules::where('provider_id',$addprovider->id)->delete();
                   $response['error']='true';
               $response['error_message']=trans('lang.Schedules was invalid.');

                }

       }else{
         DB::rollBack();
        $get_provider1=Provider::where('id',$addprovider->id)->delete();
        $get_provider2=Providerservices::where('provider_id',$addprovider->id)->delete();
        

       }


}else{

       $response['error']='true';
         $response['error_message']=trans('lang.provider not insert.');

}


}else{
    $response['error']='true';
         $response['error_message']=trans('lang.Manadatory Parameters are empty.');


}

echo json_encode($response);

}







public function elapsetime(request $request)
{
    
    if($request->booking_id)
    {   
      $dbtime = DB::table('bookings')->where('id',$request->booking_id)->first();

      $objDateTime = date("Y-m-d H:i:s");
        $dbtime = $dbtime->startjob_timestamp;
        $start_date = new DateTime($objDateTime);
        $since_start = $start_date->diff(new DateTime($dbtime));

        // echo date('c', strtotime($since_start));

         $response['error']="false";
         // $response['error_message']=trans('lang.details fetched'); 
         $response['data']=$since_start; 
    }else{
         $response['error']="true";
         // $response['error_message']=trans('lang.cannot fetch details'); 
         $response['data']=$since_start; 

    }
    echo json_encode($response);
}










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
             $response['twillo']=$verification;
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
           $check_userlogin=Provider::where('mobile',$mobile)->update(['is_mobile_verify'=>'1']);
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





    
    public function providerlogin(request $request , $locale)
    {
       app()->setLocale($locale);
     if($request->mobile && $request->password && $request->user_type && $request->countrycode)
        {
        		//$trimmed = trim($text);
            $provider=$request->user_type;
            $mobile=trim($request->mobile);
            $countrycode=$request->countrycode;
            $password=trim($request->password);
            $check_providerlogin=Provider::where('mobile',$mobile)->first();

            // if($check_providerlogin['is_mobile_verify'] == '0')
            // {
            //   $response['error']='true';
            //   $response['error_message']=trans('lang.Mobile not yet verified');  
            // }else
            // {
             
            if($check_providerlogin)
            {
              
             if($check_providerlogin['status'] == 'active')
             {
                         $client = new Client();
            
            try {
                 $res = $client->request('POST', 'http://18.218.132.174/uber_test/public/oauth/token', [
                'form_params' => [
                'client_id' => '3',
                'client_secret' => 'rZzdViE8vVmLaIFtB2BcCgRJJXAuPrGgZXgCH8a9',
                'grant_type' => 'password',
                'username' => $mobile,
                'password' => $password,
                'scope' => '*',
                'provider'=>$provider
            ]
        ]);         
            } catch (\GuzzleHttp\Exception\BadResponseException $ex) {
                $jsonresp=$ex->getMessage();
                $response['error']='true';
                $response['is_user_exists']='true';
                $response['message']=trans('lang.User Credentials are incorrect.');
                echo json_encode($response); die;
            }
            $access_token = json_decode((string) $res->getBody(), true)['access_token'];
 
             $response['error']='false'; 
             $response['error_message']='Success'; 
             $response['access_token']=$access_token;
             $response['image']=$check_providerlogin['image'];
             $response['provider_id']=$check_providerlogin['id'];
             $response['first_name']=$check_providerlogin['first_name'];
             $response['last_name']=$check_providerlogin['last_name'];
             $response['email']=$check_providerlogin['email'];
              $response['is_user_exists']='true';
             $response['latitude']=$check_providerlogin['latitude'];
             $response['longitude']=$check_providerlogin['longitude'];
             $response['is_mobile_verify']=$check_providerlogin['is_mobile_verify'];
             }else{
              $response['error']='true';
              $response['is_user_exists']='true';
             $response['error_message']=trans('lang.Thanks we are reviewing you account please wait 24hrs and try logging in');
             } 
   
            }
            else{
             $response['error']='true';
              $response['is_user_exists']='false';
             $response['error_message']=trans('lang.Provider not registered with us.');
            }
// }
                }else{
             $response['error']='true';
             $response['error_message']=trans('lang.Mandatory Params are missing.');   
                }
                echo json_encode($response);
    }
    
    
    public function appsettings(request $request , $locale)
{   
  app()->setLocale($locale);
           $location=Location::get();
        $timeslots=Timeslots::get();
        
        $response['error']='false';
        $response['error_message']='success';
        $response['location']=$location;
        $response['timeslots']=$timeslots;
         if(Auth::guard('provider')->check())
 { 
     $providerid=Auth::guard('provider')->user()->id;
     // echo $providerid; die;
     $listofstatus=array('Blocked','Dispute','Reviewpending','InvoicePending','Waitingforpaymentconfirmation','Finished','Completedjob');
     for($i=0;$i<=count($listofstatus)-1;$i++)
     {
        $bookingdetails=Bookings::where(['provider_id'=>$providerid,'status'=>$listofstatus[$i],'isProviderReviewed'=>'0'])->first();      
        if($bookingdetails)
        {
            $yesdata['booking_id']=$bookingdetails['id'];
            $yesdata['user_id']=$bookingdetails['user_id'];
            $yesdata['status']="1";
            $newdata[$listofstatus[$i]]=$yesdata;
        }else{
            $yesdata['booking_id']=$bookingdetails['id'];
            $yesdata['user_id']=$bookingdetails['user_id'];
            $yesdata['status']="0";
            $newdata[$listofstatus[$i]]=$yesdata;
        }
          
     }
     $new[]=$newdata;
     $response['status']=$new;
     // $response['booking_details']=$bookingdetails;
 }
        
        echo json_encode($response);
}

public function updatedevicetoken(request $request , $locale)
{
  app()->setLocale($locale);
    $providerid=Auth::guard('provider')->user()->id; 

    if($request->fcm_token && $request->os)
    {
     $fcmtoken=$request->fcm_token;    
     $os=$request->os;    
      Provider::where('id',$providerid)->update(['fcm_token'=>$fcmtoken,'os_type'=>$os]);
         $response['error']="false";
         $response['error_message']=trans('lang.fcm token updated.'); 
    }else{
         $response['error']="true";
         $response['error_message']=trans('lang.fcm token is empty.'); 
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
       $get_user=Provider::where('mobile',$mobile)->first();
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
        Provider::where('mobile',$mobile)->update(['password'=>$encryptpassword]);    
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
  
public function listcategory(request $request , $locale)
{
  app()->setLocale($locale);
    $category=Category::where('status','active')->get();
    
   

   foreach($category as $newcategory)
   {
   	$allsubcategory=Subcategory::where('category_id',$newcategory->id)->get();
   	if($allsubcategory)
   	{
    $newcategory->list_subcategory=$allsubcategory;
   	}else{
    $newcategory->list_subcategory=[];  
   	}
   }

    if($category)
    {
        $response['error']='false';
        $response['error_message']=trans('lang.success');
        $response['list_category']=$category;
    }else{
         $response['error']='true';
        $response['error_message']=trans('lang.No Category');
        $response['list_category']=[];
    }
    echo json_encode($response);
}

public function listsubcategory(request $request , $locale)
{
  app()->setLocale($locale);
    if($request->id)
    {
        $id=$request->id;
    $subcategory=Subcategory::where(['category_id'=>$id,'status'=>'active' ])->get();
    if($subcategory)
    {
        $response['error']='false';
        $response['error_message']=trans('lang.success');
        $response['list_subcategory']=$subcategory;
    }else{
        $response['error']='true';
        $response['error_message']=trans('lang.No Subcategory');
        $response['list_subcategory']=[];
    }   
    }else{
        $response['error']='true';
        $response['error_message']=trans('lang.Category is empty');
    }
    echo json_encode($response);
}
        public function changepassword(request $request , $locale)
        {
          app()->setLocale($locale);
            if($request->oldpassword && $request->newpassword && $request->cnfpassword)
            {
                $oldpassword=$request->oldpassword;
                $email=Auth::guard('provider')->user()->email;
                $providerid=Auth::guard('provider')->user()->id;
                $password=Provider::where('id',$providerid)->value('password');
                if(password_verify($oldpassword,$password))
                {
                   if($request->newpassword == $request->cnfpassword)
                   {
                       $newpassword=$request->newpassword;
                       $updatepassword=bcrypt($newpassword);
                       Provider::where('id',$providerid)->update(['password'=>$updatepassword]);
                       
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
public function viewprofile(request $request , $locale)
{
  app()->setLocale($locale);
    $providerid=Auth::guard('provider')->user()->id;
    $provider_details=Provider::select('first_name','last_name','mobile','gender','image','dob','addressline1','addressline2','city','state','zipcode','about')->where('id',$providerid)->first();
    	$provider_address1=$provider_details->addressline1;
    	$provider_address2=$provider_details->addressline2;
    	$provider_city=$provider_details->city;
    	$provider_state=$provider_details->state;
    	$provider_zipcode=$provider_details->zipcode;

    if(is_null($provider_address1)){

    	$provider_details->addressline1=trans('lang.address not specified');
    	
    }
     if(is_null($provider_address2)){

    	//$provider_details->addressline1="address not specified";
    	$provider_details->addressline2=trans('lang.address not specified');
    	
    }
     if(is_null($provider_city)){

    	//$provider_details->addressline1="address not specified";
    	$provider_details->city=trans('lang.city not specified');
    	
    }

     if(is_null($provider_state)){

    	//$provider_details->addressline1="address not specified";
    	$provider_details->state=trans('lang.state not specified');
    	
    }
      if(is_null($provider_zipcode)){

    	//$provider_details->addressline1="address not specified";
    	$provider_details->zipcode=trans('lang.zipcode not specified');
    	
    }

   // &&is_null($provider_address2)&&

    $response['error']='false';
    $response['error_message']=trans('lang.success');    
    $response['provider_details']=$provider_details;    
    echo json_encode($response);
}

public function view_schedules(request $request , $locale)
{
  app()->setLocale($locale);
    $providerid=Auth::guard('provider')->user()->id;
    $provider_schedules=Providerschedules::select('provider_schedules.id','provider_schedules.provider_id','provider_schedules.time_Slots_id','time_slots.timing','provider_schedules.days','provider_schedules.status')->join('time_slots','time_slots.id','=','provider_schedules.time_slots_id')->where(['provider_id'=>$providerid])->get();


    $response['error']='false';
    $response['error_message']=trans('lang.success');    
    if($provider_schedules)
    {
      $response['schedules']=$provider_schedules;
      
    }else{
      $response['schedules']=[];  
    }
     echo json_encode($response);
}

public function view_provider_category(request $request , $locale)
{
  app()->setLocale($locale);
    $providerid=Auth::guard('provider')->user()->id;
    $all_subcategories = DB::select(DB::raw("select provider_services.id,service_category.category_name,service_sub_category.category_id,provider_services.service_sub_category_id,service_sub_category.sub_category_name,service_sub_category.image,service_sub_category.icon,provider_services.priceperhour,provider_services.quickpitch,provider_services.experience,service_sub_category.short_description,service_sub_category.long_description from service_sub_category inner join service_category on service_category.id=service_sub_category.category_id left join provider_services on provider_services.service_sub_category_id=service_sub_category.id where provider_services.provider_id='$providerid'"));
    $response['error']='false';
    $response['error_message']=trans('lang.success');    
    if($all_subcategories)
    {
      $response['category']=$all_subcategories;
      
    }else{
      $response['category']=[];  
    }
     echo json_encode($response);
}

public function updateprofile(request $request , $locale)
{
  app()->setLocale($locale);
    $providerid=Auth::guard('provider')->user()->id;
    
    if($request->first_name && $request->last_name && $request->mobile && $request->gender && $request->image && $request->dob && $request->about)
    {
       $providerid=Auth::guard('provider')->user()->id;
       
       if($request->hasFile('image')){
           $imgupload=new Imageupload();
         $image=$request->file('image');
         $image_name=$imgupload->imgupload($image);
       }else{
          $image_name=$request->image; 
       }
       $updateprovider=Provider::where('id',$providerid)->update(['first_name'=>$request->first_name,
                                         'last_name'=>$request->last_name,
                                         'mobile'=>$request->mobile,
                                         'gender'=>$request->gender,
                                         'image'=>$image_name,   
                                         'dob'=>$request->dob,   
                                         
                                         'about'=>$request->about   
                                        ]);
       if($updateprovider)
       {
        $response['error']='false';          
        $response['error_message']=trans('lang.successfully Updated.');          
       }else{
        $response['error']='true';          
        $response['error_message']=trans('lang.not updated.');   
       }
    }else{
        $response['error']='true';          
        $response['error_message']=trans('lang.Mandatory Parameters are empty');
    }
    echo json_encode($response);
}

public function update_address(request $request , $locale){
  app()->setLocale($locale);
  if($request->addressline1 && $request->addressline2 && $request->city && $request->state && $request->zipcode){
    $providerid=Auth::guard('provider')->user()->id;
$updateprovider=Provider::where('id',$providerid)->update([
                                         'addressline1'=>$request->addressline1,   
                                         'addressline2'=>$request->addressline2,   
                                         'city'=>$request->city,   
                                         'state'=>$request->state,   
                                         'zipcode'=>$request->zipcode 
                                        ]);
    if($updateprovider)
       {
        $response['error']='false';          
        $response['error_message']=trans('lang.successfully Updated.');          
       }else{
        $response['error']='true';          
        $response['error_message']=trans('lang.not updated.');   
       }
    
  }else{
        $response['error']='true';          
        $response['error_message']=trans('lang.Mandatory Parameters are empty');
  }
  echo json_encode($response);
  }

public function updateschedules(request $request , $locale)
  {
    app()->setLocale($locale);
   $provider_schedules=$request->schedules;  
   $jsonarray=json_decode($provider_schedules);
   

   
   foreach($jsonarray as $schedules)
   {
      $days=$schedules->days;
      $time_slot_id=$schedules->time_Slots_id;
      $provider_id=$schedules->provider_id;
      $days=$schedules->days;
      $status=$schedules->status;
      
      $count=Providerschedules::where(['provider_id'=>$provider_id,'time_slots_id'=>$time_slot_id,'days'=>$days])->count();



      if($count > 0)
      {


        $data=array('status'=>$status,'updated_at'=>date("Y-m-d H:i:s"));

          $resultsss=DB::table('provider_schedules')->where(['id'=>$schedules->id])->update($data);


       
         // $result=Providerschedules::where(['id'=>$schedules->id,'provider_id'=>$provider_id])->update(['status'=>$status,,'updated_at'=>date("Y-m-d H:i:s")]);  
    

//       Providerschedules::where('id',$schedules->id)->insert(['status'=>$schedules->status]);
      }else{
          
       
       $addtime_slot=new Providerschedules();
       $addtime_slot->provider_id=$provider_id;
       $addtime_slot->time_slots_id=$time_slot_id;
       $addtime_slot->days=$days;
       $addtime_slot->status=$status;
       $addtime_slot->save();
      }
//      
   }
   $response['error']='false';
   $response['error_message']=trans('lang.Provider Schedules Updated.');
   
   echo json_encode($response);
   
  }
  
  public function update_provider_category(request $request , $locale)
   {
    app()->setLocale($locale);
       $provider_categories=$request->category;
       $jsonarray=json_decode($provider_categories);
       
       foreach($jsonarray as $category)
   {
        if($category->status == '0')
        {
         Providerservices::where('id',$category->id)->delete();   
        }
        
   }
   $response['error']='false';
   $response['error_message']=trans('lang.updated provider services');
   echo json_encode($response); 
   }

   public function home(request $request , $locale)
   {
    app()->setLocale($locale);
        $providerid=Auth::guard('provider')->user()->id;
        $provider_image=Auth::guard('provider')->user()->image;
        $Pending = DB::select(DB::raw("select CONCAT(users.first_name,' ',users.last_name)AS Name,users.mobile as user_mobile,users.image as userimage,service_category.category_name,service_sub_category.sub_category_name,service_sub_category.long_description,service_sub_category.short_description,service_sub_category.icon,time_slots.timing,provider_schedules.days,bookings.user_id,bookings.id,bookings.booking_order_id,bookings.status,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.Pending_time,bookings.Accepted_time,bookings.Rejected_time,bookings.Finished_time,bookings.CancelledbyUser_time,bookings.CancelledbyProvider_time,bookings.StarttoCustomerPlace_time,bookings.cost,bookings.worked_mins,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,bookings.booking_date,user_address.latitude as user_latitude,user_address.longitude as user_longitude,bookings.admin_share,bookings.provider_share from bookings INNER JOIN service_category on service_category.id=bookings.service_category_id INNER JOIN service_sub_category on service_sub_category.id=bookings.service_category_type_id INNER JOIN provider_schedules on provider_schedules.id=bookings.provider_schedules_id INNER JOIN time_slots on time_slots.id=provider_schedules.time_slots_id INNER JOIN users on users.id=bookings.user_id INNER JOIN user_address on user_address.id=bookings.address_id where bookings.provider_id=$providerid and bookings.status='Pending' ORDER BY bookings.updated_at DESC"));
        $Accepted = DB::select(DB::raw("select CONCAT(users.first_name,' ',users.last_name)AS Name,users.mobile as user_mobile,users.image as userimage,CONCAT(provider.first_name,' ',provider.last_name)AS ProviderName,service_category.category_name,service_sub_category.sub_category_name,service_sub_category.long_description,service_sub_category.short_description,service_sub_category.icon,time_slots.timing,bookings.Pending_time,bookings.Accepted_time,bookings.Rejected_time,bookings.Finished_time,bookings.CancelledbyUser_time,bookings.CancelledbyProvider_time,bookings.StarttoCustomerPlace_time,bookings.startjob_timestamp,bookings.endjob_timestamp,provider_schedules.days,bookings.user_id,bookings.id,bookings.booking_order_id,bookings.booking_date,bookings.job_start_time,bookings.job_end_time,bookings.cost,bookings.worked_mins,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,user_address.address_line_1,user_address.doorno,user_address.landmark,user_address.latitude as user_latitude,user_address.longitude as user_longitude,user_address.title,user_address.city,bookings.status,bookings.isProviderReviewed,bookings.admin_share,bookings.provider_share,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.cost,bookings.worked_mins  from bookings INNER JOIN service_category on service_category.id=bookings.service_category_id INNER JOIN service_sub_category on service_sub_category.id=bookings.service_category_type_id INNER JOIN provider_schedules on provider_schedules.id=bookings.provider_schedules_id INNER JOIN time_slots on time_slots.id=provider_schedules.time_slots_id INNER JOIN users on users.id=bookings.user_id INNER JOIN user_address on user_address.id=bookings.address_id INNER JOIN provider on bookings.provider_id=provider.id where bookings.provider_id=$providerid and bookings.status IN ('Accepted' OR 'StarttoCustomerPlace' OR 'Startedjob' OR 'Completedjob' OR 'Waitingforpaymentconfirmation' OR 'Reviewpending') ORDER BY bookings.updated_at DESC"));

        $random_request_pending = DB::select(DB::raw("select CONCAT(users.first_name,' ',users.last_name)AS Name,users.mobile as user_mobile,users.image as userimage,service_category.category_name,service_sub_category.sub_category_name,service_sub_category.long_description,service_sub_category.short_description,service_sub_category.icon,time_slots.timing,time_slots.fromTime,time_slots.toTime,provider_schedules.days,bookings.user_id,bookings.id,bookings.booking_order_id,bookings.status,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.Pending_time,bookings.Accepted_time,bookings.Rejected_time,bookings.Finished_time,bookings.CancelledbyUser_time,bookings.CancelledbyProvider_time,bookings.StarttoCustomerPlace_time,bookings.cost,bookings.worked_mins,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,bookings.booking_type,bookings.booking_date,user_address.latitude as user_latitude,user_address.longitude as user_longitude,bookings.admin_share,bookings.provider_share from provider_request inner join bookings on bookings.id=provider_request.booking_id inner join provider_schedules on provider_schedules.id=provider_request.provider_schedules_id inner join time_slots on time_slots.id=provider_schedules.time_slots_id inner join provider_services on provider_services.id=provider_request.provider_service_id INNER JOIN service_category on service_category.id=bookings.service_category_id INNER JOIN service_sub_category on service_sub_category.id=bookings.service_category_type_id INNER JOIN users on users.id=bookings.user_id INNER JOIN user_address on user_address.id=bookings.address_id where provider_request.provider_id='$providerid' and bookings.status='Pending' and provider_request.status='OPEN'"));
        
        $RecurralPending = DB::select(DB::raw("select CONCAT(users.first_name,' ',users.last_name)AS Name,users.mobile as user_mobile,users.image as userimage,service_category.category_name,service_sub_category.sub_category_name,service_sub_category.long_description,service_sub_category.short_description,service_sub_category.icon,bookings.user_id,bookings.id,bookings.booking_order_id,bookings.status,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.Pending_time,bookings.Accepted_time,bookings.Rejected_time,bookings.Finished_time,bookings.CancelledbyUser_time,bookings.CancelledbyProvider_time,bookings.StarttoCustomerPlace_time,bookings.booking_type,bookings.cost,bookings.worked_mins,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,bookings.booking_date,user_address.latitude as user_latitude,user_address.longitude as user_longitude,bookings.admin_share,bookings.provider_share from bookings INNER JOIN service_category on service_category.id=bookings.service_category_id INNER JOIN service_sub_category on service_sub_category.id=bookings.service_category_type_id INNER JOIN users on users.id=bookings.user_id INNER JOIN user_address on user_address.id=bookings.address_id where bookings.provider_id=$providerid and bookings.booking_type='recurral' and bookings.status='Pending' ORDER BY bookings.updated_at DESC"));
       
        if($Pending)
        {
         $response['Pending']=$Pending; 
        }else{   
         $response['error_message']=trans('lang.No Pending Bookings Bookings Available');   
         $response['Pending']=[];
        }
        if($Accepted)
        {
         $response['Accepted']=$Accepted;   
        }else{   
         $response['error_message']=trans('lang.No Accepted Bookings Available');   
         $response['Accepted']=[];
        }
        $response['error']='false';
        $response['message']=trans('lang.success');
        $response['image']=$provider_image;
        $response['random_request_pending']=$random_request_pending;
        $response['RecurralPending']=$RecurralPending;
        echo json_encode($response);
        
        }
        
        public function update_location(request $request , $locale)
        {
          app()->setLocale($locale);
         if($request->latitude && $request->longitude && $request->bearing)
         {
             $providerid=Auth::guard('provider')->user()->id;
             $latitude=$request->latitude;
             $longitude=$request->longitude;
             $bearing=$request->bearing;
             Provider::where('id',$providerid)->update(['latitude'=>$latitude,'longitude'=>$longitude,'Bearing'=>$bearing]);
             $response['error']='false';
             $response['error_message']=trans('lang.Location updated.');
         }else{
              $response['error']='true';
              $response['error_message']=trans('lang.not updated');    
         }
         echo json_encode($response); 
        }
        
        public function acceptbooking(request $request , $locale){
          app()->setLocale($locale);
            if($request->id)
            {
                $booking_request_id=$request->id;
                $accepted_time=date('Y-m-d H:i:s');
                $accept=Bookings::where('id',$booking_request_id)->update(['status'=>'Accepted','Accepted_time'=>$accepted_time]);
                if($accept){
                $user_id=Bookings::where('id',$booking_request_id)->value('user_id');
                $provider_id=Bookings::where('id',$booking_request_id)->value('provider_id');
          $user_details=User::where('id',$user_id)->first();
           $provider_name=Provider::where('id',$provider_id)->value('first_name');
          $response['error']='false';   
          $response['error_message']=trans('lang.updated.');
          $gcpm = new FCMPushNotification();
          $title = trans('lang.Booking Request Accepted.');
          $message =$provider_name .' '. trans('lang.has accepted your request.');
          $os=$user_details['os_type'];
          $data = array('image' => "NULL",
                 'title' => $title,
                'notification_type'=>'Accepted');
          $gcpm->setDevices($user_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KWAfy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
          $response['push_status'] = $gcpm->send($message, $data,$os,$title, $message);  
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

        public function accept_random_request(request $request , $locale)
{
  app()->setLocale($locale);
  $provider_id=Auth::guard('provider')->user()->id;
   if($request->id)
   {
    $booking_id=$request->id;
    $accepted_time=date('Y-m-d H:i:s');
    $check_booking_accepted=DB::table('bookings')->where(['id'=>$booking_id,'booking_type'=>'random','status'=>'Pending'])->get(); 
    if($check_booking_accepted)
    {
     $update_status=DB::table('provider_request')->where(['booking_id'=>$booking_id])->update(['status'=>'CLOSE']);
     $provider_schedules_id=DB::table('provider_request')->where(['booking_id'=>$booking_id,'provider_id'=>$provider_id])->value('provider_schedules_id');

     $udapte_booking_status=DB::table('bookings')->where(['id'=>$booking_id,'booking_type'=>'random','status'=>'Pending'])->update(['status'=>'Accepted','provider_id'=>$provider_id,'provider_schedules_id'=>$provider_schedules_id,'Accepted_time'=>$accepted_time]); 
     $response['error']='false';
     $response['error_message']=trans('lang.Success');
    }else{
    $response['error']='true';
    $response['error_message']=trans('lang.Oops ! Booking has been accepted by Another Provider');
    }
   }else{
    $response['error']='true';
    $response['error_message']=trans('lang.Invalid Booking id');
   }
   echo json_encode($response);
}

public function reject_random_request(request $request , $locale){
  app()->setLocale($locale);
   $provider_id=Auth::guard('provider')->user()->id;
  $booking_request_id=$request->id;
  $rejecttime=date('Y-m-d H:i:s');
  $random_update_status=DB::table('provider_request')->where(['booking_id'=>$booking_request_id,'provider_id'=>$provider_id])->update(['status'=>'CLOSE','is_cancelled_provider'=>'1']);
  $response['error']='false';   
  $response['error_message']=trans('lang.Random Request Rejected');   
  
  echo json_encode($response);
}

        public function rejectbooking(request $request , $locale){
          app()->setLocale($locale);
            if($request->id)
            {
                $booking_request_id=$request->id;
                $rejecttime=date('Y-m-d H:i:s');
                $accept=Bookings::where('id',$booking_request_id)->update(['status'=>'Rejected','Rejected_time'=>$rejecttime]);
                if($accept){
                  $user_id=Bookings::where('id',$booking_request_id)->value('user_id');
                  $provider_id=Bookings::where('id',$booking_request_id)->value('provider_id');
          $user_details=User::where('id',$user_id)->first();
          $provider_name=Provider::where('id',$provider_id)->value('first_name');
          $response['error']='false';   
          $response['error_message']=trans('lang.updated.');
          $gcpm = new FCMPushNotification();
          $title = trans('lang.Booking Request Rejected.');
          $message =$provider_name .' '.trans('lang.has rejected your request.');
          $os=$user_details['os_type'];
          $data = array('image' => "NULL",
                 'title' => $title,
                  'notification_type'=>'Rejected');
          $gcpm->setDevices($user_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KWAfy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
          $newresponse[] = $gcpm->send($message, $data,$os,$title, $message); 
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
        public function cancelbyprovider(request $request , $locale){
          app()->setLocale($locale);
            if($request->id)
            {
                $booking_request_id=$request->id;
                $cancel_time=date('Y-m-d H:i:s');
                $status=Bookings::where(['id'=>$booking_request_id])->value('status');
                if($status == 'Accepted')
                {
                  $accept=Bookings::where('id',$booking_request_id)->update(['status'=>'CancelledbyProvider','CancelledbyProvider_time'=>$cancel_time]);
                
                if($accept){
                  $user_id=Bookings::where('id',$booking_request_id)->value('user_id');
                  $provider_id=Bookings::where('id',$booking_request_id)->value('provider_id');
$provider_name=Provider::where('id',$provider_id)->value('first_name');
          $user_details=User::where('id',$user_id)->first();
          $response['error']='false';   
          $response['error_message']=trans('lang.updated.');
          $gcpm = new FCMPushNotification();
          $title = trans('lang.Booking Cancelled');
          $message =$provider_name .' '. trans('lang.has Cancelled your booking.');
          $os=$user_details['os_type'];
          $data = array('image' => "NULL",
                 'title' => $title,
                 'notification_type'=>'CancelledbyProvider');
          $gcpm->setDevices($user_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KWAfy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
          $newresponse[] = $gcpm->send($message, $data,$os,$title, $message); 
                    $response['error']='false';
                    $response['error_message']=trans('lang.Updated.');
                }else{
                    $response['error']='true';
                    $response['error_message']=trans('lang.Not Updated.');
                }
              }else{
                    $response['error']='true';
                    $response['error_message']=trans('lang.Invalid Booking Request.');
                
              }
            }else{
                $response['error']='true';
                    $response['error_message']=trans('lang.Invalid Booking id.');
            }
            echo json_encode($response);
        }
        public function starttocustomerplace(request $request , $locale){
          app()->setLocale($locale);
            if($request->id)
            {
                $booking_request_id=$request->id;
                $customer_time=date('Y-m-d H:i:s');
                $accept=Bookings::where('id',$booking_request_id)->update(['status'=>'StarttoCustomerPlace','StarttoCustomerPlace_time'=>$customer_time]);
                if($accept){
                  $user_id=Bookings::where('id',$booking_request_id)->value('user_id');
          $user_details=User::where('id',$user_id)->first();
          $provider_id=Bookings::where('id',$booking_request_id)->value('provider_id');
$provider_name=Provider::where('id',$provider_id)->value('first_name');
          $response['error']='false';   
          $response['error_message']=trans('lang.updated.');
          $gcpm = new FCMPushNotification();
          $title = trans('lang.On the Way.');
          $message =$provider_name .' '. trans('lang.has started to the job location.');
          $os=$user_details['os_type'];
          $data = array('image' => "NULL",
                 'title' => $title,'notification_type'=>'StarttoCustomerPlace');
          $gcpm->setDevices($user_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KWAfy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
          $newresponse[] = $gcpm->send($message, $data,$os,$title, $message); 
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
        
        public function startedjob(request $request , $locale){
          app()->setLocale($locale);
            if($request->id)
            {   

                $start_data = DB::table('startendjobdetails')->insert([
                  'booking_id'=>$request->id,
                  'start_image'=>$request->start_image,
                  'start_lat'=>$request->start_lat,
                  'start_long'=>$request->start_long,
                  'start_address'=>$request->start_address,
                  'start_time'=>$request->start_time
                  ]);

                $providerid=Auth::guard('provider')->user()->id;
                $booking_request_id=$request->id;
                $customer_time=date('Y-m-d H:i:s');
                $time=date("H:i:s");
                $accept=Bookings::where(['id'=>$booking_request_id,'provider_id'=>$providerid])->update(['status'=>'Startedjob','job_start_time'=>$time,'startjob_timestamp'=>$customer_time]);
                if($accept){
                  $user_id=Bookings::where('id',$booking_request_id)->value('user_id');
          $user_details=User::where('id',$user_id)->first();
                  $provider_id=Bookings::where('id',$booking_request_id)->value('provider_id');
$provider_name=Provider::where('id',$provider_id)->value('first_name');
          $response['error']='false';   
          $response['error_message']=trans('lang.updated.');
          $gcpm = new FCMPushNotification();
          $title = trans('lang.Job started.');
          $message =$provider_name .' '. trans('lang.has started the job.');
          $os=$user_details['os_type'];
          $data = array('image' => "NULL",
                 'title' => $title,'notification_type'=>'Startedjob');
          $gcpm->setDevices($user_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KWAfy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
          $newresponse[] = $gcpm->send($message, $data,$os,$title, $message); 
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
        public function completedjob(request $request , $locale){
          app()->setLocale($locale);
            if($request->id)
            { 

                $end_data = DB::table('startendjobdetails')->where(['booking_id'=>$request->id])->update([
                  'end_image'=>$request->end_image,
                  'end_lat'=>$request->end_lat,
                  'end_long'=>$request->end_long,
                  'end_address'=>$request->end_address,
                  'end_time'=>$request->end_time
                  ]);


                $booking_request_id=$request->id;
                $completed_time=date('Y-m-d H:i:s');
                $providerid=Auth::guard('provider')->user()->id;
                $customer_time=date('Y-m-d H:i:s');
                $time=date("H:i:s");
                
                $tax= Servicetax::Select('id','service_name','percentage','status')->Where('status',1)->get();
				
                $bookingdetails=Bookings::select('bookings.job_start_time','bookings.job_end_time','provider_services.priceperhour','provider.provider_commission')->leftjoin('provider_services','provider_services.provider_id','=','bookings.provider_id','provider_services.service_sub_category_id','=','bookings.service_category_type_id')->join('provider','provider.id','=','bookings.provider_id')->where('bookings.id',$booking_request_id)->first();
				// echo $tax;
				// die;
				//echo $bookingdetails;die;
                
                $to_time = strtotime($time);
                $from_time = strtotime($bookingdetails['job_start_time']);
                $totalmins_worked=round(abs($to_time - $from_time) / 60);
                $priceperminute=$bookingdetails['priceperhour']/60;
                //$gst_percent=$tax['percentage'];
                $provider_commission=$bookingdetails['provider_commission'];
                //$taxname=$tax['service_name'];
                $taxname="tax";

                //die;
                $totalcost=round($totalmins_worked * $priceperminute,2);

                if(count($tax) > 0)
                {
				
            				foreach($tax as $get_tax){
            					
                            $cal_cost [] =($totalcost/100)*$get_tax->percentage;
                            $cal_cost1 =($totalcost/100)*$get_tax->percentage;
            				
            				  
            				$tax_amt = DB::table('tax_calculation')->insert([
                              'booking_id'=>$booking_request_id,
                              'tax_name'=>$get_tax->service_name,
                              'tax_amount'=>$get_tax->percentage,
                              'tax_total_amount'=>$cal_cost1 + $totalcost
                              ]);
            				
            				
            				}
          $gst_cost  = array_sum($cal_cost);
                    
                  }else
                  {
                    $gst_cost = 0;
                  }
				 //die;
				$gst_percent  = 15;
				
				 //echo $gst_cost;
				 

                $admin_share=($totalcost/100)*$provider_commission;

                $provider_share=$totalcost - $admin_share;

                $cost_tax_included=$totalcost + $gst_cost;
                $accept=Bookings::where(['id'=>$booking_request_id,'provider_id'=>$providerid])->update(['status'=>'Completedjob','job_end_time'=>$time,'endjob_timestamp'=>$customer_time,'cost'=>$totalcost,'worked_mins'=>$totalmins_worked,'gst_percent'=>$gst_percent,'gst_cost'=>$gst_cost,'total_cost'=>$cost_tax_included,'tax_name'=>$taxname,'admin_share'=>$admin_share,'provider_share'=>$provider_share]);

                if($accept){
                  $user_id=Bookings::where('id',$booking_request_id)->value('user_id');
          $user_details=User::where('id',$user_id)->first();
                  $provider_id=Bookings::where('id',$booking_request_id)->value('provider_id');
$provider_name=Provider::where('id',$provider_id)->value('first_name');
          $response['error']='false';   
          $response['error_message']=trans('lang.updated.');
          $gcpm = new FCMPushNotification();
          $title = trans('lang.Job Completed');
          $message =$provider_name .' '. trans('lang.has completed the job.');
          $os=$user_details['os_type'];
          $data = array('image' => "NULL",
                 'title' => $title,
               'notification_type'=>'Completedjob');
          $gcpm->setDevices($user_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KWAfy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
          $newresponse[] = $gcpm->send($message, $data,$os,$title, $message); 
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
        
           public function paymentaccept(request $request , $locale){
            app()->setLocale($locale);
           if($request->id){
            $providerid=Auth::guard('provider')->user()->id;  

            $bookingid=$request->id;
            $status=Bookings::where(['id'=>$bookingid,'provider_id'=>$providerid])->value('status');
            if($status == 'Waitingforpaymentconfirmation')
            {
            Bookings::where(['id'=>$bookingid,'provider_id'=>$providerid])->update(['status'=>'Reviewpending','admin_owe_status'=>'pending','provider_owe_status'=>'completed','payment_type'=>'cash']);
            $user_id=Bookings::where('id',$bookingid)->value('user_id');
          $user_details=User::where('id',$user_id)->first();
          $user_name=$user_details['first_name'];
                  $provider_id=Bookings::where('id',$bookingid)->value('provider_id');
$provider_name=Provider::where('id',$provider_id)->value('first_name');
          $response['error']='false';   
          $response['error_message']=trans('lang.updated.');
          $gcpm = new FCMPushNotification();
          $title = trans('lang.Thanks for using Gbaleh.');
          $message =$provider_name .' '. trans('lang.has confirmed your payment.');
          $os=$user_details['os_type'];
          $data = array('image' => "NULL",
                 'title' => $title,'notification_type'=>'Reviewpending');
          $gcpm->setDevices($user_details['fcm_token']);
          // $gcpm->setDevices("epERrayTJmw:APA91bFNs1QwHNnVZdqId4_GKSqZylK-k6A2VbTSsvpHXoKbOTJCTHNZm13KcbP7247dAiiG16iXZDo6MV4ZO-Bb0-KWAfy3mkxI1Kj4jQ_UKkTxjVUn3o5XfbXqHZ3ONBdna0GZGteX");
          $newresponse[] = $gcpm->send($message, $data,$os,$title, $message); 
               $response['error']='false';
               $response['error_message']=trans('lang.Updated.');                   
            }else{
             $response['error']='true';
               $response['error_message']=trans('lang.Invalid Booking Request');   
            }
           }else{
               $response['error']='true';
               $response['error_message']=trans('lang.Invalid Id');
           }
           echo json_encode($response);
        }
        public function logout(request $request , $locale)
{
  app()->setLocale($locale);
    $providerid=Auth::guard('provider')->user()->id;
    $update=Provider::where('id',$providerid)->update(['fcm_token'=>" "]);
    
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
       
public function user_reviews(request $request , $locale)
{
  app()->setLocale($locale);
	if($request->id && $request->rating && $request->booking_id)
  {
      $userid=$request->id;
      $providerid=Auth::guard('provider')->user()->id;
     $rating =$request->rating;
     $booking_id =$request->booking_id;

     $reviewinsert=new Userreviews();
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
       Bookings::where('id',$booking_id)->update(['isProviderReviewed'=>'1']);  
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

public function add_category(request $request , $locale){
  app()->setLocale($locale);
//       $provider_categories=$request->category;
       
    if($request->category_id && $request->sub_category_id && $request->quickpitch && $request->priceperhour && $request->experience)
    {
            $providerid=Auth::guard('provider')->user()->id;

            $check_add_category=Providerservices::where(['provider_id'=>$providerid,'service_category_id'=>$request->category_id,'service_sub_category_id'=>$request->sub_category_id])->get();

             // print_r($check_add_category);
             // print_r($check_add_category->provider_id);
             // print_r($check_add_category['id']);
             // exit;

              //exit;

           if(!$check_add_category->isEmpty()){


       $response['error']='true';
   $response['error_message']=trans('lang.already existed');
          echo json_encode($response); 

   die;
           
           }else{
            if($request->category_id && $request->sub_category_id && $request->quickpitch && $request->priceperhour && $request->experience)
       {
        try {
        $addcategory=new Providerservices();
        $addcategory->service_category_id=$request->category_id;
        $addcategory->service_sub_category_id=$request->sub_category_id;
        $addcategory->provider_id=$providerid;
        $addcategory->quickpitch=$request->quickpitch;
        $addcategory->priceperhour=$request->priceperhour;
        $addcategory->experience=$request->experience;
        $addcategory->save();   
         } catch (\Illuminate\Database\QueryException $ex) {
             $jsonresp=$ex->getMessage();
          $response['error']='true';
          $response['error_message']=$jsonresp; 
          echo json_encode($response); 
          die;
         } 

   $response['error']='false';
   $response['error_message']=trans('lang.successfully Created');
    }else{
                $response['error']='true';
   $response['error_message']=trans('lang.Not Created');
    }

           
           }
               
 
}
    


       
       echo json_encode($response);
}


public function edit_category(request $request , $locale){
  app()->setLocale($locale);
    if($request->provider_service_id && $request->priceperhour && $request->quickpitch && $request->experience){
        
        $provider_service_id=$request->provider_service_id;
        $priceperhour=$request->priceperhour;
        $quickpitch=$request->quickpitch;
        $experience=$request->experience;
        
        $provider_services=Providerservices::where('id',$provider_service_id)->first();
        if($provider_services)
        {
            Providerservices::where('id',$provider_service_id)->update(['priceperhour'=>$priceperhour,'quickpitch'=>$quickpitch,'experience'=>$experience]);
        
               $response['error']='false';
   $response['error_message']=trans('lang.successfully Updated');
        }else{
            $response['error']='true';
   $response['error_message']=trans('lang.Not Updated');
        }
            
    }
    echo json_encode($response);
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
    }else{
         $response['error']="true";
         $response['error_message']=trans('lang.cannot fetch details'); 
         $response['data']=$coupon; 

    }
    echo json_encode($response);
}

public function delete_category(request $request , $locale){
  app()->setLocale($locale);
    if($request->provider_service_id)
    {
        $provider_services_id=$request->provider_service_id;
        Providerservices::where('id',$provider_services_id)->delete();
        
        $response['error']='false';
        $response['error_message']=trans('lang.Success Fully Deleted.');
    }else{
        $response['error']='true';
        $response['error_message']=trans('lang.fail');
    }
    
    echo json_encode($response);
}


public function providercal(request $request)
{
  echo "hello world";
}



public function  providercalender(request $request , $locale){
  app()->setLocale($locale);

$providerid=Auth::guard('provider')->user()->id;

$proivder_date= date("m",strtotime($request->date));


$results=Bookings::Select('bookings.id','bookings.provider_share','bookings.admin_share','bookings.status','bookings.payment_type','bookings.provider_share','bookings.Pending_time','bookings.Accepted_time','bookings.Rejected_time','bookings.Finished_time','bookings.CancelledbyUser_time','bookings.CancelledbyProvider_time','bookings.StarttoCustomerPlace_time','bookings.cost','bookings.worked_mins','bookings.tax_name','bookings.gst_percent','bookings.gst_cost','bookings.total_cost','bookings.booking_date','users.email as useremail','users.mobile as usermobile','users.image as userimages','service_category.category_name','service_sub_category.sub_category_name','user_address.latitude as user_latitude','user_address.longitude as user_longitude','service_sub_category.sub_category_name','service_sub_category.long_description','service_sub_category.short_description','service_sub_category.icon')->join('users','bookings.user_id','=','users.id')
                      ->join('service_sub_category','bookings.service_category_type_id','=','service_sub_category.id')
                      ->join('user_address','bookings.address_id','=','user_address.id')  
                      ->join('service_category' ,'bookings.service_category_id','=','service_category.id')                   
                      ->whereMonth('bookings.updated_at','=',$proivder_date)
                       ->where('bookings.provider_id',$providerid)
                      ->get();





if($results->isEmpty()){

 $response['error']='true';
     $response['error_message']=trans('lang.No recodrds Available');



}else{

 $response['error']='false';
     $response['error_message']=trans('lang.success');
     $response['providerworkingdetails']=$results;


}


echo json_encode($response);

}




public function  calenderbookingdetails(request $request , $locale){
  app()->setLocale($locale);

 $providerid=Auth::guard('provider')->user()->id;

$date=$request->date;

// $bookingid=$request->booking_id;
// $month= date("m", strtotime($proivder_date));

$pending='Pending';
 $provider_image=Auth::guard('provider')->user()->image;
$pending=Bookings::Select(DB::raw(" CONCAT(users.first_name,' ',users.last_name)AS Name"),DB::raw(" CONCAT(provider.first_name,' ',provider.last_name)AS providername"),'bookings.id','bookings.provider_share','bookings.admin_share','bookings.status','bookings.payment_type','bookings.provider_share','bookings.Pending_time','bookings.Accepted_time','bookings.Rejected_time','bookings.Finished_time','bookings.CancelledbyUser_time','bookings.CancelledbyProvider_time','bookings.StarttoCustomerPlace_time','bookings.cost','bookings.worked_mins','bookings.tax_name','bookings.gst_percent','bookings.gst_cost','bookings.total_cost','bookings.booking_date','users.email as useremail','users.mobile as usermobile','users.image as userimage','service_category.category_name','service_sub_category.sub_category_name','user_address.latitude as user_latitude','user_address.longitude as user_longitude','service_sub_category.sub_category_name','service_sub_category.long_description','service_sub_category.short_description','service_sub_category.icon','time_slots.timing','provider_schedules.days','bookings.cost','bookings.worked_mins','user_address.address_line_1','user_address.doorno','user_address.landmark','bookings.booking_order_id','bookings.booking_date','bookings.job_start_time','bookings.job_end_time','bookings.cost','bookings.worked_mins','bookings.tax_name','bookings.gst_percent','bookings.gst_cost','bookings.total_cost',
DB::raw("case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag"),'user_address.title','user_address.city')->join('users','bookings.user_id','=','users.id')
                      ->join('service_sub_category','bookings.service_category_type_id','=','service_sub_category.id')
                      ->join('user_address','bookings.address_id','=','user_address.id')  
                      ->join('provider','bookings.provider_id','=','provider.id')
                      ->join('provider_schedules','bookings.provider_schedules_id','=','provider_schedules.id')
                       ->join('time_slots','time_slots.id','=','provider_schedules.time_slots_id')
                      ->join('service_category' ,'bookings.service_category_id','=','service_category.id')                   
                      ->whereDate('bookings.booking_date','=',$date)
                       ->where(['bookings.provider_id'=>$providerid,'bookings.status'=>$pending])
                                             ->get();

$accepted=Bookings::Select(DB::raw(" CONCAT(users.first_name,' ',users.last_name)AS Name"),DB::raw(" CONCAT(provider.first_name,' ',provider.last_name)AS providername"),'bookings.id','bookings.provider_share','bookings.admin_share','bookings.status','bookings.payment_type','bookings.provider_share','bookings.Pending_time','bookings.Accepted_time','bookings.Rejected_time','bookings.Finished_time','bookings.CancelledbyUser_time','bookings.CancelledbyProvider_time','bookings.StarttoCustomerPlace_time','bookings.cost','bookings.worked_mins','bookings.tax_name','bookings.gst_percent','bookings.gst_cost','bookings.total_cost','bookings.booking_date','users.id as user_id','users.email as useremail','users.mobile as usermobile','users.image as userimage','service_category.category_name','service_sub_category.sub_category_name','user_address.latitude as user_latitude','user_address.longitude as user_longitude','service_sub_category.sub_category_name','service_sub_category.long_description','service_sub_category.short_description','service_sub_category.icon','time_slots.timing','provider_schedules.days','bookings.cost','bookings.worked_mins','user_address.address_line_1','user_address.doorno','user_address.landmark','bookings.booking_order_id','bookings.booking_date','bookings.job_start_time','bookings.job_end_time','bookings.cost','bookings.worked_mins','bookings.tax_name','bookings.gst_percent','bookings.gst_cost','bookings.total_cost','bookings.startjob_timestamp','bookings.endjob_timestamp',DB::raw("case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag"),'user_address.title','user_address.city','bookings.isProviderReviewed')->join('users','bookings.user_id','=','users.id')
                      ->join('service_sub_category','bookings.service_category_type_id','=','service_sub_category.id')
                      ->join('user_address','bookings.address_id','=','user_address.id')  
                      ->join('provider','bookings.provider_id','=','provider.id')
                      
                      ->join('provider_schedules','bookings.provider_schedules_id','=','provider_schedules.id')
                       ->join('time_slots','time_slots.id','=','provider_schedules.time_slots_id')
                      ->join('service_category' ,'bookings.service_category_id','=','service_category.id')                   
                      ->whereDate('bookings.booking_date','=',$date)
                       ->where('bookings.provider_id',$providerid)
                                             ->get();

 if($pending)
        {
         $response['Pending']=$pending; 
        }else{   
         $response['error_message']=trans('lang.No Pending Bookings Bookings Available');   
         $response['Pending']=[];
        }
        if($accepted)
        {
         $response['Accepted']=$accepted;   
        }else{   
         $response['error_message']=trans('lang.No Accepted Bookings Available');   
         $response['Accepted']=[];
        }

        $response['error']='false';
        $response['message']=trans('lang.success');
        $response['image']=$provider_image;

echo json_encode($response);


}

















}
