<?php
/*********************************************************************************
*			Project		: iKOOP.com.my
*			Filename	: voucherPaymentPrint.php
*			Date 		: 27/7/2006
*********************************************************************************/
session_start();
include("common.php");

include("koperasiQry.php");	
date_default_timezone_set("Asia/Jakarta");

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}

$header =
'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'
.'<html>'
.'<head>'
.'<title>'.$emaNetis.'</title>'
.'<meta name="GENERATOR" content="'.$yVZcSz2OuGE5U.'">'
.'<meta http-equiv="pragma" content="no-cache">'
.'<meta http-equiv="expires" content="0">'
.'<meta http-equiv="cache-control" content="no-cache">'
.'<LINK rel="stylesheet" href="images/mail.css" >'
.'</head>'
.'<body>';

$footer = '
<script>window.print();</script>
</body></html>';

if($ID){
	$sql = "SELECT a.*,b.memberID,b.address, b.city, b.postcode, b.stateID, b.departmentID, c.name FROM  resit a, userdetails b,users c WHERE b.userID = c.userID and a.bayar_nama = b.memberID and no_resit = ".tosql($ID, "Text");
	$rs = $conn->Execute($sql);


	
	$no_resit 		= $rs->fields(no_resit);
	$tarikh_resit 	= toDate("d/m/y",$rs->fields(tarikh_resit));
	$bayar_kod 		= $rs->fields(bayar_kod);
	$bayar_nama 	= $rs->fields(name);
	$no_anggota 	= $rs->fields(memberID);
	$deptID			=  $rs->fields('departmentID');
	$departmentAdd	=  dlookup("general", "b_Address", "ID=" . tosql($deptID, "Number"));
	$alamat 		= strtoupper(strip_tags($departmentAdd));
	//-----------------
	$cara_bayar 	= $rs->fields(cara_bayar);
	$kod_siri 		= $rs->fields(kod_siri);
	$tarikh 		= toDate("d/m/y",$rs->fields(tarikh));
	$akaun_bank 	= $rs->fields(akaun_bank);
	$kerani 		= $rs->fields(kerani);
	$catatan 		= $rs->fields(catatan);


	$accTabungan	=  dlookup("userdetails", "accTabungan", "userID=" . tosql($no_anggota, "Number"));
	$bankID			=  dlookup("userdetails", "bankID", "userID=" . tosql($no_anggota, "Number"));
	$bankname		=  dlookup("general", "name", "ID=" . $bankID);

	$sqltotal 	= "SELECT SUM(pymtAmt) AS tot FROM transaction WHERE docNo = '".$ID."'";
	$rstotal 	= $conn->Execute($sqltotal);
	$jumlah 	= $rstotal->fields(tot);
	
	$sql2 = "SELECT * FROM transaction WHERE docNo = ".tosql($ID, "Text")." ORDER BY ID";
	$rsDetail = $conn->Execute($sql2);
}

$header .=
'<style>
.boxTitle {
    padding: 5px;
    font-size: 20px;
    width: fit-content;
    height: fit-content;
    border: 1px solid black;
}
.boxGray {
padding: 7px;
width: 110px;
background-color: lightgray;
word-wrap: break-word;
}
.box {
padding: 7px;
width: 110px;
}
</style>
 
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="textFont">
<tr>
    <td colspan="3" align="right"><div class="boxGray"     
    align="center">Resit Rasmi</div></td>
</tr>
<tr>
    <td colspan="3" align="right"><div class="box" align="center"><b>'.$no_resit.'</b></div></td></tr>
</table>


<table border="0" cellspacing="0" cellpadding="0" width="100%">'
	.'<tr>'
		.'<td align="center" valign="middle" class="textFont">'		
		.'<div class="boxTitle" align="center"><b>ALM CORE SOLUTIONS SDN BHD</b></div>'
		.'3-1, JALAN DAGANG SB 4/1, TAMAN SUNGAI BESI INDAH,<br />'
		.'43300 SERI KEMBANGAN,<br />'
		.'SELANGOR DARUL EHSAN.<br />'
		.'TEL: +603 - 89424493<br />'
		.'EMEL: helpdesk@ikoop.com.my'
		.'</div>'
		.'</td>'
	.'</tr>'
.'</table>';

print $header;
if($jumlah<>0){
	$clsRM->setValue($jumlah);
	$strTotal = ucwords($clsRM->getValue()).' Sahaja.';
}
$jumlah = number_format($jumlah,2);

print 
'<style>
.bottom {
    position: fixed;
    bottom: 10px;
    text-align: center;
    width: 100%;
}
</style>
<table cellpadding="0" cellspacing="0" width="100%">
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
		<td nowrap="nowrap">&nbsp; </td>
		<td nowrap="nowrap" align="center">&nbsp;</td>
		<td nowrap="nowrap" align="right">Tarikh Kemaskini: '.$tarikh_resit.'</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3">Diterima Daripada: <br><u>'.$bayar_nama.'('.$no_anggota.')<br> '.$alamat.'</u>

	<br></br>Bank Anggota : '.$accTabungan.' ('.$bankname.')


	<br></br>
	Sebanyak RM <u>'.$jumlah.'</u> Ringgit <u>'.$strTotal.'</u>
	</td></tr>

	<tr><td colspan="3"><hr size="1px" /></td></tr>
	';
$jumlah = 0;
if ($rsDetail->RowCount() <> 0){
$i=0;
	while (!$rsDetail->EOF) {
	$code = dlookup("general", "code", "ID=" . tosql($rsDetail->fields('deductID'), "Number"));
	$name = dlookup("general", "name", "ID=" . tosql($rsDetail->fields('deductID'), "Number"));
			print '
			<tr>
				<td nowrap="nowrap" valign="top">&nbsp;('.++$i.')&nbsp;</td>
				<td nowrap="nowrap" align="left">&nbsp;'.$name.'</td>
				<td nowrap="nowrap" valign="top" align="right">&nbsp;'; print  number_format($rsDetail->fields(pymtAmt),2); print  '&nbsp;</td>
			</tr>';
	$jumlah += $rsDetail->fields(pymtAmt);
	$rsDetail->MoveNext();
	}
}

print '
			<tr><td colspan="3">&nbsp;</td></tr>
			<tr><td colspan="3">&nbsp;</td></tr>
			<tr><td colspan="3">&nbsp;</td></tr>
			<tr><td colspan="3">&nbsp;</td></tr>
			<tr><td colspan="3"><hr size="1px" /></td></tr>
			<tr>
    			<td nowrap="nowrap" valign="top" align="left">&nbsp;</td>
    			<td nowrap="nowrap" valign="top" align="right">&nbsp;CUKAI&nbsp;</td>
    			<td nowrap="nowrap" valign="top" align="right">&nbsp;0.00&nbsp;</td>
			</tr>
			<tr>
				<td nowrap="nowrap" valign="top" align="left">&nbsp;</td>
				<td nowrap="nowrap" valign="top" align="right">&nbsp;<b>JUMLAH</b>&nbsp;</td>
				<td nowrap="nowrap" valign="top" align="right">&nbsp;'; print number_format($jumlah,2); print '&nbsp;</td>
			</tr>
		</table>
	</td></tr>
	<tr><td colspan="3"><hr size="1px" /></td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr><td width="33%" align="left">Cara bayaran : <u>'.$cara_bayar.'&nbsp;</u></td>
	<td width="33%" align="center">Kod & No. siri : <u>'.$kod_siri.'&nbsp;</u></td>
	<td width="33%" align="right">Tarikh Bayaran: <u>'.$tarikh.'</u></td></tr>
	<tr><td colspan="3">Catatan :'.$catatan.'</td></tr>
	</table>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3" align="right">
	
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td nowrap="nowrap" align="right"><table cellpadding="0" cellspacing="0"><tr><td align="center">b.p ALM CORE SOLUTIONS SDN BHD<br />___________________________________<br />Bendahari</td></tr></table></td>
			</tr>
		</table>
	</td></tr>
</table>
<center>
<div class="bottom"><hr size="1px">
  <b>INI ADALAH CETAKAN KOMPUTER DAN TIDAK PERLU DITANDATANGAN</b>
</div>
</center>

';

print $footer;
?>
