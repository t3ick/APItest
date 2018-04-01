<?php

set_status_header(400);
$mes = array('code' => 400,
    'message' => 'error : already exist in database',
    'datas' => array('ko'));
echo json_encode($mes);