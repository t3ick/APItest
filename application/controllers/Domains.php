<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Domains extends CI_Controller {

    public function index()
    {
        $code = 200;
        $mess = 'success';

        $this->load->model('Domain_model');
        $tab = $this->Domain_model->DomainData();
        $return = array('code' => $code,
            'message' => $mess,
            'datas' => $tab);
        echo json_encode($return, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        return ($tab);
    }

    public function Mailer()
    {
        $code = 200;
        $mess = 'success';

        $this->load->model('Mailer_model');
        $tab = $this->Mailer_model->Mailer();
        $return = ['code'=>$code,
            'message' => $mess,
            'datas' => $tab];
        echo json_encode($return, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        return ($tab);
    }
}