<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	rptA13.php
*		   Description	:	Laporan Senarai Permohonan Koperasi Berhenti Yang Ditolak
*          Date 		: 	26/05/2006
*********************************************************************************/
session_start();
if (!isset($q))				$q = '';
if (!isset($by))			$by = '1';
if (!isset($status))		$status = '2';
if (!isset($dept))			$dept = '';



include	("common.php");
include ("koperasiinfo.php");
include ("koperasiQry.php");
$today = date("F j, Y");                 

$GetData = ctMemberTerminateStatusOk($q,$by,$status,$dept);
$title  = 'Senarai Permohonan Koperasi Berhenti Yang '.$statusList[$status];

if (get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'"); parent.location.href = "index.php";</script>';
}

print '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>'.$emaNetis.'</title>
	<LINK rel="stylesheet" href="images/default.css" >		
</head>
<body>';
print '
<form name="MyForm" action='.$PHP_SELF.' method="post">
<table border="0" cellpadding="5" cellspacing="0" width="100%">
	<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
		<td align="right">'.strtoupper($emaNetis).'</td>
	</tr>
	<tr bgcolor="#730b33" style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
		<th height="40"><font color="#FFFFFF">'.$title.' Pada '.date("d/m/Y").'
		</th>
	</tr>
	<tr>
		<td><font size=1>Cetak Pada : '.$today.'<br />Oleh : '.get_session('Cookie_fullName').'</font></td>
	</tr>
	<tr>
		<td>
			<table border=0  cellpadding="2" cellspacing="1" align=left width="100%">';
				print
				'<tr bgcolor="#C0C0C0" style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold;">
					<td nowrap>&nbsp;</td>
					<td nowrap align="left">No./Nama Koperasi</td>
					<td nowrap align="center">Jenis</td>
					<td nowrap align="center">Zon</td>
					<td nowrap align="center">Tarikh Memohon</td>
					<td nowrap align="center">Tarikh '.$statusList[$status].'</td>
				</tr>';							
				if ($GetData->RowCount() <> 0) {	
					while(!$GetData->EOF) {	
						$count++;		
						$department = dlookup("userdetails", "departmentID", "userID=" . tosql($GetData->fields(userID), "Text"));
						$jenis = dlookup("userdetails", "jenis", "userID=" . tosql($GetData->fields(userID), "Text"));
						print '
						<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
							<td width="2%" align="right">'.$count.')</td>
							<td>'.$GetData->fields('kopNum').' - '.strtoupper($GetData->fields('name')).'</td>
							<td align="center">&nbsp;'.$jenisList[$jenis].'</font></td>
							<tdalign="center">'.strtoupper(dlookup("general", "name", "ID=" . tosql($department, "Number"))).'</td>
							<td align="center">'.toDate('d/m/Y',$GetData->fields('applyDate')).'</td>
							<td align="center">'.toDate('d/m/Y',$GetData->fields('rejectedDate')).'</td>
						</tr>';
						$GetData->MoveNext();
					}	
					print '
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="7" height="30" valign="bottom">Jumlah Koperasi : <b>'.$count.'</b></td>
					</tr>					
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="7" height="30" valign="bottom">Jumlah Keseluruhan Koperasi : <b>'.$GetData->RowCount().'</b></td>
					</tr>';
				} else {
					print '
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="7" align="center"><b>- Tiada Rekod Dicetak-</b></td>
					</tr>';
				}
print 		'</table>
		</td>
	</tr>
	
</table>
</form>
</body>
</html>
<tr><td>&nbsp;</td></tr>
<center><tr><td><font size="1" color="#999999"><b>'.$retooFetis.'</b></font></td></tr></center>';
?>