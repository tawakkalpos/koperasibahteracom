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
                    <label for="re_name">Full Name:</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="re_name" id="re_name">
                    </div>
                    <label for="re_gender">Gender:</label>
                    <div class="form-inline mb-2">
                        <div class="custom-control custom-radio ml-3">
                            <input type="radio" class="custom-control-input" name="re_gender" id="re_gender1" value="L" checked>
                            <label class="custom-control-label" for="re_gender1">Male</label>
                        </div>
                        <div class="custom-control custom-radio ml-3">
                            <input type="radio" class="custom-control-input" name="re_gender" id="re_gender2" value="P">
                            <label class="custom-control-label" for="re_gender2">Female</label>
                        </div>
                    </div>
                    <label for="re_address">Address:</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="re_address" id="re_address">
                    </div>
                </div>
                <div class="row justify-content-center mb-4">
                    <button type="button" name="re_submit" id="re_submit" class="btn btn-sm btn-primary submit_form" data-target="form_member">Submit</button>
                    <button type="reset" class="btn btn-sm btn-warning ml-2">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->