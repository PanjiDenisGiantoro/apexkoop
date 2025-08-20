<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	member.php
*          Date 		: 	
*********************************************************************************/
if (!isset($StartRec))	$StartRec= 1; 
if (!isset($pg))		$pg= 50;
if (!isset($q))			$q="";
if (!isset($by))		$by="1";
if (!isset($filter))	$filter="0";
//if (!isset($filter))	$filter="0";
if (!isset($dept))		$dept="";
date_default_timezone_set("Asia/Jakarta");	

include("header.php");	
include("koperasiQry.php"); 

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}

$sFileName = '?vw=member3&mn=905';
$sFileRef  = '?vw=memberEdit&mn=905';
$title     = "Status Permohonan Koperasi";

$IDName = get_session("Cookie_userName");
//--- Begin : deletion based on checked box -------------------------------------------------------
if ($action == "delete") {
	$sWhere = "";
	for ($i = 0; $i < count($pk); $i++) {
		$CheckUser = ctMemberDetail($pk[$i]);
		if ($CheckUser->RowCount() == 1) {
			if ($CheckUser->fields(status) == 0) {
				$sSQL = '';
			    $sWhere = "userID=" . tosql($pk[$i], "Text");
				$sSQL = "DELETE FROM users WHERE " . $sWhere;
				$rs = &$conn->Execute($sSQL);
				$sSQL = '';
				$sSQL = "DELETE FROM userdetails WHERE " . $sWhere;
				$rs = &$conn->Execute($sSQL);
			} else {
				print '<script>alert("Pengguna '.$CheckUser->fields(name).' - tidak boleh dihapuskan...!");</script>';
			}
		}
	}
}
//--- End   : deletion based on checked box -------------------------------------------------------

/* $sSQL = "	/* SELECT a.departmentID, b.code as deptCode, b.name as deptName 
			FROM userdetails a, general b, users c
			WHERE a.departmentID = b.ID
			AND   a.status = 1 
			GROUP BY a.departmentID"; */

//--- Prepare department & code list
$deptList = Array();
$deptVal  = Array();
$jenisCodeList = Array();
$jenisCodeVal  = Array();

$sSQL = "SELECT a.departmentID, b.code as deptCode, b.name as deptName, c.loginID, a.jenisCode
         FROM userdetails a
         INNER JOIN general b ON a.departmentID = b.ID
         INNER JOIN users c ON a.userID = c.userID
         WHERE a.status = 1 
         GROUP BY a.departmentID";


		
$rs = &$conn->Execute($sSQL);
if ($rs->RowCount() <> 0){
	while (!$rs->EOF) {
		array_push ($deptList, $rs->fields(deptName));
		array_push ($deptVal, $rs->fields(departmentID));
		array_push ($jenisCodeList, $rs->fields(deptName));
		array_push ($jenisCodeVal, $rs->fields(jenisCode));
		$rs->MoveNext();
	}
}



/* array_push ($deptList, "Bersara");
array_push ($deptVal, "BSR");*/
if ($dept=="BSR"){	$filter= 4 ; $dept = ""; }

//$GetMember = ctMemberStatusDept($q,$by,$filter,$dept);
//	global $conn;
//function ctMemberStatusDept($q,$by,$status,$dept) {
	$sSQL = "";
//	$sWhere = " a.userID = b.userID AND b.status = " . tosql($filter,"Number");
	$sWhere = " a.userID = b.userID ";

	if ($dept <> "") 	{
		$sWhere .= " AND b.departmentID = " . tosql($dept,"Number");
	}

	if ($jenisCode <> "") 	{
		$sWhere .= " AND b.jenisCode = " . tosql($jenisCode,"Number");
	}

	if($filter <> "ALL") $sWhere .= "  AND b.status = " . $filter;
	
	if ($q <> "") 	{
		if ($by == 1) {
			$sWhere .= " AND b.kopNum like '%" .$q ."%'";			
		} else if ($by == 2) {
			$sWhere .= " AND a.name like '%" . $q. "%'";
		} else if ($by == 3) {
			$sWhere .= " AND a.loginID like '%" . $q. "%'";		
		}
	}
	$sWhere = " WHERE (" . $sWhere . ")";
	$sSQL = "SELECT	DISTINCT a.*, b.*
			 FROM 	users a, userdetails b";
	//$sSQL = $sSQL . $sWhere . ' ORDER BY applyDate DESC';
	$sSQL = $sSQL . $sWhere . " order by CAST( b.kopNum AS SIGNED INTEGER ) asc";
	$GetMember = &$conn->Execute($sSQL);

$GetMember->Move($StartRec-1);

$TotalRec = $GetMember->RowCount();
$TotalPage =  ($TotalRec/$pg);

print '
<form name="MyForm" action='.$sFileName.' method="post">
<input type="hidden" name="action">
<input type="hidden" name="pk" value="'.$pk.'">
<input type="hidden" name="filter" value="'.$filter.'">
<h5 class="card-title">'.strtoupper($title).'</h5>
    
<div class="mb-3 row m-1">
<div>Carian Melalui 
			<select name="by" class="form-select-sm mt-3">'; 
if ($by == 1)	print '<option value="1" selected>No./ID Koperasi</option>'; 	else print '<option value="1">No./ID Koperasi</option>';				
if ($by == 2)	print '<option value="2" selected>Nama Koperasi </option>'; 	else print '<option value="2">Nama Koperasi</option>';
if ($by == 3)	print '<option value="3" selected>Singkatan Koperasi</option>'; 	else print '<option value="3">Singkatan Koperasi</option>';					
print '		</select>
			<input type="text" name="q" value="" maxlength="50" size="20" class="form-control-sm mt-3">
 			<input type="submit" class="btn btn-sm btn-secondary" value="Cari">&nbsp;&nbsp;&nbsp;		
			Zon
			<select name="dept" class="form-select-sm mt-3" onchange="document.MyForm.submit();">
				<option value="">- Semua -';
			for ($i = 0; $i < count($deptList); $i++) {
				print '	<option value="'.$deptVal[$i].'" ';
				if ($dept == $deptVal[$i]) print ' selected';
				print '>'.$deptList[$i];
			}
print '		</select>&nbsp;</div>
</div>

<div class="mb-3 row m-1">
<div>
Jenis
			<select name="filter" class="form-select-sm" onchange="document.MyForm.submit();">';
			//print '<option value="ALL">Semua';
			for ($i = 0; $i < count($statusList); $i++) {
				if($i == 0 ||$i == 1||$i == 2){
				if ($statusVal[$i] < 3) {
					print '	<option value="'.$statusVal[$i].'" ';
					if ($filter == $statusVal[$i]) print ' selected';
					print '>'.$statusList[$i];
				}
			}
			}
	print '	</select>&nbsp;';

if (($IDName == 'superadmin') OR ($IDName == 'admin')) {

if($filter == 0) print      '<input type="button" class="btn btn-sm btn-danger" value="Hapus" onClick="ITRActionButtonClick(\'delete\');">  '; }

print'          
			<!--input type="button" class="btn btn-sm btn-danger" value="Status" onClick="ITRActionButtonStatus();"-->
			 <input type="button" class="btn btn-sm btn-primary" value="Proses" onClick="ITRActionButtonClickStatus(\'proses\');"></div>
</div>';

print '
<div class="table-responsive">    
<!--table border="1" cellspacing="1" cellpadding="3" width="100%" align="center" class="table"-->
	<tr valign="top" class="textFont">
		<td>
			<table width="100%">
				<tr>
					<!-- <td  class="textFont"><input type="checkbox" onClick="ITRViewSelectAll()" class="form-check-input"> Select All</td> -->					
					<td align="right" class="textFont">Paparan <SELECT name="pg" class="form-select-xs" onchange="doListAll();">';
					if ($pg == 5)	print '<option value="5" selected>5</option>'; 	 	else print '<option value="5">5</option>';				
					if ($pg == 10)	print '<option value="10" selected>10</option>'; 	else print '<option value="10">10</option>';				
					if ($pg == 20)	print '<option value="20" selected>20</option>'; 	else print '<option value="20">20</option>';				
					if ($pg == 30)	print '<option value="30" selected>30</option>'; 	else print '<option value="30">30</option>';				
					if ($pg == 40)	print '<option value="40" selected>40</option>'; 	else print '<option value="40">40</option>';				
					if ($pg == 50)	print '<option value="50" selected>50</option>';	else print '<option value="50">50</option>';				
					if ($pg == 100)	print '<option value="100" selected>100</option>';	else print '<option value="100">100</option>';				
	print '				</select> setiap mukasurat.
					</td>
				</tr>
			</table>
		</td>
	</tr>';	
	if ($GetMember->RowCount() <> 0) {  
		$bil = $StartRec;
		$cnt = 1;
		print '
	    <tr valign="top" >
			<td valign="top">
				<table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped">
					<tr class="table-danger">
						<td nowrap>&nbsp;</td>
						<td nowrap>&nbsp;<b>No./Nama Koperasi</b></td>
						<td nowrap align="center">&nbsp;<b>Singkatan Koperasi Koperasi</b></td>
						<td nowrap align="center">&nbsp;<b>Jenis Koperasi</b></td>
						<td nowrap align="center">&nbsp;<b>Zon</b></td>
						<td nowrap align="center">&nbsp;<b>Status</b></td>
						<td nowrap align="center">&nbsp;<b>Jenis Kod</b></td>
						<td nowrap align="center">&nbsp;<b>Tarikh Daftar</b></td>';
						if ($filter==1){
							print '
							<td nowrap align="center">&nbsp;<b>Tarikh SST Diterima</b></td>';
						}
					print '
					</tr>';	
		while (!$GetMember->EOF && $cnt <= $pg) {
			$status = dlookup("userdetails", "status", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$jenis = dlookup("userdetails", "jenis", "userID=" . tosql($GetMember->fields(userID), "Text"));
			$colorStatus = "Data";
			if ($status == 0) $colorStatus = "text-success";
			if ($status == 1) $colorStatus = "text-primary";
			if ($status == 2) $colorStatus = "text-warning";
			print ' <tr>
						<td class="Data" align="right">' . $bil . '&nbsp;</td>
						<td class="Data"><input type="checkbox" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(userID)).'">
							<a class="text-danger" href="'.$sFileRef.'&pk='.tohtml($GetMember->fields(userID)).'">
							'.$GetMember->fields(kopNum).' - 
							'.strtoupper($GetMember->fields(name)).'</a>  ';
						
							if ($filter==1) {
								print '&nbsp;<input type=button value="TESTING" class="btn btn-sm btn-secondary" onClick=window.open("rptA4.php?","pop","top=50,left=50,width=700,height=450,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");></td></font></td> ';}
							print'
						<td class="Data" align="center">&nbsp;'.$GetMember->fields(loginID).'</td>
						<!-- <td class="Data" align="center">'.$GetMember->fields(kopNum).'</td> -->
						<td class="Data" align="center">&nbsp;'.$jenisList[$jenis].'</font></td>
						<td class="Data" align="center">&nbsp;'.dlookup("general", "name", "ID=" . tosql($GetMember->fields('departmentID'), "Number")).'</td>
						<td class="Data" align="center">&nbsp;<font class="'.$colorStatus.'">'.$statusList[$status].'</font></td>
						<td class="Data" align="center">&nbsp;'.dlookup("general", "name", "ID=" . tosql($GetMember->fields('jenisCode'), "Number")).'</td>
						 <td class="Data" align="center">&nbsp;'.toDate("d/m/Y",$GetMember->fields(applyDate)).'</td>';
						 if ($filter==1){
							print '
							<td class="Data" align="center">&nbsp;'.toDate("d/m/Y",$GetMember->fields(approvedDate)).'</td>';
						}	print '
					</tr>';
				$cnt++;
				$bil++;
			$GetMember->MoveNext();
		}

		$GetMember->Close();
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
						print '<A href="'.$sFileName.'&StartRec='.(($i * $pg) + 1 - $pg).'&pg='.$pg.'&q='.$q.'&by='.$by.'&dept='.$dept.'&filter='.$filter.'">';
						print '<b><u>'.(($i * $pg) - $pg + 1).'-'.($i * $pg).'</u></b></a> &nbsp; &nbsp;';
					}
					print '</td>
						</tr>
					</table>';
				}				
		print '
			</td>
		</tr>
		<tr>
			<td class="textFont">Jumlah Rekod : <b>' . $GetMember->RowCount() . '</b></td>
		</tr>';
	} else {
		if ($q == "") {
			print '
			<tr><td align="center"><hr size=1"><b class="textFont">- Tiada Rekod Untuk '.$title.'  -</b><hr size=1"></td></tr>';
		} else {
			print '
			<tr><td align="center"><hr size=1"><b class="textFont">- Carian rekod "'.$q.'" tidak jumpa  -</b><hr size=1"></td></tr>';
		}
	}
print ' 
</table></td></tr></table></div>
</form>';

include("footer.php");	

print '
<script language="JavaScript">
	var allChecked=false;
	function ITRViewSelectAll() {
	    e = document.MyForm.elements;
	    allChecked = !allChecked;
	    for(c=0; c< e.length; c++) {
	      if(e[c].type=="checkbox" && e[c].name!="all") {
	        e[c].checked = allChecked;
	      }
	    }
	}
	
	function ITRActionButtonClick(v) {
	      e = document.MyForm;
	      if(e==null) {
			alert(\'Sila pastikan nama form diwujudkan.!\');
	      } else {
	        count=0;
	        for(c=0; c<e.elements.length; c++) {
	          if(e.elements[c].name=="pk[]" && e.elements[c].checked) {
	            count++;
	          }
	        }
	        
	        if(count==0) {
	          alert(\'Sila pilih rekod yang hendak di\' + v + \'kan.\');
	        } else {
	          if(confirm(count + \' rekod hendak di\' + v + \'kan?\')) {
	            e.action.value = v;
	            e.submit();
	          }
	        }
	      }
	    }
		
	function ITRActionButtonClickStatus(v) {
	      var strStatus="";
		  e = document.MyForm;
	      if(e==null) {
			alert(\'Sila pastikan nama form diwujudkan.!\');
	      } else {
	        count=0;
	        j=0;
			for(c=0; c<e.elements.length; c++) {
	          if(e.elements[c].name=="pk[]" && e.elements[c].checked) {
				pk = e.elements[c].value;
				strStatus = strStatus + ":" + pk;
				count++;
	          }
	        }
	        
	        if(count==0) {
	          alert(\'Sila pilih rekod yang hendak di\' + v + \'kan.\');
	        } else {
	          if(confirm(count + \' rekod hendak di\' + v + \'kan?\')) {
	          //e.submit();
	          window.location.href ="?vw=memberStatus&pk=" + strStatus;
			  }
	        }
	      }
	    }

	function ITRActionButtonStatus() {
		e = document.MyForm;
		if(e==null) {
			alert(\'Sila pastikan nama form diwujudkan.!\');
		} else {
			count=0;
			for(c=0; c<e.elements.length; c++) {
				if(e.elements[c].name=="pk[]" && e.elements[c].checked) {
					count++;
					pk = e.elements[c].value;
				}
			}
	        
			if(count != 1) {
				alert(\'Sila pilih satu rekod sahaja untuk kemaskini status\');
			} else {
				window.location.href = "memberStatus.php?pk=" + pk;
			}
		}
	}


	function doListAll() {
		c = document.forms[\'MyForm\'].pg;
		document.location = "' . $sFileName . '?&StartRec=1&pg=" + c.options[c.selectedIndex].value;
	}

</script>';

?>
