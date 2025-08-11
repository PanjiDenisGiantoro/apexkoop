<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	rptA31.php
 *          Date 		: 	26/05/2006
 *********************************************************************************/
session_start();
if (!isset($q))				$q = '';
if (!isset($by))			$by = '1';
if (!isset($status))		$status = '1';
if (!isset($dept))			$dept = '';

include("common.php");
include("koperasiinfo.php");
include("koperasiQry.php");
$today = date("F j, Y");

if (get_session("Cookie_groupID") <> 1 and get_session("Cookie_groupID") <> 2 and get_session("Cookie_groupID") <> 3 and get_session("Cookie_groupID") <> 4 or get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("' . $errPage . '"); parent.location.href = "index.php";</script>';
}

//--- Prepare state type
$stateList = array();
$stateVal  = array();
$GetState = ctGeneral("", "H");
if ($GetState->RowCount() <> 0) {
	while (!$GetState->EOF) {
		array_push($stateList, $GetState->fields(name));
		array_push($stateVal, $GetState->fields(ID));
		$GetState->MoveNext();
	}
}

//--- Prepare department type
$deptList = array();
$deptVal  = array();
$GetDept = ctGeneral("", "B");
if ($GetDept->RowCount() <> 0) {
	while (!$GetDept->EOF) {
		array_push($deptList, $GetDept->fields(name));
		array_push($deptVal, $GetDept->fields(ID));
		$GetDept->MoveNext();
	}
}


$sSQL = "";
$sWhere = " a.userID = b.userID AND b.status IN (1,4) AND b.jenis = 1 AND b.dorman = 0";
$sWhere = " WHERE (" . $sWhere . ")";
$sSQL = "SELECT	DISTINCT a.*, b.*
		 FROM 	users a, userdetails b";
$sSQL = $sSQL . $sWhere;
//$sSQL = $sSQL . " order by CAST( b.kopNum AS SIGNED INTEGER )";
$sSQL = $sSQL . " order by b.jenis asc";
$GetData = &$conn->Execute($sSQL);
$title  = 'Senarai Koperasi Bukan Kredit (TIDAK DORMAN)';

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
print
	'<tr class="table-danger" style="font-family: Arial, Helvetica, sans-serif; font-size: 10pt; font-weight: bold;">
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
if ($GetData->RowCount() <> 0) {
	while (!$GetData->EOF) {
		$count++;
		print '
						<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 10pt;">
							<td align="right">' . $count . '.</td>
							<td align="center">' . $GetData->fields('kopNum') . '</td>
							<td>' . $GetData->fields('name') . '</td>
							<td align="center">' . $GetData->fields('loginID') . '</td>
							<td align="left">' . $GetData->fields('email') . '</td>
							<td align="center">' . sprintf('%s', $GetData->fields('mobileNo')) . '</td>
							<td align="center">' . toDate('d/m/Y', $GetData->fields('approvedDate')) . '</td>
							<td align="center">' . toDate('d/m/Y', $GetData->fields('dateBirth')) . '</td>
							<td>';
		$stradd = str_replace("<pre>", "", $GetData->fields(address));
		$stradd = str_replace("</pre>", "", $stradd);
		echo $stradd;
		print '</td>
							<td align="center">' . dlookup("general", "name", "ID=" . tosql($GetData->fields('stateID'), "Number")) . '</td>
							<td align="center">' . $GetData->fields('poskod') . '</td>
							<td align="center">' . strtoupper($deptList[array_search($GetData->fields('departmentID'), $deptVal)]) . '</td>
							<td align="center">' . $GetData->fields('fax') . '</td>
							<td align="right">' . $GetData->fields('totalFee') . '</td>
							<td align="right">' . $GetData->fields('totalShare') . '</td>
							<td align="center">' . $GetData->fields('staftNo') . '</td>
							<td align="center">' . dlookup("general", "name", "ID=" . tosql($GetData->fields('jenisCode'), "Number")) . '</td>
							<td align="center">' . dlookup("general", "name", "ID=" . tosql($GetData->fields('pakej'), "Number")) . '</td>
							<td align="center">' . dlookup("general", "name", "ID=" . tosql($GetData->fields('kategori'), "Number")) . '</td>
							<td align="center">' . $jenisList[$GetData->fields(jenis)] . '</td>
							<td align="center">'; ?>
							<?php
							echo ($GetData->fields('dorman') == 1) ? 'Aktif' : 'Tidak Aktif';
							?><? print '
							</td>
							<td align="center">'; ?><?php echo ($GetData->fields('guna_fpx') == 1) ? 'Ya' : 'Tidak'; ?><? print '</td>	
							<td align="center">'; ?>
							<?php
							echo ($GetData->fields('BlackListID') == 1) ? 'Ya' : 'Tidak';
							?><? print '
							</td>	
							<td align="center">'; ?>
							<?php
							echo ($GetData->fields('cajID') === null) ? '' : (($GetData->fields('cajID') == 1) ? 'Ya' : 'Tidak');
							?><? print '
							</td>			
							<td align="center">';
								?>
							<?php
							$values = array();

							if ($GetData->fields('checkA') == "on") {
								$values[] = 'Anggota';
							}
							if ($GetData->fields('checkB') == "on") {
								$values[] = 'Pembiayaan';
							}
							if ($GetData->fields('checkC') == "on") {
								$values[] = 'Akaun';
							}

							echo implode(", ", $values);
							?><? print '
						</td>
						<td align="left">' . $GetData->fields('w_name1') . '</td>
						<td align="center">' . $GetData->fields('w_ic1') . '</td>
						<td align="left">' . $GetData->fields('w_email1') . '</td>
						<td align="center">' . sprintf('%s', $GetData->fields('w_contact1')) . '</td>
						<td align="left">' . $GetData->fields('w_jawatan1') . '</td>
						<td align="center">' . dlookup("general", "name", "ID=" . tosql($GetData->fields('bankID'), "Number")) . '</td>
						<td align="left">' . $GetData->fields('accTabungan') . '</td>
						</tr>';
								$GetData->MoveNext();
							}
							print '			
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="32" height="30" valign="bottom">Jumlah Keseluruhan Koperasi : <b>' . $GetData->RowCount() . '</b></td>
					</tr>';
						} else {
							print '
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="32" align="center"><b>- Tiada Rekod Dicetak-</b></td>
					</tr>';
						}
						print 		'</table>
		</td>
	</tr>
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
