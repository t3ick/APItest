<?php

class Translation_model extends CI_Model
{
    protected $CI;

    public function Translation() {
        $this->CI =& get_instance();
        if (empty(get_instance()->db)) {
            get_instance()->db = $this->CI->load->database('default', true);
        }

        $data = [];

        $domain = $this->db->select('id')
            ->where('name', $this->uri->segments[3])
            ->from('domain')
            ->get()
            ->result();

        $id = $this->db->select('id, code')
            ->from('translation')
            ->where('domain_id', $domain[0]->id)
            ->get()
            ->result();

        $lang_id = $this->db->select('lang_id')
            ->from('domain_lang')
            ->where('domain_id', $domain[0]->id)
            ->get()
            ->result();

        for ($i = 0; isset($id[$i]); $i++){
            $trans = $this->db->select('lang_id, trans')
                ->from('translation_to_lang')
                ->where('translation_id', $id[$i]->id)
                ->get()
                ->result();

            $data[$i] = (object)[];
            $data[$i]->trans = (object)[];

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

        return $data;
    }
}