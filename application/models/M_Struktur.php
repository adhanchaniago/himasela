<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_Struktur extends CI_Model {

	function gettotal(){
		$this->db->select_max('Length(nourut)', 'nouruta');
        $query = $this->db->get('tb_anggota');
    	return $query->result();
    }

    function getuser(){
        $this->db->select('Length(nourut) noa, nama, nourut, id_anggota');
        $this->db->where('id_upline', '1');
        $this->db->where('statusanggota', 'anggota');
        $query = $this->db->get('tb_anggota');
    	return $query->result();
    }

    function getAnak($anak){
        $this->db->where('id_upline', $anak);
        $this->db->where('statusanggota', 'anggota');
        return $this->db->get('tb_anggota')->result();
    }


    function getAdmin($anak){
        $this->db->where('id_anggota', $anak);
        return $this->db->get('tb_anggota')->result();
    }

     function getlenght(){
        $this->db->select('Length(nourut) no');
        $this->db->distinct();
        $query = $this->db->get('tb_anggota');
        return $query->result();
    }

    function getdownline(){
        $query = $this->db->get('tb_setting');
        return $query->result();
    }

    public function getCategoryTreeData()
    {
        $query = $this->db
            ->select("*")
            ->from("tb_anggota")
            ->get();


        $arrTreeById = array();
        $arrTree = $query->result();


        $objTreeWrapper = new stdClass();
        $objTreeWrapper->arrChilds = array();

        foreach($arrTree AS $row)
        {
            $arrTreeById[$row->id_anggota] = $row;
            $row->arrChilds = array();
        }

        foreach($arrTree AS $objItem)
        {
            if (isset($arrTreeById[$objItem->id_upline]))   $arrTreeById[$objItem->id_upline]->arrChilds[] = $objItem;
            elseif ($objItem->id_upline == 0)
            {
                $objTreeWrapper->arrChilds[] = $objItem;
            }
        }

        return $objTreeWrapper;
    }
}