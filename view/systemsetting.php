<?php
$db = new mDatabase();
$config = new mConfig();
include($config->getContoller($config->loadpage()));
$setting = new Setting();
//if already post from javascript to this page
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <?= $config->breadcumb()?>
    <h1 class="h3 mb-2 text-gray-800">Setting</h1>
    <p class="mb-4">Setting is used to controlling sistems like table, any options and etc.</p>
    <div class="row">
        <div class="col-md-6">
            <!-- Display the Table User or Employee List -->
            <div class="card shadow mb-4 ">
                <a href="#collapse1" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapse1">
                    <h6 class="m-0 font-weight-bold text-primary">List of Menu</h6>
                </a>
                <div class="card-body small">
                    <div class="collapse show" id="collapse1">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm" id="tablemenu" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="text-center">
                                        <th>No.</th>
                                        <th>Menu ID</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th><i class="fas fa-cog"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    //display list of user || this function defined in file config.php
                                    $sql = $db->select(TB_MENU, "*", "ORDER BY category ASC");
                                    $no = 1;
                                    foreach ($sql as $data) {
                                        //display tr td for the table
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no ?></td>
                                            <td><?= $data['MenuId']; ?></td>
                                            <td><?= $data['Description']; ?></td>
                                            <td><?= $data['Category']; ?></td>
                                            <td class="text-center">
                                                <a onclick="button_update('<?= $data['MenuId']; ?>','#menu_idcolumn','menu_idcolumn_change','#form_add_column')" href="#collapse2" class="fas fa-edit text-warning" role="button"></a>
                                                <a onclick="button_delete('menu_delete',<?= $data['no']; ?>)" class="fas fa-trash text-danger" href="#" role="button"></a></td>
                                        </tr>
                                    <?php
                                        $no++;
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Add New and Edit User or Employee List -->
            <div class="card shadow mb-4">
                <a href="#collapse2" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapse2">
                    <h6 class="m-0 font-weight-bold text-primary">Add New and Update Menu</h6>
                </a>
                <div class="card-body">
                    <div class="collapse show" id="collapse2">
                        <form class="was-validated" autocomplete="off" id="form_menu">
                            <div class="input-group input-group-sm">
                                <label for="menu_idcolumn" class="mr-sm-2 col-md-3 text-md-right">Menu ID:</label>
                                <!-- function valuechange is on javascript function -->
                                <input type="text" class="form-control mb-2 mr-sm-2 col-md-7" name="menu_idcolumn" id="menu_idcolumn" onkeyup="valuechange('menu_idcolumn_change',this,'#form_add_column')" value="" placeholder="ID Column..." required>
                            </div>
                            <div class="" id="form_add_column">
                                <?php
                                //display form for add and edit user || this function defined in file config.php
                                $setting->form_menuid();
                                ?>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="reset" class="btn btn-warning btn-sm">Reset</button>
                                    <button type="button" name="menu_submit" id="menu_submit" class="btn btn-primary btn-sm submit_form" data-target="form_menu">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->