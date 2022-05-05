<?php
class mModal{
    function printtransaction($id){
        $db = new mDatabase();
        $config = new mConfig();
        $sql = $db->select_where('transaction_history',"*","TransactionId = '" . $id . "'");
        $result = array();
        if($sql){
            $data = $sql[0];
            $result[] = "KOPERASI BAHTERA </br>";
            $result[] = "Jln. Raden Patah No.61</br>";
            $result[] = "Kec. Lubuk Baja</br>";
            $result[] = "Batam - Kepri 29444</br>";
            $result[] = $config->defaultdate($data['Date']) . " " . $data['Time'] . '</br>';
            $sql = $db->select_where(TB_USER,'fullname',"userid = '" . $data['UserId'] . "'");
            $result[] = "Cashier: " . $sql[0]['fullname'] .'</br>';
            $sql = $db->select_where('member','Name',"MemberId = '" . $data['MemberId'] . "'");
            $result[] = "Member: " . $sql[0]['Name'] .'</br>';
            $result[] = "<p></p>";
            $result[] = '<table border="0" width="100%">';
            $result[] = '<tr><th>========</th><th>========</th><th>========</th><th>========</th></tr>';
            $sql = $db->select_join('transaction AS tr,product AS pr',"tr.Barcode, tr.Qty, tr.TotalPrice, pr.ProductName","tr.Barcode = pr.Barcode","tr.TransactionId = '" . $id . "'");
            foreach($sql as $data2){
                $result[] = '<tr align="left"><td colspan="4">' . $data2[3] . '</td></tr>';
                $result[] = '<tr><td colspan="2">' . $data2[0] . ' x </td><td>' . $data2[1] . ' = </td><td align="right">' . number_format($data2[2]) . '</td></tr>';
            }
            $result[] = '<tr><th>========</th><th>========</th><th>========</th><th>========</th></tr>';
            $result[] = '<tr><td colspan="2">TOTAL</td><td>Rp</td><td align="right">' . number_format($data['GrandTotal']) . '</td></tr>';
            $result[] = '<tr><td colspan="2">CASH</td><td>Rp</td><td align="right">' . number_format($data['TotalPayment']) . '</td></tr>';
            $result[] = '<tr><td colspan="2">CHANGE</td><td>Rp</td><td align="right">' . number_format($data['MoneyChanges']) . '</td></tr>';
            $result[] = '</table>';
            $result[] = "Payment: " . $data['PaymentMethod'] . " Ref: " . $data['PaymentReference'] . "</br>";
            $result[] = "Trans. ID: #" . $id . "</br>";
            $result[] = "<p></p>";
            $result[] = "Terimakasih telah Belanja di Toko Kami</br>";
        }
        return $result;
    }    
    function dailytransaction($profit = false,$date = null, $dateend = null, $memberid = null){
        $dbconfig = new mDatabase();
        if($date == null){
            $date = date("Y-m-d");
        }
        $where = "DATE(Date) = DATE('". $date ."') ";
        if($dateend){
            $where = "DATE(Date) BETWEEN DATE('". $date ."') AND DATE('". $dateend ."')";
        }
        if($memberid){
            $where .= " AND MemberId ='". $memberid . "'";
        }
        ?>
        <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm w-100 small modaltable nosorttable" cellspacing="0">
            <thead>
                <tr class="text-center">
                    <th width="20%">Date Time</th>
                    <th width="20%">Transaction ID</th>
                    <th>Items Qty</th>
                    <th>Cash</th>
                    <th>Credit</th>
                    <th>Payment Methode</th>
                    <?= $profit? "<th>Capital</th><th>Profit</th>":""
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalitems = 0;
                $totalcash = 0;
                $totalcredit = 0;
                $totalpaymentcash = 0;
                $totalpaymentcredit = 0;
                $totaltransaction = 0;
                $totalcapital = 0;
                $totalprofit = 0;
                $sql = $dbconfig->select_where('transaction_history', "Date,Time,TotalItems,TransactionId,SUM(CASE WHEN PaymentMethod='Cash' THEN GrandTotal ELSE 0 END) AS TotalCash,SUM(CASE WHEN PaymentMethod='credit' THEN GrandTotal ELSE 0 END) AS TotalCredit, PaymentMethod, TotalCapital,TotalProfit", $where . " GROUP BY TransactionId");
                if ($sql) {
                    $totaltransaction = count($sql);
                    foreach ($sql as $data) {
                        $totalitems += $data['TotalItems'];
                        $totalcash += $data['TotalCash'];
                        $totalcredit += $data['TotalCredit'];
                        $totalcapital += $data['TotalCapital'];
                        $totalprofit += $data['TotalProfit'];
                        if ($data['PaymentMethod'] == "Cash") {
                            $totalpaymentcash++;
                        } else {
                            $totalpaymentcredit++;
                        }
                        ?>
                        <tr class="text-center">
                            <td><?= $data['Date'] . " " . $data['Time']; ?></a></td>
                            <td class="font-weight-bold"><a href="#<?= $data['TransactionId'] ?>" data-transaction="<?= $data['TransactionId'] ?>" data-toggle="modal" data-target="#printtransaction" onclick="tr_print('<?= $data['TransactionId'] ?>')"><?= $data['TransactionId'] ?></a></td>
                            <td><?= $data['TotalItems'] ?></td>
                            <td class="text-right"><?= number_format($data['TotalCash'],2) ?></td>
                            <td class="text-right"><?= number_format($data['TotalCredit'],2) ?></td>
                            <td><?= $data['PaymentMethod'] ?></td>
                            <?php
                            if($profit){
                            ?>
                            <td class="text-right"><?= number_format($data['TotalCapital'],2) ?></td>
                            <td class="text-right"><?= number_format($data['TotalProfit'],2) ?></td>
                            <?php
                            }
                            ?>
                        </tr>
                <?php
                    }
                }
                ?>
                <tr class="text-center font-weight-bold">
                    <td>Total</td>
                    <td><?= $totaltransaction ?></a></td>
                    <td><?= $totalitems ?></a></td>
                    <td class="text-right"><?= number_format($totalcash,2) ?></td>
                    <td class="text-right"><?= number_format($totalcredit,2) ?></td>
                    <td>Cash: <?= $totalpaymentcash ?> || Credit: <?= $totalpaymentcredit ?></td>
                    <?php
                    if($profit){
                        ?>
                        <td class="text-right"><?= number_format($totalcapital,2) ?></td>
                        <td class="text-right"><?= number_format($totalprofit,2) ?></td>
                        <?php
                    }
                    ?>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
    }
}
?>