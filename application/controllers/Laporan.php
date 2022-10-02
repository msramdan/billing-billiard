<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Laporan extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        is_login();
		check_admin();
    }

	public function index()
	{
		
		if(isset($_GET['start_date'])){
			$start_date = $_GET['start_date']. " " .'00:00:01';
			$start_date2 = $_GET['start_date'];
		}else{
			$start_date = date('Y-m-d'). " " .'00:00:01';
			$start_date2 = date('Y-m-d');
		}
		if(isset($_GET['end_date'])){
			$end_date = $_GET['end_date']. " " .'23:59:59';
			$end_date2 = $_GET['end_date'];
		}else{
			$end_date = date('Y-m-d'). " " .'23:59:59';
			$end_date2 = date('Y-m-d');
		}
		$query = "SELECT *,transaction.billing_id as bill from transaction  join meja on meja.meja_id = transaction.meja_id  where start >= '$start_date' and start <= '$end_date'";
		$result = $this->db->query($query)->result();
		$data = array(
			'start_date' => $start_date2,
			'end_date' => $end_date2,
			'data_laporan' => $result,
			'disclass' => $this
			
		);
		$this->template->load('template', 'laporan/index', $data);
	}

	public function detailpaket($id_paket) {
		$this->load->model('Paket_model');
		$data = $this->Paket_model->get_by_id($id_paket);
		return $data;
	}

	public function jumlah_menit($start, $end) {
		$mulainya = new DateTime($start);
		$e = new DateTime($end);
		$diffseconds = $e->getTimestamp() - $mulainya->getTimestamp();
		$hours = floor($diffseconds / 3600);
		$minutes = floor(($diffseconds / 60) % 60);
		$seconds = $diffseconds % 60;
		$jumlah_menit = $hours * 60 + $minutes;
		return $jumlah_menit;
	}
}
