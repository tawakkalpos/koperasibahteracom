<?php
$dbconfig = new mDatabase();
$config = new mConfig();
?>
<!-- Begin Page Content -->
<div class="container-fluid d-print-none">
    <!-- Page Heading -->
    <?= $config->breadcumb(); //view web position of the user
    ?>
    <h1 class="h3 mb-2 text-gray-800">Table Current Transaction</h1>
    <?php if($config->checkaccess('formtransaction')): ?>
    <a class="btn btn-primary btn-sm text-light mb-3" data-toggle="modal" data-target="#formtransaction">
        <span class="text">Add Transaction</span>
    </a>
    <?php endif; ?>
    <?php
    $modal = new mModal();
    print $modal->dailytransaction();
    ?>
</div>
<!-- /.container-fluid -->