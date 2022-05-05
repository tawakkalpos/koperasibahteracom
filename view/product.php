<?php
$dbconfig = new mDatabase();
$config = new mConfig();
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <?= $config->breadcumb(); //view web position of the user
    ?>
    <h1 class="h3 mb-2 text-gray-800">Table Product</h1>
    <?php if($config->checkaccess('formregisterproduct')):?>
    <a class="btn btn-primary btn-sm text-light mb-3" data-toggle="modal" data-target="#formregisterproduct">
        <span class="text">Add Product</span>
    </a>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm w-100 small defaulttable1" id="tablestock" cellspacing="0">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Barcode</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Stock</th>
                    <th>Purchase Date</th>
                    <th>Purchase Price</th>
                    <th>Sales Price</th>
                    <th>Sales Qty</th>
                    <th>Total Sales</th>
                    <?php if($config->checkaccess('formregisterproduct')):?>
                    <th><i class="fas fa-cog fa-xs"></i></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $sql = $dbconfig->select_where("product", "*","Status IS NULL OR Status = ''");
                foreach ($sql as $data) {
                    ?>
                    <tr class="text-center">
                        <td class="font-weight-bold no-sort"><?= $no; ?></td>
                        <td><?= $data['Barcode'];?></td>
                        <td class="text-left"><?= strtoupper($data['ProductName']); ?></td>
                        <td><?= $data['Category'] ?></td>
                        <td><?= $data['Stock'] ?></td>
                        <td><?= $config->datetable($data['PurchaseDate']) ?></td>
                        <td><?= $data['PurchasePrice'] ?></td>
                        <td><?= $data['SalesPrice'] ?></td>
                        <td><?= $data['SalesQty'] ?></td>
                        <td><?= $data['TotalSales'] ?></td>
                        <?php if($config->checkaccess('formregisterproduct')):?>
                        <td>
                            <a class="fas fa-edit text-warning edit_product" href="#" data-toggle="modal" data-product="<?=$data['Barcode']?>" data-target="#formregisterproduct"></a>
                             <a class="fas fa-trash text-danger delete_product" href="#" data-product="<?=$data['Barcode']?>"></a> 
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php
                    $no++;
                }
                ?>

            </tbody>
        </table>
    </div>
</div>
<!-- /.container-fluid -->