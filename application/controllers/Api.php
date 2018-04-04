<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    public function domains()
    {
        $this->load->helper('help');

        if (!isset($this->uri->segments[3])) {
            $this->load->model('Domain_model');
            $this->Domain_model->DomainData();
        }

        if (!isset($this->uri->segments[4])) {
            $this->load->model('Mailer_model');
            $this->Mailer_model->Mailer();
        }

        if ($this->uri->segments[4] != 'translations') {
            error (404);
        }

        if (!isset($this->uri->segments[5])) {
            if ($this->input->server('REQUEST_METHOD') == 'GET') {
                $this->load->model('Translation_model');
                $this->Translation_model->Translation();
            }

            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                $this->load->model('Authorization_model');
                $this->Authorization_model->Authorization();
            }
        }
        if ($this->input->server('REQUEST_METHOD') == 'PUT' && !isset($this->uri->segments[6])) {
            $this->load->model('My_Put_model');
            $this->Put_model->Put();
        }

        error(404);
    }
}