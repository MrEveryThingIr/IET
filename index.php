<?php include "partials/admin/header.php"; ?>
<?php include "partials/admin/navbar.php"; ?>

<?php
$current_page=isset($_GET['current_page'])?$_GET['current_page']:"404.php" ;
if($current_page=="admin"){
    include "admin.php";
}else{
include __DIR__ . DIRECTORY_SEPARATOR . "partials" . DIRECTORY_SEPARATOR . "forms" . DIRECTORY_SEPARATOR . $current_page . ".php";
}
?>
<?php include "partials/admin/footer.php"; ?>


