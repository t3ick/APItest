<?php
/**
 * Created by PhpStorm.
 * User: t3i
 * Date: 26/03/2018
 * Time: 18:26
 */
class Authorization_model extends CI_Model
{
    protected $CI;

    public function Authorization() {
        $this->CI =& get_instance();
        if (empty(get_instance()->db)) {
            get_instance()->db = $this->CI->load->database('default', true);
        }

        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            return 'pass';
        }
        else if ($this->input->server('REQUEST_METHOD') != 'POST') {
            error('403');
        }

        $pass = $this->input->get_request_header('Authorization');
        $code = $this->input->post('code');
        $trans = $this->input->post('trans');

        $domain = $this->db->from('domain')
            ->select('id')
            ->where('name', $this->uri->segments[3])
            ->get()->result();

        $user = $this->db->from('user')
            ->select('id, password')
            ->where('id', $domain[0]->id)
            ->get()
            ->result();

        if ($user[0]->password != $pass) {

            $test403 = $this->db->from('user')
                ->select('password')
                ->where('password', $pass)
                ->get()->result();

            if ($test403)
                error(403);
            else {
                error(401);
            }
        }

        if ($code == null) {
            aff('ko', 400,'error form');
        }


    $domain_lang = $this->db->select('lang_id')
        ->from('domain_lang')
        ->where('domain_id', $domain[0]->id)
        ->get()
        ->result();

        $data = (object)array();
        $data->trans = (object)array();

        foreach ($domain_lang as $lang) {
            $tag = $lang->lang_id;
            if (array_key_exists($tag, $trans)){
                $data->trans->$tag = $trans[$tag];
            }
            else {
                $data->trans->$tag = $code;
            }
        }

        $max = $this->db->select_max('id')
            ->from('translation')
            ->get()
            ->result();

        $data->id = $max[0]->id + 1;
        $data->code = $code;

        $this->db->set('id', $data->id)
            ->set('domain_id', $user[0]->id)
            ->set('code', $data->code)
            ->insert('translation');

        foreach ($trans as $trad) {
            $key = key($trans);
            next($trans);
            $this->db->set('lang_id', $key)
                ->set('trans', $trans[$key])
                ->set('translation_id', $data->id)
                ->insert('translation_to_lang');
        }

        aff($data, 201);
    }
}