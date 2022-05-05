<?php
$dbconfig = new mDatabase();
$config = new mConfig();

if (isset($_GET['datefrom'])) {
    $datefrom = $_GET['datefrom'];
} else {
    $datefrom = date('Y-m-01');
}

if (isset($_GET['dateto'])) {
    $dateto = $_GET['dateto'];
} else {
    $dateto = date('Y-m-t');
}

if (isset($_GET['session'])) {
    $session = $_GET['session'];
} else {
    $session = 0;
}


?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <?= $config->breadcumb(); //view web position of the user
    ?>
    <h1 class="h3 mb-2 text-gray-800">Report</h1>
    <form method="get">
        <div class="form-inline mb-3">
            <label class="pr-2" for="datefrom">From</label>
            <input type="date" onkeydown="return false" class="form-control form-control-sm mr-2" name="datefrom" value="<?= $datefrom ?>">
            <label class="pr-2" for="dateto">To</label>
            <input type="date" onkeydown="return false" class="form-control form-control-sm mr-2" name="dateto" value="<?= $dateto ?>">
            <input type="hidden" name="session" id="session" value="0" class="form-control form-control-sm mr-2" name="dateto" value="<?= $dateto ?>">
            <button type="submit" class="btn btn-sm btn-primary mr-2">
                <span class="text">Process</span>
            </button>
        </div>
    </form>
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link nav-report <?=$session == 0 ? 'active' : ''; ?>" data-toggle="tab" data-session="0" href="#table1">Transaction</a></li>
        <li class="nav-item"><a class="nav-link nav-report <?=$session == 1 ? 'active' : ''; ?>" data-toggle="tab" data-session="1" href="#table2">Inventory</a></li>
        <li class="nav-item"><a class="nav-link nav-report <?=$session == 2 ? 'active' : ''; ?>" data-toggle="tab" data-session="2" href="#table3">Member</a></li>
    </ul>
    <div class="tab-content pt-2">
        <div id="table1" class="tab-pane <?=$session == 0 ? 'active' : ''; ?>">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm w-100 small nosorttable" id="tablereporttransaction" cellspacing="0" data-title="SALES REPORT PER <?=$config->defaultdate($dateto)?>">
                    <thead>
                        <tr class="text-center">
                            <th>Date</th>
                            <th>Transaction</th>
                            <th>Items</th>
                            <th>Cash</th>
                            <th>Credit</th>
                            <th>Total Sales</th>
                            <th>Total Capital</th>
                            <th>Total Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totaltransaction = 0;
                        $totalitems = 0;
                        $totalcash = 0;
                        $totalcredit = 0;
                        $totalsales = 0;
                        $totalcapital = 0;
                        $totalprofit = 0;
                        $sql = $dbconfig->select_where("transaction_history", "Date, COUNT(TransactionId) AS TotalTransaction, SUM(TotalItems) AS TotalItems, SUM(CASE WHEN PaymentMethod = 'Cash' THEN GrandTotal ELSE 0 END) AS TotalCash, SUM(CASE WHEN PaymentMethod = 'Credit' THEN GrandTotal ELSE 0 END) AS TotalCredit,SUM(GrandTotal) AS SalesTotal, SUM(TotalCapital) AS TotalCapital, SUM(TotalProfit) AS TotalProfit", "`Date` BETWEEN DATE('" . $datefrom . "') AND DATE('" . $dateto . "') GROUP BY Date");
                        if ($sql) {
                            foreach ($sql as $data) {
                                $totaltransaction += $data['TotalTransaction'];
                                $totalitems += $data['TotalItems'];
                                $totalcash += $data['TotalCash'];
                                $totalcredit += $data['TotalCredit'];
                                $totalsales += $data['SalesTotal'];
                                $totalcapital += $data['TotalCapital'];
                                $totalprofit += $data['TotalProfit'];
                                ?>
                                <tr class="text-center">
                                    <td class="font-weight-bold"><a href="#" data-toggle="modal" data-target="#desmodal" class="rp_transaction" data-date="<?= $data['Date'] ?>"><?= $data['Date'] ?></a></td>
                                    <td><?= $data['TotalTransaction'] ?></td>
                                    <td><?= $data['TotalItems'] ?></td>
                                    <td class="text-right" class="text-right"><?= number_format($data['TotalCash'],2) ?></td>
                                    <td class="text-right"><?= number_format($data['TotalCredit'],2) ?></td>
                                    <td class="text-right"><?= number_format($data['SalesTotal'],2) ?></td>
                                    <td class="text-right"><?= number_format($data['TotalCapital'],2) ?></td>
                                    <td class="text-right"><?= number_format($data['TotalProfit'],2) ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                        <tr class="text-center font-weight-bold">
                            <td>Total</td>
                            <td><?= $totaltransaction ?></td>
                            <td><?= $totalitems ?></td>
                            <td class="text-right"><?= number_format($totalcash,2) ?></td>
                            <td class="text-right"><?= number_format($totalcredit,2) ?></td>
                            <td class="text-right"><?= number_format($totalsales,2) ?></td>
                            <td class="text-right"><?= number_format($totalcapital,2) ?></td>
                            <td class="text-right"><?= number_format($totalprofit,2) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="table2" class="tab-pane <?=$session == 1 ? 'active' : ''; ?>">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm w-100 small defaulttable2" id="tablereportinventory" cellspacing="0" data-title="INVENTORY REPORT PER <?=$config->defaultdate($dateto)?>">
                    <thead>
                        <tr class="text-center">
                            <th>Barcode</th>
                            <th>Product Name</th>
                            <th>Purchase Price</th>
                            <th>Sales Price</th>
                            <th>Qty In</th>
                            <th>Qty Out</th>
                            <th>Current Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = $dbconfig->select_join("product_history AS ph, product AS pr", "pr.Barcode AS Barcode, pr.ProductName AS ProductName, ph.PurchasePrice AS PurchasePrice, ph.SalesPrice AS SalesPrice, SUM(CASE WHEN ph.PurchaseDate BETWEEN DATE('" . $datefrom . "') AND DATE('" . $dateto . "') THEN ph.Qty ELSE 0 END) AS QtyIn, MAX(CASE WHEN ph.PurchaseDate BETWEEN DATE('" . $datefrom . "') AND DATE('" . $dateto . "') THEN ph.PurchaseDate ELSE '' END) AS DateIn, pr.Stock AS Stock", "ph.Barcode = pr.Barcode", "pr.Status IS NULL OR pr.Status = '' GROUP BY pr.Barcode ORDER BY DateIn DESC","RIGHT");
                        if ($sql) {
                            foreach ($sql as $data) {
                                $sql = $dbconfig->select_where('transaction', 'SUM(Qty) AS QtyOut', "Barcode = '" . $data[0] . "' AND Date BETWEEN DATE('" . $datefrom . "') AND DATE('" . $dateto . "')");
                                print $dbconfig->get_error();
                                ?>
                                <tr class="text-center">
                                    <td><?= $data['Barcode'] ?></td>
                                    <td><?= $data['ProductName'] ?></td>
                                    <td class="text-right"><?= number_format($data['PurchasePrice'],2)?></td>
                                    <td class="text-right"><?= number_format($data['SalesPrice'],2) ?></td>
                                    <td><?= $data['QtyIn'] ?></td>
                                    <td><?= $sql[0]['QtyOut'] ?></td>
                                    <td><?= $data[6] ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="table3" class="tab-pane <?=$session == 2 ? 'active' : ''; ?>">
            <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm w-100 small defaulttable1" id="tablemember" cellspacing="0" data-title="MEMBER PAYMENT REPORT PER <?=$config->defaultdate($dateto)?>">
                <thead>
                    <tr class="text-center">
                        <th width="15%">Member ID</th>
                        <th >Name</th>
                        <th width="10%">Gender</th>
                        <th width="10%">Status</th>
                        <th>Total Cash</th>
                        <th>Total Credit</th>
                        <th>Total Payment</th>
                        <th>Point</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = $dbconfig->select_join('member AS mb , transaction_history AS th', "mb.MemberId AS MemberId, Name, Gender, Status, SUM(CASE WHEN th.PaymentMethod='Cash' AND th.Date BETWEEN DATE('" . $datefrom . "') AND DATE('" . $dateto . "') THEN th.GrandTotal ELSE 0 END) AS TotalCash, SUM(CASE WHEN th.PaymentMethod='Credit' AND th.Date BETWEEN DATE('" . $datefrom . "') AND DATE('" . $dateto . "') THEN th.GrandTotal ELSE 0 END) AS TotalCredit", "mb.MemberId = th.MemberId", "mb.Status <> 'deleted' GROUP BY mb.MemberId", 'LEFT');
                    //print $dbconfig->get_error();
                    if ($sql) {
                        foreach ($sql as $data) {
                            $totalpayment = $data['TotalCash']+$data['TotalCredit'];
                            $point = $totalpayment > 0 ? floor($totalpayment / 10000) : 0 ;

                            ?>
                            <tr class="text-center">
                                <td><?= $data['MemberId'] ?></td>
                                <td class="text-left"><?= $data['Name']; ?></a></td>
                                <td><?= $data['Gender'] ?></td>
                                <td><?= $data['Status'] ?></td>
                                <td class="text-right"><?= number_format($data['TotalCash'],2) ?></td>
                                <td class="text-right"><?= number_format($data['TotalCredit'],2) ?></td>
                                <td class="text-right"><a href="#" data-toggle="modal" data-target="#desmodal" class="rp_member" data-member="<?= $data['MemberId'] ?>" data-date="<?= $datefrom ?>" data-dateend="<?= $dateto ?>"><?= number_format($totalpayment,2) ?></a></td>
                                <td><?= $point?></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>

                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->