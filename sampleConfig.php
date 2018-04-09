<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sms Service Config
    |--------------------------------------------------------------------------
    |
    |   gateway = Log/Bulksms/Clickatell/Gupshup/Infobip/Mocker/MVaayoo/Nexmo/SmsAchariya/Smsapi/SmsCountry/SmsLane/twilio/Custom
    |   Required = yes
    |   Default = Log
    |
    |   gatewayConfig is required as below
    |
    |   Log should be array with path (required) and level code, ie 400 for errors default debug
    |   100 for DEBUG
    |   200 for INFO
    |   250 for NOTICE
    |   300 for WARNING
    |   400 for ERROR
    |   500 for CRITICAL
    |   550 for ALERT
    |   600 for EMERGENCY
    |
    */

    'gateway' => 'Log',

    // http://clickatell.com
    'Clickatell' => ['api_id'  => '', 'user'  => '', 'password' => ''],

    // http://enterprise.gupshup.com
    'Gupshup' => ['userid'  => '', 'password' => ''],

    // API_CODE
    'Itexmo' => ['api_code'  => ''],

    // http://mvaayoo.com
    'MVaayoo' => ['user'  => '', 'senderID'  => ''],

    // http://smsachariya.com
    'SmsAchariya' => ['domain'  => '', 'uid'  => '', 'pin' => ''],

    // http://www.smscountry.com/
    'SmsCountry' => ['user' => '', 'passwd' => '', 'sid' => 'SMSCountry'],

    // http://smslane.com
    'SmsLane' => ['user' => '', 'password' => '', 'sid' => 'WebSMS', 'gwid' => '1'],

    // http://nexmo.com
    'Nexmo' => ['api_key' => '', 'api_secret' => '', 'from'  => ''],

    // http://twilio.com
    'Twilio' => ['account_sid' => '', 'auth_token' => '', 'from'  => ''],

    // http://mocker.in
    'Mocker' => ['sender_id'  => ''],

    // http://www.infobip.com
    'Infobip' => ['username'  => '', 'password' => ''],

    // http://www.bulksms.com
    'Bulksms' => ['eapi_url' => '', 'username'  => '', 'password' => ''],

    // http://www.smsapi.com
    'Smsapi' => ['eapi_url' => '', 'from' => 'SMSAPI'],

    /*
     * Example of Custom Gateway
     * Actual Url : http://example.com/api/sms.php?uid=737262316a&pin=YOURPIN&sender=your_sender_id&route=0&mobile=MOBILE&message=MESSAGE&pushid=1
     */
    'Custom' => [                   // Can be used for any gateway
        'url' => '',                // Gateway Endpoint
        'params' => [               // Parameters to be included in the request
            'send_to_name' => '',   // Name of the field of recipient number
            'msg_name' => '',       // Name of the field of Message Text
            'others' => [           // Other Authentication params with their values
                'param1' => '',
                'param2' => '',
                'param3' => '',
                'param4' => ''
            ]
        ]
    ],

    'log' => [
        'path' => __DIR__ . '\path\to\logs\sma.log',
        'level' => 400
    ]
];
