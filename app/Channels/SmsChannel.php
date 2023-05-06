<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use IPPanel\Client;
use IPPanel\Errors\Error;
use IPPanel\Errors\ResponseCodes;
use App\Traits\ApiResponse;

class SmsChannel
{
    use ApiResponse;

    public function send($notifiable, Notification $notification)
    {

//      get message
        $message = $notification->toSms($notifiable);

//      get phone number
        $number = $notifiable->phones;

//        token panel sms
        $client = new Client("5CHF5oMqNviHL8nJzKIDZ0jIRJJ9TootH3-v0tyXavI=");

//        connection panel sms
        try {
            $pattern = $client->sendPattern("2mz94um0bab1fk2", "+983000505", "$number", ['code' => $message[0]]);
            var_dump("Code sent");
        } catch (Error $e) {
            var_dump($e->unwrap());
            echo $e->getCode();

            if ($e->code() == ResponseCodes::ErrUnprocessableEntity) {
                echo "Unprocessable entity";
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
            echo $e->getCode();
        }
    }
}
