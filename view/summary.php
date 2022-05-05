<?php
$dbconfig = new mDatabase();
?>
<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 80vh;">
    <!-- Page Heading -->
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home </a></li>
        <li class="breadcrumb-item active">Summary</li>
    </ul>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">SALES SUMMARIES</h1>
    </div>
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total of Transaction
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                    $sql = $dbconfig->select("transaction_history","COUNT(TransactionId)");
                                    print $sql[0][0];
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-light shadow h-100 py-2 bg-gradient-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-light text-uppercase mb-1">Total of Sales
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-200">
                                <?php
                                    $sql = $dbconfig->select("transaction_history","SUM(GrandTotal)");
                                    print "Rp " . number_format($sql[0][0],2);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-200"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total of Product
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                    $sql = $dbconfig->select_where("product","COUNT(Barcode)","Status IS NULL");
                                    print $sql[0][0];
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <!-- Default Card Example -->
            <div class="card mb-4">
                <div class="card-header">
                    Sales Of the Year (<?= date('Y') ?>)
                </div>
                <div class="card-body">
                <div class="chart-area">
                    <canvas id="myMonthlyChart"></canvas>
                </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <!-- Default Card Example -->
            <div class="card mb-4">
                <div class="card-header">
                    Sales Of the Month (<?= date('M Y') ?>)
                </div>
                <div class="card-body">
                <div class="chart-area">
                    <canvas id="myDailyChart"></canvas>
                </div>
                </div>
            </div>

        </div>

    </div>

</div>