<?
include('../db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ikoop.com.my : Sistem Koperasi bagi Pembiayaan Peribadi, Pelaburan, Takaful, Insuran dan Daftar Anggota Koperasi</title>
<!-- CSS -->
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="assets/css/form-elements.css">
<link rel="stylesheet" href="assets/css/style.css">
<!-- Favicon and touch icons -->
<link rel="shortcut icon" href="assets/ico/favicon.png">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
<link href="../img/favicon.png" rel="icon">
<link href="../img/apple-touch-icon.png" rel="apple-touch-icon">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">
<!-- Bootstrap CSS File -->
<link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Libraries CSS Files -->
<link href="../lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="../lib/animate/animate.min.css" rel="stylesheet">
<link href="../lib/ionicons/css/ionicons.min.css" rel="stylesheet">
<link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
<link href="../lib/lightbox/css/lightbox.min.css" rel="stylesheet">
<!-- Main Stylesheet File -->
<link href="../css/style.css" rel="stylesheet">
</head><body>
<header id="header">
    <div class="container-fluid" >
      <div id="logo" class="pull-left">
        <h1><a href="../index.php" class="scrollto">iKOOP</a></h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="#intro"><img src="img/logo.png" alt="" title="" /></a>-->
      </div>
      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li><a href="../loginkoop.php">Login</a></li>
        </ul>
      </nav><!-- #nav-menu-container -->
    </div>
  </header><!-- #header -->
  
<? 
   if(isset($_POST['save'])) {

    $sql = "INSERT INTO userkoop (koop_name,koop_num,koop_contname,applyDate) VALUES 
	(
	'".$_POST["koop_name"]."',
	'".$_POST["koop_num"]."',
  '".$_POST["koop_contname"]."', NOW())";
	
	$result = mysqli_query($conn,$sql);
	echo "<script type='text/javascript'>alert('Koperasi Anda Telah Didaftarkan Ke Dalam iKOOP, Sila Tunggu Dari Pihak iKOOP. Terima Kasih.')</script>";
   }
 ?>
<section id="about"></section>
<section id="call-to-action" class="wow fadeIn">
<div class="top-content">
<div class="inner-bg">
<div class="container">
<div class="row">
<div class="col-sm-1 middle-border"></div>
<div class="col-sm-1"></div>
<div class="col-sm-5">
<div class="form-box">
<div class="form-top">
<div class="form-top-left">
<h3>Daftar Koperasi</h3>
<p>Sila isi ruangan di bawah <u>SEKIRANYA</u> koperasi anda tiada dalam senarai di "Login". </p>
</div>
<div class="form-top-right">
<i class="fa fa-pencil"></i>
</div>
</div>
<div class="form-bottom">
<form role="form" method="POST" action="register.php" class="registration-form">

<div class="form-group">
<label class="sr-only" for="form-first-name">Nama Koperasi</label>
<input type="text" name="koop_name" placeholder="Nama Koperasi..." class="form-first-name form-control" id="koop_name" required>
</div>

<div class="form-group">
<label class="sr-only" for="form-first-name">Nama Pengurusan Untuk Dihubungi</label>
<input type="text" name="koop_num" placeholder="Nama Pengurusan Untuk Dihubungi..." class="form-first-name form-control" id="koop_num" required>
</div>

<div class="form-group">
<label class="sr-only" for="form-last-name">Number Telefon Koperasi</label>
<input type="text" name="koop_contname" placeholder="Number Telefon Koperasi..." class="form-last-name form-control" id="koop_contname" required>
</div>

<button type="submit" name="save" class="btn">Daftar</button>

</form></div></div></div></div></div></div></div>
</section>


  <? include('../footer.php'); ?>
        <!-- Javascript -->
  <script src="assets/js/jquery-1.11.1.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/js/jquery.backstretch.min.js"></script>
  <script src="assets/js/scripts.js"></script>
  <script src="../lib/jquery/jquery.min.js"></script>
  <script src="../lib/jquery/jquery-migrate.min.js"></script>
  <script src="../lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../lib/easing/easing.min.js"></script>
  <script src="../lib/superfish/hoverIntent.js"></script>
  <script src="../lib/superfish/superfish.min.js"></script>
  <script src="../lib/wow/wow.min.js"></script>
  <script src="../lib/waypoints/waypoints.min.js"></script>
  <script src="../lib/counterup/counterup.min.js"></script>
  <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="../lib/isotope/isotope.pkgd.min.js"></script>
  <script src="../lib/lightbox/js/lightbox.min.js"></script>
  <script src="../lib/touchSwipe/jquery.touchSwipe.min.js"></script>
  <script src="../contactform/contactform.js"></script>
  <script src="../js/main.js"></script>
</body></html>