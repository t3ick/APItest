<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class api extends CI_Controller {
    public function index() {
        echo 'controller api';
    }
    public function domains()
    {
        if (!isset($this->uri->segments[3])) {
            $code = 200;
            $mess = 'success';

            $this->load->model('Domain_model');
            $tab = $this->Domain_model->DomainData();
            $return = array('code' => $code,
                'message' => $mess,
                'datas' => $tab);
            echo json_encode($return, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        }
        else {
            $code = 200;
            $mess = 'success';

            $this->load->model('Mailer_model');
            $tab = $this->Mailer_model->Mailer();

            if ($tab == '401') {
                set_status_header(401);
                $mes = array('code' => 401, 'message' => 'not found');
                echo json_encode($mes);
                die;
                }

            $return = ['code' => $code,
                'message' => $mess,
                'datas' => $tab];
            echo json_encode($return, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        }
    }
}