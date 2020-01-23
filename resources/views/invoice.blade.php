<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="css/style.css">
 

<link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="js/jquery.min.js"></script> 
  <script src="js/bootstrap.min.js"></script> -->

  <style>
  .table-bordered td, .table-bordered th {
    /* border: 1px solid #dee2e6; */
}
body{
    margin:0px;
    padding:0px;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: left;
    background-color: #fff;
}
.h6, h6 {
    font-size: 1rem;
    margin: 10px;
}
.order-hd {
    border: 1px solid #ccc;
    padding: 4px 0px;
}
.text-algn{
    text-align:end;
}
.brder-non{
    border:none;
}
.whole-container{

}
.container {
    max-width: 70%;
    margin:0px auto;
}
.col-md-12 {
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
}
.row {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
    margin-bottom: 4px;
}
table {
    border-collapse: collapse;
    width: 100%;
}
.table td, .table th {
   margin-bottom: 4px;
    padding: .75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.navigation{
    width: 100%;
    height: 74px;
    float: left;
    background: #77809f;
    margin-bottom: 39px;
}
.footers{
    width: 100%;
    height: 54px;
    float: left;
    background: #77809f;
    margin-top: 50px;

     text-align: center;
    margin-top: 18px;
    font-size: 11px;
    color: #fff;
    text-shadow: 0px 1px 15px #000;
    letter-spacing: 1px;
}
.logo{
   width:40%;
    height:auto;
    float:left;
    text-align: center;
}
.logo img{
    width: 12%;
    margin-top: 2%;
}
.header-nav{
    /*width:30%;*/
    height:auto;
    float:left;
}
.header-nav h2{
    text-align: center;
    color: #fff;
    text-shadow: 0px 1px 14px #000;
}
.table th {
color:#980606;
padding:1%;
}
.order{
    color: #2214ec;
    margin: 3px;
}

  </style>
</head>
<body>
<div class="whole-container">
   <div class="navigation">
       <div class="logo"><img src="images/logo.png"></div>
       <div class="header-nav"><h2>GBALEH_INVOICE</h2> </div>

   </div>

  <div class="container">
    <div class="row order-hd">
      <div class="col-md-12">
          <h6 class="order"><b>Booking Details</b></h6>
      </div>

    </div>

   <div class="row">
     <div class="col-md-12 tbl-rw">
       <table class="table table-bordered">
           <tr>
                <th>Booking OrderId</th>
                  <th>Booking Date</th>
                  <th>Service Name</th>
                  <th>Service Status</th>
           </tr>

           @foreach($status as $stat)
          <tr>
                  <td>
                   {{$stat['booking_order_id']}}
                  </td>
                  <td>{{$stat['booking_date']}} </td>
                  <td> {{$stat['sub_category_name']}}</td>
                  <td>{{$stat['status']}}</td>
              </tr>
              @endforeach
       </table>

     </div>
     

   </div>


    <div class="row order-hd">
      <div class="col-md-12">
          <h6 class="order"><b>Provider Details</b></h6>
      </div>

    </div>



<div class="row">
     <div class="col-md-12 tbl-rw">
       <table class="table table-bordered">
           <tr>
                <th>ProviderName</th>
                  <th> Email</th>
                   
                      <th>Job Start_time </th>
                    <th>Job End_time</th>
                  
               
           </tr>

           @foreach($status as $stat)
          <tr>
                  <td>
                   {{$stat['providername']}}
                  </td>
                  <td>{{$stat['email']}} </td>
                 
                 <td>{{$stat['job_start_time']}} </td>
                  <td> {{$stat['job_end_time']}}</td>
              </tr>
              @endforeach
       </table>

     </div>
     

   </div>















 <div class="row order-hd">
      <div class="col-md-12">
          <h6 class="order"><b>Payment Details</b></h6>
      </div>

    </div>

   <div class="row">
        <div class="col-md-12 tbl-rw">
          <table class="table table-bordered">
              @foreach($status as $stat)
              <tr>
                  <td style="border-right: none;"></td>
                  <td style="border-left:none;border-right:none;"></td>
                  <td class="text-algn" style="border-left: none;">Billing Name</td>
                  <td> {{$stat['username']}}</td>
              </tr>
              <tr>
                    <td style="border-right: none;"></td>
                    <td style="border-left:none;border-right:none;"></td>
                    <td class="text-algn" style="border-left: none;">Date</td>
                    <td> {{$stat['days']}}</td>
                </tr>
                <tr>
                        <td style="border-right: none;"></td>
                        <td style="border-left:none;border-right:none;"></td>
                        <td class="text-algn" style="border-left: none;">Time</td>
                        <td> {{$stat['timing']}}</td>
                    </tr>
                    <tr>
                            <td style="border-right: none;"></td>
                            <td style="border-left:none;border-right:none;"></td>
                            <td class="text-algn" style="border-left: none;">Working Hours</td>
                            <td> {{$stat['worked_mins']}}</td>
                        </tr>

                        <tr>
                            <td style="border-right: none;"></td>
                            <td style="border-left:none;border-right:none;"></td>
                            <td class="text-algn" style="border-left: none;">Price</td>
                            <td> {{$stat['cost']}}</td>
                        </tr>

                        <tr>
                            <td style="border-right: none;"></td>
                            <td style="border-left:none;border-right:none;"></td>
                            <td class="text-algn" style="border-left: none;">VAT Tax</td>
                            <td> {{$stat['gst_cost']}}</td>
                        </tr>
                        <tr>
                            <td style="border-right: none;"></td>
                            <td style="border-left:none;border-right:none;"></td>
                            <td class="text-algn" style="border-left: none;">Total</td>
                            <td> {{$stat['total_cost']}}</td>
                        </tr>
                        @endforeach
          </table>
   
        </div>
        
   
      </div>


















  </div>

<div class="footers"><h6>GBALEH_INVOICE</h6></div>
</div>
</body>
</html>
