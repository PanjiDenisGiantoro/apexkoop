<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	loanUserYearly.php
*		   Description	:	Penyata Pembiayaan Tahunan
*          Date 		: 	13/7/2006
*********************************************************************************/
session_start();
include("common.php");	

date_default_timezone_set("Asia/Jakarta");
$today = date("F j, Y, g:i a");   

$title2  = 'PENYATA LEDGER';
$title2 = strtoupper($title2);            

if (get_session("Cookie_groupID") == "" OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");window.close();</script>';
	exit;
}
print '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>'.$emaNetis.'</title>
	<LINK rel="stylesheet" href="images/mail.css" >
</head>
<body><table border="0" cellpadding="5" cellspacing="0" width="100%">';

print '
	<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
		<td colspan="2" align="right">'.strtoupper($emaNetis).'</td>
	</tr>
	<tr bgcolor="#730b33" style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
		<th colspan="2" height="40"><font color="#FFFFFF">'.$title2.' '.$dtFrom.' HINGGA '.$dtTo.'</font></th>
	</tr> 	
	<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
		<td colspan="2" align="right"></td>
	</tr>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlLoan = "SELECT DISTINCT(a.deductID) AS deduct, b.* FROM transactionacc a, generalacc b WHERE a.deductID=b.ID AND (tarikh_doc BETWEEN '".$dtFrom."' AND '".$dtTo."') ORDER BY b.code ASC";
$rsLoan = $conn->Execute($sqlLoan);

while (!$rsLoan->EOF) {
$i = 0;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql = "SELECT * FROM generalacc WHERE ID = '".$rsLoan->fields(deduct)." ' "; 	
$Get =  &$conn->Execute($sql);
if ($Get->RowCount() > 0) 

$id = $Get->fields(ID);

$nameakaun = dlookup("generalacc", "name", "ID=" . tosql($Get->fields(ID), "Number"));
$codeakaun = dlookup("generalacc", "code", "ID=" . tosql($Get->fields(ID), "Number"));

$title  = 'CARTA AKAUN :- ('.$codeakaun.') - '.$nameakaun.' ';
$title = strtoupper($title);
echo $yr;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sSQL = "";
$sSQL = "SELECT	* FROM transactionacc WHERE deductID = '$id' AND docID NOT IN (15) AND (tarikh_doc BETWEEN '".$dtFrom."' AND '".$dtTo."') ORDER BY tarikh_doc ASC,docNo";
$rs = &$conn->Execute($sSQL);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$getOpen = "SELECT * FROM transactionacc WHERE docID IN (15) AND deductID = '".$id."' AND YEAR(tarikh_doc) = ".(int)substr($dtFrom,0,4)." ";
$rsOpen = $conn->Execute($getOpen);

$balanced = 0;
$balancek = 0;

if ($rsOpen->fields(addminus) == 0) {
	$balanced = $rsOpen->fields(pymtAmt);  
}

if ($rsOpen->fields(addminus) == 1) {
	$balancek = $rsOpen->fields(pymtAmt);
}



if ($rsOpen->RowCount() == 1) {
	$totalbalance = $balanced - $balancek;
}
	else $totalbalance = 0;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

print '

	<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
		<th colspan="2" height="40" align="left"><b>'.$title.'</b></th>
	</tr> 
	
	<tr>
		<td colspan="2">
			<table border=1  cellpadding="2" cellspacing="0" align=left width="100%">
				<tr bgcolor="#C0C0C0" style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold;">
					<th nowrap>Bil</th>
					<th nowrap align="center">Tarikh</th>
					<th nowrap align="left">Batch</th>
					<th nowrap align="left">Nombor Rujukan</th>
					<th nowrap align="left">Perkara</th>
					<th nowrap align="right">Debit(RM)</th>
					<th nowrap align="right">Kredit(RM)</th>
					<th nowrap align="right">Baki(RM)</th>
				</tr>';


				print '
		<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
			<td width="10%" colspan=5 align="right">&nbsp;<b>Baki H/B</b></td>
			<td width="10%" align="right">&nbsp;<b>'.number_format($balanced,2).'</b></td>
			<td width="10%" align="right">&nbsp;<b>'.number_format($balancek,2).'</b></td>
			<td width="10%" align="right">&nbsp;<b>'.number_format($totalbalance,2).'</b></td>
		</tr>';
				
				$totaldebit = 0;
				$totalkredit =0; 
				$debTkre=0;

			if ($rs->RowCount() <> 0) {	
				while(!$rs->EOF) {		

			$namabatch = dlookup("generalacc", "name", "ID=" . tosql($rs->fields(batchNo), "Number"));	

			print '
			<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
				<td width="5%" align="center">'.++$i.'.</td>
				<td width="5%" align="center">&nbsp;'.toDate('d/m/y',$rs->fields(tarikh_doc)).'</td>
				<td width="10%">'.$namabatch.'</td>
				<td width="2%">'.$rs->fields(docNo).'</td>';

				if ($rs->fields(docID)==11) 
				{
					$namaded = dlookup("general", "name", "ID=" . tosql($rs->fields(JdeductID), "Number"));	
					
					print '<td width="20%">'.$namaded.'</td>';
				}
				else 
				{ 
					print '<td width="20%">'.$rs->fields(desc_akaun).'</td>';
				}

			if ($rs->fields(addminus)==0) {

				$debit = $rs->fields(pymtAmt);
				$totaldebit += $debit;	
				
				print '<td width="5%" align="right">'.number_format($debit,2).'</td>
						<td width="5%" align="right">0.00</td>';
			}

			if ($rs->fields(addminus)==1) {

				$kredit = $rs->fields(pymtAmt);
				$totalkredit += $kredit;
				print '<td width="5%" align="right">0.00</td>
						<td width="5%" align="right">'.number_format($kredit,2).'</td>';
			}

							
			$debTkre = ($totaldebit - $totalkredit);

			$belen = ($totalbalance + $debTkre);
			
			print'	<td width="5%" align="right">'.number_format($belen,2).'</td>
			</tr>';


			
			$rs->MoveNext();
			}	

		print '
			<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;font-weight:bold;" bgcolor="FFFFFF">
			<td width="10%" colspan=4 align="right">&nbsp;</td>
			<td width="10%" align="right"><b>Jumlah </b></td>
			<td width="10%" align="right">&nbsp;'.number_format($totaldebit,2).'</td>
			<td width="10%" align="right">&nbsp;'.number_format($totalkredit,2).'</td>
			<td width="10%" align="right">&nbsp;</td>
		</tr>';



		print '
		<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
			<td width="10%" colspan=4 align="right">&nbsp;</td>
			<td width="10%" align="right">&nbsp;<b>Baki B/B</b></td>
			<td width="10%" align="left">&nbsp;</td>
			<td width="10%" align="left">&nbsp;</td>
			<td width="10%" align="right">&nbsp;<b>'.number_format($belen,2).'</b></td>
		</tr>';


					
		} else {
		print '
			<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
			<td colspan="8" align="center"><b>- Tiada Rekod Urusniaga -</b></td>
			</tr>';
		}
print '		</table></td></tr>';

$rsLoan->MoveNext();
	}

if ($rsLoan->RecordCount()<1)
print '	<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
		<td colspan="7" align="center"><b>- Tiada Rekod -</b></td>
		</tr>';
print '	
</table></body></html>';?>