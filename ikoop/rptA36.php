<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	rptA14.php
*          Date 		: 	26/05/2006
*********************************************************************************/
session_start();
if (!isset($q))				$q = '';
if (!isset($by))			$by = '1';
if (!isset($status))		$status = '1';
if (!isset($dept))			$dept = '';

include	("common.php");
include ("koperasiinfo.php");
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
$sWhere = " a.userID = b.userID AND b.pakej NOT IN (2016)";
$sWhere = " WHERE (" . $sWhere . ")";
$sSQL = "SELECT	DISTINCT a.*, b.*
		 FROM 	users a, userdetails b";
$sSQL = $sSQL . $sWhere;
$sSQL = $sSQL . "AND b.tempohDate BETWEEN " . tosql($dtFrom, "Text") . " AND " . tosql($dtTo, "Text") ." order by b.tempohDate ASC";
//$sSQL = $sSQL . " order by b.jenis asc";
$GetData = &$conn->Execute($sSQL);
$title  = 'Senarai Tamat Langganan Koperasi';

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
		<th height="40"><font color="#FFFFFF">'.$title.' Dari '.toDate("d/m/Y",$dtFrom).' Hingga '.toDate("d/m/Y",$dtTo).'
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
					<th nowrap>&nbsp;</th>
					
					<th nowrap align="left" width="400">No./Nama Koperasi</th>
					<th nowrap align="center">Nama Singkatan Koperasi</th>
					<th nowrap>Pakej</th>
					<th nowrap>Kod</th>
                    <th nowrap align="center">Tarikh SST Diterima</th>
					<th nowrap align="center">Tarikh Langganan</th>					
					<th nowrap align="center">Tarikh Tamat Langganan</th>			
					<th nowrap align="center">Amaun Pakej</th>
				</tr>';							
				if ($GetData->RowCount() <> 0) {	
					while(!$GetData->EOF) {	
						$count++;		
						print '
						<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
							<td align="right"  width="2%">'.$count.')</td>
							<td>'.$GetData->fields('kopNum').'-'.strtoupper($GetData->fields('name')).'</td>
							<td >'.$GetData->fields('loginID').'</td>
							<td align="center">'.dlookup("general", "name", "ID=" . tosql($GetData->fields('pakej'), "Text")).'</td>';				
							print'
							<td align="center">'.dlookup("general", "name", "ID=" . tosql($GetData->fields('jenisCode'), "Text")).'</a></td>
                            <td align="center">'.toDate('d/m/Y',$GetData->fields('approvedDate')).'</td>
							<td align="center">'.toDate('d/m/Y',$GetData->fields('langgananDate')).'</td>
							<td align="center">'.toDate('d/m/Y',$GetData->fields('tempohDate')).'</td>	
							<td >'.$GetData->fields('pakej_amaun').'</td>						
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