<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mailer extends CI_Controller {

    public function index()
    {
        $code = 200;
        $mess = 'success';

        $this->load->model('Mailer_model');
        $tab = $this->Mailer_model->MailerData();
        $return = ['code'=>$code,
            'message' => $mess,
            'datas' => $tab];
//        echo '<pre>';
//        print_r($return);
        echo json_encode($return, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        return ($tab);
    }
}