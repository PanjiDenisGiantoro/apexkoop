<?php
/*********************************************************************************
*          Project		:	Sistem e-Koperasi(ikoop)
*          Filename		: 	complete.php
*          Date 		: 	
*********************************************************************************/
include ("header.php");	

?>
<h5 class="card-title">
<img src="images/number1.png" width="17" height="17">&nbsp;ISI PROFIL&nbsp;<i class="mdi mdi-arrow-right-bold-outline"></i>&nbsp;
<img src="images/number2.png" width="17" height="17">&nbsp;PEMBAYARAN&nbsp;<i class="mdi mdi-arrow-right-bold-outline"></i>&nbsp;
<font class="text-primary"><img src="images/number3-primary.png" width="17" height="17">&nbsp;SELESAI</font></h5>
<div class="progress mb-3">
  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
</div>
<?php

print '    
    <div align="center" class="mt-3">    
      <div class="card-body bg-soft-secondary">
        <img src="images/success.png" width="100" height="100" >    
           <p><h5 class="text-danger">!! SILA BUAT PEMBAYARAN PENDAFTARAN UNTUK PERCEPATKAN PROSES KELULUSAN !!</h5></p>
           <p><p>Untuk membuat pembayaran secara manual, sila ikut langkah-langkah di bawah untuk memudahkan permohonan diluluskan dengan segera.</p>
           <p>IBFT di Mesin CDM  - <b> [NAMA BANK], NO AKAUN : [NO. AKAUN KOPERASI] atas nama : ALM CORE SOLUTIONS SDN BHD. Sila serahkan slip bayaran kepada helpdesk@ikoop.com.my berserta NO.IC </b></p>
           <p>Online Banking Payment Seperti Maybank2u, CIMB Click, Credit Card - di akaun : <b> [NAMA BANK], NO AKAUN : [NO. AKAUN KOPERASI] atas nama : ALM CORE SOLUTIONS SDN BHD. Sila serahkan slip bayaran kepada helpdesk@ikoop.com.my beserta NO.IC </b></p>
             
             BAYARAN YANG PERLU DI BUAT SEBANYAK :-</p>
   
             <p><b> <h5 class="text-primary"> *YURAN PENDAFTARAN MASUK : RM 30.00  </h5></b> </p>
             <p> <b> <h5 class="text-primary">&nbsp;  SYER MINIMA : RM 100.00  </h5></b> </p>
             <p> *Yuran Pendaftaran (Tidak akan dipulangkan) dan Syer (sepenuhnya dipulangkan)  akan dikembalikan semula sekiranya permohonan menjadi anggota <b class="text-danger"> DITOLAK </b> oleh lembaga Koperasi mengikut Akta Koperasi 50(a).</p>
        </p>
    </div>
      <br/>
        <center>
        <td class="textFont" align="center" colspan="3"><input type="button" class="btn btn-secondary waves-effect waves-light" onClick="window.location.href=\'index.php?vw=cara_byr\'" value="<<">
           <button type="button" class="btn btn-secondary waves-effect" onClick="window.location.href=\'?page=login&error=\'"><i class="fas fa-home"></i></button>
        </center>   
';

?>
