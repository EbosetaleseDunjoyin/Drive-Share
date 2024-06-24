<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SMSService
{
    protected $servicePlanId;
    protected $bearerToken;
    protected $sendFrom;

    public function __construct()
    {
        $this->servicePlanId = env('SINCH_SERVICE_PLAN_ID');
        $this->bearerToken = env('SINCH_API_TOKEN');
        $this->sendFrom = env('SINCH_VIRTUAL_NUMBER');
    }

    public function sendSMS($recipientPhoneNumbers, $message)
    {
        // Check recipientPhoneNumbers for multiple numbers and make it an array.
        if (stristr($recipientPhoneNumbers, ',')) {
            $recipientPhoneNumbers = explode(',', $recipientPhoneNumbers);
        } else {
            $recipientPhoneNumbers = [$recipientPhoneNumbers];
        }

        // Set necessary fields to be JSON encoded
        $content = [
            'to' => array_values($recipientPhoneNumbers),
            'from' => $this->sendFrom,
            'body' => $message
        ];

        $response = Http::withToken($this->bearerToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post("https://us.sms.api.sinch.com/xms/v1/{$this->servicePlanId}/batches", $content);

        if ($response->failed()) {
            throw new \Exception('SMS sending failed: ' . $response->body());
        }

        return $response->body();
    }
}
