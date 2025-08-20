<?php
/*********************************************************************************
*			Project		: iKOOP.com.my
*			Filename	: accprintresit.php
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
.'<meta name="GENERATOR" content="'.$yVZcSz2OuGE8U.'">'
.'<meta http-equiv="pragma" content="no-cache">'
.'<meta http-equiv="expires" content="0">'
.'<meta http-equiv="cache-control" content="no-cache">'
.'<LINK rel="stylesheet" href="images/mail.css" >'
.'</head>'
.'<body>';

$footer = '
<script>window.print();</script>
</body></html>';

if($id){
	$sql = "SELECT * FROM  resitacc WHERE no_resit = '". $id."'";
	$rs = $conn->Execute($sql);

	$no_resit 		= $rs->fields(no_resit);
	$tarikh_resit 	= toDate("d/m/y",$rs->fields(tarikh_resit));
	$tarikh 		= toDate("d/m/y",$rs->fields(tarikh));
	$bayar_kod 		= $rs->fields(bayar_kod);
	$bayar_nama 	= $rs->fields(name);
	$no_anggota 	= $rs->fields(memberID);
	$cara_bayar 	= $rs->fields(cara_bayar);
	$Cheque			= $rs->fields(Cheque);
	$akaun_bank 	= $rs->fields(akaun_bank);
	$kod_project 	= $rs->fields(kod_project);
	$kod_jabatan	= $rs->fields(kod_jabatan);
	$keterangan		= $rs->fields(keterangan);
	$diterima_drpd	= $rs->fields(diterima_drpd);
	$catatan 		= $rs->fields(catatan);

	$kerani			= $rs->fields(kerani);

	$master 		= $rs->fields(masteraccount);
	$masterA 		= dlookup("generalacc", "name", "ID=".$master);

	$kod_bank 		= $rs->fields(kod_bank);
	$kod_bankA 		= dlookup("generalacc", "name", "ID=".$kod_bank);

	$sqltotal = "SELECT sum(pymtAmt) as tot FROM transactionacc WHERE addminus IN (1) AND docNo = '".$id."'";
	$rstotal = $conn->Execute($sqltotal);
	$jumlah = $rstotal->fields(tot);
	
	$sql2 = "SELECT * FROM transactionacc WHERE addminus IN (1) AND docNo = ".tosql($no_resit, "Text")." ORDER BY ID";
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
</style>'

.'<div align="right"><b>RESIT RASMI</b></div>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="textFont">
	<tr>
    	<td colspan="2" align="center">
        <div class="boxTitle" align="center"><b>ALM CORE SOLUTIONS SDN BHD</b></div>
        </td>
    </tr>
    
    <tr>
    	<td colspan="2" align="center" valign="middle" class="textFont">
        3-1, JALAN DAGANG SB 4/1,<br />
    	TAMAN SUNGAI BESI INDAH,<br />
		43300 SERI KEMBANGAN,<br />
		SELANGOR DARUL EHSAN.<br />
		TEL: +603 - 89424493<br />
		EMEL: helpdesk@ikoop.com.my<br />
        </td>
    </tr>
</table>';

print $header;
if($jumlah<>0){
	$clsRM->setValue($jumlah);
	$strTotal = ucwords($clsRM->getValue()).' Ringgit Sahaja.';
}
$jumlah = number_format($jumlah,2);

print 
'<table cellpadding="0" cellspacing="0" width="100%">
	<tr><td colspan="8">&nbsp;</td></tr>
	<td valign="top">&nbsp;</td>	
	<td width="48%" align="right">
		<table border="0" cellspacing="1" cellpadding="2">
			<tr>
				<td colspan="2" align="right"><b>TARIKH</b></td>
				<td colspan="2">:</td>
				<td colspan="2">'.$tarikh_resit.'</td>
			</tr>
			<tr>
				<td colspan="2" align="right"><b>NO RESIT</b></td>
				<td colspan="2">:</td>
				<td colspan="2">'.$no_resit.'</td>
			</tr>
		</table>
	</td>

	<tr><td colspan="8">&nbsp;</td></tr>
	<table>
	<tr>
		<td nowrap="nowrap" align="left">DITERIMA DARIPADA</td>
		<td nowrap="nowrap" align="left">:</td>
		<td nowrap="nowrap" align="left">'.$diterima_drpd.'</td>
	</tr>

	<tr>
		<td nowrap="nowrap" align="left">KETERANGAN</td>
		<td nowrap="nowrap" align="left">:</td>
		<td nowrap="nowrap" align="left">'.$keterangan.'</td>
	</tr>

	<tr>
		<td nowrap="nowrap" align="left">CHEQUE NO</td>
		<td nowrap="nowrap" align="left">:</td>
		<td nowrap="nowrap" align="left">'.$Cheque.'</td>
	</tr>

	<tr>
		<td nowrap="nowrap" align="left">CARA BAYARAN</td>
		<td nowrap="nowrap" align="left">:</td>
		<td nowrap="nowrap" align="left">'.$cara_bayar.'</td>
	</tr>
	</table>
	
	<tr><td colspan="5"><hr size="2px"></td></tr>
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td nowrap="nowrap" align="left">&nbsp;KETERANGAN&nbsp;</td>
				<td nowrap="nowrap" align="left">&nbsp;CUKAI SST&nbsp;</td>
				<td nowrap="nowrap" align="left"></td>
				<td nowrap="nowrap" align="right">&nbsp;AMAUN (RM)&nbsp;</td>
			</tr>
			
		<tr><td colspan="8"><hr size="2px" /></td></tr>';

	if ($rsDetail->RowCount() <> 0){
		$i=1;
		while (!$rsDetail->EOF) {
			
		$accNom 	= $rsDetail->fields(deductID);
		$accN 		= $rsDetail->fields(deductID);
		$codeproject= $rsDetail->fields(kod_project);
		$codejabatan= $rsDetail->fields(kod_jabatan);
		$desc_akaun = $rsDetail->fields(desc_akaun);
		$taxing 	= $rsDetail->fields(taxNo);

		$accNombor 	= dlookup("generalacc", "code", "ID=".$accN);
		$accdet 	= dlookup("generalacc", "name", "ID=".$accNom);
		$kodprojek 	= dlookup("generalacc", "name", "ID=".$codeproject);
		$kodjabatan = dlookup("generalacc", "name", "ID=".$codejabatan);
		$cukai  	= dlookup("generalacc", "name", "ID=".$taxing);
		$totPymt = number_format($rsDetail->fields(pymtAmt),2);

		print
			'<tr>
				<td nowrap="nowrap" align="left">&nbsp;'.$desc_akaun.'&nbsp;</td>
				<td nowrap="nowrap" align="left">&nbsp;'.$cukai.'&nbsp;</td>
				<td nowrap="nowrap" align="left"></td>
				<td nowrap="nowrap" align="right">&nbsp;'.$totPymt.'&nbsp;</td>
			</tr>';
			$jumlah1 += $rsDetail->fields(pymtAmt);
			$rsDetail->MoveNext();
			}
			if($jumlah1<>0){
			$clsRM->setValue($jumlah1);
			$strTotal1 = strtoupper($clsRM->getValue()).' RINGGIT SAHAJA.';
			}
		}

		//addition of cukai column and row
		print
			'<tr><td colspan="8"><hr size="2px" /></td></tr>
			
			<tr>
				<td nowrap="nowrap" align="left"></td>
				<td nowrap="nowrap" align="left"></td>
				<td nowrap="nowrap" align="right">&nbsp;CUKAI&nbsp;</td>
				<td nowrap="nowrap" align="right">&nbsp;0.00&nbsp;</td>
			</tr>';

		//change on jumlah column and row
		print 
			'<tr>
				<td nowrap="nowrap" align="left"></td>
				<td nowrap="nowrap" align="left"></td>
				<td nowrap="nowrap" align="right"><b>&nbsp;JUMLAH&nbsp;</b></td>
				<td nowrap="nowrap" align="right"><b>&nbsp;RM '.number_format($jumlah1,2).'&nbsp;</b></td>
			</tr>


			<tr><td colspan="8"><hr size="2px" /></td></tr>
	
		</table>';

		//print jumlah dalam perkataan
		print
		'
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td nowrap="nowrap" align="left"><b>&nbsp;JUMLAH DALAM PERKATAAN :&nbsp;'.$strTotal1.'</b></td>
		</tr>
		
		<tr><td colspan="8"><hr size="2px" /></td></tr>

		</table>
		
		
	</td></tr>
	

	<!--Disemak and Signature -->

	<br></br><br></br><br></br>
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				
				<td nowrap="nowrap">&nbsp;</td>
				<td nowrap="nowrap" align="right"><table cellpadding="0" cellspacing="0">
				
				<tr>
				<td align="center">_____________________________<br />DISEMAK</td>
				</tr>
				<tr>
				<td align="center">('.$kerani.')</td>
				</tr>

				</table></td>
			</tr>
		</table>


	<!-- Cetakan Computer -->

	<style>
	.bottom {
		position: fixed;
		bottom: 10px;
		text-align: center;
		width: 100%;
	}
	</style>

	<center>
		<div class="bottom"><hr size="1px">
			<b>INI ADALAH CETAKAN KOMPUTER DAN TIDAK PERLU DITANDATANGAN</b>
		</div>
	</center>


	
</table>'; 

print $footer;
?>