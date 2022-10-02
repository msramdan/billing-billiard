<div id="content" class="content">
	<div class="row">
		<div class="col-md-3">
			<div class="panel panel-inverse">
				<div class="panel-heading">

					<h4 class="panel-title">Filter Tanggal</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="box-body">
									<div class="box-body">
										<form class="form-horizontal" action="<?= base_url() ?>laporan" method="GET">
											<div class="form-group">
												<label class="col-md-4 control-label">Start Date</label>
												<div class="col-md-8">
													<input type="date" class="form-control" placeholder="" name="start_date" value="<?= $start_date ?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-4 control-label">End Date</label>
												<div class="col-md-8">
													<input type="date" class="form-control" placeholder="" name="end_date" value="<?= $end_date ?>">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-8 col-md-offset-4">
													<button type="submit" class="btn btn-sm btn-primary m-r-5"><i class="fa fa-search" aria-hidden="true"></i> Filter</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

		</div>
		<div class="col-md-9">
			<div class="panel panel-inverse">
				<div class="panel-heading">

					<h4 class="panel-title">List Transaksi</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								<div class="box-body">
									<div class="box-body">
										<table id="data-table" class="table table-bordered table-hover table-td-valign-middle">
											<thead>
												<tr>
													<th>No</th>
													<th>Bill</th>
													<th>Meja</th>
													<th>Mulai</th>
													<th>Selesai</th>
													<th>Detail Paket</th>
													<th>Detail Makanan/Minuman</th>
													<th>Grand Total</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody><?php $no = 1;
											$jml = 0;
													foreach ($data_laporan as $data) {
													?>
														<tr>
															<td><?= $no++ ?></td>
															<td><?php echo $data->bill ?></td>
															<td><?php echo $data->nama_meja ?></td>
															<td><?php echo $data->start ?></td>
															<td><?php echo $data->end ?></td>
															<td> <?php 
															
															if(strlen($data->paket) >= 5) {
																$data_bill= json_decode($data->paket);
																foreach ($data_bill as $value) {
																	echo "Nama Paket : " . $value->nama_paket . '<br>';
																	echo "Menit : " . $value->menit . '<br>';
																	echo "Harga : " . rupiah($value->harga)  . '<br>';
																	echo "____________________________ <br>";
																}
															} else {
																echo "Nama Paket : " . $disclass->detailpaket($data->paket)->nama_paket . '<br>';
																echo "Menit : " . $disclass->jumlah_menit($data->start,$data->end) . '<br>';
																echo "Harga : " . rupiah($data->billiard_play_price)  . '<br>';
																echo "____________________________ <br>";
															}
															?>

														
															<b>Total : <?php echo rupiah($data->billiard_play_price)  ?></b>
														</td>
														<td> <?php $additional_item = json_decode($data->additional_item);
																$total_makanan = 0;
																foreach ($additional_item as $row) {
																	echo "Nama Produk : " . $row->nama_produk . '<br>';
																	echo "Harga : " . rupiah($row->harga)  . '<br>';
																	echo "Qty : " . $row->qty . '<br>';
																	echo "Subtotal : " . rupiah($row->qty * $row->harga) . '<br>';
																	echo "____________________________ <br>";
																	$total_makanan = $total_makanan + ($row->qty * $row->harga);
																}
																?>
															<b>Total : <?php echo rupiah($total_makanan)  ?></b>
														</td>
														<td><?php echo rupiah($data->billiard_play_price + $total_makanan) ?></td>
														<td>
															<a href="<?= base_url() ?>billing/print/<?= $data->bill ?>" class="btn btn-sm btn-danger m-r-5"><i class="fa fa-print" aria-hidden="true"></i> Invoice</a>
														</td>
													</tr>
												<?php 
												$jml += $data->billiard_play_price + $total_makanan;
												} 
												?>
											</tbody>


											<tbody>

											</tbody>
										</table>
										<div class="alert alert-success" role="alert">
											<h5>Total Pemasukan : <?= rupiah($jml) ?></h5> 
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
