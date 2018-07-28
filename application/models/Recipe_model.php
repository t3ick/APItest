<?php
/**
 * Created by PhpStorm.
 * User: t3i
 * Date: 28/07/2018
 * Time: 15:26
 */

class Recipe_model extends CI_Model
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();

        if (empty(get_instance()->db)) {
            get_instance()->db = $this->CI->load->database('default', true);
        }
    }

    public function recipes () {
        $data = $this->db->from('recipes__recipe')
            ->select('id, name, slug')
            ->get()->result();


        $aff = (object) array ('code' => 200, 'message' => 'OK');
        $aff->data = $data;

        echo json_encode($aff);die;
    }
}