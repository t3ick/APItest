<?php

set_status_header(400);
$mes = array('code' => 400,
    'message' => 'error : code already exist in database',
    'datas' => []);
echo json_encode($mes);