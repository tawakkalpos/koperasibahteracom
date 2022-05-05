<?php
$config = new mConfig();
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="row mb-2">
                    <div class="col-md-7 col-lg-9">
                        <label for="tr_code">Code:</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" name="tr_code" id="tr_code" autofocus="autofocus">
                        </div>
                    </div>
                    <div class="col-md-5 col-lg-3">
                        <label for="tr_qty">Qty:</label>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control" name="tr_qty" id="tr_qty" value="1">
                        </div>
                    </div>
                </div>
                <label for="tr_name">Product Name:</label>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="tr_name" id="tr_name">
                    <ul id="name_result" class="dropdown-menu animated--fade-in w-100 mt-0">
                    </ul>
                </div>
                <!-- <label class="mt-2" for="tr_price">Price:</label>
                <div class="input-group input-group-sm mb-2">
                    <input type="text" class="form-control" name="tr_price" id="tr_price">
                </div> -->
            </div>

            <hr class="sidebar-divider">

            <form autocomplete="off" id="form_transaction">
                <div class="form-group">
                    <label for="tr_payment">Member Status:</label>
                    <div class="form-inline mb-2">
                        <div class="custom-control custom-radio ml-3">
                            <input type="radio" class="custom-control-input" data-toggle="collapse" data-target="#collapse_member" name="tr_member" id="tr_member1" value="Non Member" checked>
                            <label class="custom-control-label" for="tr_member1">Non Member</label>
                        </div>
                        <div class="custom-control custom-radio ml-3">
                            <input type="radio" class="custom-control-input" data-toggle="collapse" data-target="#collapse_member" name="tr_member" value="Member" id="tr_member2">
                            <label class="custom-control-label" for="tr_member2">Member</label>
                        </div>
                    </div>
                    <div class="row mb-2 collapse" id="collapse_member">
                        <div class="col-md-6">
                            <label for="tr_memberid">Member ID:</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="tr_memberid" id="tr_memberid">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="tr_membername">Member Name:</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="tr_membername" id="tr_membername">
                                <ul id="membername_result" class="dropdown-menu animated-fade-in w-100 mt-0">
                            </div>
                        </div>
                    </div>
                    <label for="tr_payment_method">Payment Method:</label>
                    <div class="form-inline mb-2">
                        <div class="custom-control custom-radio ml-3">
                            <input type="radio" class="custom-control-input" data-toggle="collapse" data-target="#collapse_method" name="tr_payment_method" value="Cash" id="tr_payment_method1" checked>
                            <label class="custom-control-label" for="tr_payment_method1">Cash</label>
                        </div>
                        <div class="custom-control custom-radio ml-3">
                            <input type="radio" class="custom-control-input" data-toggle="collapse" data-target="#collapse_method" name="tr_payment_method" value="Credit" id="tr_payment_method2">
                            <label class="custom-control-label" for="tr_payment_method2">Credit</label>
                        </div>
                    </div>
                    <div class="row mb-2 collapse" id="collapse_method">
                        <div class="col-12">
                            <label for="tr_payment_referense">Referense:</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" name="tr_payment_referense" id="tr_payment_referense">
                            </div>
                        </div>
                    </div>
                    <label for="tr_payment">Total Payment:</label>
                    <div class="input-group input-group-sm mb-2">
                        <input type="number" class="form-control" name="tr_payment" id="tr_payment" required>
                    </div>
                </div>
                <div class="row justify-content-center mb-4">
                    <button type="button" name="tr_submit" id="tr_submit" class="btn btn-sm btn-primary">Process</button>
                    <button type="button" name="tr_print" id="tr_print" data-transaction="" data-toggle="modal" data-target="#printtransaction" class="btn btn-sm btn-dark d-none ml-3 tr_print">Print</button>
                </div>
            </form>
        </div>
        <div class="col-md-8 p-2 border-left-dark">
            <div class="row text-primary">
                <div class="col-md-4">
                    <h6>Total Price</h6>
                </div>
                <div class="col-md-8 text-right">
                    <h1>Rp. <span id="total_price"></span> </h1>
                </div>
            </div>
            <div class="row text-primary">
                <div class="col-md-4">
                    <h6>Total Payment</h6>
                </div>
                <div class="col-md-8 text-right">
                    <h1>Rp. <span id="total_payment"></span></h1>
                </div>
            </div>
            <div class="row text-primary">
                <div class="col-md-4">
                    <h6>Money changes</h6>
                </div>
                <div class="col-md-8 text-right">
                    <h1>Rp. <span id="money_changes"></span></h1>
                </div>
            </div>
            <div class="table-responsive-md">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th style="width: 15%; text-align: center;">Barcode</th>
                            <th style="text-align: center;">Product Name</th>
                            <th style="width: 15%; text-align: center;">Price</th>
                            <th style="width: 10%; text-align: center;">Qty</th>
                            <th style="width: 15%; text-align: center;">Total</th>
                            <th style="width: 10%; text-align: center;"><i class="fas fa-cog fa-xs"></i></th>
                        </tr>
                    </thead>
                    <tbody id="list-transaction">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>