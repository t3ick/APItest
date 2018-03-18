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
        echo 'test';
    }

    public function help()
    {
        $this->load->helper('Help');
        connect();
    }
}