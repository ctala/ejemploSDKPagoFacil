<?php

include_once "vendor/autoload.php";

use ctala\transaccion\classes\Transaccion;
use ctala\transaccion\classes\Response;
use ctala\HTTPHelper\HTTPHelper;

/*
 * Usaremos los mismos datos para no tener que validar contra una Base de Datos
 */

$token_servicio = "2518f28bb66a8eddb1ec1ec28e601f638c2f9e0d1aace8f0f5e86f549555a330";
$token_secret = "ba0786b0756726777c9a4f5b8ae2ee312ea77e9a129d3120252efc4b6a149887";
$order_id_tienda = "123456";
$token_tienda = "1214124";
$amount = "100.00";
$email = "yomismo@cristiantala.cl";

/*
 * Nos ayudará con las respuestas.
 */
$HTTPHelper = new HTTPHelper();

/*
 * Solo el método post debería ser aceptado.
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    /*
     * Error 405
     * Método no permitido.
     * Se finaliza
     */
    $HTTPHelper->my_http_response_code(405);
}



/*
 * Revisamos si la orden existe.
 * En este caso simplemente comparamos.
 * Si la orden estuviera ya marcada como aceptada en el sistema podríamos
 * terminar el proceso.
 */

/*
 * Obtenemos el order id del post
 */
$ct_order_id = filter_input(INPUT_POST, "ct_order_id");

if ($ct_order_id != $order_id_tienda) {
    /*
     * Ojo, en este caso solo corroboramos contra una orden.
     * En general lo haremos contra la BdD.
     */
    /*
     * Error 404
     * Orden no encontrada
     * Se finaliza
     */
    $HTTPHelper->my_http_response_code(404);
}

/*
 * Si la oden existe
 * Corroboramos las firmas del mensaje
 * Para hacerlo debemos firmar el mensaje nuevamente y corroborar si la firma 
 * es la misma
 */


$ct_token_tienda = filter_input(INPUT_POST, "ct_token_tienda");
$ct_monto = filter_input(INPUT_POST, "ct_monto");
$ct_token_service = filter_input(INPUT_POST, "ct_token_service");
$ct_estado = filter_input(INPUT_POST, "ct_estado");
$ct_authorization_code = filter_input(INPUT_POST, "ct_authorization_code");
$ct_payment_type_code = filter_input(INPUT_POST, "ct_payment_type_code");
$ct_card_number = filter_input(INPUT_POST, "ct_card_number");
$ct_card_expiration_date = filter_input(INPUT_POST, "ct_card_expiration_date");
$ct_shares_number = filter_input(INPUT_POST, "ct_shares_number");
$ct_accounting_date = filter_input(INPUT_POST, "ct_accounting_date");
$ct_transaction_date = filter_input(INPUT_POST, "ct_transaction_date");
$ct_order_id_mall = filter_input(INPUT_POST, "ct_order_id_mall");

$response = new Response($ct_order_id, $ct_token_tienda, $ct_monto, $ct_token_service, $ct_estado, $ct_authorization_code, $ct_payment_type_code, $ct_card_number, $ct_card_expiration_date, $ct_shares_number, $ct_accounting_date, $ct_transaction_date, $ct_order_id_mall);


/*
 * Si las firmas corresponden corroboramos los valores
 * o montos.
 */

$ct_firma = filter_input($POST, "ct_firma");
$response->setCt_token_secret($this->token_secret);
$arregloFirmado = $response->getArrayResponse();

if ($arregloFirmado["ct_firma"] != $ct_firma) {
    /*
     * Firmas no corresponden. POsible inyección de datos.
     * Se termina el proceso.
     */
    $http_helper->my_http_response_code(400);
}
/*
 * Si los montos corresponden revisamos y actualizamos el estado
 */

if ($arregloFirmado["ct_monto"] != $amount) {
    /*
     * Montos no corresponden. POsible inyección de datos.
     * Se termina el proceso.
     */
    $http_helper->my_http_response_code(400);
}

if ($arregloFirmado["ct_estado"] == "COMPLETADA") {
    /*
     * Ac'a debes de marcar la orden como completa
     */
} else {
    /*
     * Acá la puedes marcar como pendiente o fallida.
     */
}

/*
 * Terminamos el proceso con resultado 200
 * TODO OK.
 */
$HTTPHelper->my_http_response_code(200);
