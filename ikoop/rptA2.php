<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	rptA2.php
 *		   Description	:	Report Status Koperasi SST Diterima 
 *		   Parameter	:   $dateFrom , $dateTo
 *          Date 		: 	12/12/2003
 *********************************************************************************/
session_start();
include("common.php");
include("setupinfo.php");
$today = date("F j, Y");

if (get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("' . $errPage . '"); parent.location.href = "index.php";</script>';
}

$code 	= "B";
$title  = 'SST Diterima Koperasi';

$sSQL = "";
$sSQL = "SELECT	a.name, a.loginID, b.userID, b.kopNum, b.jenis,b.staftNo , a.applyDate, b.approvedDate, c.name as department  
		 FROM 	users a, userdetails b
		 INNER JOIN general c
		 ON		c.ID = b.departmentID 
		 WHERE  a.userID = b.userID 
		 AND 	b.status = '1'  
		 AND	approvedDate >= " . tosql($dtFrom, "Text") . "
		 AND	approvedDate <= " . tosql($dtTo, "Text") . "
		 ORDER BY CAST( b.kopNum AS SIGNED INTEGER ), department, approvedDate DESC ";
$rs = &$conn->Execute($sSQL);
print '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>' . $emaNetis . '</title>
</head>
<body>'; //bgcolor="008080"
print '
<table border="0" cellpadding="5" cellspacing="0" width="100%">
	<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
		<td colspan="7" align="right">' . strtoupper($emaNetis) . '</td>
	</tr> 
	<tr bgcolor="730b33" style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
		<th colspan="85 height="40"><font color="#FFFFFF">' . $title . '<br>
			Dari ' . toDate("d/m/Y", $dtFrom) . ' Hingga ' . toDate("d/m/Y", $dtTo) . '</font>
		</th>
	</tr>
	<tr>
		<td colspan="7"><font size=1>Cetak Pada : ' . $today . '<br />Oleh : ' . get_session('Cookie_fullName') . '</font></td>
	</tr>
	<tr><td colspan="7">&nbsp;</td></tr>
	<tr>
		<td colspan="7">
			<table border=0  cellpadding="2" cellspacing="1" align=left width="100%" >
				<tr bgcolor="#C0C0C0" style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold;">
					<td nowrap>&nbsp;</td>
					<td nowrap><b>No./Nama Koperasi</b></td>
					<td nowrap align="center"><b>Singkatan Koperasi</b></td>
					<td nowrap align="center"><b>Jenis Koperasi</b></td>
					<td nowrap align="center"><b>Kod</b></td>
					<td nowrap align="center"><b>Bilangan Anggota</b></td>
					<!-- <td nowrap align="right"><b>Yuran Bulanan (RM)</b></td> -->
					<td nowrap align="center"><b>Zon</b></td>
					<td nowrap align="center"><b>Tarikh Permohonan</b></td>
					<td nowrap align="center"><b>Tarikh SST Diterima</b></td>
				</tr>';
if ($rs->RowCount() <> 0) {
	while (!$rs->EOF) {
		$bil++;

		$jenisCode1 = dlookup("userdetails", "jenisCode", "userID=" . tosql($rs->fields(userID), "Text"));
		$jenisCode = dlookup("general", "name", "ID=" . tosql($jenisCode1, "Text"));
		print '
						<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
							<td width="2%" align="center">' . $bil . ')</td>
							<td>' . $rs->fields(kopNum) . ' - ' . $rs->fields(name) . '</a></td>
							<td align="center">' . $rs->fields(loginID) . '</a></td>
							<td align="center">' . $jenisList[$rs->fields(jenis)] . '</a></td>
							<td align="center">' . $jenisCode . '</a></td>
							<td align="center">' . $rs->fields(staftNo) . '</a></td>
							<!-- <td align="right">' . $rs->fields(monthFee) . ' </a></td> -->
							<td align="center">' . $rs->fields(department) . ' </a></td>
							<td align="center">' . toDate("d/m/Y", $rs->fields(applyDate)) . '</a></td>
							<td align="center">' . toDate("d/m/Y", $rs->fields(approvedDate)) . '</a></td>
						</tr>';
		$rs->MoveNext();
	}
} else {
	print '
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="9" align="center"><b>- Tiada Rekod Dicetak-</b></td>
					</tr>';
}
print '		</table> 
		</td>
	</tr>
	
</table>
</body>
</html>
<tr><td colspan="7">&nbsp;</td></tr>
<center><tr><td colspan="7"><font size="1" color="#999999"><b>' . $retooFetis . '</b></font></td></tr></center>';
