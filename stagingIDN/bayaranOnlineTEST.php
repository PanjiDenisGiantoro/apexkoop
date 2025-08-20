<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	bayaranTambahan.php
 *          Date 		: 	15/06/2020
 *********************************************************************************/
include("header.php");
include("koperasiQry.php");
include("forms.php");
date_default_timezone_set("Asia/Jakarta");

$koperasiID = dlookup("setup", "koperasiID", "setupID=" . tosql(1, "Text"));

if (get_session('Cookie_userID') == "" or get_session("Cookie_koperasiID") <> $koperasiID) {
  print '<script>alert("' . $errPage . '"); parent.location.href = "index.php";</script>';
}

$sFileName    = "bayaranOnlineTEST.php";
$sActionFileName = "testapi2.php?userID=" . $userID . "&amount=" . $amount . "&paymentName=" . $paymentName . "";
$title        = "PERMOHONAN PENAMBAHAN SYER";

$strErrMsg = array();

//--- Prepare state type
$stateList = array();
$stateVal  = array();
$GetState = ctGeneral("", "J");
if ($GetState->RowCount() <> 0) {
  while (!$GetState->EOF) {
    array_push($stateList, $GetState->fields(name));
    array_push($stateVal, $GetState->fields(ID));
    $GetState->MoveNext();
  }
}
print '
<table cellspacing="0" cellpadding="0">
  <tr>
    <td > <b class="maroonText">LANGKAH-LANGKAH UNTUK PENAMBAHAN SYER<b></td>
  </tr>
  <tr>
    <td><p><p>Bagi  membolehkan kami memberikan perkhidmatan yang terbaik kepada anggota,  sila pastikan langkah-langkah berikut diikuti, iaitu :-</p>
        </p>
      <p> 1. Sentiasa mengemaskini <b>PROFAIL ANGGOTA</b>, terutamanya alamat emel serta lokasi semasa.<br>
        </p>
      <p> 2. Pastikan semasa membuat pembayaran menggunakan <b>RAUDAHPAY</b> masukkan nombor anggota di nombor rujukan.<br>
        </p>
      <p> 3. Setelah bayaran dibuat, penyata anggota akan dikemaskini dalam dua hari bekerja. Sila semak di menu pengguna setelah dua hari membuat pembayaran. Sekiranya penyata tidak dikemaskini, sila hubungi pihak <b>[NAMA KOPERASI]</b> untuk tindakan lanjut.</p>
      <p>
        4. Segala kesulitan amat dikesali.</p>
      <p></p></td>
  </tr>
</table>
';

$a = 0;
$FormLabel[$a]    = "* Nombor Anggota";
$FormElement[$a]  = "userID";
$FormType[$a]     = "hiddentext";
$FormData[$a]     = "";
$FormDataValue[$a] = "";
$FormCheck[$a]    = array(CheckBlank);
$FormSize[$a]     = "10";
$FormLength[$a]   = "10";

$a++;
$FormLabel[$a]    = "Nama";
$FormElement[$a]  = "name";
$FormType[$a]     = "hiddentext";
$FormData[$a]     = "";
$FormDataValue[$a] = "";
$FormCheck[$a]    = array();
$FormSize[$a]     = "35";
$FormLength[$a]   = "50";

$a++;
$FormLabel[$a]    = "No KP Baru";
$FormElement[$a]  = "newIC";
$FormType[$a]     = "hiddentext";
$FormData[$a]     = "";
$FormDataValue[$a] = "";
$FormCheck[$a]    = array();
$FormSize[$a]     = "10";
$FormLength[$a]   = "12";

$a++;
$FormLabel[$a]    = "Nombor Telefon";
$FormElement[$a]  = "mobileNo";
$FormType[$a]     = "text";
$FormData[$a]     = "";
$FormDataValue[$a] = "";
$FormCheck[$a]    = array(CheckBlank);
$FormSize[$a]     = "20";
$FormLength[$a]   = "12";

$a++;
$FormLabel[$a]    = "Emel";
$FormElement[$a]  = "email";
$FormType[$a]     = "hiddentext";
$FormData[$a]     = "";
$FormDataValue[$a] = "";
$FormCheck[$a]    = array(CheckBlank);
$FormSize[$a]     = "30";
$FormLength[$a]   = "20";

$a++;
$FormLabel[$a]    = "Pilihan Bayaran";
$FormElement[$a]  = "paymentName";
$FormType[$a]     = "radio";
$FormData[$a]     = array('Syer');
$FormDataValue[$a] = array('1596');
$FormCheck[$a]    = array();
$FormSize[$a]     = "1";
$FormLength[$a]   = "1";

$a++;
$FormLabel[$a]    = "Jumlah (RM)";
$FormElement[$a]  = "amount";
$FormType[$a]     = "text";
$FormData[$a]     = "";
$FormDataValue[$a] = "";
$FormCheck[$a]    = array();
$FormSize[$a]     = "10";
$FormLength[$a]   = "10";


$userID = get_session('Cookie_userID');
$strMember = "SELECT a.*,b.* FROM users a, userdetails b WHERE a.userID = '" . $userID . "' AND a.userID = b.userID";
$GetMember = &$conn->Execute($strMember);

if ($SubmitForm <> "") {
  //--- Begin : Call function FormValidation ---  
  for ($i = 0; $i < count($FormLabel); $i++) {
    for ($j = 0; $j < count($FormCheck[$i]); $j++) {
      FormValidation(
        $FormLabel[$i],
        $FormElement[$i],
        $$FormElement[$i],
        $FormCheck[$i][$j],
        $i
      );
    }
  }

  if ($mobileNo) {
    if (!ereg("(6[0-9]{3})([0-9]{3})([0-9]{4})", $mobileNo, $regs)) {
      array_push($strErrMsg, "mobileNo");
      print '- <font class=redText>* Masukkan Nombor Kod Negara [6][0112223333].</font><br />';
    }
  }


  if (count($strErrMsg) == "0") {

    //$updatedDate = date("Y-m-d H:i:s");

    $sSQL = "";
    $sWhere = "";
    $sWhere = "userID=" . tosql($userID, "Text");
    $sWhere = " WHERE (" . $sWhere . ")";
    $sSQL = "UPDATE users SET " .
      " email=" . tosql($email, "Text") .
      //",updatedDate=" . tosql($updatedDate, "Text") .
      //",updatedBy=" . tosql($updatedBy, "Text") ;
      $sSQL = $sSQL . $sWhere;
    $rs = &$conn->Execute($sSQL);

    $sSQL = "";
    $sWhere = "";
    $sWhere = "userID=" . tosql($userID, "Text");
    $sWhere = " WHERE (" . $sWhere . ")";
    $sSQL = "UPDATE userdetails SET " .
      " mobileNo=" . tosql($mobileNo, "Text") .
      $sSQL = $sSQL . $sWhere;
    $rs = &$conn->Execute($sSQL);


    print '<script>
          alert ("Sila Tunggu Sebentar Sementara Untuk Ke Paparan RaudhahPay");
          window.open ("' . $sActionFileName . '");
        </script>';
  }
}

?>

<div style="width: 500px; text-align:left">
  <div>&nbsp;</div>
  <form name="MyForm" action=<? print $sFileName; ?> method="POST">
    <table class="lightgrey" border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
      <tr>
        <td class="borderallteal" align="left" valign="middle">
          <div class="headerteal"><b>PERMOHONAN PENAMBAHAN Syer</b></div>
        </td>
      </tr>
      <tr>
        <td class="borderleftrightbottomteal">
          <table border="0" cellspacing="6" cellpadding="0" width="100%" align="center">
            <tr>
              <td>&nbsp;</td>
            </tr>
            <?


            //--- Begin : Looping to display label -------------------------------------------------------------
            for ($i = 0; $i < count($FormLabel); $i++) {
              print '<tr valign="top"><td align="right">' . $FormLabel[$i] . ' :</td>';
              if (in_array($FormElement[$i], $strErrMsg))
                print '<td class="errdata">';
              else
                print '<td>';
              //--- Begin : Call function FormEntry ---------------------------------------------------------  
              $strFormValue = tohtml($GetMember->fields($FormElement[$i]));
              FormEntry(
                $FormLabel[$i],
                $FormElement[$i],
                $FormType[$i],
                $strFormValue,
                $FormData[$i],
                $FormDataValue[$i],
                $FormSize[$i],
                $FormLength[$i]
              );

              //--- End   : Call function FormEntry ---------------------------------------------------------  
            ?>&nbsp;
        </td>
      </tr><?
            }

            if ($userID) {
            ?>
      <tr>
        <td colspan="2" align="center">
          <div>&nbsp;</div>
          <input type="Submit" name="SubmitForm" value="Hantar">
          <div>&nbsp;</div>
        </td>
      </tr>
    <? } ?>
    </table>
    </td>
    </tr>
    </table>
  </form>
</div>
<?
include("footer.php");
?>