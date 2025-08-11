<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	rptDateOpt.php
*		   Description	:	Selection Date Option
*		   Parameter	:   $rpt - represent report program name
*          Date 		: 	04/03/2003
*********************************************************************************/
include ("common.php");
$today = date("F j, Y, g:i a");                 

if ($rpt == "") {
	print '	<script>
				alert ("'.$rpt.' - Nama laporan ini tidak wujud...!");
				window.close();
			</script>';
}

if (!isset($ddFrom)) $ddFrom	= 1;                 		
if (!isset($mmFrom)) $mmFrom	= date("n");                 		
if (!isset($yyFrom)) $yyFrom	= date("Y");                 		
if (!isset($ddTo)) 	 $ddTo  	= date("j");                 		
if (!isset($mmTo)) 	 $mmTo  	= date("n");                 		
if (!isset($yyTo)) 	 $yyTo  	= date("Y");                 		
if ($action == "Jana Laporan") {
	$msg	= "";
    $kod_bank = $kod_bank;
	$dtFrom = sprintf("%04d-%02d-%02d", $yyFrom, $mmFrom, $ddFrom);
	$dtTo	= sprintf("%04d-%02d-%02d", $yyTo, $mmTo, $ddTo);
    if ($kod_bank == "") $msg = "Tiada Koperasi Dipilih...";
	if ($dtFrom > $dtTo) $msg = "Tarikh Pada tidak boleh  melebihi dari Tarikh Hingga";
	if ($msg <> "") {
		print '<script>alert("'.$msg.'");</script>';
	} else {
		$rptURL = $rpt.'.php?dtFrom='.$dtFrom.'&dtTo='.$dtTo.'&id='.$kod_bank;
		print '
		<script>
			var rptUrl;
			window.open ("'.$rptURL.'", "rpt","scrollbars=yes,resizable=yes,toolbars=yes,location=no,menubar=yes");
			window.close();
		</script>	';
	}
}

function selectsyarikatK($code,$name){
	global $conn;
		//get list of admin value into array
		$sSQL = "SELECT * FROM users WHERE userID NOT like '%a%' ORDER BY name";
		$GetData = $conn->Execute($sSQL);
		if ($GetData->RowCount() <> 0) {
			$strbankCodeList = array();
			$strbankNameList = array();
			$nCount = 0;
			while (!$GetData->EOF) {
				$strbankCodeList[$nCount] = $GetData->fields('userID');
				$strbankNameList[$nCount] = $GetData->fields('name');
				$GetData->MoveNext();
				$nCount++;
			}
		}
		//end get list
	
	
	
	$strSelect = '<select name="'.$name.'" class="form-select-xs">
					<option value="">- Pilih -';
				for ($i = 0; $i < count($strbankCodeList); $i++) {
					$strSelect .= '	<option value="'.$strbankCodeList[$i].'" ';
					if ($code == $strbankCodeList[$i]) $strSelect .= ' selected';
					$strSelect .=  '>'.$strbankNameList[$i];
				}
	$strSelect .= '</select>';
	return $strSelect;
}

print '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>'.$emaNetis.'</title>
	<!--LINK rel="stylesheet" href="images/default.css" -->	
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />        
</head>
<body leftmargin="5" topmargin="5" class="bodyBG">';

print '
<form name="FrmSelection" action="'.$PHP_SELF.'" method="post">
	<input type="hidden" name="rpt" value="'.$rpt.'">
	<table border="0" cellpadding="3" cellspacing="0" class="table table-sm table-striped" style="padding: 1 0 0 0;font-size:9pt" height="100" width="100%">
		<tr valign="top">
			<td class=""><b>Tarikh Pada</b></td>
			<td class="">
				<select name="ddFrom" class="form-select-xs">';
for ($i = 1; $i < 32; $i++) {
	print '			<option value="'.$i.'"';
	if ($ddFrom == $i) print 'selected';
	print 			'>'.$i;
}
print '			</select> 
				<select name="mmFrom" class="form-select-xs">';
for ($j = 1; $j < 13; $j++) {
	print '			<option value="'.$j.'"';
	if ($mmFrom == $j) print 'selected';
	print 			'>'.$j;
}
print '			</select>
				<input type="text" name="yyFrom" size="3" maxlength="4" value="'.$yyFrom.'" class="form-select-xs">
			</td>
			<td class="textFont"><b>Tarikh Hingga</b></td>
			<td class="textFont">
				<select name="ddTo" class="form-select-xs">';
for ($i = 1; $i < 32; $i++) {
	print '			<option value="'.$i.'"';
	if ($ddTo == $i) print 'selected';
	print 			'>'.$i;
}
print '			</select> 
				<select name="mmTo" class="form-select-xs">';
for ($j = 1; $j < 13; $j++) {
	print '			<option value="'.$j.'"';
	if ($mmTo == $j) print 'selected';
	print 			'>'.$j;
}
print '			</select>
				<input type="text" name="yyTo" size="3" maxlength="4" value="'.$yyTo.'" class="form-select-xs">
			</td>
		</tr>
        <tr> 
			<td class="textFont" align="right"><b>Pilih Syarikat</b></td>
			<td align="left">'.selectsyarikatK($kod_bank,'kod_bank').'</td>
		</tr>
		<tr>
			<td colspan="4" align="center"><input type="submit" name="action" value="Jana Laporan" class="btn btn-primary"></td>
		</tr>
	</table>
</form>


</body>
</html>';
?>