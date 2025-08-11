<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	rptA14.php
*		   Description	:	
*          Date 		: 	26/05/2006
*********************************************************************************/
session_start();
if (!isset($q))				$q = '';
if (!isset($by))			$by = '1';
if (!isset($status))		$status = '1';
if (!isset($dept))			$dept = '';

include	("common.php");
include ("koperasiQry.php");
$today = date("F j, Y");                 
if (get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'"); parent.location.href = "index.php";</script>';
}
//--- Prepare state type
$stateList = Array();
$stateVal  = Array();
$GetState = ctGeneral("","H");
if ($GetState->RowCount() <> 0){
	while (!$GetState->EOF) {
		array_push ($stateList, $GetState->fields(name));
		array_push ($stateVal, $GetState->fields(ID));
		$GetState->MoveNext();
	}
}	

//--- Prepare department type
$deptList = Array();
$deptVal  = Array();
$GetDept = ctGeneral("","B");
if ($GetDept->RowCount() <> 0){
	while (!$GetDept->EOF) {
		array_push ($deptList, $GetDept->fields(name));
		array_push ($deptVal, $GetDept->fields(ID));
		$GetDept->MoveNext();
	}
}	

$sSQL = "";
$sWhere = " a.userID = b.userID AND b.status IN (1,4) ";
$sWhere = " WHERE (" . $sWhere . ")";
$sSQL = "SELECT	DISTINCT a.*, b.*
		 FROM 	users a, userdetails b";
$sSQL = $sSQL . $sWhere;
$sSQL = $sSQL . " order by CAST( b.kopNum AS SIGNED INTEGER )";
$GetData = &$conn->Execute($sSQL);

$sSQL2 = "";
$sWhere2 = " status = 3 group by userID ";
$sWhere2 = " WHERE (" . $sWhere2 . ")";
$sSQL2 = "SELECT	userID,status
		 FROM 	loans ";
$sSQL2 = $sSQL2 . $sWhere2;
$sSQL2 = $sSQL2 . " order by CAST( userID AS SIGNED INTEGER )";
$GetData2 = &$conn->Execute($sSQL2);


$title  = 'Senarai Koperasi Yang Tiada Pembiayaan (Loan)';

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
					<td nowrap align="left"><b>No./Nama Koperasi</b></td>
					<td nowrap align="center"><b>Jenis</b></td>
					<td nowrap align="center"><b>Zon</b></td>
					<td nowrap align="center" width="150"><b>Tarikh SST Diterima</b></td>
					<td nowrap align="center"><b>Status</b></td>
				</tr>';							
				if ($GetData->RowCount() <> 0) {	
					while(!$GetData->EOF) {	
					
					$sSQL2 = "SELECT	* FROM loans 
		 WHERE	userID = '".$GetData->fields('kopNum')."' AND status = 3
		 ORDER BY applyDate DESC";
      $GetLoan2 = &$conn->Execute($sSQL2);
					if($GetLoan2->fields('status') == 3){ $countAktif++; $statusRed = "Masih Aktif Loan"; $colorStatus = "greenText";  }else
 {$statusRed = "TIADA LOAN"; $colorStatus = "redText"; $countTiada++; }						
 $count++;		
						print '
						<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
							<td align="right" valign="top" width="2%">'.$count.')</td>
							<td valign="top">'.$GetData->fields('kopNum').' - '.strtoupper($GetData->fields('name')).'</td>
							<td align="center">'.$jenisList[$GetData->fields(jenis)].'</a></td>
							<td align="center">'.strtoupper($deptList[array_search($GetData->fields('departmentID'),$deptVal)]).'</td>
							<td align="center" valign="top">'.toDate('d/m/Y',$GetData->fields('approvedDate')).'</td>
				        <td align="center"><font class="'.$colorStatus.'">'.$statusRed.'</font></td>
						</tr>';
						$GetData->MoveNext();
					}	
					print '
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="7" height="30" valign="bottom">Jumlah Koperasi : <b>'.$count.'</b></td>
					</tr>';	
					if (!empty($countAktif) && $countAktif > 0) {
						print '
						<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
							<td colspan="7" height="30" valign="bottom">Jumlah Koperasi Masih Aktif Loan : <b>'.$countAktif.'</b></td>
						</tr>';
					} else {
						print '
						<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
							<td colspan="7" height="30" valign="bottom">Jumlah Koperasi Masih Aktif Loan : <b>0</b></td>
						</tr>';
					}
					print '
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="7" height="30" valign="bottom">Jumlah Koperasi TIADA LOAN : <b>'.$countTiada.'</b></td>
					</tr>				
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="7" height="30" valign="bottom">Jumlah Keseluruhan Koperasi : <b>'.$GetData->RowCount().'</b></td>
					</tr>';
				} else {
					print '
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="5" align="center"><b>- Tiada Rekod Dicetak-</b></td>
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