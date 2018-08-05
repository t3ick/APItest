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

        $user = $this->db->from('users__user')
            ->select('username, last_login, id')
            ->where('password', $pass)
            ->get()->result();

        if($user == null) {
            error(401, 'Unauthorized');
        }

        if ($name == null) {
            error(400, 'Bad Request', array('name'));
        }

        if ($slug == null) {
            $slug = $name;
        }

        if ($step == null) {
            $step = array('');
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

        $id = $this->db->from('recipes__recipe')
            ->select('id')
            ->where('slug', $slug)
            ->get()->result();

        $data = (object) array ('id' => (int)$id[0]->id);
        $data->name = $name;
        $data->user = $user[0];
        $data->slug = $slug;
        $data->step = $step;

        $aff->datas = $data;

        set_status_header(201);
        echo json_encode($aff, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);die;
    }

    public function put ($aData, $pass) {

        $recipes = $this->db->from('recipes__recipe')
            ->select('id, user_id, ')
            ->where('slug', $aData['slug'])
            ->get()->result();

        if($recipes == null) {
            error(400, 'Bad Request', array('slug'));
        }

        $user = $this->db->from('users__user')
            ->select('id, username')
            ->where('id', $recipes[0]->user_id)
            ->where('password', $pass)
            ->get()->result();

        if($user == null) {

            $if403 = $this->db->from('users__user')
                ->select('id')
                ->where('password', $pass)
                ->get()->result();

            if ($if403 == null) {
                error(401, 'Unauthorized');
            }
            error(403, 'Forbidden');
        }

        $i = 0;
        $field = '';
        foreach ($aData['stream'] as $key => $val) {
            $field = $key;
            $value = $val;
            $i++;
        }
        if ($i != 1) {
            error (400, 'Bad Request', array('slug or name or step'));
        }

        if (!($field == 'slug' || $field == 'name' || $field == 'step') || $value == '') {
            error (400, 'Bad Request', array('slug or name or step'));
        }

        if ($field === 'step') {
            $ifStep = $value;
            $value = serialize($value);
        }

        $recipes = $this->db->from('recipes__recipe')
            ->select('id, user_id, '.$field)
            ->where('slug', $aData['slug'])
            ->get()->result();

        if($recipes == null) {
            error(400, 'Bad Request', array('slug'));
        }

        $date = date('Y-m-d');

        $lastDate = $this->db->set('last_login', $date)
            ->where('id', $recipes[0]->user_id)
            ->update('users__user');

        if($lastDate == null) {
            error(400, 'Bad Request', array('slug'));
        }

        $update = $this->db->set($field, $value)
            ->where('slug', $aData['slug'])
            ->update('recipes__recipe');

        if($update == null) {
            error(400, 'Bad Request', array('slug'));
        }

        $aff = (object) array ('code' => 200, 'message' => 'OK');

        $data = (object) array ('id' => (int)$recipes[0]->id);

        if ($field == 'step') {
            $value = $ifStep;
        }

        $data->$field = $value;
        $data->user = (object)array();
        $data->user->username = $user[0]->username;
        $data->user->last_login = $date;
        $data->user->id = $user[0]->id;

        $aff->datas = $data;

        if ($field === 'sluf') {
            $aData['slug'] = $val;
        }
        $data->slug = $aData['slug'];

        echo json_encode($aff, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);die;
    }
}