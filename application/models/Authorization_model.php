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

        if ($this->input->post() == null && $this->input->get_request_header('Authorization') == null) {
            return 'pass';
        }

        $pass = $this->input->get_request_header('Authorization');
        $code = $this->input->post('code');
        $trans = $this->input->post('trans');

        if ($code == null) {
            aff(null, 403, 'accès refusé');
        }


        $domain = $this->db->select('id')
            ->where('name', $this->uri->segments[3])
            ->from('domain')
            ->get()
            ->result();

        $user = $this->db->select('password')
            ->from('user')
            ->where('id', $domain[0]->id)
            ->get()
            ->result();

        if ($user[0]->password != $pass) {
            aff([], 401, 'non identifié');
        }

        $domain_lang = $this->db->select('lang_id')
            ->from('domain_lang')
            ->where('domain_id', $domain[0]->id)
            ->get()
            ->result();

        $data = (object)[];
        $data->trans = (object)[];
        foreach ($domain_lang as $lang) {
            $tag = $lang->lang_id;
            if (array_key_exists($tag, $trans)){
                $data->trans->$tag = $trans[$tag];
            }
            else {
                $data->trans->$tag = $code;
            }
        }

        $max = $this->db->select('id')
            ->from('translation')
            ->where('code', 'test')
            ->get()
            ->result();

        $data->id = $max[0]->id;
        $data->code = $code;



        aff($data, 201);
        die;
    }
}