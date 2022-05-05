<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog tr_print" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <form method="post" action="<?= BASE_URL; ?>">
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="logout" class="btn btn-primary">Logout</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gray-100">
                <h5 class="modal-title">Login</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= htmlspecialchars($_SERVER["REQUEST_URI"]) ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Username..." required autofocus>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password..." required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" name="login" class="btn btn-primary btn-user btn-block">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Description Modal-->
<div class="modal fade" id="desmodal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gray-100">
                <h5 class="modal-title" id="destitle"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="desbody"></div>
        </div>
    </div>
</div>
<?php if (!empty(USER_ID)) : ?>
    <div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header bg-gray-100">
                    <h5 class="modal-title">Change Password</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= BASE_URL ?>">
                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <input type="password" class="form-control form-control-user password" id="newpassword" name="newpassword" placeholder="New Password" required autofocus>
                                <div class="input-group-append">
                                    <span class="input-group-text showpassword" data-target="#newpassword"><i style="cursor: pointer;" class="fas fa-eye fa-xs"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control form-control-user confirm_password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
                                <div class="input-group-append">
                                    <span class="input-group-text showpassword" data-target="#confirmpassword"><i style="cursor: pointer;" class="fas fa-eye fa-xs"></i></span>
                                </div>
                            </div>
                            <span class="text-danger mb-3" id="check_confirmpassword">
                            </span>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" name="change_password" id="change_password" class="btn btn-primary btn-user btn-block btn_submit">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php if (!empty(USER_ID) && $config->checkaccess("formtransaction")) : ?>
    <!-- Form Transaction-->
    <div class="modal fade d-print-none" id="formtransaction" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-xl w-100" role="document">
            <div class="modal-content" style="min-width: 90vw; min-height:90vh">
                <div class="modal-header bg-gray-100">
                    <h5 class="modal-title">FORM TRANSACTION</h5>
                    <a href="<?= BASE_URL . $config->loadpage() ?>/">
                        <span aria-hidden="true">×</span>
                    </a>
                </div>
                <div class="modal-body">
                    <?php include($config->getView("formtransaction")); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty(USER_ID)): ?>
    <div class="modal fade d-print-block" id="printtransaction" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header bg-gray-100 d-print-none">
                    <h5 class="modal-title">PRINT NOTE</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close" onclick="closenote()">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-center" id="printarea">
                </div>
                <div class="text-center d-print-none mb-3">
                    <button class="btn btn-dark btn-icon-split btn-sm" type="button" onclick="window.print()">
                        <span class="icon text-white-50"><i class="fas fa-print fa-xs"></i></span>
                        <span class="text">Print</span></button>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
<?php if (!empty(USER_ID) && $config->loadpage() == "member" && $config->checkaccess("formregistermember")) : ?>
    <!-- Form Member-->
    <div class="modal fade " id="formregistermember" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-xl w-100" role="document">
            <div class="modal-content" style="min-width: 90vw; min-height:90vh">
                <div class="modal-header bg-gray-100">
                    <h5 class="modal-title">FORM REGISTER OR UPDATE MEMBER</h5>
                    <a href="<?= BASE_URL . $config->loadpage() ?>/">
                        <span aria-hidden="true">×</span>
                    </a>
                </div>
                <div class="modal-body">
                    <?php include($config->getView("formregistermember")); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if (!empty(USER_ID) && $config->loadpage() == "product" && $config->checkaccess("formregisterproduct")) : ?>
    <!-- Form Member-->
    <div class="modal fade " id="formregisterproduct" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-xl w-100" role="document">
            <div class="modal-content" style="min-width: 90vw; min-height:90vh">
                <div class="modal-header bg-gray-100">
                    <h5 class="modal-title">FORM REGISTER OR UPDATE PRODUCT</h5>
                    <a href="<?= BASE_URL . $config->loadpage() ?>/">
                        <span aria-hidden="true">×</span>
                    </a>
                </div>
                <div class="modal-body">
                    <?php include($config->getView("formregisterproduct")); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if (!empty(USER_ID) && $config->loadpage() == "usersetting" && $config->checkaccess("usersetting")) : ?>
    <!-- Form Member-->
    <div class="modal fade" id="formregisteruser" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-xl w-100" role="document">
            <div class="modal-content" style="min-width: 90vw; min-height:90vh">
                <div class="modal-header bg-gray-100">
                    <h5 class="modal-title">FORM REGISTER OR UPDATE USER</h5>
                    <a href="<?= BASE_URL . $config->loadpage() ?>/">
                        <span aria-hidden="true">×</span>
                    </a>
                </div>
                <div class="modal-body">
                    Coming Soon...
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>