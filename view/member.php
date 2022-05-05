<?php
$dbconfig = new mDatabase();
//$config = new mConfig();
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <?= $config->breadcumb(); //view web position of the user
    ?>
    <h1 class="h3 mb-2 text-gray-800">Table Member</h1>
    <?php if($config->checkaccess("formregistermember")){ ?>
    <a class="btn btn-primary btn-sm text-light mb-3" data-toggle="modal" data-target="#formregistermember">
        <span class="text">Add Member</span>
    </a>
    <?php } ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm w-100 small defaulttable1" id="tablemember" cellspacing="0">
            <thead>
                <tr class="text-center">
                    <th width="7%">No</th>
                    <th width="15%">Member ID</th>
                    <th >Name</th>
                    <th width="10%">Gender</th>
                    <th width="10%">Status</th>
                    <th>Total Transaction</th>
                    <th>Point</th>
                    <?php if($config->checkaccess("formregistermember")){?>
                    <th><i class="fas fa-cog fa-xs"></i></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $sql = $dbconfig->select_join('member AS mb , transaction_history AS th', 'mb.MemberId AS MemberId, Name, Gender, Status, SUM(th.GrandTotal) AS Point', "mb.MemberId = th.MemberId", "mb.Status <> 'deleted' GROUP BY mb.MemberId ORDER BY mb.No", 'LEFT');
                if ($sql) {
                    foreach ($sql as $data) :
                        $point = $data[4] > 0 ? floor($data[4] / 10000) : 0 ;
                        ?>
                        <tr class="text-center">
                            <td><?= $no ?></td>
                            <td><?= $data[0] ?></td>
                            <td class="text-left"><?= $data[1]; ?></a></td>
                            <td><?= $data[2] ?></td>
                            <td><?= $data[3] ?></td>
                            <td><?= number_format($data[4],2) ?></td>
                            <td><?= $point?></td>
                            <?php if($config->checkaccess("formregistermember")): ?>
                            <td>
                                <a class="fas fa-edit text-warning edit_member" href="#" data-toggle="modal" data-member="<?=$data[0]?>" data-target="#formregistermember"></a>
                                 <a class="fas fa-trash text-danger delete_member" href="#" data-member="<?=$data[0]?>"></a> 
                            </td>
                            <?php endif; ?>
                        </tr>
                <?php
                    $no++;
                    endforeach;
                }
                ?>

            </tbody>
        </table>
    </div>
</div>
<!-- /.container-fluid -->