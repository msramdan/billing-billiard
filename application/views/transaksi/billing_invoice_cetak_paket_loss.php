<style>
    @import url('https://fonts.googleapis.com/css2?family=Abel&display=swap');
    @page {
        margin: 0px 1rem;
    }
    body {
        margin: 0px; font-family: 'Abel', sans-serif;
    }

    /* font arial from google */

    .table-header::after {
        content: "";
        display: table;
        clear: both;
        width: 100%;
        background-color: black;
    }

    .small-text {
        font-size: 11px;
    }
</style>
<table class="table-header" style="margin-top: 3rem; text-align: center;">
    <tr>
        <td style="font-size: 17px;"><b>NIKO BILLIARD KETAPANG</b></td>
    </tr>
    <tr>
        <td style="font-size: 8px; margin: 0;">JL. DI PANJAITAN KETAPANG, TELP<br>+62 852-4962-3888</td>
    </tr>
</table>

<table>
    <tr class="small-text">
        <td style="width: 22%;"><b>Bill</b></td>
        <td style="width: 1%;">:</td>
        <td><span class="bill-id"><?= $billing_id ?></span></td>
    </tr>
    <tr class="small-text">
        <td><b>Mulai</b></td>
        <td>:</td>
        <td><span class="start-time"><?= $start_time ?></span></td>
    </tr>
    <tr class="small-text">
        <td><b>Durasi</b></td>
        <td>:</td>
        <td><span class="duration-time"><?= $total_durasi ?></span></td>
    </tr>
    <tr>
        <td colspan="3"><b style="font-size: 13px;">Billiard</b></td>
    </tr>
    <tr>
        <td colspan="3">
            <table style="width: 100%;" class="small-text">
                <tr>
                    <td>No.</td>
                    <td>Paket</td>
                    <td>Harga</td>
                </tr>
                <tr>
                    <td><?= 1 ?></td>
                    <td>
                        <?= $paketnya->nama_paket?>
                    </td>
                    <td>
                        <?= $paketnya->harga.'/'.$paketnya->menit ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Total</td>
                    <td><b><?= $total_harga_billiard ?></b></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2" style="font-size: 13px;">Total</td>
                    <td><b style="font-size: 13px;"><?= $total_harga_billiard ?></b></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3"><b style="font-size: 13px;">Additional Item</b></td>
    </tr>
    <tr>
        <td colspan="3">
            <table style="width: 100%;" class="small-text">
                <tr>
                    <td><b>Item</b></td>
                    <td><b>Qty</b></td>
                    <td><b>Harga</b></td>
                    <td><b>Total</b></td>
                </tr>
                <?php
                $total_harga_additem = 0;

                if($itemlist) {
                    foreach ($itemlist as $key => $value) {
                        $subtotalitem = $value['harga'] * $value['qty'];
                        ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $value['nama_produk'] ?></td>
                            <td><?= $value['qty'] ?></td>
                            <td><?= $value['harga'] ?></td>
                            <td><?= $subtotalitem ?></td>
                        </tr>
                        <?php
                        $total_harga_additem += $subtotalitem;
                    }
                } else {
                    ?>
                        <td colspan="5">Tidak ada item</td>
                    <?php
                }

                ?>
                <tr>
                    <td colspan="4" style="font-size: 13px;">Total</td>
                    <td><b style="font-size: 13px;"><?= $total_harga_additem ?></b></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td><b>Grand Total</b></td>
        <td>:</td>
        <td style="text-align: right;"><b><?= $total_harga_additem + $total_harga_billiard ?></b></td>
    </tr>
</table>
<div style="text-align: center; font-size: 16px;">
    <b>Terima Kasih</b>
</div>