<?php

class Mailer_model extends CI_Model
{
    protected $CI;

    public function Mailer() {

        $this->CI =& get_instance();
        if (empty(get_instance()->db)) {
            get_instance()->db = $this->CI->load->database('default', true);
        }

        $lang = $this->db->select('lang_id')
            ->from('domain_lang')
            ->get()
            ->result();

        for ($i = 0; isset($lang[$i]); $i++) {
            $data->langs[$i] = $lang[$i]->lang_id;
        }

        $domain = $this->db->select('id, slug, name, description, created_at')
            ->where('name', $this->uri->segments[3])
            ->from('domain')
            ->get()
            ->result();

        if (($domain) == null) {
            return '404';
        }

        $data->id = $domain[0]->id;
        $data->slug = $domain[0]->slug;
        $data->name = $domain[0]->name;
        $data->description = $domain[0]->description;

        $user = $this->db->select('id, username')
            ->where('id', $domain[0]->id)
            ->from('user')
            ->get()
            ->result();

        $data->creator = $user[0];

        $date = new DateTime($domain[0]->created_at.' '.date_default_timezone_get());
        $data->created_at = $date->format('Y-m-d\TH:i:sP');

        return ($data);
    }
}