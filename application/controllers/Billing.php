<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Billing extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function index()
	{
		$barang = $this->Barang_model->get_all();
		$data = array(
			'barang_data' => $barang,
		);
		$this->template->load('template', 'barang/barang_list', $data);
	}

	public function print($id)
	{
		$id_billing = $id;

		// get data billing
		$this->load->model('Transaksi_model');
		$billing_data = $this->Transaksi_model->get_by_billing_id($id_billing);
		$arrdata = '';

		$this->load->library('pdfgenerator');
        
        // filename dari pdf ketika didownload
        $file_pdf = 'Invoice Billing #'.$id_billing;
        // setting paper
        $paper = array(0,0,204,650);
        //orientasi paper potrait / landscape
        $orientation = "portrait";

		if(is_numeric($billing_data->paket)){
			$this->load->model('Paket_model');
			$getmenitpaketchoice = $this->Paket_model->get_by_id($billing_data->paket)->menit;

			$paketinseconds = $getmenitpaketchoice * 60;
			$additionalitemdaridatabilling = json_decode($billing_data->additional_item, TRUE);

			$mulainya = new DateTime($billing_data->start);
			$e = new DateTime($billing_data->end);
			$diffseconds = $e->getTimestamp() - $mulainya->getTimestamp();
			
			// end of date mulainya + diffseconds
			$akhirnya = date('Y-m-d H:i:s', strtotime($mulainya->format('Y-m-d H:i:s') . ' + ' . $diffseconds . ' seconds'));

			$getdatapaket = $this->Paket_model->get_by_id($billing_data->paket);

			$hours = floor($diffseconds / 3600);
			$minutes = floor(($diffseconds / 60) % 60);
			$seconds = $diffseconds % 60;

			$durasinya = $hours.':'.$minutes.':'.$seconds;

			$bayarnyabilling = $getdatapaket->harga / $paketinseconds * $diffseconds;

			$arrdata = array(
				'billing_id' => $id_billing,
				'start_time' => $billing_data->start,
				'total_durasi' => $durasinya,
				'paketnya' => $getdatapaket,
				'total_harga_billiard' => intval($bayarnyabilling),
				'itemlist' => $additionalitemdaridatabilling,
				'title_pdf' => 'Billing #'.$id_billing,
			);

			$html = $this->load->view('transaksi/billing_invoice_cetak_paket_loss', $arrdata, true);
			$this->pdfgenerator->generate_pdf_for_thermal($html, $file_pdf, $paper, $orientation);



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
				'title_pdf' => 'Billing #'.$id_billing,
			);

			$html = $this->load->view('transaksi/billing_invoice_cetak_paket_custom',$arrdata, true);	    
        
			// run dompdf
			$this->pdfgenerator->generate_pdf_for_thermal($html, $file_pdf,$paper,$orientation);
		}
    }
}