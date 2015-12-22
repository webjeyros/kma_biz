<?php
require_once __DIR__ . '/../../kma_biz.php';
$html =<<<EOD
<!DOCTYPE html>
<html lang="RU">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div style="margin: 0 auto; width: 300px;"><h1>%s</h1></div>
</body>
</html>
EOD;

$kma = new \cpa\kma('AUTH_ID_HERE', 'AUTH_HASH_HERE');
if (isset($_POST['country']) && isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['s1'])) {

    list($code, $name, $phone, $price) = array_values($_POST);



    $result = $kma->addLead($name, $phone, 'CHANNEL_HERE', $price, $_SERVER['REMOTE_ADDR']);

    if ($kma->error) {

        echo sprintf($html, 'Ошибка!');
        file_put_contents('errors.log', date("Y-m-d H:i:s ") . $result . PHP_EOL, FILE_APPEND);

    } else {

        echo sprintf($html, 'Ваш заказ принят!');

    }

}
