<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	rptAsas.php
*		   Description	:	Report Informasi Asas
*********************************************************************************/
session_start();
include("common.php");		
include("koperasiQry.php");	
$today = date("F j, Y,");                 
if (get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'"); parent.location.href = "index.php";</script>';
}
if (!(in_array($code,$basicVal))) {
	print '	<script>
				alert ("'.$code.' - Kod Asas ini tidak wujud...!");
				window.location = "index.php";
			</script>';
}
$title  = $basicList[array_search($code,$basicVal)];

$GetList = ctGeneral("",$code);

print '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>'.$emaNetis.'</title>
</head>
<body>';
print '
<table border="0" cellpadding="5" cellspacing="0" width="100%">
	<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
		<td align="right">'.strtoupper($emaNetis).'</td>
	</tr>
	<tr bgcolor="#730b33" style="font-family: Arial, Helvetica, sans-serif; font-size: 9pt; font-weight: bold;">
		<th height="40"><font color="#FFFFFF">Maklumat Asas - '.$title.' Pada '.date("d/m/Y").'
		</th>
	</tr>
	<tr>
		<td><font size=1>Cetak Pada : '.$today.'<br />Oleh : '.get_session('Cookie_fullName').'</font></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td>
			<table border=0  cellpadding="2" cellspacing="1" align=left width="100%" bgcolor="999999">
				<tr bgcolor="#C0C0C0" style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt; font-weight: bold;">
					<td nowrap>&nbsp;</td>
					<td nowrap width="150" align="left">Kod</td>
					<td nowrap align="left">Nama</td>';
$noSpan = 3;
/* if ($code == 'B') {
	$noSpan = 4;
	print '			<th nowrap align="center">Majikan Induk</td>';
} */
if ($code == 'C') {
	$noSpan = 8;
	print '			<td nowrap align="left">Kod Objek</td>
					<td nowrap align="left">Kod Akaun</td>
					<td nowrap align="left">Nama Kod</td>
					<td nowrap align="right">Caj(%)</td>
					<td nowrap align="right">Tempoh Maksima</td>
					<td nowrap align="right">Jumlah Maksima (RM)</td>
					<td nowrap align="center">Penjamin</td>';
}
if ($code == 'D') {
	$noSpan = 7;
	print '			<td nowrap>Jenis</td>
					<td nowrap>Alamat</td>
					<td nowrap>Dihubungi</td>
					<td nowrap>No. Telefon</td>';
}
/* if ($code == 'G') {
	$noSpan = 6;
	print '			<td nowrap align="right">Harga Syer (RM)</td>
					<td nowrap align="right">&Minimum Unit</td>
					<td nowrap align="right">Jumlah Unit Syer (RM)</td>';
} */
if ($code == 'J') {
	$noSpan = 4;
	print '			<td nowrap align="left">Kod Akaun</td>';
}
if ($code == 'M') {
	$noSpan = 5;
	print '			<td nowrap align="right">Dari (RM)</td>
					<td nowrap align="right">Hingga (RM)</td>';
}
if ($code == 'N') {
	$noSpan = 5;
	print '			<td nowrap align="right">Dari</td>
					<td nowrap align="right">Hingga</td>';
}
if ($code == 'O') {
	$noSpan = 4;
	print '			<td nowrap align="center">Kod Potongan</td>';
}
print '			</tr>';
				if ($GetList->RowCount() <> 0) {	
					while(!$GetList->EOF) {	
						$bil++;		
						print '
						<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
							<td width="2%" align="right" valign="top">'.$bil.')&nbsp;</td>
							<td valign="top">&nbsp;'.$GetList->fields(code).'</td>
							<td valign="top">&nbsp;'.$GetList->fields(name).'</td>';
/* if ($code == 'B') {
	print '					<td>&nbsp;'.dlookup("general", "code", "ID=" . tosql($GetList->fields(parentID), "Number")).'</td>';
} */					
if ($code == 'C') {
	print '					<td>&nbsp;'.dlookup("general", "code", "ID=" . tosql($GetList->fields(c_Deduct), "Number")).'</td>
							<td>&nbsp;'.dlookup("general", "c_Panel", "ID=" . tosql($GetList->fields(c_Deduct), "Number")).'</td>
							<td>&nbsp;'
							.dlookup("general", "name", "ID=" . tosql($GetList->fields(c_Deduct), "Number")).'</td>
							<td align="right">'.$GetList->fields(c_Caj).'&nbsp;</td>
							<td align="right">'.$GetList->fields(c_Period).'&nbsp;</td>
							<td align="right">'.$GetList->fields(c_Maksimum).'&nbsp;</td>
							<td align="center">'.toYN($GetList->fields(c_gurrantor)).'</td>';
}					
if ($code == 'D') {
	if ($GetList->fields(d_Type) == 'P') 
		$type='Panel'; 
	elseif ($GetList->fields(d_Type) == 'I')
		$type = 'Insuran';
	elseif ($GetList->fields(d_Type) == 'T')
		$type = 'Tabung';
	print '					<td valign="top">&nbsp;'.$type.'</td>
							<td valign="top">'.$GetList->fields(d_Address).'</td>
							<td valign="top">&nbsp;'.$GetList->fields(d_Contact).'</td>
							<td valign="top">&nbsp;'.$GetList->fields(d_Phone).'</td>';
}							
/* if ($code == 'G') {
	print '					<td align="right">'.$GetList->fields(g_Price).'&nbsp;</td>
							<td align="right">'.$GetList->fields(g_Minimum).'&nbsp;</td>
							<td align="right">'.$GetList->fields(g_Maksimum).'&nbsp;</td>';
}	 */				
if ($code == 'J') {
	//$groupNo = dlookup("codegroup", "groupNo", "codeNo=" . tosql($GetList->fields(code), "Text"));
	//print '					<td valign="top">&nbsp;'
	//						.$groupNo.' - '.dlookup("general", "name", "code=" . tosql($groupNo, "Text")).'</td>';
	print '					<td valign="top">&nbsp;'. $GetList->fields('c_Panel') .'</td>';
}					
if ($code == 'M') {
	print '					<td align="right">'.$GetList->fields(m_Start).'&nbsp;</td>
							<td align="right">'.$GetList->fields(m_End).'&nbsp;</td>';
}					
if ($code == 'N') {
	print '					<td align="right">'.$GetList->fields(n_Start).'&nbsp;</td>
							<td align="right">'.$GetList->fields(n_End).'&nbsp;</td>';
}					
if ($code == 'O') {
	$sSQL = '';
	$sWhere = '';
	$sWhere .= 'a.groupNo = ' . tosql($GetList->fields(code) ,"Text");
	$sWhere .= ' AND a.codeNo = b.code ';
	$sWhere = ' WHERE (' . $sWhere . ')';
	$sSQL = ' SELECT a.codeNo, b.name FROM codegroup a, general b ';
	$sSQL = $sSQL . $sWhere ;
	$rs = &$conn->Execute($sSQL);
	print '					<td>';
	if ($rs->RowCount() <> 0){
		while (!$rs->EOF) {
			print '&nbsp;'.$rs->fields('codeNo').' - '.$rs->fields('name').'<br>';
			$rs->MoveNext();
		}
	}	
	print '					</td>';
}					
print '					</tr>';
					$GetList->MoveNext();
					}	
				} else {
					print '
					<tr style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt;" bgcolor="FFFFFF">
						<td colspan="'.$noSpan.'" align="center"><b>- Tiada Rekod Dicetak-</b></td>
					</tr>';
				}
print '		</table> 
		</td>
	</tr>
	
</table>
</body>
</html>
<tr><td>&nbsp;</td></tr>
<center><tr><td><font size="1" color="#999999"><b>'.$retooFetis.'</b></font></td></tr></center>';
?>