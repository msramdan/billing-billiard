<?php

function check_already_login()
{
	$ci = &get_instance();
	$user_session = $ci->session->userdata('userid');
	if ($user_session) {
		redirect('dashboard');
	}
}

function is_login()
{
	$ci = &get_instance();
	$user_session = $ci->session->userdata('userid');
	if (!$user_session) {
		redirect('auth');
	}
}

function cekJson($string){
	return is_string($string) && is_array(json_decode($string, true)) ? true : false;
 }

function check_admin()
{
	$ci = &get_instance();
	$ci->load->library('fungsi');
	if ($ci->fungsi->user_login()->level_id != 1) {
		redirect('dashboard_user');
	}
}

function rupiah($angka){
    
    $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
    return $hasil_rupiah;
}

function generateBillingId() {
	$ci = &get_instance();
	$ci->load->model('Transaksi_model');
	$last_id = $ci->Transaksi_model->get_last_id();
	$last_id = $last_id->transaction_id;
	$last_id = $last_id + 1;
	$billing_id = $last_id . date('Ymd');
	return $billing_id;
}

 function name_paket($paket_id)
	{
		$ci = &get_instance();
		$ci->load->model('Paket_model');
		$data = $ci->Paket_model->get_by_id($paket_id);
		return $data->nama_paket;
	}

