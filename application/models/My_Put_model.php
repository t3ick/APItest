<?php
/**
 * Created by PhpStorm.
 * User: t3i
 * Date: 30/03/2018
 * Time: 14:00
 */

class My_Put_model extends CI_Model
{
    protected $CI;

    public function Put() {

        $this->CI =& get_instance();
        if (empty(get_instance()->db)) {
            get_instance()->db = $this->CI->load->database('default', true);
        }

        $trans = $this->input->raw_input_stream;
        $pass = $this->input->get_request_header('Authorization');


        $nb = $this->db->from('translation')
            ->select('code')
            ->where('id', $this->uri->segments[5])
            ->get()->result();

        if (($nb) == null) {
            error(404);
        }

        $domain = $this->db->from('domain')
            ->select('id')
            ->where('name', $this->uri->segments[3])
            ->get()->result();

        $user = $this->db->from('user')
            ->select('id, password')
            ->where('id', $domain[0]->id)
            ->get()->result();

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

        if ($trans == null) {
            aff('ko', 400, 'error form');
        }

        $regexTag = '#\[(.+)\]#U';
        $regexTrad = '#=([a-zA-Z+]+)#';
        preg_match($regexTag, $trans, $tag);
        preg_match($regexTrad, $trans, $trad);
        $translation = str_replace('+', ' ', $trad[1]);

        $this->db->set('translation_id', $this->uri->segments[5])
            ->set('lang_id', $tag[1])
            ->set('trans', $translation)
            ->insert('translation_to_lang');

        $trans = $this->db->from('translation_to_lang')
            ->select('lang_id, trans')
            ->where('translation_id', $this->uri->segments[5])
            ->get()->result();

        $data = (object)array();
        $data->trans = (object)array();
        for ($i = 0; isset($trans[$i]); $i++) {
            $langTag = $trans[$i]->lang_id;
            $data->trans->$langTag = $trans[$i]->trans;
        }

        $data->id = $this->uri->segments[5];
        $data->code = $nb[0]->code;
        aff($data, 200);
    }
}