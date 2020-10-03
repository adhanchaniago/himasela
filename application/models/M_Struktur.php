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

     function getlenght(){
        $this->db->select('Length(nourut) no');
        $this->db->distinct();
        $query = $this->db->get('tb_anggota');
        return $query->result();
    }
}