<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="utf-8">
  <title>ikoop.com.my : Sistem Koperasi bagi Pembiayaan Peribadi, Pelaburan, Takaful, Insuran dan Daftar Anggota Koperasi</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">
  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">
  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Libraries CSS Files -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet">
</head>
<body>

<? include('header.php'); ?>


<section id="call-to-action" class="wow fadeIn">
<div class="container">
<div class="row"></div>
<div align="center">
<div class="col-md-4 col-md-offset-4">
<div class="login-panel panel panel-default" align="center">
<div class="panel-heading" align="center">
	<h3 class="panel-title"></h3>
</div>
<div class="panel-body">
	<form role="form">
	<fieldset>


<div class="form-group">
	<select id="menu" class="form-control">
		<option value="">Pilih Koperasi</option>
		<option value="http://sekatarakyat.bankrakyat.com.my/sekata/">Koperasi Kakitangan Bank Rakyat (SEKATARAKYAT)</option>
    <option value="kofrim/index.php">Koperasi FRIM Berhad (KOFRIM)</option>
    <option value="kobizz/index.php">Koperasi Bekas Pelajar-Pelajar Izzuddin Shah Ipoh (KOBIZZ)</option>
    <option value="kokuis/index.php">Koperasi Kolej Universiti Islam Antarabangsa Selangor (KOKUIS)</option>
    <option value="kobatim/index.php">Koperasi Pribumi Batang Padang dan Mualim Berhad (KOBATIM)</option>
    
    <option value="demo/index.php">DEMO</option>

	</select>
</div>



	<a name="go" class="cta-btn" id="go" onClick="gotosite()">SETERUSNYA</a>
    </fieldset>
</form>
</div></div></div></div></div>
</section>


 <section id="portfolio"  class="section-bg" >
      <div class="container">

        <header class="section-header">
          <h3 class="section-title">Senarai Koperasi</h3>
        </header>

        <div class="row">
          <div class="col-lg-12">
            <ul id="portfolio-flters">
              <li data-filter="*" class="filter-active">All</li>
              <li data-filter=".filter-app">Kredit</li>
              <li data-filter=".filter-card">Perkhidmatan</li>
              <li data-filter=".filter-web">Pemilikan Saham</li>
            </ul>
          </div>
        </div>

        <div class="row portfolio-container">

          <div class="col-lg-4 col-md-6 portfolio-item filter-app wow fadeInUp">
            <div class="portfolio-wrap">
              <figure>
                <img src="img/portfolio/app1.jpg" class="img-fluid" alt="">
                <a href="img/portfolio/app1.jpg" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="#" class="link-details" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="#">SEKATARAKYAT</a></h4>
                <p>Koperasi Kakitangan Bank Rakyat Berhad</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-web wow fadeInUp" data-wow-delay="0.1s">
            <div class="portfolio-wrap">
              <figure>
                <img src="img/portfolio/web3.jpg" class="img-fluid" alt="">
                <a href="img/portfolio/web3.jpg" class="link-preview" data-lightbox="portfolio" data-title="Web 3" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="#" class="link-details" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="#">KOBIZ</a></h4>
                <p>Koperasi Bekas Pelajar Izzuddin Shah</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-app wow fadeInUp" data-wow-delay="0.2s">
            <div class="portfolio-wrap">
              <figure>
                <img src="img/portfolio/app2.jpg" class="img-fluid" alt="">
                <a href="img/portfolio/app2.jpg" class="link-preview" data-lightbox="portfolio" data-title="App 2" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="#" class="link-details" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="#">KOFRIM</a></h4>
                <p>Koperasi Kakitangan FRIM Berhad</p>
              </div>
            </div>
          </div>

          <!--div class="col-lg-4 col-md-6 portfolio-item filter-card wow fadeInUp">
            <div class="portfolio-wrap">
              <figure>
                <img src="img/portfolio/card2.jpg" class="img-fluid" alt="">
                <a href="img/portfolio/card2.jpg" class="link-preview" data-lightbox="portfolio" data-title="Card 2" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="#" class="link-details" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="#">KAPB</a></h4>
                <p>Koperasi Amanah Pelaburan Berhad</p>
              </div>
            </div>
          </div-->

          <div class="col-lg-4 col-md-6 portfolio-item filter-web wow fadeInUp" data-wow-delay="0.1s">
            <div class="portfolio-wrap">
              <figure>
                <img src="img/portfolio/web2.jpg" class="img-fluid" alt="">
                <a href="img/portfolio/web2.jpg" class="link-preview" data-lightbox="portfolio" data-title="Web 2" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="https://www.facebook.com/kokuis/" class="link-details" target="_blank" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="https://www.facebook.com/kokuis/">KOKUIS</a></h4>
                <p>Koperasi Kolej Universiti Islam Antarabangsa Selangor</p>
              </div>
            </div>
          </div>

           <div class="col-lg-4 col-md-6 portfolio-item filter-web wow fadeInUp" data-wow-delay="0.1s">
            <div class="portfolio-wrap">
              <figure>
                <img src="img/portfolio/app3.jpg" class="img-fluid" alt="">
                <a href="img/portfolio/app3.jpg" class="link-preview" data-lightbox="portfolio" data-title="Web 2" title="Preview"><i class="ion ion-eye"></i></a>
                <a href="" class="link-details" target="_blank" title="More Details"><i class="ion ion-android-open"></i></a>
              </figure>

              <div class="portfolio-info">
                <h4><a href="">KOBATIM</a></h4>
                <p>Koperasi Pribumi Batang Padang dan Mualim Berhad</p>
              </div>
            </div>
          </div>



        </div>

      </div>
    </section><!-- #portfolio -->
</main>





<? include('footer.php'); ?>


  <a href="#" class="back-to-top"><i class="fa fa-chevron-up">atas</i></a>
  <!-- JavaScript Libraries -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/jquery/jquery-migrate.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/superfish/hoverIntent.js"></script>
  <script src="lib/superfish/superfish.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/waypoints/waypoints.min.js"></script>
  <script src="lib/counterup/counterup.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="lib/isotope/isotope.pkgd.min.js"></script>
  <script src="lib/lightbox/js/lightbox.min.js"></script>
  <script src="lib/touchSwipe/jquery.touchSwipe.min.js"></script>
  <!-- Contact Form JavaScript File -->
  <script src="contactform/contactform.js"></script>
  <!-- Template Main Javascript File -->
  <script src="js/main.js"></script>
<script>


function gotosite() {
  window.location = document.getElementById("menu").value; // JQuery:  $("#menu").val();
}
</script>
</body></html>