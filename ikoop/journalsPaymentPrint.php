<?php
/*********************************************************************************
*			Project		: iKOOP.com.my
*			Filename	: voucherPaymentPrint.php
*			Date 		: 04/08/2006
*********************************************************************************/
session_start();
include("common.php");
include("koperasiQry.php"); 
date_default_timezone_set("Asia/Kuala_Lumpur");

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
	$sql = "SELECT * FROM jurnal WHERE no_jurnal = '".$id."'";
	$rs = $conn->Execute($sql);
	
	$no_jurnal 		= $rs->fields(no_jurnal);
	$userID			= $rs->fields(no_anggota);
	$tarikh_jurnal 	= toDate("d/m/y",$rs->fields(tarikh_jurnal));
	//-------------------------------

	//---------------------------------
	$name 			=  dlookup("users", "name", "userID=" . tosql($userID, "Number"));
	$accTabungan	=  dlookup("userdetails", "accTabungan", "userID=" . tosql($userID, "Number"));

	$bankID			=  dlookup("userdetails", "bankID", "userID=" . tosql($userID, "Number"));
	$bankname		=  dlookup("general", "name", "ID=" . $bankID);

	$departmentAdd	=  dlookup("userdetails", "address", "userID=" . tosql($userID, "Number"));
	//$alamat 		=  strtoupper(strip_tags($departmentAdd));
	$catatan		=  $rs->fields('keterangan');
	//-----------------
	
	$sql2 = "SELECT * FROM transactionacc WHERE docNo = ".tosql($no_jurnal, "Text")." ORDER BY ID";
	$rsDetail = $conn->Execute($sql2);
}

print '
<style>
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
.boxTitle {
padding: 5px;
font-size: 20px;
width: fit-content;
height: fit-content;
border: 1px solid black;
}
.bottomUp {
    position: fixed;
    bottom: 0px;
    text-align: center;
    width: 100%;
}
.borderRight {
	border-right: 1px solid;
}
.borderRightBelow {
	border-bottom: 1px solid;
	border-right: 1px solid;
	border-top: 1px solid;
}
.borderLeftBelow {
	border-bottom: 1px solid;
	border-left: 1px solid;
	border-top: 1px solid;
}
.borderSide {
	border-right: 1px solid;
	border-left: 1px solid;
}
.borderLeft {
	border-left: 1px solid;
}
.borderRightTitle {
	border-right: 1px solid;
	border-bottom: 1px solid;
	border-top: 1px solid;
}
.borderSideTitle {
	border: 1px solid;
}
.borderLeftTitle {
	border-left: 1px solid;
	border-bottom: 1px solid;
	border-top: 1px solid;
}
</style>
 
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="textFont">
	<tr><td colspan="3" align="right"><div class="boxGray" align="center">Baucer Bayaran Anggota
    <tr><td colspan="3" align="right"><div class="box" align="center"><b>'.$no_jurnal.'</b></div>&nbsp;</td></tr>
	<tr><td colspan="3" align="center">
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>		
	  <tr><td colspan="3" align="center"><div class="boxTitle" align="center"><b>ALM CORE SOLUTIONS SDN BHD</b></div></td></tr>
      <tr><td align="center" valign="midle" class="textFont">
	3-1, JALAN DAGANG SB 4/1, TAMAN SUNGAI BESI INDAH,<br />
	43300 SERI KEMBANGAN,<br />
	SELANGOR DARUL EHSAN.<br />
	TEL: +603 - 89424493<br />
	EMEL: helpdesk@ikoop.com.my<br />
		</td>
	</tr>
	</table>
	&nbsp;
	</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
		<td nowrap="nowrap" align="right">&nbsp;</td>
		<td nowrap="nowrap" align="center">&nbsp;</td>
		<td nowrap="nowrap" align="right">TARIKH : '.$tarikh_jurnal.'</td>
	</tr>
	<table>
	<tr>
		<td colspan="3">BAYARAN KEPADA</td>
		<td colspan="3">:</td>
		<td colspan="3">'.$name.'&nbsp;('.$userID.')</td>
	</tr>
	<tr>
		<td colspan="3">AKAUN BANK</td>
		<td colspan="3">:</td>
		<td colspan="3">'.$accTabungan.'&nbsp;('.$bankname.')</td>
	</tr>
	
	</table>	
	<tr>
		<table cellpadding="2" cellspacing="0" width="100%" class="textFont">
		&nbsp;
			<tr>
				<td nowrap="nowrap" align="left" class="borderLeftTitle">&nbsp;<b>A/C KETERANGAN</b>&nbsp;</td>
				<td nowrap="nowrap" align="right" class="borderSideTitle">&nbsp;<b>DEBIT&nbsp;(RM)</b></td>
				<td nowrap="nowrap" align="right" class="borderRightTitle">&nbsp;<b>KREDIT&nbsp;(RM)</b></div></td>
			</tr>
			';
	if ($rsDetail->RowCount() <> 0){
		$i=1;
		while (!$rsDetail->EOF) {
				
			$deductID 	= $rsDetail->fields(deductID);
			$desc 		= dlookup("general", "name", "ID=".$deductID);
			$totPymt 	= number_format($rsDetail->fields(pymtAmt),2);
			$accNombor 	= dlookup("general", "code", "ID=".$deductID);
			$accdet 	= dlookup("general", "name", "ID=".$deductID);
		
		if($rsDetail->fields(addminus)){
			$kredit 	= $rsDetail->fields(pymtAmt);
			$jumlahKrt += $rsDetail->fields(pymtAmt);
		}
		else{
			$debit 		= $rsDetail->fields(pymtAmt);
			$jumlahDbt += $rsDetail->fields(pymtAmt);
		}	
	print
			'<tr>
				<td nowrap="nowrap" align="left" class="borderLeft">&nbsp;'.$accNombor.'&nbsp;-&nbsp;'.$accdet.'</td>

				<td nowrap="nowrap" valign="top" align="right" class="borderSide">&nbsp;';
					if($debit<>0) print number_format($debit,2); 
					print '&nbsp;</td>
				<td nowrap="nowrap" valign="top" align="right" class="borderRight">&nbsp;';
					if($kredit<>0) print number_format($kredit,2);
					print '&nbsp;</td>
			</tr>';

	$jumlah += $rsDetail->fields(pymtAmt);
	$debit = '';
	$kredit = '';
	$rsDetail->MoveNext();
	}
	if($jumlah<>0){
	$clsRM->setValue($jumlah);
	$strTotal = ucwords($clsRM->getValue());
	}
}
print '

			
			<tr>
				
				<td nowrap="nowrap" valign="top" align="left" class="borderLeftBelow"><b>&nbsp;JUMLAH&nbsp;</b></td>
				<td nowrap="nowrap" valign="top" align="right" class="borderSideTitle">RM&nbsp;'.number_format($jumlahDbt,2).'&nbsp;</td>
				<td nowrap="nowrap" valign="top" align="right" class="borderRightBelow">RM&nbsp;'.number_format($jumlahKrt,2).'&nbsp;</td>
			</tr>
			

			</table>		
			
		</td>
	</tr>
	<tr><td  colspan="8"><br></td></tr>
	<tr><td colspan="3">CATATAN : '.$catatan.'</td></tr>

	<tr><td  colspan="8"><br></td></tr>
	<tr><td  colspan="8"><br></td></tr>
	<tr><td  colspan="8"><br></td></tr>
	<tr><td  colspan="8"><br></td></tr>
	<tr><td  colspan="8"><br></td></tr>
	<tr><td  colspan="8"><br></td></tr>

	<tr><td colspan="3">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td nowrap="nowrap"><table cellpadding="0" cellspacing="0"><tr><td align="center">_____________________________<br />DISAHKAN OLEH</td></tr></table></td>
				<td nowrap="nowrap">&nbsp;</td>
				<td nowrap="nowrap" align="right"><table cellpadding="0" cellspacing="0"><tr><td align="center">_____________________________<br />DILULUSKAN</td></tr></table></td>
			</tr>
		</table>
	</td></tr>
	<tr><td colspan="3"><hr size="1px" /></td></tr>&nbsp;
	<table width="100%" border="1" cellpadding="10" cellspacing="0" class="textFont">
<tr><td>Disediakan Oleh:<br />
</table>
<center>
<div class="bottomUp"><hr size="1px">
  <b>INI ADALAH CETAKAN KOMPUTER DAN TIDAK PERLU 	DITANDATANGAN</b>
</div> 
</center>';

print $footer;
?>