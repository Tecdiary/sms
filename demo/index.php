<?php

require __DIR__ . '/../vendor/autoload.php';

$config = [
    'countryCode' => '+91',
    'gateway' => 'Log',
    'log' => [
        'path' => __DIR__ . '/logs/sms.log',
        'level' => 100
    ]
];

$sms = new \Tecdiary\Sms\Sms($config);

$result = $sms->send(['+919090909090', '009190909090901'], 'This is test message for Log gateway.')->response();

?><html>
<head>
    <meta charset="UTF-8">
    <title>SMS DEMO</title>
</head>
<body>
    <pre/>

    TECDIARY/SMS DEMO
    <?php var_dump($result); ?>
</body>
</html>
