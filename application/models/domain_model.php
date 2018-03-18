<?php
/**
 * Created by PhpStorm.
 * User: t3i
 * Date: 13/03/2018
 * Time: 15:10
 */

class domain_model extends CI_Model
{
    public $table = 'domain';
    protected $CI;

    public function domainData() {
        $this->CI =& get_instance();
        if (empty(get_instance()->db)) {
            get_instance()->db = $this->CI->load->database('default', true);
        }
        echo '<pre>';

        return $this->db->select('id, slug, name, description')
            ->from($this->table)
            ->get()
            ->result();
    }
}