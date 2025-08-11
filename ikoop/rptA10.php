<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	rptA10.php
*		   Description	:	Laporan Senarai Koperasi Staff yang Bertanggungjawab
*          Date 		: 	12/12/2003
*********************************************************************************/
session_start();
if (!isset($dept))		$dept="ALL";

include("common.php");	
include ("koperasiinfo.php");
$today = date("F j, Y, g:i a"); 
                
if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'"); parent.location.href = "index.php";</script>';
}


$title  = 'Senarai Koperasi Staff yang Bertanggungjawab';

//--- Prepare department list
$deptList = Array();
$deptVal  = Array();
$sSQL = "	SELECT a.departmentID, b.code as deptCode, b.name as deptName 
			FROM userdetails a, general b
			WHERE a.departmentID = b.ID
			AND   a.status = 1 
			GROUP BY a.departmentID";
$rs = &$conn->Execute($sSQL);
if ($rs->RowCount() <> 0){
	while (!$rs->EOF) {
		array_push ($deptList, $rs->fields(deptName));
		array_push ($deptVal, $rs->fields(departmentID));
		$rs->MoveNext();
	}
}

$sSQL = "";
$sSQL = 	"SELECT	a.name, b.kopNum, "
			."b.w_name1, b.w_jawatan1, b.w_ic1, b.w_address1, b.w_contact1, "
			."c.name as department "
		 	."FROM users a, userdetails b "
		 	."INNER JOIN general c "
		 	."ON c.ID = b.departmentID "
		 	."WHERE a.userID = b.userID "
			."AND (b.w_name1 <> '') "
		 	."AND b.status = '1'";
if ($dept <> "ALL")
	$sSQL.= " AND b.departmentID  = " .tosql($dept,"Number");		 
$sSQL.= " ORDER BY department, CAST( b.kopNum AS SIGNED INTEGER ), approvedDate DESC ";
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
<p class="textFont">Pilihan Cawangan/Kawasan/Zon
		<select name="dept" class="textFont" onchange="document.MyForm.submit();">
			<option value="ALL">- Semua -';
	for ($i = 0; $i < count($deptList); $i++) {
		print '	<option value="'.$deptVal[$i].'" ';
		if ($dept == $deptVal[$i]) print ' selected';
		print '>'.$deptList[$i];
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
							Cawangan/Kawasan/Zon : '.$rs->fields(department).'</td></tr>
							<tr bgcolor="#C0C0C0" style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold;">
								<td nowrap>&nbsp;</td>
								<td nowrap align="center">No./ID Koperasi</td>
								<td nowrap align="left">Nama Koperasi</td>
								<td nowrap align="left">Staff yang Bertanggungjawab</td>
								<td nowrap align="left">Jawatan</td>
								<td nowrap align="left">No Telefon</td>
							</tr>';							
							$bil=0;
						}
						$bil++;		
						if ($rs->fields('w_name1') <> '' ) {
							print '
							<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
								<td width="2%" align="right">'.$bil.')</td>
								<td width="8%"  align="center">'.$rs->fields('kopNum').'</td>
								<td>'.strtoupper($rs->fields('name')).'</td>
								<td>'.strtoupper($rs->fields('w_name1')).'</td>
								<td width="10%"  align="left">'.strtoupper($rs->fields('w_jawatan1')).'</td>
								<td width="10%"  align="left">'.$rs->fields('w_contact1').'</td>
							</tr>';
						}
						/* if ($rs->fields('w_name2') <> '' ) {
							print '
							<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;'.strtoupper($rs->fields('w_name2')).'&nbsp;</td>
								<td align="center">&nbsp;'.strtoupper($rs->fields('w_relation2')).'&nbsp;</td>
								<td align="left">&nbsp;'.$rs->fields('w_contact2').'</td>
							</tr>';
						}
						if ($rs->fields('w_name3') <> '' ) {
							print '
							<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;'.strtoupper($rs->fields('w_name3')).'&nbsp;</td>
								<td align="center">&nbsp;'.strtoupper($rs->fields('w_relation3')).'&nbsp;</td>
								<td align="right">&nbsp;'.$rs->fields('w_contact3').'</td>
							</tr>';
						} */
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
	
</table>
</form>
</body>
</html>
<tr><td>&nbsp;</td></tr>
<center><tr><td><font size="1" color="#999999"><b>'.$retooFetis.'</b></font></td></tr></center>';
?>