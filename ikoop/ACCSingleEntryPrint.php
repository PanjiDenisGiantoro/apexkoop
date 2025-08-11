<?php
/*********************************************************************************
*			Project		:iKOOP.com.my
*			Filename	: voucherPaymentPrint.php
*			Date 		: 4/8/2006
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
	$sql = "SELECT * FROM singleentry WHERE SENO = '".$id."'";
 	
	$rs 				= $conn->Execute($sql);
	$SENO 				= $rs->fields(SENO);
	$tarikh_entry 		= $rs->fields(tarikh_entry);
	$tarikh_entry 		= substr($tarikh_entry,8,2)."/".substr($tarikh_entry,5,2)."/".substr($tarikh_entry,0,4);
	$keterangan 		= $rs->fields(keterangan);
	$description 		= $rs->fields(description);
	$nama 				= $rs->fields(name);
	$maklumat        	= $rs->fields(maklumat);
	$batchNo 			= $rs->fields(batchNo);
	$accountNo 			= $rs->fields(accountNo);
	$taxNo				= $rs->fields(taxNo);
	$disedia			=  $rs->fields('disediakan');
	$disedia1	=  dlookup("users", "name", "userID=" . tosql($disedia, "Text"));
	$sedia = strtoupper(strip_tags($disedia1));
	$namabatch	= dlookup("generalacc", "name", "ID=".$batchNo);
	
	$sql2 = "SELECT * FROM transactionacc WHERE docNo = '". $SENO ."' ORDER BY ID";
	$rsDetail = $conn->Execute($sql2);
}

print '
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="textFont">
	<tr>
	<td colspan="9" align="center">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center" valign="midle" class="textFont">
				<b>CETAKAN SINGLE ENTRY</b><br />
		</td></tr>
	</table>&nbsp;</td></tr>
	<tr><td colspan="9">&nbsp;
	<tr><td colspan="9"><b>NAMA BATCH :</b> '.$namabatch.'&nbsp;</td></tr>

	<tr><td  colspan="9"><br></td></tr></td></tr>
	<tr><td colspan="9"><hr size="1px" /></td></tr>
	<tr>
		<td nowrap="nowrap" align="left">&nbsp;TARIKH&nbsp;</td>
		<td nowrap="nowrap" align="left">&nbsp;NO AKAUN&nbsp;</td>
		<td nowrap="nowrap" align="left">&nbsp;DESCRIPTION AKAUN&nbsp;</td>
		<td nowrap="nowrap" align="left">&nbsp;NO RUJUKAN&nbsp;</td>
		<td nowrap="nowrap" align="left">&nbsp;PERKARA&nbsp;</td>
		<td nowrap="nowrap" align="center">&nbsp;DEBIT(RM)&nbsp;</td>
		<td nowrap="nowrap" align="center">&nbsp;KREDIT(RM)&nbsp;</td>
	</tr>
	<tr><td colspan="9"><hr size="1px" /></td></tr>';

	if ($rsDetail->RowCount() <> 0){
		$i=1;
		while (!$rsDetail->EOF) {
		$deductID 	= $rsDetail->fields(deductID);
		$taxing 	= $rsDetail->fields(taxNo);
		$tarikh 	= $rsDetail->fields(createdDate);
		$tarikh 	= substr($tarikh,8,2)."/".substr($tarikh,5,2)."/".substr($tarikh,0,4);
		$batchNom 	= $rsDetail->fields(batchNo);
		$batchN 	= $rsDetail->fields(batchNo);
		$perkara 	= $rsDetail->fields(deductID);
		$desc_akaun =	$rsDetail->fields(desc_akaun);

		$desc 			= dlookup("generalacc", "name", "ID=".$deductID);
		$tax 			= dlookup("generalacc", "name", "ID=".$taxing);
		$batchNombor 	= dlookup("generalacc", "ID", "ID=".$batchN);
		$batchdet 		= dlookup("generalacc", "name", "ID=".$batchNom);
		$a_keterangan 	= dlookup("generalacc", "code", "ID=" . tosql($rsDetail->fields(deductID), "Number"));
		$perkara2 		= dlookup("generalacc", "name", "ID=".$perkara);

		$totPymt = number_format($rsDetail->fields(pymtAmt),2);

		if($rsDetail->fields(addminus))
		{
			$kredit = $rsDetail->fields(pymtAmt);
			$jumlahKrt += $rsDetail->fields(pymtAmt);
		}else{
			$debit = $rsDetail->fields(pymtAmt);
			$jumlahDbt += $rsDetail->fields(pymtAmt);
		}
		print
			'<tr>
				<td nowrap="nowrap" align="left">&nbsp;'.$tarikh_entry.'&nbsp;</td>
				<td nowrap="nowrap" align="left">&nbsp;'.$a_keterangan.'&nbsp;</td>
				<td nowrap="nowrap" align="left">&nbsp;'.$perkara2.'</td>
				<td nowrap="nowrap" align="left">&nbsp;'.$SENO.'</td>
				<td nowrap="nowrap" align="left">&nbsp;'.$desc_akaun.'</td>
				<td nowrap="nowrap" valign="top" align="center">&nbsp;';
					if($debit<>0) print number_format($debit,2); 
					print '&nbsp;</td>
				<td nowrap="nowrap" valign="top" align="center">&nbsp;';
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
print
	'<tr><td colspan="9">&nbsp;</td></tr>
	<tr><td colspan="9">&nbsp;</td></tr>
	<tr><td colspan="9">&nbsp;</td></tr>
	<tr><td colspan="9">&nbsp;</td></tr>
	<tr><td colspan="9">&nbsp;</td></tr>
	<tr><td colspan="9">&nbsp;</td></tr>
	<tr><td colspan="9">&nbsp;</td></tr>
	<tr><td colspan="9">&nbsp;</td></tr>
	<tr><td colspan="9"><hr size="1px" /></td></tr>
	<tr>
		<td nowrap="nowrap" valign="top" align="right">&nbsp;</td>
		<td nowrap="nowrap" valign="top" align="right">&nbsp;</td>
		<td nowrap="nowrap" valign="top" align="right">&nbsp;</td>
		<td nowrap="nowrap" valign="top" align="right">&nbsp;</td>
		<td nowrap="nowrap" valign="top" align="right">&nbsp;JUMLAH (RM)&nbsp;</td>
		<td nowrap="nowrap" valign="top" align="center">&nbsp;'.number_format($jumlahDbt,2).'&nbsp;</td>
		<td nowrap="nowrap" valign="top" align="center">&nbsp;'.number_format($jumlahKrt,2).'&nbsp;</td>
	</tr>
	<tr><td colspan="9"><hr size="1px" /></td></tr>	
	</td></tr>
		
</table>
<tr><td colspan="5">&nbsp;</td></tr>
	<tr><td colspan="5">&nbsp;</td></tr>
	<tr><td colspan="5" align="right"></td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3" align="center">&nbsp;&nbsp;</td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3" align="center">
	<br></br><br></br><br></br>
		<table cellpadding="0" cellspacing="0" width="100%" >
			<tr>
				<td nowrap="nowrap">&nbsp;</td>
				<td nowrap="nowrap" align="center"><table cellpadding="0" cellspacing="0"><tr><td align="center">_____________________________<br />DISEDIAKAN OLEH <br>'.$sedia.'</br></td></tr></table></td>
				<td nowrap="nowrap" align="center"><table cellpadding="0" cellspacing="0"><tr><td align="center">_____________________________<br />DISEMAK OLEH <br></br></td></tr></table></td>
				<td nowrap="nowrap" align="center"><table cellpadding="0" cellspacing="0"><tr><td align="center">_____________________________<br />DISAHKAN OLEH<br></br></td></tr></table></td>
			</tr>
		</table>';
print $footer; ?>