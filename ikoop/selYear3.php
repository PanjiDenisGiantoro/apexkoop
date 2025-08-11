<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	selYear3.php
 *		   Description	:	Selection Month and Year Option
 *		   Parameter	:   $rpt, $id
 *          Date 		: 	15/12/2003
 *********************************************************************************/
include("common.php");
include("koperasiQry.php");

date_default_timezone_set("Asia/Kuala Lumpur");
$today = date("F j, Y, g:i a");

if ($rpt == "") {
    print '	<script>
				alert ("Pengguna tidak boleh akses mukasurat ini...!");
				window.close();
			</script>';
    exit;
}

if (!isset($mmTo)) $mmTo = date("n");
if (!isset($yyTo)) $yyTo = date("Y");

if ($action == "Jana Laporan") {
    $msg    = "";
    // Set tarikh pertama bulan
    $dtFrom = sprintf("%04d-%02d-01", $yyTo, $mmTo);  // Tarikh mula dari 1/bulan/tahun yang dipilih

    // Set tarikh akhir bulan
    $dtTo = date("Y-m-t", strtotime($dtFrom));         // Fungsi "Y-m-t" akan memberi tarikh terakhir bulan tersebut

    // Jika ada mesej untuk diberi
    if ($msg <> "") {
        print '<script>alert("' . $msg . '");</script>';
    } else {
        $rptURL = $rpt . '.php?dtFrom=' . $dtFrom . '&dtTo=' . $dtTo;
        print '
        <script>
            var rptUrl;
            window.open ("' . $rptURL . '", "rpt","scrollbars=yes,resizable=yes,toolbars=yes,location=no,menubar=yes");
            window.close();
        </script>';
    }
}


print '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>' . $emaNetis . '</title>
	<!--LINK rel="stylesheet" href="images/default.css" -->	
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />        
</head>
<body leftmargin="5" topmargin="5" class="bodyBG">';

print '
<form name="FrmSelection" action="' . $PHP_SELF . '" method="post">
	<input type="hidden" name="rpt" value="' . $rpt . '">
	<table border="0" cellpadding="3" cellspacing="0" class="table table-striped table-sm" style="padding: 1 0 0 0" height="100" width="100%">
   		<tr valign="top">
			<td align="right"><b>Sehingga Bulan</b></td>
			<td class="textFont">
				<select name="mmTo" class="form-select-xs">';
for ($j = 1; $j < 13; $j++) {
    print '			<option value="' . $j . '"';
    if ($mmTo == $j) print 'selected';
    print             '>' . $j;
}
print '			</select>
				<input type="text" name="yyTo" size="3" maxlength="4" value="' . $yyTo . '" class="form-select-xs">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" name="action" value="Jana Laporan" class="btn btn-primary"></td>
		</tr>
	</table>
</form>

</body>
</html>';
