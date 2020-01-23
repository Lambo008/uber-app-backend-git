<?php

namespace App;


class FCMPushNotification
{
    var $url = 'https://fcm.googleapis.com/fcm/send';
    var $serverApiKey = "AAAAUsDZnAk:APA91bG5T4kyBOIwKcgSOA8KzlrZ_9OLzKuHMpvWC78rC07JT05PRxjU6Tf7nPN6Amw1hW_EaeUkg8XUKMh7arl6pvVzWNcQBwTkDdVpSAtFl5rFnILBKIfe9dhmDwWlige8iPc3tfWh";
    var $devices = array();

    /*
        Constructor
        @param $apiKeyIn the server API key
    */
    function FCMPushNotification($apiKeyIn){
        $this->serverApiKey = $apiKeyIn;
    }

    /*
        Set the devices to send to
        @param $deviceIds array of device tokens to send to
    */
    function setDevices($deviceIds){

        if(is_array($deviceIds)){
            $this->devices = $deviceIds;
        } else {
            $this->devices = array($deviceIds);
        }

    }

    /*
        Send the message to the device
        @param $message The message to send
        @param $data Array of data to accompany the message
    */
    function send($message, $data = false, $os = false,$title,$body){

        if(!is_array($this->devices) || count($this->devices) == 0){
            $this->error("No devices set");
        }

        if(strlen($this->serverApiKey) < 8){
            $this->error("Server API Key not set");
        }

        if($os == "iOS")
        {
            $msg['sound'] = 'default';
            $msg['icon'] = '@drawable/icon';
            $msg['title'] = $title;
            $msg['body'] = $body;
            $fields = array(
                'registration_ids'  => $this->devices,
                'data'              => array( "message" => $message,
                                                "image" => "false"),
                'content_available' => true,
                'priority'   => 'high',
                'badge' => 1,
                'sound' => 'default',
                'notification' =>$msg
            );
        }
        else
        {
            $fields = array(
                'registration_ids'  => $this->devices,
	              'sound'		=> 'default',
                'vibrate'	=> 'default',
                'data'              => array( "message" => $message,
                                                "image" => $data['image'],
                                                "title" => $data['title'])
            );
        }
        //print_r($fields);
//        if(is_array($data)){
//            foreach ($data as $key => $value) {
//                $fields['data'][$key] = $value;
//            }
//        }

        $headers = array(
            'Authorization: key=' . $this->serverApiKey,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt( $ch, CURLOPT_URL, $this->url );

        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );

        // Avoids problem with https certificate
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);

        // Execute post
        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);

        return $result;
    }

    function error($msg){
        echo "Android send notification failed with error:";
        echo "\t" . $msg;
        exit(1);
    }
}
