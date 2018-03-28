<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    public function domains()
    {
        $this->load->helper('help');
        $data = '404';

        if (isset($this->uri->segments[5])) {
            error('404');
        }
            if (isset($this->uri->segments[3])) {
                if (isset($this->uri->segments[4]) && $this->uri->segments[4] == 'translations') {
                    $this->load->model('Authorization_model');
                    $this->Authorization_model->Authorization();
                    $this->load->model('Translation_model');
                    $data = $this->Translation_model->Translation();
                }
                elseif (!isset($this->uri->segments[4])) {
                    $this->load->model('Mailer_model');
                    $data = $this->Mailer_model->Mailer();
                }
            }
            else {
                $this->load->model('Domain_model');
                $data = $this->Domain_model->DomainData();
            }
            error($data);
            aff($data);
    }
}