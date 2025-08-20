<?php
/*********************************************************************************
*			Project		:iKOOP.com.my
*			Filename	: voucherPaymentPrint.php
*			Date 		: 4/8/2006
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

print $header;

if($id){
	$sql = "SELECT a.*,b.memberID,b.address, b.city, b.postcode, b.stateID, b.departmentID, c.name FROM  vauchers a, userdetails b,users c WHERE b.userID = c.userID and a.no_anggota = b.memberID and no_baucer = '" . $id ."'";
	$rs = $conn->Execute($sql);
	$no_baucer = $rs->fields(no_baucer);
	$tarikh_baucer = toDate("d/m/y",$rs->fields(tarikh_baucer));
	$name = $rs->fields(name);
	$no_anggota = $rs->fields(memberID);
	$userID = $rs->fields(userID);
	$deptID			=  $rs->fields('departmentID');
	$departmentAdd	=  dlookup("general", "b_Address", "ID=" . tosql($deptID, "Number"));
	$alamat = strtoupper(strip_tags($departmentAdd));
	$catatan			=  $rs->fields('keterangan');
	
	$sql2 = "SELECT * FROM transaction WHERE docNo = ".tosql($no_baucer, "Text")." ORDER BY ID";
	$rsDetail = $conn->Execute($sql2);
}

print '
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="textFont">
	<tr><td colspan="3"><div align="right" width="650">BAUCER BAYARAN<br>'.$no_baucer.'</div>&nbsp;</td></tr>
	<tr><td colspan="3" align="center">
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="midle" class="textFont">
	<b>ALM CORE SOLUTIONS SDN BHD</b><br />
	3-1, JALAN DAGANG SB 4/1, TAMAN SUNGAI BESI INDAH,<br />
	43300 SERI KEMBANGAN,<br />
	SELANGOR DARUL EHSAN.<br />
	TEL: +603 - 89424493<br />
	EMEL: helpdesk@ikoop.com.my<br />
</td></tr></table>&nbsp;</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
		<td nowrap="nowrap" align="right">&nbsp;</td>
		<td nowrap="nowrap" align="center">&nbsp;</td>
		<td nowrap="nowrap" align="right">Tarikh : '.$tarikh_baucer.'</td>
	</tr>
	<tr><td colspan="3">Bayaran Kepada : '.$name.'&nbsp;('.$no_anggota.')</td></tr>
	<tr><td colspan="3">Alamat : '.$alamat.'</td></tr>
	<tr><td colspan="3"><hr size="1px" /></td></tr>
	<tr>
		<td nowrap="nowrap" align="right">&nbsp;BIL&nbsp;</td>
		<td nowrap="nowrap" align="center">&nbsp;PERKARA&nbsp;</td>
		<td nowrap="nowrap" align="right">&nbsp;AMAUN (RM)&nbsp;</td>
	</tr>
	<tr><td colspan="3"><hr size="1px" /></td></tr>';
if ($rsDetail->RowCount() <> 0){
$i=1;
while (!$rsDetail->EOF) {
$deductID = $rsDetail->fields(deductID);
$desc = dlookup("general", "name", "ID=".$deductID);
$totPymt = number_format($rsDetail->fields(pymtAmt),2);
print
	'<tr>
		<td nowrap="nowrap" valign="top" align="right">('.$i++.')&nbsp;&nbsp;</td>
		<td valign="top">'.$desc.'</td>
		<td nowrap="nowrap" valign="top" align="right">&nbsp;'.$totPymt.'&nbsp;</td>
	</tr>';
	$jumlah += $rsDetail->fields(pymtAmt);
	$rsDetail->MoveNext();
	}
	if($jumlah<>0){
	$clsRM->setValue($jumlah);
	$strTotal = ucwords($clsRM->getValue());
	}
}
print
	'<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3"><hr size="1px" /></td></tr>
	<tr>
		<td nowrap="nowrap" valign="top" align="right">&nbsp;</td>
		<td nowrap="nowrap" valign="top" align="right">&nbsp;JUMLAH&nbsp;</td>
		<td nowrap="nowrap" valign="top" align="right">&nbsp;'.number_format($jumlah,2).'&nbsp;</td>
	</tr>
	<tr><td colspan="3"><hr size="1px" /></td></tr>
	<tr><td colspan="3">Catatan : '.$catatan.'</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3" align="right">b.p ALM CORE SOLUTIONS SDN BHD</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td nowrap="nowrap"><table cellpadding="0" cellspacing="0"><tr><td align="center">_____________________________<br />Disemak</td></tr></table></td>
				<td nowrap="nowrap">&nbsp;</td>
				<td nowrap="nowrap" align="right"><table cellpadding="0" cellspacing="0"><tr><td align="center">_____________________________<br />Diluluskan</td></tr></table></td>
			</tr>
		</table>
	</td></tr>
	<tr><td colspan="3"><hr size="1px" /></td></tr>
	
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td nowrap="nowrap">Tarikh : _____________________</td>
				<td nowrap="nowrap">&nbsp;</td>
				<td nowrap="nowrap" align="right"><table cellpadding="0" cellspacing="0"><tr><td align="center">______________________________<br />Tandatangan Penerima</td></tr></table></td>
			</tr>
		</table>
	</td></tr>
	<tr><td colspan="3"><hr size="1px" /></td></tr>
	<tr><td colspan="3">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td nowrap="nowrap" colspan=2>&nbsp;</td>
				
			</tr>
			<tr><td nowrap="nowrap" colspan="3">&nbsp;</td></tr>
			<tr><td nowrap="nowrap" colspan="3">Disemak oleh ______________________________________________</td></tr>
		</table>
	</td></tr>
</table>';
print $footer;
?>
