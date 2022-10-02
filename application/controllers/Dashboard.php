<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Dashboard extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        is_login();
		// check_admin();
		$this->load->model('Meja_model');
		$this->load->model('Paket_model');
		$this->load->model('Produk_model');
    }

	public function index()
	{
		$meja_list = $this->Meja_model->get_all();
		$paket_list = $this->Paket_model->get_all();
		$produk_list = $this->Produk_model->get_all();



		$data = array(
			'meja_list' => $meja_list,
			'paket_list' => $paket_list,
			'makananminuman_list' => $produk_list,
			'disclass' => $this
		);

		$this->template->load('template', 'dashboard', $data);
	}

 

	public function start_billing() {
		$id_meja = $this->input->post('id_meja');
		$paket_choice = $this->input->post('paket_choice');
		$billing_id = $this->input->post('billing_id');
		$action = $this->input->post('action');

		$getpaketdetail = $this->Paket_model->get_by_id($paket_choice);

		$this->load->helper('fungsi');
		if($getpaketdetail->paket_id == 0 || $getpaketdetail->paket_id == 1) {
			$start_main = date('Y-m-d H:i:s');
			$arr_data_transaksi = array(
				'billing_id' => generateBillingId(),
				'paket' => $getpaketdetail->paket_id,
				'start' => $start_main,
				'end' => 'N/A',
				'meja_id' => $id_meja,
				'billiard_play_price' => $getpaketdetail->harga,
				'additional_item' => json_encode(array()),
				'payment_status' => 0
			);

			$secondscappedconvertedfromminutes = $getpaketdetail->menit * 60;

			$this->load->model('Transaksi_model');
			$this->Transaksi_model->insert($arr_data_transaksi);

			$update_datameja = array(
				'in_use' => 1,
				'billing_id' => $arr_data_transaksi['billing_id'],
			);
			$this->load->model('Meja_model');
			$this->Meja_model->update($id_meja, $update_datameja);

			$arr = array(
				'bill_id' => $arr_data_transaksi['billing_id'],
				'start_time' => $start_main,
				// 'left_time' => $time_left,
				'seconds' => 0,
				'seconds_capped' => $secondscappedconvertedfromminutes,
				'end_time' => '-',
				'status' => 'success',
				'jenis_paket' => 'loss'
			);

			echo json_encode($arr);
		} else {
			$getmenitbypaketchoice = $getpaketdetail->menit;

			// convert getmenitbypaketchoice to seconds
			$seconds_paket = $getmenitbypaketchoice * 60;
			
			$total_jam_tambah = $getmenitbypaketchoice.' Minutes';


			$start_main = date('Y-m-d H:i:s');

			$end_main = date('Y-m-d H:i:s', strtotime($start_main . ' + ' . $total_jam_tambah));

			$total_hours = (strtotime($end_main) - strtotime($start_main)) / 3600;
			$total_minutes = ($total_hours - floor($total_hours)) * 60;
			$total_seconds = ($total_minutes - floor($total_minutes)) * 60;

			$datenow = date('Y-m-d H:i:s');

			$total_hours_until_now = (strtotime($datenow) - strtotime($start_main)) / 3600;
			$total_minutes_until_now = ($total_hours_until_now - floor($total_hours_until_now)) * 60;
			$total_seconds_until_now = ($total_minutes_until_now - floor($total_minutes_until_now)) * 60;

			// get duration of billing
			// $durasi_total = floor($total_hours_until_now) . ' Jam ' . floor($total_minutes_until_now) . ' Menit ' . floor($total_seconds_until_now) . ' Detik';
			
			$total_hours_left = (strtotime($end_main) - strtotime($datenow)) / 3600;
			$total_minutes_left = ($total_hours_left - floor($total_hours_left)) * 60;
			$total_seconds_left = ($total_minutes_left - floor($total_minutes_left)) * 60;

			$time_left = floor($total_hours_left) . ' Jam ' . floor($total_minutes_left) . ' Menit ' . floor($total_seconds_left) . ' Detik';

			$arrdatapaket = array(
				[
					'id_paket' => $getpaketdetail->paket_id,
					'nama_paket' => $getpaketdetail->nama_paket,
					'menit' => $getpaketdetail->menit,
					'harga' => $getpaketdetail->harga,
				]
			);
			$arr_data_transaksi = array(
				'billing_id' => generateBillingId(),
				'paket' => json_encode($arrdatapaket),
				'start' => $start_main,
				'end' => $end_main,
				'meja_id' => $id_meja,
				'billiard_play_price' => $getpaketdetail->harga,
				'additional_item' => json_encode(array()),
				'payment_status' => 0
			);

			$this->load->model('Transaksi_model');
			$this->Transaksi_model->insert($arr_data_transaksi);

			$update_datameja = array(
				'in_use' => 1,
				'billing_id' => $arr_data_transaksi['billing_id'],
			);
			$this->load->model('Meja_model');
			$this->Meja_model->update($id_meja, $update_datameja);

			$arr = array(
				'bill_id' => $arr_data_transaksi['billing_id'],
				'start_time' => $start_main,
				// 'left_time' => $time_left,
				'seconds' => $seconds_paket,
				'end_time' => $end_main,
				'status' => 'success',
				'jenis_paket' => 'custom'
			);

			echo json_encode($arr);
		}
	}

	public function tambah_billing() {

		$this->load->model('Transaksi_model');
		$id_paket = $this->input->post('id_paket');
		$id_billing = $this->input->post('id_billing');

		$getmenitbypaketchoice = $this->Paket_model->get_by_id($id_paket)->menit;

		$total_menit_tambah = $getmenitbypaketchoice.' Minutes';

		$billing_data = $this->Transaksi_model->get_by_billing_id($id_billing);

		$paketpaketnya = json_decode($billing_data->paket, TRUE);

		$paketyangdipilih = $this->Paket_model->get_by_id($id_paket);

		$additionalitem = 0;

		$cekadditionalitem = json_decode($billing_data->additional_item, TRUE);

		if($cekadditionalitem != NULL) {
			foreach ($cekadditionalitem as $key => $value) {
				$additionalitem += $value['harga'];
			}
		}


		$end_main_old = date('Y-m-d H:i:s', strtotime($billing_data->end));
		$end_main_new = '';
		// if date now <= end_main_old, then add time to end_main_old, else return
		
		

		$datenow = date('Y-m-d H:i:s');
		if($datenow <= $end_main_old) {
			$end_main_new = date('Y-m-d H:i:s', strtotime($end_main_old . ' + ' . $total_menit_tambah));
		} else {
			$end_main_new = date('Y-m-d H:i:s', strtotime($datenow . ' + ' . $total_menit_tambah));
		}

		$paketpaketnya[] = array(
			'id_paket' => $paketyangdipilih->paket_id,
			'nama_paket' => $paketyangdipilih->nama_paket,
			'menit' => $paketyangdipilih->menit,
			'harga' => $paketyangdipilih->harga,
		);

		$billiard_play_price = $billing_data->grand_total + $paketyangdipilih->harga;

		$menit_list = [];
		foreach ($paketpaketnya as $q => $v) {
			$menit_list[] = intval($v['menit']);
		}

		$arr_data_transaksi = array(
			'paket' => json_encode($paketpaketnya),
			'end' => $end_main_new,
			'billiard_play_price' => $billiard_play_price
		);

		$this->Transaksi_model->update_by_billing_id($id_billing, $arr_data_transaksi);

		$arr = array(
			'bill_id' => $id_billing,
			'start_time' => $billing_data->start,
			// 'left_time' => $this->get_time_left($end_main_new),
			'menit_list' => $menit_list,
			'end_time' => $end_main_new,
			'status' => 'success',
		);

		echo json_encode($arr);

	}

	public function get_informasibilling() {
		$id_billing = $this->input->post('billing_id');

		// get data billing
		$this->load->model('Transaksi_model');
		$billing_data = $this->Transaksi_model->get_by_billing_id($id_billing);

		if($billing_data) {

			if(is_numeric($billing_data->paket)) {
				if($billing_data->paket == 0 || $billing_data->paket == 1) {

					$additionalitemdaridatabilling = json_decode($billing_data->additional_item, TRUE);

					$mulainya = new DateTime($billing_data->start);
					$e = new DateTime();
					$diffseconds = $e->getTimestamp() - $mulainya->getTimestamp();
					
					// end of date mulainya + diffseconds
					$akhirnya = date('Y-m-d H:i:s', strtotime($mulainya->format('Y-m-d H:i:s') . ' + ' . $diffseconds . ' seconds'));
					$this->load->model('Paket_model');
					$getdatapaket = $this->Paket_model->get_by_id($billing_data->paket);

					$hours = floor($diffseconds / 3600);
					$minutes = floor(($diffseconds / 60) % 60);
					$seconds = $diffseconds % 60;

					$durasinya = $hours.':'.$minutes.':'.$seconds;


					$bayarnyabilling = $getdatapaket->harga / $getdatapaket->menit * $minutes;

					$arrdata = array(
						'billing_id' => $id_billing,
						'nama_paket_lossnya' => $getdatapaket->nama_paket,
						'start_time' => $mulainya,
						'total_durasi' => $durasinya.' ('.$minutes.' Menit)',
						'paketnya' => $getdatapaket,
						'total_harga_billiard' => intval($bayarnyabilling),
						'itemlist' => $additionalitemdaridatabilling,
					);

					$arrresp = array(
						'status' => 'success',
						'data' => $this->load->view('transaksi/meja_billing_detail_paketloss', $arrdata, TRUE),
						'message' => 'ok'
					);
	
					echo json_encode($arrresp);
				}
			} else {
				$paketyangdimaenindaridatabilling = json_decode($billing_data->paket, TRUE);
				$additionalitemdaridatabilling = json_decode($billing_data->additional_item, TRUE);
				$menittotal = 0;
		
				foreach ($paketyangdimaenindaridatabilling as $q => $v) {
					$menittotal += intval($v['menit']);
				}
		
				// convert menittotal to hours and minutes and seconds
				$hours = floor($menittotal / 60);
				$minutes = ($menittotal % 60);
				$seconds = 0;
		
				$durasinya = $hours.':'.$minutes.':'.$seconds;
		
				$arrdata = array(
					'billing_id' => $id_billing,
					'start_time' => $billing_data->start,
					'total_durasi' => $durasinya.' ('.$menittotal.' Menit)',
					'paketlist' => $paketyangdimaenindaridatabilling,
					'itemlist' => $additionalitemdaridatabilling,
				);
				
				$arrresp = array(
					'status' => 'success',
					'data' => $this->load->view('transaksi/meja_billing_detail_paketcustom', $arrdata, TRUE),
					'message' => 'ok'
				);

				echo json_encode($arrresp);
			}
		} else {
			$arrresp = array(
				'status' => 'not found',
				'data' => '',
				'message' => 'Silahkan Memulai Billing Terlebih dahulu'
			);

			echo json_encode($arrresp);
		}

	}

	public function get_order_itemofbilling() {

		$billing_id = $this->input->post('billing_id');

		$this->load->model('Transaksi_model');
		$billing_data = $this->Transaksi_model->get_by_billing_id($billing_id);

		$additionalitemdaridatabilling = json_decode($billing_data->additional_item, TRUE);

		$str = '';

		if($additionalitemdaridatabilling != NULL) {
			foreach ($additionalitemdaridatabilling as $key => $value) {
				$str .= '<tr>
							<td>'.$value['nama_produk'].'<input type="hidden" name="menu_id[]" value="'.$value['produk_id'].'"/></td>
							<td><input type="number" min="1" class="qty-order" name="qty[]" value="'.$value['qty'].'"/></td>
							<td>'.$value['harga'].'</td>
							<td>'.$value['harga'] * $value['qty'].'</td>
							<td><button class="btn btn-danger btn-sm btn-hapus-order" type="button">Hapus</button></td>
						</tr>';
			}
		}

		echo $str;

		
	}

	public function update_menu_order() {
		$billing_id = $this->input->post('billing_id');
		$menu_id = $this->input->post('menu_id[]');
		$qty = $this->input->post('qty[]');

		$pesenan = array();
		
		if($menu_id == NULL) {
			$pesenan = [];
		} else {
			foreach($menu_id as $key => $value) {

				$getprodukdetail = $this->Produk_model->get_by_id($value);
	
				$pesenan[] = array(
					'produk_id' => $value,
					'nama_produk' => $getprodukdetail->nama_produk,
					'harga' => $getprodukdetail->harga,
					'qty' => $qty[$key],
				);
			}
		}



		$this->load->model('Transaksi_model');

		$arraydatabilling = array(
			'additional_item' => json_encode($pesenan),
		);

		$this->Transaksi_model->update_by_billing_id($billing_id, $arraydatabilling);

		$arrr = array(
			'status' => 'success',
		);

		echo json_encode($arrr);

	}

}
