<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class C_Donasi extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->library('session');
        $this->load->model('M_Donasi');
        $this->load->model('M_Level');
        $this->load->model('M_Setting');
        if(!$this->session->userdata('id_user')){
            redirect('C_Login');
        }
    }

    function index()
    {
        $this->load->view('template/header');
        $id = $this->session->userdata('statusanggota');
        $iduser = $this->session->userdata('id_user');
        $nourut = $this->session->userdata('nourut');
        $data['menu'] = $this->M_Setting->getmenu1($id);
        $this->load->view('template/sidebar.php', $data);
        $data['upline'] = $this->M_Donasi->getuserupline($nourut);

        $tabel = 'tb_akses';
        $edit = array(
            'tipeuser' => $id,
            'edit' => '1',
            'id_menu' => '1'
        );
        $hasiledit = $this->M_Setting->cekakses($tabel, $edit);
        if(count($hasiledit)!=0){ 
            $tomboledit = 'aktif';
        } else {
            $tomboledit = 'tidak';
        }

        $hapus = array(
            'tipeuser' => $id,
            'delete' => '1',
            'id_menu' => '1'
        );
        $hasilhapus = $this->M_Setting->cekakses($tabel, $hapus);
        if(count($hasilhapus)!=0){ 
            $tombolhapus = 'aktif';
        } else{
            $tombolhapus = 'tidak';
        }

        $data['akseshapus'] = $tombolhapus;
        $data['aksesedit'] = $tomboledit;   

        $data['donasi'] = $this->M_Donasi->getdonasi();
        $data['donasianggota'] = $this->M_Donasi->getdonasianggota($nourut);
        if($id != 'administrator'){ 
            $this->load->view('donasi/v_donasianggota',$data); 
        } else {
            $this->load->view('donasi/v_donasiadmin',$data); 
        }
        $this->load->view('template/footer');
    }

    function bayar($idgo, $level)
    {
        $this->load->view('template/header');
        $id = $this->session->userdata('statusanggota');
        $iduser = $this->session->userdata('id_user');
        $nourut = $this->session->userdata('nourut');
        $data['menu'] = $this->M_Setting->getmenu1($id);
        $this->load->view('template/sidebar.php', $data);
        $levelup = $level+1;
        $data['level'] = $this->M_Level->getspek($levelup);
        $data['data'] = $this->M_Donasi->getuserspek($idgo);
        $this->load->view('donasi/v_adddonasi',$data); 
        $this->load->view('template/footer');
    }

     function add()
    {
        $this->load->view('template/header');
        $id = $this->session->userdata('statusanggota');
        $iduser = $this->session->userdata('id_user');
        $nourut = $this->session->userdata('nourut');
        $data['menu'] = $this->M_Setting->getmenu1($id);
        $this->load->view('template/sidebar.php', $data);
        $data['data'] = $this->M_Donasi->getuserupline($nourut);
        $this->load->view('donasi/v_adddonasianggota',$data); 
        $this->load->view('template/footer');
    }


    function upgrade()
    {   
        $upload = $this->M_Donasi->upload();
        if ($upload['result'] == "success"){
            $this->M_Donasi->upgrade($upload);
            $this->session->set_flashdata('sukses','<div class="alert alert-warning left-icon-alert" role="alert">
                                                    <strong>Sukses!</strong> Silahkan tunggu aprove admin.
                                                </div>');
            redirect('C_Donasi');  
        } else {
            'gagal';
        }
    }

    function aprove($iduser,$idanggota,$level)
    {  
        $this->M_Donasi->aprove($iduser,$idanggota,$level);
        $this->session->set_flashdata('Sukses', "Pembayaran berhasil di aprove!!!!");
            redirect('C_Donasi'); //data calon anggota
    }

    function getuserspek(){
        $id = $this->input->post('idanggota');
        $data = $this->M_Donasi->getuserspek($id);
        foreach($data as $data){
            $levelup = $data->level+1;
            $getlevel = $this->M_Level->getspek($levelup);
            foreach ($getlevel as $key) {
             $nominal = "<input type='text' name='nominal' value='".$key->nominal."' readonly class='form-control'> ";
            }
          $level = "<input type='text' name='level' value='".$levelup."' readonly class='form-control'> ";
          $upline =  "<input type='text' name='upline' value='".$data->id_upline."' class='form-control'>".$data->namaupline;
        }
        
        $callback = array('level'=>$level, 'upline' => $upline, 'nominal'=>$nominal); // Masukan variabel lists tadi ke dalam array $callback dengan index array : list_kota
        echo json_encode($callback); // konversi varibael $callback menjadi JSON
    }

     function transaksi()
    {
        $this->load->view('template/header');
        $id = $this->session->userdata('statusanggota');
        $iduser = $this->session->userdata('id_user');
        $nourut = $this->session->userdata('nourut');
        $data['menu'] = $this->M_Setting->getmenu1($id);
        $this->load->view('template/sidebar.php', $data);
        $data['data'] = $this->M_Donasi->gethistory($iduser);
        $this->load->view('donasi/v_history',$data); 
        $this->load->view('template/footer');
    }

}
