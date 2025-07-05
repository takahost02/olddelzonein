<?php

namespace App\Traits;

trait NotificationTrait
{
    public function sendNotification($title, $notification_data, $data, $registrationIdsArray)
    {
        $headers = [
            "Content-Type:application/json",
            "Authorization:key=AAAA4fQOP7c:APA91bFQ5CnWTLW8s1-DqDQ0F8liGRK9CQ0GoWf9vhoZ946u2IEDbVLHkpgtb2MaOOTfdREtsbkqIn3Sey_weKFqkpY2hSiT3a0e-PyOjnrhQCdzUaJ3v87TwkmtiNBrnVBV2a_UGJAE"
        ];

        $bodytxt = (isset($notification_data['status']) && $notification_data['status'] == 1) ? $notification_data['name'] : 'By ' . $notification_data['name'];

        $notification = [
            'title' => $title,
            'body' => $bodytxt,
            'android_channel_id' => 'high_importance_channel',
            'sound' => 'default',
            'mutable-content' => 1,
            'content-available' => 1,
            'badge' => 1,
            'priority' => 'high',
           
        ];

        // if(isset($notification_data['image']) && $notification_data['image'] !== null)
        // {
        //     $notification['image'] = $notification_data['image'];
        // }
    

       
       
        //dd($title, $notification_data, $data, $registrationIdsArray);
        $data = [
            'notification' => $notification,
            'content_available' => true,
            'data' => $data,
            'registration_ids' => [$registrationIdsArray],
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($ch);

        //dd($response);
        if ($response === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }
}