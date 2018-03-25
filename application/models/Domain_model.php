<?php

class Domain_model extends CI_Model
{
    protected $CI;

    public function DomainData() {

        $this->CI =& get_instance();
        if (empty(get_instance()->db)) {
            get_instance()->db = $this->CI->load->database('default', true);
        }

        return $this->db->select('id, slug, name, description')
            ->from('domain')
            ->get()
            ->result();
    }
}