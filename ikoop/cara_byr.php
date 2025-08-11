<?php
/*********************************************************************************
*          Project    : Sistem e-Koperasi(ikoop)
*          Filename   :   cara_byr.php
*          Date     :   
*********************************************************************************/
include ("header.php"); 
$newIC    = $_GET["ic"];
$mobileNo = $_GET["mobileNo"];
$email    = $_GET["email"];

?>
<h5 class="card-title">
<img src="images/number1.png" width="17" height="17">&nbsp;ISI PROFIL&nbsp;<i class="mdi mdi-arrow-right-bold-outline"></i>&nbsp;
<font class="text-primary"><img src="images/number2-primary.png" width="17" height="17">&nbsp;PEMBAYARAN&nbsp;<i class="mdi mdi-arrow-right-bold-outline"></i></font>&nbsp;
<img src="images/number3.png" width="17" height="17">&nbsp;SELESAI</h5>

<div class="progress mb-3">
  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
</div>
<?php

print '
        
    <div>
    <table class="lightgrey" border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
      <tr>
        <td align="center" valign="middle"><h2 class="text-primary"><b>!! TAHNIAH !!</b></h2></td>
      </tr>
      <tr>
        <td class="borderleftrightbottomblue">
        <table border="0" cellpadding="0" cellspacing="6" width="100%" align="center">
          <tr>
           <br>
            <div align="center"><h5 class="text-primary">PERMOHONAN ANDA UNTUK MENJADI ANGGOTA BERJAYA DIHANTAR</h5></div>
            <div align="center"><h5 class="text-danger">!! SILA BUAT PEMBAYARAN PENDAFTARAN UNTUK PERCEPATKAN PROSES KELULUSAN !!</h5></div><br/>

  <p align="center">Bagi  membolehkan kami memberikan perkhidmatan yang terbaik kepada anggota,  sila pastikan langkah-langkah BAYARAN bagi memudahkan permohonan diluluskan dengan segera, melalui :-</p>
        
      <p align="center"> 1. Online Banking Payment Seperti Maybank2u, CIMB Click, Credit Card - di akaun : <b> BANK BERHAD, NO AKAUN : [111111111111] atas nama : ALM CORE SOLUTIONS SDN BHD. Sila serahkan slip bayaran kepada helpdesk@ikoop.com.my beserta NO.IC </b><br>
        </p>

      <p align="center"> 2. IBFT di Mesin CDM  - <b> BANK BERHAD, NO AKAUN : [111111111111] atas nama : ALM CORE SOLUTIONS SDN BHD. Sila serahkan slip bayaran kepada helpdesk@ikoop.com.my berserta NO.IC </b><br></p>
          
          <p align="center">3. BAYARAN YANG PERLU DI BUAT SEBANYAK :</p>

          <p> <b> <h4 class="text-primary" align="center"> *YURAN PENDAFTARAN MASUK : RM 50.00  </h4></b> </p>
          <p> <b> <h4 class="text-primary" align="center">&nbsp;  SYER MINIMA : RM 100.00  </h4></p>
          <p align="center">(Bayaran Pendahuluan sebanyak RM 1,000.00 dan baki dalam tempoh 6 bulan)</b> </p>
         
          <p align="center"> *Yuran Pendaftaran (Tidak akan dipulangkan) dan Syer (sepenuhnya dipulangkan)  akan dikembalikan semula sekiranya permohonan menjadi anggota <b class="text-danger"> DITOLAK </b> oleh lembaga Koperasi mengikut Akta Koperasi 50(a).</p>

          <p> <h5 align="center"> BAYARAN YANG DIBUAT MELALUI PAYMENT GATEWAY SWIPEGO <b>(FINTECH WORLDWIDE SDN BHD) </b> DAN AKAN DIKREDITKAN KE DALAM AKAUN KOPERASI DALAM TEMPOH 24 JAM</h5></p>
          
            </td>
          </tr>
<tr><td align="center">
 <br><p align="center"><a title= "UNTUK PEMBAYARAN ONLINE" href="" target="_blank"rel="noopener"><strong><h4>BAYARAN ONLINE DISINI</h4></strong> </a></p>
           <br>

       </td></tr>   
        </table>
        </td>
      </tr>
    </table>
</div>
';

//https://app.swipego.io/payment/open-link/pay?id=63e87f3bbf6868022d068fe2
//https://test-api.swipego.io/payment/open-link/pay?id=634cf729c617b997f5009142
?>