<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	rptA11.php
*		   Description	:	Laporan Senarai Permohonan Koperasi Berhenti Yang Diluluskan
*          Date 		: 	26/05/2006
*********************************************************************************/
session_start();
if (!isset($q))				$q = '';
if (!isset($by))			$by = '1';
if (!isset($status))		$status = '3'; 
if (!isset($dept))			$dept = 'ALL';

include("common.php");
include("koperasiinfo.php");
include("koperasiQry.php");
$today = date("F j, Y, g:i a");

// Ambil parameter dari GET
$q = isset($_GET['q']) ? $_GET['q'] : '';
$by = isset($_GET['by']) ? $_GET['by'] : 1;
$dept = isset($_GET['dept']) ? $_GET['dept'] : 'ALL';
$dtFrom = isset($_GET['dtFrom']) ? $_GET['dtFrom'] : '';
$dtTo = isset($_GET['dtTo']) ? $_GET['dtTo'] : '';

// Dapatkan data status ditolak
$GetData = ctTugasanKoperasiStatusOk($q, $by, $dept, $dtFrom, $dtTo);


$title = 'Senarai Koperasi Dengan Status ' . $tugasanList[$status];

// Check if session is valid
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
            <table border=0 cellpadding="2" cellspacing="1" align=left width="100%">';
                print
                '<tr bgcolor="#C0C0C0" style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold;">
                    <td nowrap>&nbsp;</td>
                    <td nowrap align="left">No./Nama Koperasi</td>
                    <td nowrap align="center">Masalah</td>
                    <td nowrap align="center">Orang Yang Bertugas</td>
                    <td nowrap align="center">Keterangan</td>
                    <td nowrap align="center">Tarikh Daftar</td>
                    <td nowrap align="center">Tarikh '.$tugasanList[$status].'</td>
                </tr>';
                if ($GetData->RowCount() > 0) {  
                    $count = 0;
                    while (!$GetData->EOF) {
                        $count++;
                        print '
                        <tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="#FFFFFF">
							<td width="2%" align="center">' . $count . ')</td>
							<td align="left">' . htmlspecialchars($GetData->fields('kopNum') . ' - ' . strtoupper($GetData->fields('name'))) . '</td>
							<td align="center">' . htmlspecialchars($GetData->fields('title_problem')) . '</td>
							<td align="center">' . htmlspecialchars($GetData->fields('person_in_charge')) . '</td>
							<td align="center">' . htmlspecialchars($GetData->fields('keterangan')) . '</td>
							<td align="center">' . toDate('d/m/Y', $GetData->fields('startDate')) . '</td>
							<td align="center">' . toDate('d/m/Y', $GetData->fields('rejectedDate')) . '</td>
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
                    <tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="#FFFFFF">
                        <td colspan="8" align="center"><b>- Tiada Rekod Dicetak -</b></td>
                    </tr>';
                }

print       '</table>
        </td>
    </tr>
</table>
</form>
</body>
</html>
<tr><td>&nbsp;</td></tr>
<center><tr><td><font size="1" color="#999999"><b>'.$retooFetis.'</b></font></td></tr></center>';
?>