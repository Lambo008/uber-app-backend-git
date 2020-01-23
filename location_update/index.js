var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);


var FCM = require('fcm-node');
    var serverKey = 'AAAAUsDZnAk:APA91bG5T4kyBOIwKcgSOA8KzlrZ_9OLzKuHMpvWC78rC07JT05PRxjU6Tf7nPN6Amw1hW_EaeUkg8XUKMh7arl6pvVzWNcQBwTkDdVpSAtFl5rFnILBKIfe9dhmDwWlige8iPc3tfWh'; //put your server key here
    var fcm = new FCM(serverKey);

app.get('/', function(req, res) {
   res.sendfile('index.html');
});
var mysql = require('mysql')


var connection  = mysql.createPool({
  host     : 'localhost',
  user     : 'root',
  password : 'gbaleh@123',
  database : 'uber_test'
});

function time()
{                                                         //get current time
    var now = new Date();
  var year = "" + now.getFullYear();
  var month = "" + (now.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
  var day = "" + now.getDate(); if (day.length == 1) { day = "0" + day; }
  var hour = "" + now.getHours(); if (hour.length == 1) { hour = "0" + hour; }
  var minute = "" + now.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
  var second = "" + now.getSeconds(); if (second.length == 1) { second = "0" + second; }
  return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
}
io.on('connection', function(socket) {

   socket.on('UpdateLocation', function(data) {
    console.log(data);
      var latitude=data.latitude;
      var longitude=data.longitude;
      var id=data.provider_id;
      var Bearing=data.bearing;
      
      var update_location = "UPDATE provider SET latitude=?,longitude=?,Bearing=? where id=?";
  connection.query(update_location, [latitude,longitude,Bearing,id], function(err, rows, fields) {
if(err){
   console.log(err);
}else{
   var getlocation="GetLocation"+"-"+id;
   io.sockets.emit(getlocation, data);
}
  });
      
   })

socket.on('GetRandomRequest',function(data){

 console.log(data);
// console.log(socket.id);
    var user_id=data.user_id;
    var service_sub_category_id=data.subcategory_id;
    var service_category_id=data.category_id;
    var time_slot_id=data.time_slot_id;
    var latitude=data.latitude;
    var longitude=data.longitude;
    var radius=data.radius;
    var address_id=data.address_id;
    var date=data.date;
      var weekday=new Array("Sun","Mon","Tue","Wed","Thu",
                "Fri","Sat");
        var day_convert = new Date(date);
        var Pending_time = time();
    var day = weekday[day_convert.getDay()];
var get_random_query='SELECT distinct(provider.id) as app_provider_id,CONCAT(first_name," ",last_name) as name,provider.email,provider.image,provider.mobile,provider.fcm_token,provider.latitude,provider.longitude,provider.about, (  6371 * acos (cos ( radians('+latitude+') )* cos( radians( provider.latitude ) )* cos( radians( provider.longitude ) - radians('+longitude+') )+ sin ( radians('+latitude+') )* sin( radians( provider.latitude ) ))) AS distance,provider.addressline1,provider.addressline2,provider.city,provider.state,provider.zipcode,provider.fcm_token,provider.os_type,service_sub_category.sub_category_name,(select id from provider_schedules where provider_id=app_provider_id and days="'+day+'" and time_slots_id="'+time_slot_id+'" LIMIT 1) as provider_schedules_id,(select id from provider_services where provider_id=app_provider_id and service_sub_category_id="'+service_sub_category_id+'" LIMIT 1) as provider_service_id from provider Inner join provider_schedules on provider_schedules.provider_id=provider.id Inner join provider_services on provider_services.provider_id=provider.id inner join service_sub_category on service_sub_category.id=provider_services.service_sub_category_id where provider.id NOT IN(select distinct(provider.id) from provider inner join schedule_bookings on schedule_bookings.provider_id=provider.id inner join provider_schedules on provider_schedules.provider_id=provider.id where provider_schedules.time_slots_id="'+time_slot_id+'" and provider_schedules.days="'+day+'" and schedule_bookings.provider_service_id="'+service_sub_category_id+'" and schedule_bookings.booking_date="'+date+'" and schedule_bookings.provider_schedules_id=provider_schedules.id) AND provider_schedules.time_slots_id="'+time_slot_id+'" and provider_schedules.days="'+day+'" and provider_services.service_sub_category_id="'+service_sub_category_id+'" Having distance < "'+radius+'" ';
connection.query(get_random_query,function(error,results,fields){
if(error)
{
 console.log(error);
}else{
  // console.log(results);
  var all_providers=results;
  var length_provider=results.length;
  var booking_order_id='UX'+(Math.floor(Math.random() * (9999999 - 1000000 + 1)) + 1000000);

var insert_bookings='Insert into bookings(user_id,booking_order_id,booking_type,service_category_id,service_category_type_id,booking_date,status,address_id,Pending_time)Values("'+user_id+'","'+booking_order_id+'","random","'+service_category_id+'","'+service_sub_category_id+'","'+date+'","PENDING","'+address_id+'","'+Pending_time+'")';
 connection.query(insert_bookings,function(error,insert_bookings,fields){
   if(error)
   {
    console.log(error);
   }else{
    // console.log(insert_bookings);
   var booking_id=insert_bookings.insertId;
     var user_booking_sockets="user_booking"+"-"+user_id;
     var booking_data={};
     booking_data.booking_id=booking_id;
     io.sockets.emit(user_booking_sockets,booking_data);
                     
   var time_data={};
   time_data.booking_id=booking_id;
   time_data.user_id=user_id;
   time_data.date=date;

  all_providers.forEach(function(all_provider_value,all_provider_index){
    var provider_id=all_provider_value.app_provider_id
    var provider_schedules_id=all_provider_value.provider_schedules_id
    var provider_service_id=all_provider_value.provider_service_id
    var sub_category_name=all_provider_value.sub_category_name
    
    var booking_data_id=time_data.booking_id;
    var user_data_id=time_data.user_id;
    var user_date=time_data.date;
 
    var send_request='Insert into provider_request(user_id,provider_id,provider_schedules_id,provider_service_id,booking_date,booking_id)Values("'+user_id+'","'+provider_id+'","'+provider_schedules_id+'","'+provider_service_id+'","'+date+'","'+booking_id+'")';
    connection.query(send_request,function(error,rows,fields){
      if(error)
      {
         console.log(error);
      }else{
        var prov_token=all_provider_value.fcm_token;
        console.log(prov_token);
                              var message = { //this may vary according to the message type (single recipient, multicast, topic, et cetera)
        to: prov_token,
        collapse_key: 'Random',
        
        notification: {
            title: 'طلب خدمة جديد', 
            body: 'لديك طلب جديد لخدمة  '+sub_category_name,
            sound: "default",
            icon: "ic_launcher"
        },
        
        data: {  //you can send only notification or only data(or include both)
            title: 'طلب خدمة جديد', 
            body: 'لديك طلب جديد لخدمة  '+sub_category_name,
            sound: "default",
            icon: "ic_launcher" 
        }
    };
    
    fcm.send(message, function(err, response){
        if (err) {
            console.log("Something has gone wrong!");
        } else {
            console.log("Successfully sent with response: ", response);
        }
    });


  if(0 === --length_provider)
  {
       var i=0;
       var clearinterval=setInterval(function (time_data) { 
               i++;

               if(i == 30)
               {
                  var check_status='select * from provider_request where booking_id="'+booking_id+'" and status="OPEN" ';
                  connection.query(check_status,function(error,check_status_rows,fields){
                   if(error)
                   {
                     console.log(error);
                   }else{
                    if(check_status_rows.length > 0)
                    {
                     var delete_request='DELETE from bookings where id="'+booking_id+'"';
                     connection.query(delete_request,function(error,delete_request_rows,fields){
                       if(error)
                       {
                        console.log(error);
                       }else{
                        var request_comp="request_completed"+"-"+user_id; 
                 io.sockets.emit(request_comp,{'error':'completed'});
                  clearInterval(clearinterval);
                       } 
                     });
                  
                    }
                   }
                  }); 
                 clearInterval(clearinterval);
               }else{
                console.log('checking');
                         var check_accepted='SELECT provider_request.id,CONCAT(provider.first_name,provider.last_name) AS provider_name,provider_request.provider_id,provider_request.status,provider.fcm_token from provider_request inner join provider on provider.id=provider_request.provider_id where provider_request.booking_id="'+booking_id+'" and provider_request.booking_date="'+user_date+'" and provider_request.user_id="'+user_data_id+'" and provider_request.provider_id=provider.id and provider_request.status="CLOSE" and is_cancelled_provider="0" LIMIT 1';
                  connection.query(check_accepted,function(error,check_accepted_rows,fields){
                    if(error)
                    {
                     console.log(error);
                    }else{

                      if(check_accepted_rows.length > 0)
                      {
                      var dcm_token=check_accepted_rows[0].fcm_token;
                      var provider_name=check_accepted_rows[0].provider_name;
                      var message = { //this may vary according to the message type (single recipient, multicast, topic, et cetera)
                                        to: dcm_token, 
                                        collapse_key: 'Random_push',
                                        
                                        notification: {
                                            title: 'الموافقة على طلب الخدمة', 
                                            body: provider_name+' وافق على طلب الخدمة',
                                            sound: "default",
                                            icon: "ic_launcher"
                                        },
                                        
                                        data: {  //you can send only notification or only data(or include both)
                                            title: 'الموافقة على طلب الخدمة', 
                                            body: provider_name+' وافق على طلب الخدمة',
                                            sound: "default",
                                            icon: "ic_launcher" 
                                        }
                                    };
    
    fcm.send(message, function(err, response){
        if (err) {
            console.log("Something has gone wrong!");
        } else {
            console.log("Successfully sent with response: ", response);
        }
    });

      var all_data=check_accepted_rows[0];
                       var user_sockets="random_request_accepted"+"-"+user_data_id;
                      io.sockets.emit(user_sockets,all_data);
                      clearInterval(clearinterval);
                      }
     
    console.log(i);
                    
                    }
                  })
               }
         
               // if(clearInterval(check)){
               //   io.sockets.emit(Request_Completed,{'error':'completed'});
               // }
               //  } 
}, 1000); 
  }




      }
    }); 
   }); 
   }
 })



}
});
   });

});







http.listen(3000, function() {
   console.log('listening on localhost:3000');
});