<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Admin;
use App\Imageupload;
use App\Provider;
use App\Bookings;
use App\Useraddress;
use App\Location;
// use App\Timeslots;
use App\Servicetax;
use App\Category;
use App\Timeslots;
use App\Subcategory;
use App\Providerservices;
use App\Providerschedules;
use App\Providerreviews;
// use App\Coupons;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;

class AdminController extends Controller
{
    
public function Login(request $request)
{
        if($request->email && $request->password && $request->user_type)
        {   
            $provider=$request->user_type;
            $email=$request->email;
            $password=$request->password;
            $check_adminlogin=Admin::where('email',$email)->first();
     
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
                $response['error_message']=$jsonresp;
                echo json_encode($response); die;
            }
            $access_token = json_decode((string) $res->getBody(), true)['access_token'];
 
             $response['error']='false'; 
             $response['error_message']='Success'; 
             $response['access_token']=$access_token;


        }else{
            $response['error']='true';
      $response['error_message']='Mandatory Parameters are missing';
    }
    echo json_encode($response);
}

     
    public function listusers(request $request){
    

        $listusers=User::select(DB::raw("id,first_name,last_name,email,mobile,status,image"))->orderBy('created_at', 'desc')->get();

      
        //Your query

        // foreach($listusers as $users)
        // {
        //     $users->image="http://18.218.132.174/uber_test/public/images/".$users->image;
        // }
        if($listusers){
            $response['error']='false';
            $response['error_message']='success';
            $response['list_users']=$listusers;
        }else{
            $response['error']='true';
            $response['error_message']='no users available';
            $response['list_users']=[];
        }
        echo json_encode($response);
    }

    
    
    public function getuserdetails(request $request){
        if($request->id)
        {
            $id=$request->id;
            $userdetails=User::where('id',$id)->first();
            $all_bookings=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,CONCAT(users.first_name,users.last_name) AS username,CONCAT(provider.first_name,provider.last_name) AS providername,service_sub_category.sub_category_name,time_slots.timing,bookings.booking_date,provider_schedules.days,bookings.rating,bookings.status,bookings.feedback,bookings.created_at"))
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                       ->where('bookings.user_id',$id)
                       ->get();
 
 foreach($all_bookings as $bookings)
 {
            $bookings->time=strtotime($bookings->created_at);
 }
            if($userdetails && $all_bookings)
            {
                $response['error']='false';
                $response['error_message']='success';
                $response['user_details']=$userdetails;
                $response['booking_details']=$all_bookings;
            }else{
                $response['error']='true';
                $response['error_message']='nodetails';
                $response['user_details']=[];    
            }
        }else{
                $response['error']='true';
                $response['error_message']='nodetails';
                $response['user_details']=[];
        }
        echo json_encode($response);
    }
    
    public function listproviders(request $request){
        $listproviders=Provider::select(DB::raw("id,first_name,last_name,email,mobile,image,status,provider_commission"))->orderBy('created_at', 'desc')->get();
     
        if($listproviders){
            $response['error']='false';
            $response['error_message']='success';
            $response['list_providers']=$listproviders;
        }else{
            $response['error']='true';
            $response['error_message']='no users available';
            $response['list_providers']=[];
        }
        echo json_encode($response);
    }

     public function listbannerimages(request $request){
        $listbannerimages=DB::table('banner_images')->get();
     
        if($listbannerimages){
            $response['error']='false';
            $response['error_message']='success';
            $response['list_banners']=$listbannerimages;
        }else{
            $response['error']='true';
            $response['error_message']='no images available';
            $response['list_banners']=[];
        }
        echo json_encode($response);
    }


    public function editbanner(request $request)
{
  if($request->id && $request->banner_name && $request->icon)
  {
   $id=$request->id;     
   $icon=$request->icon; 
   $banner_name = $request->banner_name;    
   
   $editbanner=DB::table('banner_images')
   ->where('id',$id)
   ->update(['banner_logo'=>$icon,'banner_name'=>$banner_name]);
   
   if($editbanner)
   {
       $response['error']='false';
       $response['error_message']='banner updated .';
       
   }else{
       $response['error']='true';
       $response['error_message']='Error in banner updating.';     
   }
  }else{
       $response['error']='true';
       $response['error_message']='Invalid Params';     
  }
  echo json_encode($response);
}
    
    
        public function getproviderdetails(request $request){
        if($request->id)
        {
            $id=$request->id;
            $providerdetails=Provider::where('id',$id)->first();
//            foreach($providerdetails as $providers)
//        {
            //$providerdetails['image']="http://18.218.132.174/UberDoo/public/images/".$providerdetails['image'];
        
            $all_bookings=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,CONCAT(users.first_name,users.last_name) AS username,CONCAT(provider.first_name,provider.last_name) AS providername,service_sub_category.sub_category_name,time_slots.timing,bookings.booking_date,provider_schedules.days,bookings.rating,bookings.status,bookings.feedback,bookings.cost,bookings.created_at"))
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                       ->where('bookings.provider_id',$id)
                       ->get();
             
             


             $allservices=Providerservices::select('provider_services.id','service_category.category_name','service_sub_category.sub_category_name','provider_services.priceperhour','provider_services.quickpitch','provider_services.experience')->join('service_category','provider_services.service_category_id','=','service_category.id')->join('service_sub_category','provider_services.service_sub_category_id','=','service_sub_category.id')->where('provider_services.provider_id',$id)->get();          
            
            $allschedules=Providerschedules::select('provider_schedules.id','time_slots.timing','provider_schedules.days')->join('time_slots','time_slots.id','=','provider_schedules.time_slots_id')->where(['provider_schedules.provider_id'=>$id,'provider_schedules.status'=>'1'])->get(); 

            if($providerdetails)
            {
                $response['error']='false';
                $response['error_message']='success';
                $response['provider_details']=$providerdetails;
                $response['provider_bookings']=$all_bookings;
                $response['allschedules']=$allschedules;
                $response['allservices']=$allservices;
            }else{
                $response['error']='true';
                $response['error_message']='nodetails';
                $response['provider_details']=[];  
                $response['allschedules']=$allschedules;
                $response['allservices']=$allservices;  
            }
        }else{
                $response['error']='true';
                $response['error_message']='nodetails';
                $response['provider_details']=[];
                $response['allschedules']=$allschedules;
                $response['allservices']=$allservices;
        }
        echo json_encode($response);
    }
    public function allbookings(request $request){
         
 $all_bookings=Bookings::select(DB::raw("bookings.id,bookings.booking_order_id,bookings.updated_at,CONCAT(users.first_name,' ',users.last_name) AS username,CONCAT(provider.first_name,' ',provider.last_name) AS providername,service_sub_category.sub_category_name,time_slots.timing,bookings.booking_date,provider_schedules.days,bookings.rating,bookings.status,bookings.feedback,bookings.created_at"))
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
//                       ->where('bookings.user_id',$userid)
                       ->orderBy('bookings.id', 'desc')
                       ->get();
 
 // foreach($all_bookings as $bookings)
 // {
 //            $bookings->time=strtotime($bookings->created_at);
 // }
if($all_bookings)
{
    $response['error']='false';
    $response['error_message']='success';
    $response['all_bookings']=$all_bookings;
}else{
    
    $response['error']='true';
    $response['error_message']='No Bookings';
    $response['all_bookings']=[];
}
 echo json_encode($response);
    }


    public function get_payments()
{

        $pay=Bookings::where(['bookings.status'=>'Finished','bookings.payment_type'=>'cash','bookings.admin_owe_status'=>'pending'])->select('provider.first_name','provider.last_name','bookings.id','bookings.booking_order_id','bookings.payment_type','bookings.cost','bookings.provider_share','bookings.admin_share','bookings.gst_cost','bookings.total_cost','bookings.payment_type')->join('provider','provider.id','=','bookings.provider_id');  


        $payments=Bookings::where(['bookings.status'=>'Finished','bookings.payment_type'=>'card','bookings.provider_owe_status'=>'pending'])->select('provider.first_name','provider.last_name','bookings.id','bookings.booking_order_id','bookings.payment_type','bookings.cost','bookings.provider_share','bookings.admin_share','bookings.gst_cost','bookings.total_cost','bookings.payment_type')->union($pay)->join('provider','provider.id','=','bookings.provider_id')->get();  


//     $pay1 = DB::table('bookings')
//             ->where(['status'=>'Finished','payment_type'=>'cash','admin_owe_status'=>'pending'])
//             ->join('provider','provider.id','=','bookings.provider_id')
//             ->get();

// $payments = DB::table('bookings')
//             ->where(['status'=>'Finished','payment_type'=>'card','provider_owe_status'=>'pending'])
//             ->join('provider','provider.id','=','bookings.provider_id')
//             ->union($pay1)
//             ->get();

    $response['error']='false';
    $response['error_message']='all payments';
    $response['all_payments']=$payments;
    echo json_encode($response);
}
    
public function update_payment_status(request $request){
   
  if($request->id && $request->payment_type)
  {
      $booking_id=$request->id;
      $type=$request->payment_type;
      if($type == "cash")
      {
          Bookings::where(['id'=>$booking_id,'payment_type'=>$type])->update(['admin_owe_status'=>"completed"]); 
      }else{
          Bookings::where(['id'=>$booking_id,'payment_type'=>$type])->update(['provider_owe_status'=>"completed"]);
      }
      $response['error']='false';
      $response['error_message']='success';
  }else{
      $response['error']='true';
      $response['error_message']='fail';
  }
    echo json_encode($response);
} 
    
   public function listcategory(request $request){
           $category=Category::get();
     
//           $type_category=Category::get();
//      $list_types=Category::select('type')->distinct()->get();
   
//    
//    
//    foreach($list_types as $types)
//    {
////         $maintype=$types->type;
//         
//         foreach($type_category as $newcategory)
//         {
//                 $newcategory->image="http://18.218.132.174/UberDoo/public/images/".$newcategory->image;
//                 $newcategory->icon="http://18.218.132.174/UberDoo/public/images/".$newcategory->icon; 
//         }
////         
//         $all_categories[$maintype]=$type_category;
//      }
//      $arraycategory[]=$all_categories;
    if($category)
    {
        $response['error']='false';
        $response['error_message']='success';
        $response['list_category']=$category;
    }else{
         $response['error']='true';
        $response['error_message']='No Category';
        $response['list_category']=[];
    }
    echo json_encode($response);
   }
   public function listsubcategory(request $request)
{

    $subcategory=Subcategory::select('service_sub_category.id','service_sub_category.sub_category_name','service_sub_category.image','service_sub_category.icon','service_sub_category.short_description','service_sub_category.long_description','service_category.category_name','service_sub_category.status')->join('service_category','service_sub_category.category_id','=','service_category.id')->get();
         //         foreach($subcategory as $newsubcategory)
         // {
         //         $newsubcategory->image="http://18.218.132.174/UberDoo/public/images/".$newsubcategory->image;
         //         $newsubcategory->icon="http://18.218.132.174/UberDoo/public/images/".$newsubcategory->icon; 
         // }
    if($subcategory)
    {
        $response['error']='false';
        $response['error_message']='success';
        $response['list_subcategory']=$subcategory;
    }else{
        $response['error']='true';
        $response['error_message']='No Subcategory';
        $response['list_subcategory']=[];
    }   

    echo json_encode($response);
}

public function addcategory(request $request)
{

  if($request->category_name && $request->type && $request->short_description && $request->long_description)
  {

   $Category_name=$request->category_name;    
   $icon=$request->icon;   
   $type=$request->type;   
   $shortdescription=$request->short_description;   
   $longdescription=$request->long_description;   
   // $baseamount= $request->baseamount;  
   
   try {
   $addcategory=new Category();
   $addcategory->category_name=$Category_name;
   $addcategory->icon=$icon;
   $addcategory->type=$type;
   $addcategory->short_description=$shortdescription;
   $addcategory->long_description=$longdescription;
   // $addcategory->baseamount=$baseamount;
   $addcategory->save();
       
   } catch (Exception $ex) {
        $jsonresp=$ex->getMessage();
        $response['error']='true';
        $response['error_message']=$jsonresp;
        echo json_encode($response); die;
   }
   
   if($addcategory)
   {
       $response['error']='false';
       $response['error_message']='Category created .';
       
   }else{
       $response['error']='true';
       $response['error_message']='Error in Creating.';     
   }
  }else{
       $response['error']='true';
       $response['error_message']='Invalid Params';     
  }
  echo json_encode($response);
}

public function updatecategory(request $request)
{
  if($request->id && $request->type && $request->short_description && $request->long_description)
  {
    $id=$request->id;
    $category_name=$request->category_name;
    $icon=$request->icon;
    $type=$request->type;
    $shortdescription=$request->short_description;
    $longdescription=$request->long_description;
    // $baseamount= $request->baseamount;  

    $updatecategory=Category::where('id',$id)->update(['category_name'=>$category_name,'icon'=>$icon,'type'=>$type,'short_description'=>$shortdescription,'long_description'=>$longdescription]);

    if($updatecategory)
    {
     $response['error']='false';
     $response['error_message']='Category Updated';
    }else{
    $response['error']='true';
    $response['error_message']='Category not updated';
    }
  }else{
    $response['error']='true';
    $response['error_message']='Mandatory Params are missing';
  }

  echo json_encode($response); 
}

   public function update_baseamount_status(request $request)
  {
    if($request->id && $request->status)
    {
      $id=$request->id;
     
      if($request->status == 'true')
      {
        $status='active';
      }else{
        $status = 'blocked';
      }
      $updatestatus=Category::where('id',$id)->update(['baseamount_status'=>$status]);
       if($updatestatus)
       {
       $response['error']='false';
       $response['error_message']='updated';
       }else{
       $response['error']='true';
       $response['error_message']='Not Updated';
       }
    }else{
       $response['error']='true';
       $response['error_message']='Mandatory Params are empty';
       
    }
        echo json_encode($response);
    }
    
    public function updatesubcategory(request $request)
{
  if($request->id && $request->short_description && $request->long_description)
  {
    $id=$request->id;
    $category_name=$request->subcategory_name;
    $icon=$request->icon;

    $shortdescription=$request->short_description;
    $longdescription=$request->long_description;
    $updatesubcategory=Subcategory::where('id',$id)->update(['sub_category_name'=>$category_name,'icon'=>$icon,'short_description'=>$shortdescription,'long_description'=>$longdescription]);
    if($updatesubcategory)
    {
     $response['error']='false';
     $response['error_message']='SubCategory Updated';
    }else{
    $response['error']='true';
    $response['error_message']='SubCategory not updated';
    }
  }else{
    $response['error']='true';
    $response['error_message']='Mandatory Params are missing';
  }

  echo json_encode($response); 
}



public function addsubcategory(request $request)
{
  if($request->category_id && $request->sub_category_name && $request->icon && $request->short_description && $request->long_description)
  {
   $categoryid=$request->category_id;   
   $Category_name=$request->sub_category_name;     
   $icon=$request->icon;   
   $type=$request->type;   
   $shortdescription=$request->short_description;   
   $longdescription=$request->long_description;   
   
   try {
   $addsubcategory=new Subcategory();
   $addsubcategory->category_id=$categoryid;
   $addsubcategory->sub_category_name=$Category_name;
   $addsubcategory->image=" ";
   $addsubcategory->icon=$icon;
   $addsubcategory->short_description=$shortdescription;
   $addsubcategory->long_description=$longdescription;
   $addsubcategory->save();
       
   } catch (Exception $ex) {
        $jsonresp=$ex->getMessage();
        $response['error']='true';
        $response['error_message']=$jsonresp;
        echo json_encode($response); die;
   }
   
   if($addsubcategory)
   {
       $response['error']='false';
       $response['error_message']='SubCategory created .';
       
   }else{
       $response['error']='true';
       $response['error_message']='Error in Creating.';     
   }
  }else{
       $response['error']='true';
       $response['error_message']='Invalid Params';     
  }
  echo json_encode($response);
}

public function viewreviews(request $request)
{
 
    $allreviews=Providerreviews::select(DB::raw("concat(provider.first_name,' ',provider.last_name) as Providername,concat(users.first_name,' ',users.last_name) as Username,provider_reviews.feedback,provider_reviews.rating"))->join('provider','provider.id','=','provider_reviews.provider_id')->join('users','users.id','=','provider_reviews.user_id')->get();
    
   if($allreviews){
       $response['error']='false';
       $response['error_message']='success';
       $response['all_reviews']=$allreviews;
   }else{
       $response['error']='true';
       $response['error_message']='No Reviews';
//       $response['all_reviews']=$allreviews;
       
   } 
    echo json_encode($response);
}


public function add_tax(request $request){
 
    if($request->tax_name && $request->tax_percentage)
    {
        $tax_name=$request->tax_name;
        $tax_percentage=$request->tax_percentage;
        try {
          $addtax=new Servicetax();
          $addtax->service_name=$tax_name;
          $addtax->percentage=$tax_percentage;
          $addtax->status="1";
          $addtax->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            $jsonresp=$ex->getMessage();
                             $response['error']='true';
$response['error_message']=$jsonresp;
echo json_encode($response); 
die;
        }
        $response['error']='false';
        $response['error_message']='Tax Created.';
    }else{
        $response['error']='true';
        $response['error_message']='Invalid Params';
    }
    echo json_encode($response);
}
 public function list_tax(request $request)
 {
   $listax=Servicetax::get();
   if($listax)
   {
       $response['error']='false';
       $response['error_message']='success';
       $response['all_taxes']=$listax;
   }else{
        $response['error']='true';
       $response['error_message']='No Taxes';
   }
   
   echo json_encode($response);
 }
 
 public function imageupload(request $request)
 {  
        if($request->hasFile('file'))
        { 
                $image=$request->file;
                $filesize = filesize($image);
                $fileName = $image->getClientOriginalName();
                $fileExtension = $image->getClientOriginalExtension();
                $fileName = rand(11111, 99999) . '.' . $fileExtension;
                $destinationPath = 'images';
                $upload_success = $image->move($destinationPath, $fileName);
                if($upload_success)
                {
                $response['error']='false';    
                $response['error_message']='success';    
                $response['image']="http://18.218.132.174/uber_test/public/images/".$fileName;
                }else{
                    $response['error']='true';    
                $response['error_message']='not uploaded';
                }   
        }else{
                $response['error']='true';    
                $response['error_message']='No Image';
                }
            echo json_encode($response);
 }


public function viewslots(request $request)
{
  $gettimeslots=Timeslots::select(DB::raw("id,fromTime,toTime,HOUR(fromTime) as from_hour,Minute(toTime) as from_minute,HOUR(toTime) as to_hour,Minute(toTime) as to_minute,timing,created_at"))->get();
  if($gettimeslots)
  {
   $response['error']='false';
   $response['error_message']='success';
   $response['all_slots']=$gettimeslots;
  }else{
    $response['error']='true';
   $response['error_message']='No Slots Available';
   $response['all_slots']=[];
  }
  echo json_encode($response); 
}

 public function add_timeslots(request $request)
 {
   $slot=$request->slot;
   $fromTime=$request->fromTime;
   $toTime=$request->toTime;
   
   if($slot && $request->fromTime && $request->toTime)
   {
$add_timeslots=new Timeslots();
   $add_timeslots->timing=$slot;
   $add_timeslots->fromTime=$fromTime;
   $add_timeslots->toTime=$toTime;
   $add_timeslots->save();
   if($add_timeslots)
   {
    $response['error']='false';
    $response['error_mesage']='Timeslot created';
   }else{
    $response['error']='true';
    $response['error_message']='Not Created';
   }
   }else{
    $response['error']='true';
    $response['error_message']='Mandatory Parameters are missing'; 
   }
   
   echo json_encode($response);
 }

  public function update_user_status(request $request)
  {
    if($request->id && $request->status)
    {
      $id=$request->id;
     
      if($request->status == 'true')
      {
        $status='active';
      }else{
        $status = 'blocked';
      }
      $updatestatus=User::where('id',$id)->update(['status'=>$status]);
       if($updatestatus)
       {
       $response['error']='false';
       $response['error_message']='updated';
       }else{
       $response['error']='true';
       $response['error_message']='Not Updated';
       }
    }else{
       $response['error']='true';
       $response['error_message']='Mandatory Params are empty';
       
    }

    echo json_encode($response);
  }
  public function HTTP_set_course(request $request)
 {  
        if($request->hasFile('file'))
        { 
                $image=$request->file;
                $filesize = filesize($image);
                $fileName = $image->getClientOriginalName();
                $fileExtension = $image->getClientOriginalExtension();
                $fileName = rand(11111, 99999) . '.' . $fileExtension;
                $destinationPath = 'images';
                $upload_success = $image->move($destinationPath, $fileName);
                if($upload_success)
                {
                $response['error']='false';    
                $response['error_message']='success';    
                $response['image']="http://18.218.132.174/uber_test/public/images/".$fileName;
                }else{
                    $response['error']='true';    
                $response['error_message']='not uploaded';
                }   
        }else{
                $response['error']='true';    
                $response['error_message']='No Image';
                }
            echo json_encode($response);
 }
    public function update_provider_status(request $request)
  {
    if($request->id && $request->status)
    {
      $id=$request->id;
     
      if($request->status == 'true')
      {
        $status='active';
      }else{
        $status = 'blocked';
      }
      $updatestatus=Provider::where('id',$id)->update(['status'=>$status]);
       if($updatestatus)
       {
       $response['error']='false';
       $response['error_message']='updated';
       }else{
       $response['error']='true';
       $response['error_message']='Not Updated';
       }
    }else{
       $response['error']='true';
       $response['error_message']='Mandatory Params are empty';
       
    }

    echo json_encode($response);
  }
    
        public function update_category_status(request $request)
  {
    if($request->id && $request->status)
    {
      $id=$request->id;
     
      if($request->status == 'true')
      {
        $status='active';
      }else{
        $status = 'blocked';
      }
      $updatestatus=Category::where('id',$id)->update(['status'=>$status]);
       if($updatestatus)
       {
       $response['error']='false';
       $response['error_message']='updated';
       }else{
       $response['error']='true';
       $response['error_message']='Not Updated';
       }
    }else{
       $response['error']='true';
       $response['error_message']='Mandatory Params are empty';
       
    }
        echo json_encode($response);
    }
        public function update_subcategory_status(request $request)
  {
    if($request->id && $request->status)
    {
      $id=$request->id;
     
      if($request->status == 'true')
      {
        $status='active';
      }else{
        $status = 'blocked';
      }
      $updatestatus=Subcategory::where('id',$id)->update(['status'=>$status]);
       if($updatestatus)
       {
       $response['error']='false';
       $response['error_message']='updated';
       }else{
       $response['error']='true';
       $response['error_message']='Not Updated';
       }
    }else{
       $response['error']='true';
       $response['error_message']='Mandatory Params are empty';
       
    }
        echo json_encode($response);
    }

 public function dashboard(request $request)
 {
     $view_users=User::count();
     $view_providers=Provider::count();
     $allbookings=Bookings::count();

     $android_users=User::where('os_type','android')->count();
     $ios_users=User::where('os_type','iOS')->count();

     $android_providers=Provider::where('os_type','android')->count();
     $ios_providers=Provider::where('os_type','iOS')->count();
     
     $all_services=Subcategory::get();
     
      foreach($all_services as $allcategory)
      {
        $totalcount=DB::table('provider_services')->where('service_sub_category_id',$allcategory->id)->count();
        $allcategory->totalproviders=$totalcount;
       

      }
 

     $pendingbookings=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,CONCAT(users.first_name,users.last_name) AS username,CONCAT(provider.first_name,provider.last_name) AS providername,provider.id as provider_id,provider.image,service_sub_category.sub_category_name,service_sub_category.icon,time_slots.timing,bookings.booking_date,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,provider_schedules.days,bookings.job_start_time,bookings.job_end_time,bookings.cost,user_address.doorno,user_address.landmark,user_address.address_line_1,bookings.rating,bookings.status,bookings.feedback,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.worked_mins,bookings.created_at,bookings.updated_at"))
                       ->join('user_address', 'bookings.address_id', '=', 'user_address.id')
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                       ->where(['bookings.status'=>'Pending'])
                       ->groupBy('bookings.id')
                       ->orderBy('bookings.updated_at', 'desc')
                       ->get();
     $Acceptedbookings=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,CONCAT(users.first_name,users.last_name) AS username,CONCAT(provider.first_name,provider.last_name) AS providername,provider.id as provider_id,provider.image,service_sub_category.sub_category_name,service_sub_category.icon,time_slots.timing,bookings.booking_date,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,provider_schedules.days,bookings.job_start_time,bookings.job_end_time,bookings.cost,user_address.doorno,user_address.landmark,user_address.address_line_1,bookings.rating,bookings.status,bookings.feedback,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.worked_mins,bookings.created_at,bookings.updated_at"))
                       ->join('user_address', 'bookings.address_id', '=', 'user_address.id')
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                       ->where(['bookings.status'=>'Accepted'])
                       ->groupBy('bookings.id')
                       ->orderBy('bookings.updated_at', 'desc')
                       ->get();
     $Startedjobbookings=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,CONCAT(users.first_name,users.last_name) AS username,CONCAT(provider.first_name,provider.last_name) AS providername,provider.id as provider_id,provider.image,service_sub_category.sub_category_name,service_sub_category.icon,time_slots.timing,bookings.booking_date,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,provider_schedules.days,bookings.job_start_time,bookings.job_end_time,bookings.cost,user_address.doorno,user_address.landmark,user_address.address_line_1,bookings.rating,bookings.status,bookings.feedback,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.worked_mins,bookings.created_at,bookings.updated_at"))
                       ->join('user_address', 'bookings.address_id', '=', 'user_address.id')
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                       ->where(['bookings.status'=>'Startedjob'])
                       ->groupBy('bookings.id')
                       ->orderBy('bookings.updated_at', 'desc')
                       ->get();
     $Completedjobbookings=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,CONCAT(users.first_name,users.last_name) AS username,CONCAT(provider.first_name,provider.last_name) AS providername,provider.id as provider_id,provider.image,service_sub_category.sub_category_name,service_sub_category.icon,time_slots.timing,bookings.booking_date,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,provider_schedules.days,bookings.job_start_time,bookings.job_end_time,bookings.cost,user_address.doorno,user_address.landmark,user_address.address_line_1,bookings.rating,bookings.status,bookings.feedback,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.worked_mins,bookings.created_at,bookings.updated_at"))
                       ->join('user_address', 'bookings.address_id', '=', 'user_address.id')
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                       ->where(['bookings.status'=>'Completedjob'])
                       ->groupBy('bookings.id')
                       ->orderBy('bookings.updated_at', 'desc')
                       ->get();
     
     $livebooking=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,CONCAT(users.first_name,users.last_name) AS username,CONCAT(provider.first_name,provider.last_name) AS providername,provider.id as provider_id,provider.image,service_sub_category.sub_category_name,service_sub_category.icon,time_slots.timing,bookings.booking_date,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,provider_schedules.days,bookings.job_start_time,bookings.job_end_time,bookings.cost,user_address.doorno,user_address.landmark,user_address.address_line_1,bookings.rating,bookings.status,bookings.feedback,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.worked_mins,bookings.created_at,bookings.updated_at"))
                       ->join('user_address', 'bookings.address_id', '=', 'user_address.id')
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                       ->groupBy('bookings.id')
                       ->orderBy('bookings.updated_at', 'desc')
                       ->get();
$provider_jan_reg=DB::select(DB::raw("SELECT count(*) as provider_jan_reg FROM provider WHERE month(created_at) = 1 AND YEAR(CURDATE())"));
             $provider_feb_reg=DB::select(DB::raw("SELECT count(*) as provider_feb_reg FROM provider WHERE month(created_at) = 2 AND YEAR(CURDATE())"));
             $provider_mar_reg=DB::select(DB::raw("SELECT count(*) as provider_mar_reg FROM provider WHERE month(created_at) = 3 AND YEAR(CURDATE())"));
             $provider_apr_reg=DB::select(DB::raw("SELECT count(*) as provider_apr_reg FROM provider WHERE month(created_at) = 4 AND YEAR(CURDATE())"));
             $provider_may_reg=DB::select(DB::raw("SELECT count(*) as provider_may_reg FROM provider WHERE month(created_at) = 5 AND YEAR(CURDATE())"));
             $provider_jun_reg=DB::select(DB::raw("SELECT count(*) as provider_jun_reg FROM provider WHERE month(created_at) = 6 AND YEAR(CURDATE())"));
             $provider_jul_reg=DB::select(DB::raw("SELECT count(*) as provider_jul_reg FROM provider WHERE month(created_at) = 7 AND YEAR(CURDATE())"));
             $provider_aug_reg=DB::select(DB::raw("SELECT count(*) as provider_aug_reg FROM provider WHERE month(created_at) = 8 AND YEAR(CURDATE())"));
             $provider_sep_reg=DB::select(DB::raw("SELECT count(*) as provider_sep_reg FROM provider WHERE month(created_at) = 9 AND YEAR(CURDATE())"));
             $provider_oct_reg=DB::select(DB::raw("SELECT count(*) as provider_oct_reg FROM provider WHERE month(created_at) = 10 AND YEAR(CURDATE())"));
             $provider_nov_reg=DB::select(DB::raw("SELECT count(*) as provider_nov_reg FROM provider WHERE month(created_at) = 11 AND YEAR(CURDATE())"));
             $provider_dec_reg=DB::select(DB::raw("SELECT count(*) as provider_dec_reg FROM provider WHERE month(created_at) = 12 AND YEAR(CURDATE())")); 

             $user_jan_reg=DB::select(DB::raw("SELECT count(*) as user_jan_reg FROM users WHERE month(created_at) = 1 AND YEAR(CURDATE())"));
             $user_feb_reg=DB::select(DB::raw("SELECT count(*) as user_feb_reg FROM users WHERE month(created_at) = 2 AND YEAR(CURDATE())"));
             $user_mar_reg=DB::select(DB::raw("SELECT count(*) as user_mar_reg FROM users WHERE month(created_at) = 3 AND YEAR(CURDATE())"));
             $user_apr_reg=DB::select(DB::raw("SELECT count(*) as user_apr_reg FROM users WHERE month(created_at) = 4 AND YEAR(CURDATE())"));
             $user_may_reg=DB::select(DB::raw("SELECT count(*) as user_may_reg FROM users WHERE month(created_at) = 5 AND YEAR(CURDATE())"));
             $user_jun_reg=DB::select(DB::raw("SELECT count(*) as user_jun_reg FROM users WHERE month(created_at) = 6 AND YEAR(CURDATE())"));
             $user_jul_reg=DB::select(DB::raw("SELECT count(*) as user_jul_reg FROM users WHERE month(created_at) = 7 AND YEAR(CURDATE())"));
             $user_aug_reg=DB::select(DB::raw("SELECT count(*) as user_aug_reg FROM users WHERE month(created_at) = 8 AND YEAR(CURDATE())"));
             $user_sep_reg=DB::select(DB::raw("SELECT count(*) as user_sep_reg FROM users WHERE month(created_at) = 9 AND YEAR(CURDATE())"));
             $user_oct_reg=DB::select(DB::raw("SELECT count(*) as user_oct_reg FROM users WHERE month(created_at) = 10 AND YEAR(CURDATE())"));
             $user_nov_reg=DB::select(DB::raw("SELECT count(*) as user_nov_reg FROM users WHERE month(created_at) = 11 AND YEAR(CURDATE())"));
             $user_dec_reg=DB::select(DB::raw("SELECT count(*) as user_dec_reg FROM users WHERE month(created_at) = 12 AND YEAR(CURDATE())")); 

     $response['view_users']=$view_users;
     $response['view_providers']=$view_providers;
     $response['all_bookings']=$allbookings;
     $response['pending_bookings']=$pendingbookings;
     $response['accepted_bookings']=$Acceptedbookings;
     $response['started_bookings']=$Startedjobbookings;
     $response['completed_bookings']=$Completedjobbookings;
     $response['live_bookings']=$livebooking;
     $response['android_users']=$android_users;
     $response['ios_users']=$ios_users;
     $response['android_providers']=$android_providers;
     $response['ios_providers']=$ios_providers;
     
     $response['user_jan_reg']=$user_jan_reg;
     $response['user_feb_reg']=$user_feb_reg;
     $response['user_mar_reg']=$user_mar_reg;
     $response['user_apr_reg']=$user_apr_reg;
     $response['user_may_reg']=$user_may_reg;
     $response['user_jun_reg']=$user_jun_reg;
     $response['user_jul_reg']=$user_jul_reg;
     $response['user_aug_reg']=$user_aug_reg;
     $response['user_sep_reg']=$user_sep_reg;
     $response['user_oct_reg']=$user_oct_reg;
     $response['user_nov_reg']=$user_nov_reg;
     $response['user_dec_reg']=$user_dec_reg;

     $response['provider_jan_reg']=$provider_jan_reg;
     $response['provider_feb_reg']=$provider_feb_reg;
     $response['provider_mar_reg']=$provider_mar_reg;
     $response['provider_apr_reg']=$provider_apr_reg;
     $response['provider_may_reg']=$provider_may_reg;
     $response['provider_jun_reg']=$provider_jun_reg;
     $response['provider_jul_reg']=$provider_jul_reg;
     $response['provider_aug_reg']=$provider_aug_reg;
     $response['provider_sep_reg']=$provider_sep_reg;
     $response['provider_oct_reg']=$provider_oct_reg;
     $response['provider_nov_reg']=$provider_nov_reg;
     $response['provider_dec_reg']=$provider_dec_reg;

     $response['services_list']=$all_services;


     
     echo json_encode($response);
 }

public function getbookingdetails(request $request)
{
  if($request->id)
  {
    $booking_id=$request->id;
    $getdetails=Bookings::select(DB::raw("distinct(bookings.id),bookings.booking_order_id,CONCAT(users.first_name,' ',users.last_name) AS username,CONCAT(provider.first_name,' ',provider.last_name) AS providername,provider.id as provider_id,provider.image,users.image as userimage,users.email as useremail,provider.email as provideremail,service_sub_category.sub_category_name,service_sub_category.icon,time_slots.timing,bookings.booking_date,bookings.tax_name,bookings.gst_percent,bookings.gst_cost,bookings.total_cost,provider_schedules.days,bookings.job_start_time,bookings.job_end_time,bookings.cost,bookings.admin_share,bookings.provider_share,user_address.doorno,user_address.landmark,user_address.address_line_1,bookings.rating,bookings.status,bookings.feedback,case when(bookings.status) IN ('Completedjob','Reviewpending','Waitingforpaymentconfirmation','Finished') then '1' else '0' end as show_bill_flag,bookings.worked_mins,bookings.created_at,bookings.updated_at"))
                       ->join('user_address', 'bookings.address_id', '=', 'user_address.id')
                       ->join('users', 'bookings.user_id', '=', 'users.id')
                       ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                       ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                       ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                       ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                       ->where('bookings.id',$booking_id)
                       ->groupBy('bookings.id')
                       ->orderBy('bookings.updated_at', 'desc')
                       ->first();

    $startendjobdetails = DB::table('startendjobdetails')->where(['booking_id'=>$booking_id])->get();

      $response['error']='false';                
      $response['error_message']='success';                
      $response['detail_booking']=$getdetails; 
      $response['startendjobdetails']=$startendjobdetails; 
      
      echo json_encode($response);               
  }
}

public function all_provider_location(request $request)
{

  $allproviderlocation=Provider::select('provider.first_name','provider.image','provider.latitude','provider.longitude')->get();

  foreach($allproviderlocation as $providerlocation)
  {
        $providerlocation->content=$providerlocation->first_name." " ."<IMG BORDER='0' ALIGN='Right' SRC='$providerlocation->image'>";
        unset($providerlocation->first_name);
        unset($providerlocation->image);
  }
   
  $response['error']='false';
  $response['error_message']='success';
  $response['provider_location']=$allproviderlocation;

  echo json_encode($response);
  }


  public function about_us(request $request)
  {
      $static_pages=DB::table('static_pages')->where('type','about')->get();

      $response['error']='false';
      $response['error_message']='success';
      $response['static_pages']=$static_pages;

      echo json_encode($response);
  }
  public function faq(request $request)
  {
      $static_pages=DB::table('static_pages')->where('type','faq')->get();

      $response['error']='false';
      $response['error_message']='success';
      $response['static_pages']=$static_pages;

      echo json_encode($response);
  }
  public function terms(request $request)
  {
      $static_pages=DB::table('static_pages')->where('type','terms')->get();

      $response['error']='false';
      $response['error_message']='success';
      $response['static_pages']=$static_pages;

      echo json_encode($response);
  }

  public function update_percentage(request $request)
  {
   if($request->id && $request->provider_commission)
   { 
    $provider_commission=$request->provider_commission;
    $id=$request->id;
    $allproviderlocation=Provider::where('id',$id)->Update(['provider_commission'=>$provider_commission]);
      $response['error']='false';
      $response['error_message']='success';
    
   }else{
    $response['error']='true';
    $response['error_message']='Invalid Params';

   }
   echo json_encode($response);
  }

  public function update_slots(request $request)
  {
    if($request->id)
    {
      $timing=$request->timing;
      $fromTime=$request->fromTime;
      $toTime=$request->toTime;
      
      Timeslots::where('id',$request->id)->update(['timing'=>$timing,'fromTime'=>$fromTime,'toTime'=>$toTime]);
      $response['error']='false';
      $response['error_message']='success';
    }else{
      $response['error']='true';
      $response['error_message']='Invalid Params';

    }
    echo json_encode($response);
  }
  public function get_radius(request $request)
  {
    $radius_data=DB::table('radius')->where('id','1')->first();

    echo json_encode($radius_data);
  }

  public function update_radius(request $request){
    $radius=$request->radius;
    $radius_data=DB::table('radius')->where('id','1')->update(['radius'=>$radius]);
    $response['error']='false';
    $response['error_message']='Updated';
    echo json_encode($response);
  }
  
      public function update_tax_percentage(request $request)
  {
   if($request->id && $request->percentage && $request->service_name)
   { 
    $percentage=$request->percentage;
    $id=$request->id;
    $update_percentage=Servicetax::where('id',$id)->Update(['percentage'=>$percentage,'service_name'=>$request->service_name]);
      $response['error']='false';
      $response['error_message']='success';
   }else{
    $response['error']='true';
    $response['error_message']='Invalid Params';
   }
   echo json_encode($response);
  }

  public function delete_tax(request $request)
  {
   if($request->id)
   {
    $id=$request->id;
    $update_percentage=Servicetax::where('id',$id)->delete();
      $response['error']='false';
      $response['error_message']='success';
   }else{
    $response['error']='true';
    $response['error_message']='Invalid Params';
   }
   echo json_encode($response);
  }

    
      public function changepassword(request $request)
        {
        
            if($request->oldpassword && $request->newpassword)
            {
                $oldpassword=$request->oldpassword;
                $email=Auth::guard('admin')->user()->email;
                $userid=Auth::guard('admin')->user()->id;
                $password=Admin::where('id',$userid)->value('password');
                if(password_verify($oldpassword,$password))
                {
                       $newpassword=$request->newpassword;
                       $updatepassword=bcrypt($newpassword);
                       Admin::where('id',$userid)->update(['password'=>$updatepassword]);
                       
                       $response['error']='false';
                       $response['error_message']='Password changed';
                   
                }else{
                     $response['error']='true';
                       $response['error_message']='The Entered Password is incorrect';
                }
            }else{
                       $response['error']='true';
                       $response['error_message']='Mandatory Parameters are empty';
            }
            echo json_encode($response);
        }


         public function editpage(request $request)
        {
        $pos=$request->pos;
        $terms=$request->terms;
        $button1=$request->button1;
        $button2=$request->button2;
        echo json_encode($pos);
        echo json_encode($terms);
        echo json_encode($button1);
        echo json_encode($button2);

           if($pos != null && $pos != 'undefined'){

            $privacy_value = DB::table('page')->update(['privacyPolicy'=>$pos]); 
            if($privacy_value)
              {
               $response['error']='false';
               $response['error_message']='success';
              }else{
                $response['error']='true';
               $response['error_message']='No page data Available';
              }
              echo json_encode($pos); 
              echo json_encode($response); 
            }


           else if($terms != null && $terms != 'undefined'){
            $terms_value = DB::table('page')->update(['termsAndCondition'=>$terms]); 
            if($terms_value)
              {
               $response['error']='false';
               $response['error_message']='success';
              }else{
                $response['error']='true';
               $response['error_message']='No page data Available';
              }
              echo json_encode($terms);               
              echo json_encode($response);               
            }              

           else if($button1 != null && $button1 != 'undefined'){

            $button1_value = DB::table('page')->update(['privacyPolicyContent'=>$button1]); 
            if($button1_value)
              {
               $response['error']='false';
               $response['error_message']='success';
              }else{
                $response['error']='true';
               $response['error_message']='No page data Available';
              }
              echo json_encode($button1); 
              echo json_encode($response); 
            }


            else if($button2 != null && $button2 != 'undefined'){

            $button2_value = DB::table('page')->update(['termsAndConditionContent'=>$button2]); 
            if($button2_value)
              {
               $response['error']='false';
               $response['error_message']='success';
              }else{
                $response['error']='true';
               $response['error_message']='No page data Available';
              }
              echo json_encode($button2); 
              echo json_encode($response); 
            }else
            {
              // $response['error']='false';
              //  $response['error_message']='success';
              // echo json_encode($response); 

            }
          

        }

        public function showPag(request $request)
        {
          $page = DB::table('page')->select('privacyPolicy','termsAndCondition','privacyPolicyContent','termsAndConditionContent')->get();
          if($page)
          {
           $response['error']='false';
           $response['error_message']='success';
           $response['page']=$page;
          }else{
            $response['error']='true';
           $response['error_message']='No page data Available';
           $response['page']=[];
          }
          echo json_encode($response); 
        }

         public function data(request $request)
        {
          $name = $request->name;
          $password = $request->password;

          $page = DB::table('data')->select('name','password')->where('password',$password)->get();
          // print_r(?)
          if(!$page -> isEmpty())
          {
           $response['error']='false';
           $response['error_message']='success';
          }else{
            $response['error']='true';
           $response['error_message']='No page data Available';
          }
          echo json_encode($response); 
        }

        public function datainsert(request $request)
        {
          $teststripe = $request->teststripe;
          $livestripe = $request->livestripe;

          $page = DB::table('data')->update(['testkey'=>$teststripe,'livekey'=>$livestripe]);
          // print_r(?)
          if($page)
          {
           $response['error']='false';
           $response['error_message']='success';
          }else{
            $response['error']='true';
           $response['error_message']='No page data Available';
          }
          echo json_encode($response); 
        }

       public function add_coupons(request $request){
 
          if($request->coupon_code && $request->description && $request->valid_from && $request->valid_to && $request->discount_value)
          {
              $coupon_code=$request->coupon_code;
              $description=$request->description;
              $valid_from=$request->valid_from;
              $valid_to=$request->valid_to;
              $discount_value=$request->discount_value;
              $addcoupon = DB::table('coupons')->insert(['coupon_code'=>$coupon_code,'description'=>$description,'valid_from'=>$valid_from,'valid_to'=>$valid_to,'discount_value'=>$discount_value,'usage'=>'true']);
              if($addcoupon)
              {
              $response['error']='false';
              $response['error_message']='Coupon Created.';
          }else{
              $response['error']='true';
              $response['error_message']='Invalid Params';
          }
          echo json_encode($response);
        }
      }

       public function list_coupons(request $request)
       {
         $listcoupon = DB::table('coupons')->get();
         if($listcoupon)
         {
             $response['error']='false';
             $response['error_message']='success';
             $response['all_coupons']=$listcoupon;
         }else{
              $response['error']='true';
             $response['error_message']='No Coupons';
         }
         
         echo json_encode($response);
       }

       public function delete_coupons(request $request)
       {
        $id = $request->id;
         $listcoupon = DB::table('coupons')->where('id', $id)->delete();
         if($listcoupon)
         {
             $response['error']='false';
             $response['error_message']='success';
         }else{
              $response['error']='true';
             $response['error_message']='failure';
         }
         
         echo json_encode($response);
       }

       public function edit_coupons(request $request)
        {
          if($request->id && $request->coupon_code && $request->description && $request->valid_from && $request->valid_to && $request->discount_value)
          {
              $coupon_code=$request->coupon_code;
              $description=$request->description;
              $valid_from=$request->valid_from;
              $valid_to=$request->valid_to;
              $discount_value=$request->discount_value;    
           
           $editcoupons=DB::table('coupons')
           ->where('id',$request->id)
           ->update(['coupon_code'=>$coupon_code,'description'=>$description,'valid_from'=>$valid_from,'valid_to'=>$valid_to,'discount_value'=>$discount_value,'usage'=>'true']);
           
           if($editcoupons)
           {
               $response['error']='false';
               $response['error_message']='coupon updated .';
               
           }else{
               $response['error']='true';
               $response['error_message']='Error in coupon updating.';     
           }
          }else{
               $response['error']='true';
               $response['error_message']='Invalid Params';     
          }
          echo json_encode($response);
        }

public function update_tax_status(request $request)
  {
    if($request->id && $request->status)
    {
      $id=$request->id;
     
      if($request->status == 'true')
      {
        $status=1;
      }else{
        $status = 0;
      }
      $updatestatus=Servicetax::where('id',$id)->update(['status'=>$status]);
       if($updatestatus)
       {
       $response['error']='false';
       $response['error_message']='updated';
       }else{
       $response['error']='true';
       $response['error_message']='Not Updated';
       }
    }else{
       $response['error']='true';
       $response['error_message']='Mandatory Params are empty';
       
    }
        echo json_encode($response);
    }

    public function getearningreportsearchdetails(request $request)
{
  if($request->provider_id && $request->from_date && $request->to_date)
  {
    $provider_id = $request->provider_id;
    $from_date = $request->from_date;
    $to_date = $request->to_date;

     $all_bookings=Bookings::select(DB::raw("bookings.id,bookings.booking_order_id,CONCAT(provider.first_name,' ',provider.last_name) AS providername,service_sub_category.sub_category_name,time_slots.timing,bookings.booking_date,provider_schedules.days,bookings.rating,bookings.status,bookings.feedback,bookings.created_at,bookings.job_start_time,bookings.job_end_time,bookings.cost,bookings.worked_mins,bookings.total_cost,bookings.Pending_time,bookings.Pending_time,bookings.Accepted_time,bookings.Rejected_time,bookings.Finished_time,bookings.CancelledbyUser_time,bookings.CancelledbyProvider_time,bookings.StarttoCustomerPlace_time,bookings.payment_type,bookings.admin_owe_status,bookings.provider_owe_status , bookings.coupon_applied , bookings.reduced_cost, bookings.invoicelink"))
                         ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                         ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                         ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                         ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                         ->orderBy('bookings.id', 'desc')
                         ->WHERE(['bookings.provider_id'=>$provider_id])
                         ->whereBetween('bookings.booking_date',array($from_date,$to_date))
                         ->get();
  }
   if(empty($all_bookings))
  {
    $response['error']='true';
      $response['error_message']='No Bookings';
      $response['all_bookings']=[];
      
  }else{
      $response['error']='false';
      $response['error_message']='success';
      $response['all_bookings']=$all_bookings;
      
  }
  echo json_encode($response);

 }

 public function getbookingsearchdetails(request $request)
{
  if($request->user_id && $request->provider_id && !$request->from_date && !$request->to_date)
  {
    $user_id = $request->user_id;
    $provider_id = $request->provider_id;

     $all_bookings=Bookings::select(DB::raw("bookings.id,bookings.booking_order_id,bookings.updated_at,CONCAT(users.first_name,' ',users.last_name) AS username,CONCAT(provider.first_name,' ',provider.last_name) AS providername,service_sub_category.sub_category_name,time_slots.timing,bookings.booking_date,provider_schedules.days,bookings.rating,bookings.status,bookings.feedback,bookings.created_at"))
                         ->join('users', 'bookings.user_id', '=', 'users.id')
                         ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                         ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                         ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                         ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                         ->orderBy('bookings.id', 'desc')
                         ->WHERE(['bookings.user_id'=>$user_id,'bookings.provider_id'=>$provider_id])
                         ->get();
  }

  if($request->user_id && $request->provider_id && $request->from_date && $request->to_date)
  {
    $user_id = $request->user_id;
    $provider_id = $request->provider_id;
    $from_date = $request->from_date;
    $to_date = $request->to_date;

     $all_bookings=Bookings::select(DB::raw("bookings.id,bookings.booking_order_id,bookings.updated_at,CONCAT(users.first_name,' ',users.last_name) AS username,CONCAT(provider.first_name,' ',provider.last_name) AS providername,service_sub_category.sub_category_name,time_slots.timing,bookings.booking_date,provider_schedules.days,bookings.rating,bookings.status,bookings.feedback,bookings.created_at"))
                         ->join('users', 'bookings.user_id', '=', 'users.id')
                         ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                         ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                         ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                         ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                         ->orderBy('bookings.id', 'desc')
                         ->WHERE(['bookings.user_id'=>$user_id,'bookings.provider_id'=>$provider_id])
                         ->whereBetween('bookings.booking_date',array($from_date,$to_date))
                         ->get();
  }

  if(!$request->user_id && !$request->provider_id && $request->from_date && $request->to_date)
  {
    $from_date = $request->from_date;
    $to_date = $request->to_date;

     $all_bookings=Bookings::select(DB::raw("bookings.id,bookings.booking_order_id,bookings.updated_at,CONCAT(users.first_name,' ',users.last_name) AS username,CONCAT(provider.first_name,' ',provider.last_name) AS providername,service_sub_category.sub_category_name,time_slots.timing,bookings.booking_date,provider_schedules.days,bookings.rating,bookings.status,bookings.feedback,bookings.created_at"))
                         ->join('users', 'bookings.user_id', '=', 'users.id')
                         ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                         ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                         ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                         ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                         ->orderBy('bookings.id', 'desc')
                         ->whereBetween('bookings.booking_date',array($from_date,$to_date))
                         ->get();
  }

  if($request->user_id && !$request->provider_id && $request->from_date && $request->to_date)
  {
    $user_id = $request->user_id;
    $from_date = $request->from_date;
    $to_date = $request->to_date;

     $all_bookings=Bookings::select(DB::raw("bookings.id,bookings.booking_order_id,bookings.updated_at,CONCAT(users.first_name,' ',users.last_name) AS username,CONCAT(provider.first_name,' ',provider.last_name) AS providername,service_sub_category.sub_category_name,time_slots.timing,bookings.booking_date,provider_schedules.days,bookings.rating,bookings.status,bookings.feedback,bookings.created_at"))
                         ->join('users', 'bookings.user_id', '=', 'users.id')
                         ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                         ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                         ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                         ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                         ->orderBy('bookings.id', 'desc')
                         ->WHERE(['bookings.user_id'=>$user_id])
                         ->whereBetween('bookings.booking_date',array($from_date,$to_date))
                         ->get();
  }

   if(!$request->user_id && $request->provider_id && $request->from_date && $request->to_date)
  {
    $provider_id = $request->provider_id;
    $from_date = $request->from_date;
    $to_date = $request->to_date;

     $all_bookings=Bookings::select(DB::raw("bookings.id,bookings.booking_order_id,bookings.updated_at,CONCAT(users.first_name,' ',users.last_name) AS username,CONCAT(provider.first_name,' ',provider.last_name) AS providername,service_sub_category.sub_category_name,time_slots.timing,bookings.booking_date,provider_schedules.days,bookings.rating,bookings.status,bookings.feedback,bookings.created_at"))
                         ->join('users', 'bookings.user_id', '=', 'users.id')
                         ->join('provider', 'bookings.provider_id', '=', 'provider.id')
                         ->join('service_sub_category', 'bookings.service_category_type_id', '=', 'service_sub_category.id')
                         ->join('provider_schedules', 'bookings.provider_schedules_id', '=', 'provider_schedules.id')
                         ->join('time_slots', 'provider_schedules.time_slots_id', '=', 'time_slots.id')
                         ->orderBy('bookings.id', 'desc')
                         ->WHERE(['bookings.provider_id'=>$provider_id])
                         ->whereBetween('bookings.booking_date',array($from_date,$to_date))
                         ->get();
  }

  if(empty($all_bookings))
  {
    $response['error']='true';
      $response['error_message']='No Bookings';
      $response['all_bookings']=[];
      
  }else{
      $response['error']='false';
      $response['error_message']='success';
      $response['all_bookings']=$all_bookings;
      
  }
  echo json_encode($response);

}


    
}
