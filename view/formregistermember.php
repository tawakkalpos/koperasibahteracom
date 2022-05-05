<?php
$dbconfig = new mDatabase();
$config = new mConfig();
?>
<!-- Begin Page Content -->
<div class="container-fluid" onsubmit="return(false)">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form autocomplete="off" id="form_member">
                <div class="form-group">
                    <label for="mb_id">Member Id:</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="mb_id" id="mb_id" autofocus="autofocus">
                    </div>
                    <label for="mb_name">Full Name:</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="mb_name" id="mb_name">
                    </div>
                    <label for="mb_gender">Gender:</label>
                    <div class="form-inline mb-2">
                        <div class="custom-control custom-radio ml-3">
                            <input type="radio" class="custom-control-input" name="mb_gender" id="mb_gender1" value="L" checked>
                            <label class="custom-control-label" for="mb_gender1">Male</label>
                        </div>
                        <div class="custom-control custom-radio ml-3">
                            <input type="radio" class="custom-control-input" name="mb_gender" id="mb_gender2" value="P">
                            <label class="custom-control-label" for="mb_gender2">Female</label>
                        </div>
                    </div>
                    <label for="mb_status">Status:</label>
                    <div class="form-inline mb-2">
                        <div class="custom-control custom-radio ml-3">
                            <input type="radio" class="custom-control-input" name="mb_status" id="mb_status1" value="Aktif" checked>
                            <label class="custom-control-label" for="mb_status1">Active</label>
                        </div>
                        <div class="custom-control custom-radio ml-3">
                            <input type="radio" class="custom-control-input" name="mb_status" id="mb_status2" value="Pasif">
                            <label class="custom-control-label" for="mb_status2">Not Active</label>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center mb-4">
                    <button type="button" name="mb_submit" id="mb_submit" class="btn btn-sm btn-primary submit_form" data-target="form_member">Submit</button>
                    <button type="reset" class="btn btn-sm btn-warning ml-2">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->