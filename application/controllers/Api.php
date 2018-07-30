<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Api
 */

class Api extends CI_Controller {
    public function recipes()
    {
        $this->load->helper('help');

        $this->load->model('Recipe_model');

        if (isset($this->uri->segments[4])){
            error(404);
        }

        if (isset($this->uri->segments[3])){
            $this->Recipe_model->slug($this->uri->segments[3]);die;
        }
        $this->Recipe_model->recipes();die;


//            $this->load->model('Domain_model');
//            $this->Domain_model->DomainData();
//        }

//        if (!isset($this->uri->segments[3])) {
//            $this->load->model('Domain_model');
//            $this->Domain_model->DomainData();
//        }
//
//        if (!isset($this->uri->segments[4])) {
//            $this->load->model('Mailer_model');
//            $this->Mailer_model->Mailer();
//        }
//
//        if ($this->uri->segments[4] != 'translations') {
//            error (404);
//        }
//
//        if ($this->input->server('REQUEST_METHOD') == 'GET' && !isset($this->uri->segments[6])) {
//            $this->load->model('Translation_model');
//            $this->Translation_model->Translation();
//        }
//
//        if ($this->input->server('REQUEST_METHOD') == 'POST' && !isset($this->uri->segments[5])) {
//            $this->load->model('Authorization_model');
//            $this->Authorization_model->Authorization();
//        }
//
//        if ($this->input->server('REQUEST_METHOD') == 'PUT' && !isset($this->uri->segments[6])) {
//            $this->load->model('My_Put_model');
//            $this->My_Put_model->Put();
//        }

        error(404);
    }
}