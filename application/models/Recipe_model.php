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
        $aff->datas = $data;

        echo json_encode($aff);die;
    }

    public function slug ($slug) {
        $data = $this->db->from('recipes__recipe')
            ->select('id, name, user_id')
            ->where('slug', $slug)
            ->get()->result();

        if (($data) == null) {
            error(404);
        }

        $user = $this->db->from('users__user')
            ->select('username, last_login, id')
            ->where('id', $data[0]->user_id)
            ->get()->result();

        if (($user) == null) {
            error(404);
        }

        $aff = (object) array ('code' => 200, 'message' => 'OK');
        $aff->datas = (object) array();
        $aff->datas->id = $data[0]->id;
        $aff->datas->name = $data[0]->name;
        $aff->datas->user = $user[0];
        $aff->datas->slug = $slug;

        echo json_encode($aff);die;
    }

    public function step ($slug) {
        $data = $this->db->from('recipes__recipe')
            ->select('step')
            ->where('slug', $slug)
            ->get()->result();

        if (($data) == null) {
            echo 'error';
            error(404);
        }

        $aff = (object) array ('code' => 200, 'message' => 'OK');

        foreach (unserialize($data[0]->step) as $val) {
            $step[] = $val;
        }

        $aff->datas = $step;

        echo json_encode($aff);die;
    }
}