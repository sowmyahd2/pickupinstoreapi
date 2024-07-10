<?php 

function success($data, $code, $message="")
{
    $response = new stdClass();
    $response->code = $code;
    ($message!="") ? $response->message = $message : $response->message="success";
    $response->data = $data;
    return $response;
}