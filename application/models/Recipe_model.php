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
        $test = $this->db->from('recipes__recipe')
            ->select('id')
            ->where('slug', $slug)
            ->get()->result();

        if (($test) == null) {
            error(404);
        }

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

    public function post ($pass, $name, $slug, $step) {

        if ($name == null) {
            error(400, 'Bad Request', array('name'));
        }

        if ($step == null) {
            error(400, 'Bad Request', array('step'));
        }

        if ($slug == null) {
            $slug = $name;
        }

        $user = $this->db->from('users__user')
            ->select('username, last_login, id')
            ->where('password', $pass)
            ->get()->result();

        if($user == null) {
            error(401, 'Unauthorized');
        }

        $insert = $this->db->set('name', $name)
            ->set('slug', $slug)
            ->set('step', serialize($step))
            ->set('user_id', $user[0]->id)
            ->insert('recipes__recipe');

        if($insert == null) {
            error(400, 'Bad Request', array('slug'));
        }

        $aff = (object) array ('code' => 201, 'message' => 'Created');

        $date = date('Y-m-d');

        $this->db->set('last_login', $date)
            ->where('password', $pass)
            ->update('users__user');

        $user[0]->last_login = $date.'';

        $data = (object) array ('id' =>$user[0]->id);
        $data->name = $name;
        $data->user = $user;
        $data->slug = $slug;
        $data->step = $step;

        $aff->datas = $data;

        set_status_header(201);
        echo json_encode($aff, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);die;
    }
}