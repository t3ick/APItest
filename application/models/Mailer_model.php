<?php

class Mailer_model extends CI_Model
{
    protected $CI;

    public function Mailer() {
        $this->CI =& get_instance();
        if (empty(get_instance()->db)) {
            get_instance()->db = $this->CI->load->database('default', true);
        }

        $domain = $this->db->from('domain')
            ->select('id, slug, name, description, created_at')
            ->where('name', $this->uri->segments[3])
            ->get()->result();

        if (($domain) == null) {
            error(404);
        }

        $lang = $this->db->from('domain_lang')
            ->select('lang_id')
            ->where('domain_id', $domain[0]->id)
            ->get()->result();

        $data =  new stdClass();
        $data->langs[] = null;

        for ($i = 0; isset($lang[$i]); $i++) {
            $data->langs[$i] = $lang[$i]->lang_id;
        }

        $data->id = $domain[0]->id;
        $data->slug = $domain[0]->slug;
        $data->name = $domain[0]->name;
        $data->description = $domain[0]->description;

        $user = $this->db->from('user')
            ->select('id, username')
            ->where('id', $domain[0]->id)
            ->get()->result();

        $data->creator = $user[0];

        $date = new DateTime($domain[0]->created_at.' '.date_default_timezone_get());
        $data->created_at = $date->format('Y-m-d\TH:i:sP');

        aff($data);
    }
}