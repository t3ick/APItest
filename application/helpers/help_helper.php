<?php
/**
 * Created by PhpStorm.
 * User: t3i
 * Date: 13/03/2018
 * Time: 16:10
 */
function connect()
{
    $servername = 'localhost';
    $username = 'root';
    $password = 'root';
    $db = 'etna_crowdin';
    $conn = mysqli_connect($servername, $username, $password, $db);
    if (!$conn) {
        die ("connection fail" . mysqli_connect_error());
    }
    echo "Connected successfully";
}

function error($error, $message = 'not found', $data = null)
{
    header('Content-Type: application/json');
    set_status_header($error);
    $mess = array('code' => $error, 'message' => $message);

    if ($data != null) {
        $mess['datas'] = $data;
    }

    echo json_encode($mess);
    die;
}

class out extends CI_Output {
    public function output($aff) {
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($aff, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    }
}

function aff ($data = array(), $code = 200, $mess = 'success') {
//    header('Content-Type: application/json');
    set_status_header($code);
    $aff = array('code' => $code,
        'message' => $mess,
        'datas' => $data);
//    $this->output->set_content_type('application/json');
//    $this->output->set_output(json_encode($aff, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    $outp = new out;
    $outp->output($aff);
    die;
}