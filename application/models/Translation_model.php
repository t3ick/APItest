<?php

class Translation_model extends CI_Model
{
    protected $CI;

    public function Translation() {

        $this->CI =& get_instance();
        if (empty(get_instance()->db)) {
            get_instance()->db = $this->CI->load->database('default', true);
        }
        $data = array();

        $domain = $this->db->from('domain')
            ->select('id')
            ->where('name', $this->uri->segments[3])
            ->get()->result();

        $id = $this->db->from('translation')
            ->select('id, code')
            ->where('domain_id', $domain[0]->id)
            ->get()
            ->result();

        $lang_id = $this->db->select('lang_id')
            ->from('domain_lang')
            ->where('domain_id', $domain[0]->id)
            ->get()->result();

        for ($i = 0; isset($id[$i]); $i++){
            $trans = $this->db->from('translation_to_lang')
                ->select('lang_id, trans')
                ->where('translation_id', $id[$i]->id)
                ->get()->result();

            $data[$i] = (object)array();
            $data[$i]->trans = (object)array();

            foreach ($lang_id as $langList) {
                $langTag = $langList->lang_id;
                for ($j = 0; isset($trans[$j]); $j++) {
                    if ($langTag == $trans[$j]->lang_id) {
                        $data[$i]->trans->$langTag = $trans[$j]->trans;
                    }
                    elseif (!isset($data[$i]->trans->$langTag)) {
                        $data[$i]->trans->$langTag = $id[$i]->code;
                    }
                }
            }
            $data[$i]->id = $id[$i]->id;
            $data[$i]->code = $id[$i]->code;
        }
        aff($data);
    }
}