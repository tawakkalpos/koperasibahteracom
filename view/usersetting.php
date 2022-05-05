<?php
$db = new mDatabase();
$config = new mConfig();
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <?= $config->breadcumb() ?>
    <h1 class="h3 mb-2 text-gray-800">User Setting</h1>
    <a class="btn btn-primary btn-sm text-light mb-3" data-toggle="modal" data-target="#formregisteruser">
        <span class="text">Add User</span>
    </a>
    <div class="card shadow mb-4">
        <a href="#collapse1" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapse1">
            <h6 class="m-0 font-weight-bold text-primary">Table User</h6>
        </a>
        <div class="card-body">
            <div class="collapse show" id="collapse1">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm defaulttable1" id="tableuser" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th>No.</th>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Status</th>
                                <th><i class="fas fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //display list of user || this function defined in file config.php
                            $sql = $db->select_where("user", "*", "`status` <> 'deleted'");
                            if ($sql) :
                                foreach ($sql as $data) :
                                    if ($data['status'] == 'online')
                                        $online = 'fa-check-circle text-success';
                                    else
                                        $online = 'fa-minus-circle text-danger';
                                    ?>
                                    <tr class="text-center"> 
                                        <td><?= sprintf("%03s", $data['userid']); ?></td>
                                        <td><?= $data['username']; ?></td>
                                        <td><?= $data['fullname']; ?></td>
                                        <td><i class="fas <?= $online; ?>"></i></td>
                                        <td></td>
                                    </tr>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Display Log Activity for User-->
    <div class="card shadow mb-4">
        <a href="#collapse4" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapse4">
            <h6 class="m-0 font-weight-bold text-primary">User Log</h6>
        </a>
        <div class="card-body">
            <div class="collapse show" id="collapse4">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm w-100 small defaulttable1" id="tablelog" cellspacing="0">
                        <thead>
                            <tr class="text-center">
                                <th width="15%">Date Time</th>
                                <th width="15%">User</th>
                                <th width="">Describtions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //read data in log.txt
                            $file = fopen(FILE_LOG, "r") or die("Unable to open file!");
                            while (!feof($file)) {
                                echo fgetc($file);
                            }
                            fclose($file);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
