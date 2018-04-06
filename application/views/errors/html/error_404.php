<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-Type: application/json');
$mes = array('code' => 404, 'message' => 'not found');
echo json_encode($mes);