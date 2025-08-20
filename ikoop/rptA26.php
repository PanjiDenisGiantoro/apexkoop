<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	rptA20.php
*		   Description	:	Report Senarai Koperasi Terlatih
*          Date 		: 	12/12/2021
*********************************************************************************/
session_start();
if (!isset($fasa))		$fasa="ALL";
if (!isset($dept))		$dept="";

include("common.php");	
date_default_timezone_set("Asia/Jakarta");
$today = date("F j, Y, g:i a");     

if (get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'"); parent.location.href = "index.php";</script>';
}
$title  = 'Senarai Koperasi Terlatih';

$deptList = Array();
$deptVal  = Array();

$fasaList = Array();
$fasaVal  = Array();

// Query for deptList
$sSQLDept = "SELECT a.departmentID, b.code as deptCode, b.name as deptName, c.loginID
         FROM userdetails a
         INNER JOIN general b ON a.departmentID = b.ID
         INNER JOIN users c ON a.userID = c.userID
         GROUP BY a.departmentID";

$rsDept = &$conn->Execute($sSQLDept);
if ($rsDept->RowCount() <> 0){
	while (!$rsDept->EOF) {
		array_push ($deptList, $rsDept->fields(deptName));
		array_push ($deptVal, $rsDept->fields(departmentID));
		$rsDept->MoveNext();
	}
}


$sSQL = "	SELECT a.fasa, b.code as deptCode, b.name as deptName 
			FROM userdetails a, general b
			WHERE a.fasa = b.ID
			GROUP BY a.fasa";
$rs = &$conn->Execute($sSQL);
if ($rs->RowCount() <> 0){
	while (!$rs->EOF) {
		array_push ($fasaList, $rs->fields(deptName));
		array_push ($fasaVal, $rs->fields(fasa));
		$rs->MoveNext();
	}
}

$sSQL = "";
$sSQL = "SELECT	a.name,CAST(b.kopNum AS SIGNED INTEGER) as kopNum, b.approvedDate, b.jenis, c.name as department , b.training, b.fasa, b.departmentID, a.email
		 FROM 	users a, userdetails b
		 INNER JOIN general c
		 ON		c.ID = b.fasa 
		 WHERE  a.userID = b.userID 
		 AND b.training = 1"; 

if ($fasa <> "ALL")
	$sSQL.= " AND b.fasa  = " .tosql($fasa,"Number");	

$sSQL.= " ORDER BY department, kopNum, approvedDate ASC ";
$rs = &$conn->Execute($sSQL);
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
<p class="textFont">&nbsp;Pilihan Fasa
		<select name="fasa" class="textFont" onchange="document.MyForm.submit();">
			<option value="ALL">- Semua -';
	for ($i = 0; $i < count($fasaList); $i++) {
		print '	<option value="'.$fasaVal[$i].'" ';
		if ($fasa == $fasaVal[$i]) print ' selected';
		print '>'.$fasaList[$i];
	}
print '	</select>
</p>
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
				$tempDept = '';
				if ($rs->RowCount() <> 0) {	
					while(!$rs->EOF) {	
						if ($tempDept <> $rs->fields(department)) {
							if ($tempDept <> "") {
								print '
								<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
									<td colspan="7" height="30" valign="bottom">Jumlah Koperasi : <b>'.$bil.'</b></td>
								</tr>';
							}
							print '
							<tr><td colspan="7"  style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold;" height="30" valign="bottom">
							Zon : '.$rs->fields(department).'</td></tr>
							<tr bgcolor="#C0C0C0" style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold;">
								<th nowrap>&nbsp;</th>
								<th nowrap align="left">&nbsp;No./Nama Koperasi</th>
								<th nowrap width="150">&nbsp;Jenis</th>
								<th nowrap width="150">&nbsp;Zon</th>
								<td nowrap>Emel</td>
								<th nowrap align="center" width="150">&nbsp;Tarikh SST Diterima</th>
							</tr>';							
							$bil=0;
						}
						$bil++;		
						print '
						<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
							<td width="2%" align="right">'.$bil.')&nbsp;</td>
							<td>&nbsp;'.$rs->fields(kopNum).' - '.$rs->fields(name).'</a></td>
							<td align="center">&nbsp;'.$jenisList[$rs->fields(jenis)].'</a></td>
							<td align="center">&nbsp;'.dlookup("general", "name", "ID=" . tosql($rs->fields('departmentID'), "Number")).'</td>
							<td>'.$rs->fields(email).'</a></td>
							<td align="center">&nbsp;'.toDate("d/m/Y",$rs->fields(approvedDate)).'</a></td>
						</tr>';
						$tempDept = $rs->fields(department);
						$rs->MoveNext();
					}	
					print '
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="7" height="30" valign="bottom">Jumlah Koperasi : <b>'.$bil.'</b></td>
					</tr>					
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="7" height="30" valign="bottom">Jumlah Keseluruhan Koperasi : <b>'.$rs->RowCount().'</b></td>
					</tr>';
				} else {
					print '
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="5" align="center"><b>- Tiada Rekod Dicetak-</b></td>
					</tr>';
				}
print '		</table> 
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td><font size="1" color="#999999"><b>'.$retooFetis.'</b></font></td></tr>	
</table>
</form>
</body>
</html>';
?>