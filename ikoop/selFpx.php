<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	selFpx.php
*          Date 		: 	07/11/2023
*********************************************************************************/
include ("common.php");	
include("koperasiQry.php");	

if (!isset($StartRec))	$StartRec= 1; 
if (!isset($pg))		$pg= 100;
if (!isset($q))			$q="";
if (!isset($by))		$by="1";
if (!isset($dept))		$dept="";

//--- Prepare department list
$deptList = Array();
$deptVal  = Array();
$sSQL = "	SELECT a.departmentID, b.code as deptCode, b.name as deptName 
			FROM userdetails a, general b
			WHERE a.departmentID = b.ID
			AND   a.status IN ('1','4') 
			GROUP BY a.departmentID";
$rs = &$conn->Execute($sSQL);
if ($rs->RowCount() <> 0){
	while (!$rs->EOF) {
		array_push ($deptList, $rs->fields(deptName));
		array_push ($deptVal, $rs->fields(departmentID));
		$rs->MoveNext();
	}
}

// query to fecth all the data from user only if it's not in fpx ^^

/* SELECT DISTINCT a.userID, a.name, a.loginID, b.kopNum, c.fpxStatus
FROM users a
JOIN userdetails b ON a.userID = b.userID
LEFT JOIN fpx c ON a.userID = c.userID
AND c.fpxStatus IN ( 0, 1, 2 )
WHERE c.userID IS NULL
ORDER BY CAST( b.kopNum AS SIGNED INTEGER ) */


$sSQL = "";
$sSQL = "SELECT DISTINCT a.*, b.*
         FROM users a
         JOIN userdetails b ON a.userID = b.userID
         LEFT JOIN fpx c ON a.userID = c.userID
		 AND c.fpxStatus IN ( 0, 1, 2 )
		 AND b.status IN ('1','4')";

$sWhere = "c.userID IS NULL";

if ($dept <> "") 	{
	$sWhere .= " AND b.departmentID = " . tosql($dept,"Number");
}
if ($q <> "") {
    if ($by == 1) {
        $sWhere .= " AND b.kopNum LIKE '%" . $q . "%'";
    } elseif ($by == 2) {
        $sWhere .= " AND a.name LIKE '%" . $q . "%'";
    } elseif ($by == 3) {
        $sWhere .= " AND a.loginID LIKE '%" . $q . "%'";
    }
}
$sWhere2 = " WHERE (" . $sWhere . ")";
$sSQL = $sSQL . $sWhere2 . " order by CAST( b.kopNum AS SIGNED INTEGER )";
$GetMember = &$conn->Execute($sSQL);

$GetMember->Move($StartRec-1);

$TotalRec = $GetMember->RowCount();
$TotalPage =  ($TotalRec/$pg);

print '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>' . $emaNetis . '</title>
<meta name="GENERATOR" content="' . $yVcSz2OuGE5U . '">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0"> 
<meta http-equiv="cache-control" content="no-cache">
<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />   	
</head>
<script language="JavaScript">
	function selAnggota(userid,name)
	{	
		window.opener.document.MyForm.no_koperasi.value = userid;	
		window.opener.document.MyForm.userName.value = name;	
		window.close();
	}
</script>

<body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" class="bodyBG">';
//window.opener.document.MyForm.emel.value = email;
print '
<form name="MyForm" action='.$PHP_SELF.' method="post">
<input type="hidden" name="action">
<input type="hidden" name="by" value="'.$by.'">
<div class="table-responsive">
<table border="0" cellspacing="1" cellpadding="0" width="95%" align="center" class="table">
	<tr>
		<td class="Data">
			<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center" class="table table-sm">
				<tr>
					<td	class="Header" colspan="2">Senarai Koperasi</b></td>
				</tr>
				<tr class="class="table table-danger">
					<td>
						Carian melalui 
						<select name="by" class="form-select-sm">'; 
		if ($by == 1)	print '<option value="1" selected>No./ID Koperasi</option>'; 	else print '<option value="1"> No./ID Koperasi</option>';			
		if ($by == 2)	print '<option value="2" selected>Nama Koperasi</option>'; 	else print '<option value="2">Nama Koperasi</option>';				
		if ($by == 3)	print '<option value="3" selected>Singkatan Koperasi</option>'; 	else print '<option value="3">Singkatan Koperasi</option>';							
			print '		</select>
						<input type="text" name="q" value="" class="form-control-sm" maxlength="50" size="30" class="Data">
			           	<input type="submit" class="btn btn-sm btn-secondary" value="Cari">&nbsp;&nbsp;&nbsp;
						Zon
						<select name="dept" class="form-select-sm" onchange="document.MyForm.submit();">
							<option value="">- Semua -';
						for ($i = 0; $i < count($deptList); $i++) {
							print '	<option value="'.$deptVal[$i].'" ';
							if ($dept == $deptVal[$i]) print ' selected';
							print '>'.$deptList[$i];
						}
		print '			</select>
					</td>
				</tr>';
if ($GetMember->RowCount() == 0) {
	print '		<tr><td class="Label" align="center" height=50 valign=middle>
					<b>- Sila masukkan No / Nama Koperasi-</b>
				</td></tr>';
} else {				
	if ($GetMember->RowCount() <> 0) {  
		$bil = $StartRec;
		$cnt = 1;
		print '	<tr>
		<td class="Data" width="100%">	
			<table border="0" cellpadding="2" cellspacing="1" width="100%" class="table table-bordered table-striped table-sm" style="font-size: 10pt;">
				<tr class="table table-danger">
					<td class="header" nowrap>&nbsp;</td>
					<td class="header" align="left"><b>No./Nama Koperasi</b></td>
					<td class="header"><b>Email Koperasi</b></td>
					<td class="header" align="center"><b>Zon</b></td>
				</tr><br>
			</tr>';
			while (!$GetMember->EOF && $cnt <= $pg) {
				$userid		= $GetMember->fields(userID);
				$kopNum	= $GetMember->fields(kopNum);
				$name		= $GetMember->fields(name);			
				$email		= $GetMember->fields(email);
				print '
				<tr>
					<td class="Data" align="right">' . $bil . '</td>
					<!-- <td class="Data" align="center"><a class="text-danger" href="javascript:selAnggota(\''.$userid.'\',\''.$name.'\');">'.$userid.'</a></td> -->
					<td class="Data"><a class="text-danger" href="javascript:selAnggota(\''.$userid.'\',\''.$name.'\');">'.$kopNum.' - '.strtoupper($name).'</a></td>
					<td class="Data">'.$email.'&nbsp;</td>';
				print'
					<td class="Data" align="center">&nbsp;'.dlookup("general", "name", "ID=" . tosql($GetMember->fields('departmentID'), "Number")).'</td>
				</tr>';
				$cnt++;
				$bil++;
				$GetMember->MoveNext();
			}	
			print ' </table>
				</td>
			</tr>		
			<tr>
				<td>';
				if ($TotalRec > $pg) {
					print '
					<table border="0" cellspacing="5" cellpadding="0"  class="textFont" width="100%">';
					if ($TotalRec % $pg == 0) {
						$numPage = $TotalPage;
					} else {
						$numPage = $TotalPage + 1;
					}
					print '<tr><td class="textFont" valign="top" align="left">Rekod Dari : <br>';
					for ($i=1; $i <= $numPage; $i++) {
						print '<A class="text-danger" href="'.$sFileName.'?&StartRec='.(($i * $pg) + 1 - $pg).'&pg='.$pg.'&q='.$q.'&by='.$by.'&dept='.$dept.'">';
						print '<b><u>'.(($i * $pg) - $pg + 1).'-'.($i * $pg).'</u></b></a> &nbsp; &nbsp;';
					}
					print '</td>
						</tr>
					</table>';
				}				
			print '
				</td>
			</tr>
			</td>
			</tr>
			</table>
			</td>
			</tr>';

	} else { 
		print '
					<tr><td	class="Label" align="center" height=50 valign=middle>
						<b>- Tiada rekod mengenai anggota  -</b>
					</td></tr>';
	} // end of ($GetMember->RowCount() <> 0)
} // end of ($q == "" AND $dept == "")
print '		</table>
		</td>
	</tr>
</table></div>
</form>
<p align="center" class="footer">'.$retooFetis.'</p>
</body>
</html>';
?>

