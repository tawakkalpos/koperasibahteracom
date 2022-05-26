<?php
$dbconfig = new mDatabase();
$config = new mConfig();
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <form autocomplete="off" id="form_product" onsubmit="return(false)">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <label for="pr_barcode">Barcode:</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="pr_barcode" id="pr_barcode" autofocus="autofocus">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="pr_name">Product Name:</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="pr_name" id="pr_name">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="pr_category">Category:</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" class="form-control" name="pr_category" id="pr_category">
                        <ul id="category_result" class="dropdown-menu w-100 mt-0">
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="pr_qty">Qty:</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="number" class="form-control" name="pr_qty" id="pr_qty" value="0">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="pr_purchase">Purchase Price:</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="number" class="form-control" name="pr_purchase" id="pr_purchase">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="pr_purchase_category">Purchase Price Category:</label>
                    <div class="form-inline mb-2">
                        <div class="custom-control custom-radio ml-3">
                            <input type="radio" class="custom-control-input" name="pr_purchase_category" id="pr_purchase_category1" value="peritem" checked>
                            <label class="custom-control-label" for="pr_purchase_category1">Per Item</label>
                        </div>
                        <div class="custom-control custom-radio ml-3">
                            <input type="radio" class="custom-control-input" name="pr_purchase_category" id="pr_purchase_category2" value="allitem">
                            <label class="custom-control-label" for="pr_purchase_category2">All Items</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="pr_sales">Sales Price:</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="number" class="form-control" name="pr_sales" id="pr_sales">
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="pr_photo">Upload Photo:</label>
                    <div class="input-group input-group-sm mb-2 img-overlay col-md-6">
                        <div>
                            <?= $config->image("takephoto.png", "w-100", "product_img");
                            ?>
                        </div>
                        <div class="overlay bg-gray-300">
                            <div class="custom-file badge-file">
                                <div class="position-absolute w-100 text-center">
                                    <button class="btn btn-sm btn-light">
                                        <span class="icon text-primary">
                                            <i class="fa fa-paperclip fa-sm"></i>
                                        </span>
                                        <span class="text">Browse</span>
                                    </button>
                                </div>
                                <input type="file" class="form-control custom-file-input inputfile"  name="pr_photo" id="pr_photo" accept="image/*" capture>
                                <input type="hidden" name="imgtmp" id="imgtmp" value="imgtemp_<?=date('ymd_His')?>.jpg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mb-4">
                <button type="button" name="pr_submit" id="pr_submit" class="btn btn-sm btn-primary submit_form" data-target="form_product">Submit</button>
                <button type="reset" class="btn btn-sm btn-warning ml-2">Reset</button>
            </div>
        </div>
    </form>
</div>
<!-- /.container-fluid -->