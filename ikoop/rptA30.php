<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	rptA30.php
 *		   Description	:	Report Senarai Kategori Penubuhan Koperasi
 *          Date 		: 	12/12/2003
 *********************************************************************************/
session_start();
if (!isset($kategori))        $kategori = "ALL";

include("common.php");
include("koperasiinfo.php");
$today = date("F j, Y");
if (get_session("Cookie_koperasiID") <> 0) {
    print '<script>alert("' . $errPage . '"); parent.location.href = "index.php";</script>';
}
$title  = 'Senarai Kategori Penubuhan Koperasi';

//--- Prepare department list
$kategoriList = array();
$kategoriVal  = array();
$sSQL = "SELECT a.kategori, b.code as pakejCode, b.name as pakejName 
         FROM userdetails a, general b
         WHERE a.kategori = b.ID
         AND   a.status = 1
         GROUP BY a.kategori";
$rs = &$conn->Execute($sSQL);
if ($rs->RowCount() <> 0) {
    while (!$rs->EOF) {
        array_push($kategoriList, $rs->fields(pakejName));
        array_push($kategoriVal, $rs->fields(kategori));
        $rs->MoveNext();
    }
}

$sSQL = "";
$sSQL = "SELECT  a.name, a.loginID, a.email, CAST( b.kopNum AS SIGNED INTEGER ) as kopNum, b.approvedDate, b.jenis, c.name as kategori
         FROM  users a, userdetails b
         INNER  JOIN general c
         ON      c.ID = b.kategori
         WHERE  a.userID = b.userID
         AND  b.status = '1'";
if ($kategori <> "ALL")
    $sSQL .= " AND b.kategori  = " . tosql($kategori, "Number");
$sSQL .= " ORDER BY kategori, kopNum, approvedDate ASC ";
$rs = &$conn->Execute($sSQL);
print '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    <title>' . $emaNetis . '</title>
    <LINK rel="stylesheet" href="images/default.css" >        
</head>
<body>';
print '
<form name="MyForm" action=' . $PHP_SELF . ' method="post">
<p class="textFont">&nbsp;Pilihan kategori
        <select name="kategori" class="textFont" onchange="document.MyForm.submit();">
            <option value="ALL">- Semua -';
for ($i = 0; $i < count($kategoriList); $i++) {
    print '    <option value="' . $kategoriVal[$i] . '" ';
    if ($kategori == $kategoriVal[$i]) print ' selected';
    print '>' . $kategoriList[$i];
}
print '    </select>
</p>
<table border="0" cellpadding="5" cellspacing="0" width="100%">
    <tr style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
        <td align="right">' . strtoupper($emaNetis) . '</td>
    </tr>
    <tr bgcolor="#730b33" style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
        <th height="40"><font color="#FFFFFF">' . $title . ' Pada ' . date("d/m/Y") . '
        </th>
    </tr>
    <tr>
        <td><font size=1>Cetak Pada : ' . $today . '<br />Oleh : ' . get_session('Cookie_fullName') . '</font></td>
    </tr>
    <tr>
        <td>
            <table border=0  cellpadding="2" cellspacing=1" align=left width="100%">';
$tempkategori = '';
if ($rs->RowCount() <> 0) {
    while (!$rs->EOF) {
        if ($tempkategori <> $rs->fields(kategori)) {
            if ($tempkategori <> "") {
                print '
                                <tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
                                    <td colspan="7" height="30" valign="bottom">Jumlah Koperasi : <b>' . $bil . '</b></td>
                                </tr>';
            }
            print '
                            <tr><td colspan="7"  style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold;" height="30" valign="bottom">
                            kategori : ' . $rs->fields(kategori) . '</td></tr>
                            <tr bgcolor="#C0C0C0" style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold;">
                                <td nowrap></td>
                                <td nowrap align="left">No./Nama Koperasi</td>
                                <td nowrap align="center">Jenis</td>
                                <td nowrap>Emel</td>
                                <td nowrap align="center">Tarikh SST Diterima</td>
                            </tr>';
            $bil = 0;
        }
        $bil++;
        print '
                        <tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
                            <td width="2%" align="right">' . $bil . ')</td>
                            <td>' . $rs->fields(kopNum) . ' - ' . $rs->fields(name) . '</a></td>
                            <td align="center">&nbsp;' . $jenisList[$rs->fields(jenis)] . '</a></td>         
                            <td>' . $rs->fields(email) . '</a></td>
                            <td align="center">' . toDate("d/m/Y", $rs->fields(approvedDate)) . '</a></td>
                        </tr>';
        $tempkategori = $rs->fields(kategori);
        $rs->MoveNext();
    }
    print '
                    <tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
                        <td colspan="7" height="30" valign="bottom">Jumlah Koperasi : <b>' . $bil . '</b></td>
                    </tr>                    
                    <tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
                        <td colspan="7" height="30" valign="bottom">Jumlah Keseluruhan Koperasi : <b>' . $rs->RowCount() . '</b></td>
                    </tr>';
} else {
    print '
                    <tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
                        <td colspan="5" align="center"><b>- Tiada Rekod Dicetak-</b></td>
                    </tr>';
}
print '          </table> 
        </td>
    </tr>
</table>
</form>
</body>
</html>
<tr><td>&nbsp;</td></tr>
<center><tr><td><font size="1" color="#999999"><b>' . $retooFetis . '</b></font></td></tr></center>';
