<?php
class Dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$isLogin = $this->session->userdata('LoggedIn');
		if($isLogin) {
			$level = $this->session->userdata('level');
			if($level=="Master") {
				$this->load->model('Dosen/M_Dashboard','m');
			}
		} else {
			redirect('portal');
		}
	}

	public function index() {
		$data["Title"] = "Dosen";
		$data["Konten"] = "dosen/v_dashboard";
		$this->load->view("v_master",$data);
	}

	public function list_data() {
		$list = $this->m->get_list_data();
		$datatb = array();
		foreach($list as $data) {
			$row = array();
			$row[] = $data->dosen_nip;
			$row[] = $data->dosen_nama;
			$row[] = $data->dosen_email;
			$row[] = $data->dosen_alamat;
			$row[] = $data->dosen_kontak;
			$row[] = $data->dosen_kuota;
			$row[] = "<button id='edit' data='".$data->dosen_id."' class='btn btn-xs btn-warning'><i class='fa fa-pencil-alt'></i></button> | 
			<button id='hapus' class='btn btn-xs btn-danger' data='".$data->dosen_id."'><i class='fa fa-trash-alt'></i></a>";
			$datatb[] = $row;
		}
		$output = array(
			"draw" => $this->input->post('draw'),
			"data" => $datatb
		);
		echo json_encode($output);
	}

	public function get_data() {
		$res = $this->m->get_data();
		$data = array(
			'dosen_id' => $res->dosen_id,
			'dosen_nip' => $res->dosen_nip,
			'dosen_nama' => $res->dosen_nama,
			'dosen_email' => $res->dosen_email,
			'dosen_alamat' => $res->dosen_alamat,
			'dosen_kontak' => $res->dosen_kontak,
			'dosen_kuota' => $res->dosen_kuota
		);
		echo json_encode($data);
	}

	public function simpan() {
		$dosen_id = $this->input->post('dosen_id');
		if($dosen_id=="") {
			$data = array(
				'dosen_id' => $this->m->get_id(),
				'dosen_nip' => $this->input->post('dosen_nip'),
				'dosen_nama' => $this->input->post('dosen_nama'),
				'dosen_email' => $this->input->post('dosen_email'),
				'dosen_alamat' => $this->input->post('dosen_alamat'),
				'dosen_kontak' => $this->input->post('dosen_kontak'),
				'dosen_kuota' => $this->input->post('dosen_kuota'),
				'created_by' => $this->session->userdata('nama')
			);
			$res = $this->m->simpan($data);
			$pesan = array(
				'warning' => 'Berhasil!',
				'kode' => 'success',
				'pesan' => 'Data dosen '.$this->input->post('dosen_nama').' berhasil di simpan'
			);
		} else {
			$data = array( 
				'dosen_nip' => $this->input->post('dosen_nip'),
				'dosen_nama' => $this->input->post('dosen_nama'),
				'dosen_email' => $this->input->post('dosen_email'),
				'dosen_alamat' => $this->input->post('dosen_alamat'),
				'dosen_kontak' => $this->input->post('dosen_kontak'),
				'dosen_kuota' => $this->input->post('dosen_kuota'),
				'updated_by' => $this->session->userdata('nama'),
				'last_update' => date('Y-m-d H:i:s')
			);
			$res = $this->m->edit($data);
			$pesan = array(
				'warning' => 'Berhasil!',
				'kode' => 'success',
				'pesan' => 'Data dosen '.$this->input->post('dosen_nama').' berhasil di perbaharui'
			);
		}
		echo json_encode($pesan);
	}

	public function hapus() {
		$data = array( 'deleted' => TRUE );
		$this->m->hapus($data);
		$pesan = array(
			'warning' => 'Berhasil!',
			'kode' => 'success',
			'pesan' => 'Data dosen berhasil di hapus'
		);
		echo json_encode($pesan);
	}

	public function options() {
		$res = $this->m->options();
		echo json_encode($res);
	}

}