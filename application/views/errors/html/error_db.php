<?php
header('Content-Type: application/json');
set_status_header(400);
$mes = array('code' => 400,
    'message' => 'Bad Request',
    'datas' => array('slug'));
echo json_encode($mes);die;