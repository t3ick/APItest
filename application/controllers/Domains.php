<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Domains extends CI_Controller {

    // public function index()
    // {
    //     $this->load->model('Domain_model');
    //     $tab = $this->domain_model->domainData();
    //     $return = array('code' => 200,
    //         'message' => 'success',
    //         'dat' => $tab);
    //     echo json_encode($return, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    // }

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

    public function help()
    {
        $this->load->helper('Help');
        connect();
    }
}