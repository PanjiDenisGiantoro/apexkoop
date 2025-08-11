<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	rptA28.php
 *          Date 		: 	26/05/2006
 *********************************************************************************/
session_start();
if (!isset($q))                $q = '';
if (!isset($by))            $by = '1';
if (!isset($status))        $status = '1';
if (!isset($dept))            $dept = '';

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
$sWhere = " a.userID = b.userID AND b.status IN (1,4) AND b.jenis = 0 AND b.cajID = 0";
$sWhere = " WHERE (" . $sWhere . ")";
$sSQL = "SELECT	DISTINCT a.*, b.*
		 FROM 	users a, userdetails b";
$sSQL = $sSQL . $sWhere;
$sSQL = $sSQL . " order by b.jenis asc";
$GetData = &$conn->Execute($sSQL);
$title  = 'Senarai Koperasi Kredit (Tidak Caj)';

print '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>' . $emaNetis . '</title>
	<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />	
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />   
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
								<td nowrap align="center">Zon Koperasi</td>
								<td nowrap align="center">Caj</td>
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
							<td align="center">' . strtoupper($deptList[array_search($GetData->fields('departmentID'), $deptVal)]) . '</td>
							<td align="center">'; ?>
							<?php
                            echo ($GetData->fields('cajID') === null) ? '' : (($GetData->fields('cajID') == 1) ? '<i class="mdi mdi-check text-primary" style="font-size: 24px;"></i>' : '<i class="mdi mdi-close text-danger" style="font-size: 24px;"></i>');
                            ?><? print '
							</td>			
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
                        print         '</table>
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
