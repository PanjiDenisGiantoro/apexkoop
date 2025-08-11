<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	rptA20.php
 *		   Description	:	Report Senarai Koperasi Dorman Tidak Aktif
 *          Date 		: 	12/12/2021
 *********************************************************************************/
session_start();
if (!isset($jenisCode))		$jenisCode = "ALL";
if (!isset($dept))		$dept = "";

include("common.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
$today = date("F j, Y, g:i a");

if (get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("' . $errPage . '"); parent.location.href = "index.php";</script>';
}
$title  = 'Senarai Koperasi Tidak Aktif (TIDAK DORMAN)';

$deptList = array();
$deptVal  = array();

$jenisCodeList = array();
$jenisCodeVal  = array();

// Query for deptList
$sSQLDept = "SELECT a.departmentID, b.code as deptCode, b.name as deptName, c.loginID
         FROM userdetails a
         INNER JOIN general b ON a.departmentID = b.ID
         INNER JOIN users c ON a.userID = c.userID
         GROUP BY a.departmentID";

$rsDept = &$conn->Execute($sSQLDept);
if ($rsDept->RowCount() <> 0) {
	while (!$rsDept->EOF) {
		array_push($deptList, $rsDept->fields(deptName));
		array_push($deptVal, $rsDept->fields(departmentID));
		$rsDept->MoveNext();
	}
}


$sSQL = "	SELECT a.jenisCode, b.code as deptCode, b.name as deptName 
			FROM userdetails a, general b
			WHERE a.jenisCode = b.ID
			GROUP BY a.jenisCode";
$rs = &$conn->Execute($sSQL);
if ($rs->RowCount() <> 0) {
	while (!$rs->EOF) {
		array_push($jenisCodeList, $rs->fields(deptName));
		array_push($jenisCodeVal, $rs->fields(jenisCode));
		$rs->MoveNext();
	}
}

// Retrieve dorman data using JOINs
$sSQLDorman = "SELECT a.departmentID, b.code AS deptCode, b.name AS deptName, c.loginID, d.dorman
               FROM userdetails a
               INNER JOIN general b ON a.departmentID = b.ID
               INNER JOIN users c ON a.userID = c.userID
               INNER JOIN usersdetails d ON a.userID = d.userID
               GROUP BY a.departmentID";

$rsDorman = $conn->Execute($sSQLDorman);

if ($rsDorman && $rsDorman->RowCount() > 0) {
	while (!$rsDorman->EOF) {
		$deptList[] = $rsDorman->fields('deptName');
		$deptVal[] = $rsDorman->fields('departmentID');
		$dormanList[] = $rsDorman->fields('dorman');
		$rsDorman->MoveNext();
	}
}

$sSQL = "";
$sSQL = "SELECT	a.*, CAST(b.kopNum AS SIGNED INTEGER) as kopNum, c.name as department , b.*
		 FROM 	users a, userdetails b
		 INNER JOIN general c
		 ON		c.ID = b.jenisCode 
		 WHERE  a.userID = b.userID 
		 AND b.dorman = 0";

if ($jenisCode <> "ALL")
	$sSQL .= " AND b.jenisCode  = " . tosql($jenisCode, "Number");

$sSQL .= " ORDER BY department, kopNum, approvedDate ASC ";
$rs = &$conn->Execute($sSQL);
print '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>' . $emaNetis . '</title>
	<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
</head>
<body>';
print '
<form name="MyForm" action=' . $PHP_SELF . ' method="post">
<p class="textFont">&nbsp;Pilihan Jenis Kod
		<select name="jenisCode" class="form-select-xs" onchange="document.MyForm.submit();">
			<option value="ALL">- Semua -';
for ($i = 0; $i < count($jenisCodeList); $i++) {
	print '	<option value="' . $jenisCodeVal[$i] . '" ';
	if ($jenisCode == $jenisCodeVal[$i]) print ' selected';
	print '>' . $jenisCodeList[$i];
}
print '	</select>
</p>
<table border="0" cellpadding="5" cellspacing="0" width="100%">
	<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
		<td align="right">' . strtoupper($emaNetis) . '</td>
	</tr>
	<tr bgcolor="#730b33" style="font-family: Arial, Helvetica, sans-serif; font-weight: bold;">
		<td height="40" align="center"><font color="#FFFFFF">' . $title . ' Pada ' . date("d/m/Y") . '
		</td>
	</tr>
	<tr>
		<td><font size=1>Cetak Pada : ' . $today . '<br />Oleh : ' . get_session('Cookie_fullName') . '</font></td>
	</tr>
	<tr>
		<td>
			<table class="table table-sm table-striped">';
$tempDept = '';
if ($rs->RowCount() <> 0) {
	while (!$rs->EOF) {
		if ($tempDept <> $rs->fields(department)) {
			if ($tempDept <> "") {
				print '
								<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
									<td colspan="32" height="30" valign="bottom">Jumlah Koperasi : <b>' . $bil . '</b></td>
								</tr>';
			}
			print '
							<tr><td colspan="32"  style="font-family: Arial, Helvetica, sans-serif; font-size: 10pt; font-weight: bold;" height="30" valign="bottom">
							Zon : ' . $rs->fields(department) . '</td></tr>
							<tr class="table-danger" style="font-family: Arial, Helvetica, sans-serif; font-size: 10pt; font-weight: bold;">
								<td nowrap>&nbsp;</td>					
								<td nowrap align="center" nowrap>No. ID Koperasi</td>
								<td nowrap align="left">Nama Penuh Koperasi</td>
								<td nowrap align="center">Singkatan Koperasi</td>
								<td nowrap>Emel Koperasi</td>
								<td nowrap align="center">No. Telefon Koperasi</td>
								<td nowrap align="center">Tarikh SST Diterima</td>
								<td nowrap align="center">Tarikh Ditubuhkan</td>
								<td nowrap>Alamat Koperasi</td>
								<td nowrap align="center">Negeri Koperasi</td>
								<td nowrap align="center">Poskod Koperasi</td>
								<td nowrap align="centers">Zon Koperasi</td>
								<td nowrap align="center">No. Faks Koperasi</td>
								<td nowrap align="center">Yuran Terkumpul (RM)</td>
								<td nowrap align="center">Syer Terkumpul (RM)</td>
								<td nowrap align="center">Bilangan Anggota</td>
								<td nowrap align="center">Jenis Kod</td>
								<td nowrap align="center">Pakej</td>
								<td nowrap align="center">Kategori</td>
								<td nowrap align="center">Jenis Koperasi</td>
								<td nowrap align="center">Dorman</td>
								<td nowrap align="center">FPX</td>
								<td nowrap align="center">Status Senarai Hitam</td>
								<td nowrap align="center">Caj</td>
								<td nowrap align="center">Modul</td>
								<td nowrap>Nama Staf</td>
								<td nowrap align="center">Kad Pengenalan Staf</td>
								<td nowrap>Emel Staf</td>
								<td nowrap align="center">No. Telefon Staf</td>
								<td nowrap>Jawatan Staf</td>
								<td nowrap>Nama Bank Koperasi</td>
								<td nowrap>No. Akaun Bank Koperasi</td>	
							</tr>';
			$bil = 0;
		}
		$bil++;
		print '
						<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 10pt;">
							<td align="right">' . $bil . '.</td>
							<td align="center">' . $rs->fields('kopNum') . '</td>
							<td>' . $rs->fields('name') . '</td>
							<td align="center">' . $rs->fields('loginID') . '</td>
							<td align="left">' . $rs->fields('email') . '</td>
							<td align="center">' . sprintf('%s', $rs->fields('mobileNo')) . '</td>
							<td align="center">' . toDate('d/m/Y', $rs->fields('approvedDate')) . '</td>
							<td align="center">' . toDate('d/m/Y', $rs->fields('dateBirth')) . '</td>
							<td>';
		$stradd = str_replace("<pre>", "", $rs->fields(address));
		$stradd = str_replace("</pre>", "", $stradd);
		echo $stradd;
		print '</td>
							<td align="center">' . dlookup("general", "name", "ID=" . tosql($rs->fields('stateID'), "Number")) . '</td>
							<td align="center">' . $rs->fields('poskod') . '</td>
							<td align="center">' . strtoupper($deptList[array_search($rs->fields('departmentID'), $deptVal)]) . '</td>
							<td align="center">' . $rs->fields('fax') . '</td>
							<td align="right">' . $rs->fields('totalFee') . '</td>
							<td align="right">' . $rs->fields('totalShare') . '</td>
							<td align="center">' . $rs->fields('staftNo') . '</td>
							<td align="center">' . dlookup("general", "name", "ID=" . tosql($rs->fields('jenisCode'), "Number")) . '</td>
							<td align="center">' . dlookup("general", "name", "ID=" . tosql($rs->fields('pakej'), "Number")) . '</td>
							<td align="center">' . dlookup("general", "name", "ID=" . tosql($rs->fields('kategori'), "Number")) . '</td>
							<td align="center">' . $jenisList[$rs->fields(jenis)] . '</td>
							<td align="center">'; ?>
							<?php
							echo ($rs->fields('dorman') == 1) ? 'Aktif' : 'Tidak Aktif';
							?><? print '
							</td>
							<td align="center">'; ?><?php echo ($rs->fields('guna_fpx') == 1) ? 'Ya' : 'Tidak'; ?><? print '</td>	
							<td align="center">'; ?>
							<?php
							echo ($rs->fields('BlackListID') == 1) ? 'Ya' : 'Tidak';
							?><? print '
							</td>	
							<td align="center">'; ?>
							<?php
							echo ($rs->fields('cajID') === null) ? '' : (($rs->fields('cajID') == 1) ? 'Ya' : 'Tidak');
							?><? print '
							</td>			
							<td align="center">';
								?>
							<?php
							$values = array();

							if ($rs->fields('checkA') == "on") {
								$values[] = 'Anggota';
							}
							if ($rs->fields('checkB') == "on") {
								$values[] = 'Pembiayaan';
							}
							if ($rs->fields('checkC') == "on") {
								$values[] = 'Akaun';
							}

							echo implode(", ", $values);
							?><? print '
						</td>
						<td align="left">' . $rs->fields('w_name1') . '</td>
						<td align="center">' . $rs->fields('w_ic1') . '</td>
						<td align="left">' . $rs->fields('w_email1') . '</td>
						<td align="center">' . sprintf('%s', $rs->fields('w_contact1')) . '</td>
						<td align="left">' . $rs->fields('w_jawatan1') . '</td>
						<td align="center">' . dlookup("general", "name", "ID=" . tosql($rs->fields('bankID'), "Number")) . '</td>
						<td align="left">' . $rs->fields('accTabungan') . '</td>
						</tr>';

								$tempDept = $rs->fields(department);
								$rs->MoveNext();
							}
							print '		
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="32" height="30" valign="bottom">Jumlah Keseluruhan Koperasi : <b>' . $rs->RowCount() . '</b></td>
					</tr>';
						} else {
							print '
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="32" align="center"><b>- Tiada Rekod Dicetak-</b></td>
					</tr>';
						}
						print '		</table> 
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
    <td colspan="32" align="center">
        <center>
            <font size="1" color="#999999">
                <b>';
						echo $retooFetis;
						print '</b>
            </font>
        </center>
    </td>
</tr>
</table>
</form>
</body>
</html>';
