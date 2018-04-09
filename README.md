# sms
Simple SMS Gateway Package for sending short text messages. Currently Bulksms, Clickatell, Gupshup, Infobip, Itexmo, Mocker, MVaayoo, Nexmo, SmsAchariya, Smsapi, SmsCountry, SmsLane, twilio and Any HTTP/s based Gateways are supported with Custom Gateway. Default Log gateway can be used for testing.

### Installation

```php
composer require tecdiary/sms
 ```

### Usage

Prepare your `$config` for your sms gateways as below (refer to sampleConfig.php for your sms gateway requirements). Initialize class with config and start sending sms messages `$sms->send($mobile, $message);`.

```php
use Tecdiary\Sms\Sms;

$config = [
    'gateway' => 'Log',
    'log' => [
        'path' => __DIR__ . '/logs/sms.log',
        'level' => 100
    ]
];

$sms = new Sms($config);
 ```

Send Single SMS:

```php
$sms->send('+919090909090', 'This is sms body');
 ```

Send Multiple SMS:

```php
$sms->send(['+60123456789', '+601111442122'], 'This is sms body');
 ```

Gateway Response:

```php
$response = $sms->send(['+60123456789', '+601111442122'], 'This is sms body')->response();
```

### Gateways

Currently these gateways are supported

1. Bulksms
2. Clickatell
3. Gupshup
4. Infobip
5. Itexmo
6. Mocker
7. MVaayoo
8. Nexmo
9. SmsAchariya
10. Smsapi
11. SmsCountry
12. SmsLane
13. Twilio
14. Custom

Default Gateway: `Log`

### Custom Gateway
Let us suppose you want to use any other gateway. Find the API URL with which SMS can be sent.
For Example : <code>http://example.com/api/sms.php?uid=737262316a&pin=YOURPIN&sender=your_sender_id&route=0&mobile=8888888888&message=How are You&pushid=1</code>

Then you can setup the Config of Custom Gateway like this:

```php
$config = [
  'gateway' => 'Custom',
  'Custom' => [
    'url' => 'http://example.com/api/sms.php?',
    'params' => [
      'send_to_name' => 'mobile',
      'msg_name' => 'message',
      'others' => [
        'uid' => '737262316a',
        'pin' => 'YOURPIN',
        'sender' => 'your_sender_id',
        'route' => '0',
        'pushid' => '1',
      ]
    ]
  ]
];
```

### Contributing
Any sort of contributions and/or feedback is much appreciated, specially if you have added any new gateway. Just leave an issue or pull-request!
