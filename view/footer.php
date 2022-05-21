    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; JS POS <?= date("Y") ?></span>
            </div>
        </div>
        <iframe name="upload-frame" id="upload-frame" class="d-none"></iframe>
        <div id="alert-massage" class="alert-center">

        </div>
        <div id="alert-massage2">
            <div class="bg-light fixed-top d-none" id="progress">
                <div class="progress progress-sm">
                    <div class="progress-bar bg-primary" id="progressbar" role="progressbar" aria-valuemin="0"
                        aria-valuemax="100">
                    </div>
                </div>
                <div class="small text-primary font-weight-bold mt-1">Loading Progress
                    <span class="float-right" id="progresstext">Complete!</span>
                </div>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fa fa-angle-up"></i>
    </a>
    <?php
    include($config->getView("modal"));
    ?>
    <!-- Bootstrap core JavaScript-->  
    <script src="https://koperasibahtera.com/vendor/jquery/jquery.min.js"></script>
    <script src="https://koperasibahtera.com/vendor/jquery/jquery.form.js"></script>
    <script src="https://koperasibahtera.com/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Image creator core JavaScript-->
    <script src="https://koperasibahtera.com/vendor/jquery/html2canvas.min.js"></script>
    <script src="https://koperasibahtera.com/vendor/jquery/canvas2image.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="https://koperasibahtera.com/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://koperasibahtera.com/vendor/jquery/jquery-ui.js"></script>    
    <!-- <script src="vendor/jquery/push.min.js"></script>     -->
    <!-- <script src="https://koperasibahtera.com/vendor/jquery/serviceWorker.min.js"></script>    
    <script src="https://koperasibahtera.com/vendor/jquery/jquery-confirm.min.js"></script> -->
    <!-- Custom scripts for all pages-->
    
    
    
    <script src="<?= BASE_URL ?>js/main.js"></script>
    <script src="<?= BASE_URL ?>js/js-pos.js"></script>
    <script src="https://koperasibahtera.com/vendor/chart.js/Chart.min.js"></script>
    <script src="<?= BASE_URL ?>js/demo/chart-area.js"></script>
    <script src="https://koperasibahtera.com/vendor/jquery/jspdf.min.js"></script>
    <script src="https://koperasibahtera.com/vendor/jquery/jspdf.plugin.autotable.min.js"></script>
    <!-- Page level plugins -->
    <script src="https://koperasibahtera.com/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="https://koperasibahtera.com/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="https://koperasibahtera.com/vendor/cloudflare/dataTables.buttons.min.js"></script>
    <script src="https://koperasibahtera.com/vendor/cloudflare/buttons.flash.min.js"></script>
    <script src="https://koperasibahtera.com/vendor/cloudflare/jszip.min.js"></script>
    <script src="https://koperasibahtera.com/vendor/cloudflare/pdfmake.min.js"></script>
    <script src="https://koperasibahtera.com/vendor/cloudflare/vfs_fonts.js"></script>
    <script src="https://koperasibahtera.com/vendor/cloudflare/buttons.html5.min.js"></script>
    <script src="https://koperasibahtera.com/vendor/cloudflare/buttons.print.min.js"></script>
    <script src="https://koperasibahtera.com/vendor/cloudflare/moment.min.js"></script>
    <script src="https://koperasibahtera.com/vendor/cloudflare/browser-image-compression.js"></script> 

    </body>

    </html>