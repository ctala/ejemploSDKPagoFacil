<?php

include_once "vendor/autoload.php";
use ctala\transaccion\classes\Transaccion;


$token_servicio = "cec998d4feecef6a8e908ed8c8d17c3faa091be1b8743b9f8c490c3ab4cc7ba9";
$token_secret = "a062d71df8e93843fa7ffd6522bca2784ff96c93c6f1e88bb5d4bb3abb3dc46c";
$order_id_tienda = "123456";
$token_tienda = "1214124";
$amount = "100.00";
$email = "yomismo@cristiantala.cl";

$url = "https://dev-env.sv1.tbk.cristiantala.cl/tbk/v2/initTransaction";


$transaccion = new Transaccion($order_id_tienda, $token_tienda, $amount, $token_servicio, $email);
$transaccion->setCt_token_secret($token_secret);
$pago_args = $transaccion->getArrayResponse();


?>

<form action="<?=$url?>" method="POST">

    <input type="text" name="ct_monto" value="<?=$amount?>">
    <input type="text" name="ct_order_id" value="<?=$order_id_tienda?>">
    <input type="text" name="ct_email" value="<?=$email?>">
    <input type="text" name="ct_token_service" value="<?=$token_servicio?>">
    <input type="text" name="ct_token_tienda" value="<?=$token_tienda?>">
    <input type="text" name="ct_firma" value="<?=$pago_args["ct_firma"]?>">
    
    
    <input type="submit">
</form>