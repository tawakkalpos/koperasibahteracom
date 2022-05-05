<?php
function basedir(){
  $base = str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
  preg_match_all('/("[^\/]*")|[^\/]*/',$base,$matches);
  foreach($matches[0] as $entry){
     $main[] = preg_replace('/[^\/](.*)[^\/]/','../',$entry);
  }
  return substr(implode($main),3);
}
require_once (basedir().md5("pages")."/config.php");
checklogin();
include (basedir().md5("pages")."/header.php");

// $sql=select($conn,"workername,workerintranetpassword","workerdata");
// foreach($sql as $data){
//   print $data['workername']."||".md5($data['workerintranetpassword'])."</br>";
// }

?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- 404 Error Text -->
    <div class="text-center">
        <div class="error mx-auto" data-text="404">404</div>
        <p class="lead text-gray-800 mb-5">Page Not Found</p>
        <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
        <a href="<?= $base_url;?>">&larr; Back to Home</a>
    </div>
</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->
<?php
include (basedir().md5("pages")."/footer.php");
?>