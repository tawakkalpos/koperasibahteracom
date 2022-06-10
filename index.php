<?php
//by: JOKO SANTOSA (0877 5829 3281) || mailto:jspos.info@gmail.com
//require file config.php
require "model/config.php";

$config = new mConfig();
$config->htaccess();
?>

<!-- Begin Page Content -->
<div class="container-fluid" style="min-height: 80vh;">
<div id="slideshow" class="carousel slide shadow" data-ride="carousel">
        <ul class="carousel-indicators">
            <li data-target="#slideshow" data-slide-to="0" class="active"></li>
            <!--<li data-target="#slideshow" data-slide-to="1"></li>-->
        </ul>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="<?= BASE_URL ?>img/home/img_1.jpg" class="w-100">
            </div>
            
        </div>
    </div>
</div>
<!-- End of Main Content -->
<?php
require $config->getView("footer");
?>
