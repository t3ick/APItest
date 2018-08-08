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

        if (isset($this->uri->segments[5])){
            error(404);
        }

        if ($this->input->server('REQUEST_METHOD') == 'DELETE') {
            if (isset($this->uri->segments[4])) {
                error(404);
            }
            $pass = $this->input->get_request_header('Authorization');
            $aData['slug'] = $this->uri->segments[3];
            $this->Recipe_model->delete($aData, $pass);die;
        }

        if ($this->input->server('REQUEST_METHOD') == 'PUT') {
            if (isset($this->uri->segments[4])) {
                error(404);
            }
            $pass = $this->input->get_request_header('Authorization');
            $aData['stream'] = array();
            parse_str($this->input->raw_input_stream,$aData['stream']);
            $aData['slug'] = $this->uri->segments[3];
            $this->Recipe_model->put($aData, $pass);die;
        }


        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if (isset($this->uri->segments[3])){
                error(404);
            }
            $pass = $this->input->get_request_header('Authorization');
            $name = $this->input->post('name');
            $slug = $this->input->post('slug');
            $step = $this->input->post('step');
            $this->Recipe_model->post($pass, $name, $slug, $step);die;
        }

//        if ($this->input->server('REQUEST_METHOD') == 'GET') {

            $pass = $this->input->get_request_header('Authorization');
            if (isset($this->uri->segments[3]) && !isset($this->uri->segments[4])) {
                $this->Recipe_model->auth($pass, $this->uri->segments[3]);
            }

            $name = $this->input->get('name');
            if ($name != null) {
                if (isset($this->uri->segments[3])) {
                    error(404);
                }
                $this->Recipe_model->filter($name);
                die;
            }

            if (isset($this->uri->segments[4])) {
                if ($this->uri->segments[4] === 'steps') {
                    $this->Recipe_model->step($this->uri->segments[3]);
                    die;
                } else {
                    error(404);
                }
            }

            if (isset($this->uri->segments[3])) {
                $this->Recipe_model->slug($this->uri->segments[3]);
                die;
            }
            $this->Recipe_model->recipes();die;

        error(404);
    }
}