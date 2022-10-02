<table class="table table-hover">
    <tr>
        <td style="width: 22%;"><b>Bill</b></td>
        <td style="width: 1%;">:</td>
        <td><span class="bill-id"><?= $billing_id ?></span></td>
    </tr>
    <tr>
        <td><b>Mulai</b></td>
        <td>:</td>
        <td><span class="start-time"<?= $start_time ?></span></td>
    </tr>
    <tr>
        <td><b>Durasi</b></td>
        <td>:</td>
        <td><span class="duration-time"><?= $total_durasi ?></span></td>
    </tr>
    <tr>
        <td><b>Billiard</b></td>
        <td>:</td>
        <td>
            <table style="width: 100%;">
                <?php
                $total_harga_billiard = 0;
                foreach ($paketlist as $key => $value) {
                    ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $value['nama_paket'] ?>
                            <?php
                            // if the element is last, then show the button
                            if ($key == count($paketlist) - 1) {
                                ?>
                                <label class="badge badge-success">Terakhir</label>
                                <?php
                            }
                            ?>
                        </td>
                        <td><?= $value['menit'] ?></td>
                        <td><?= $value['harga'] ?></td>
                    </tr>
                    <?php
                    $total_harga_billiard += $value['harga'];
                }
                ?>
                <tr>
                    <td></td>
                    <td colspan="2">Total</td>
                    <td><b><?= $total_harga_billiard ?></b></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td><b>Additional Item</b></td>
        <td>:</td>
        <td>
            <table style="width: 100%;">
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
                    <td colspan="3">Total</td>
                    <td><b><?= $total_harga_additem ?></b></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td><b>Grand Total</b></td>
        <td>:</td>
        <td><b><?= $total_harga_additem + $total_harga_billiard ?></b></td>
    </tr>
</table>