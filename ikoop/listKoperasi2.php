<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	admin.php
*          Date 		: 	04/12/2018
*********************************************************************************/

if (!isset($StartRec))	$StartRec= 1; 
if (!isset($pg))		$pg= 50;
if (!isset($q))			$q="";
if (!isset($by))		$by="1";
if (!isset($filter))	$filter="0";
if (!isset($dept))		$dept="";
if (!isset($active))	$active="1";
if ($filter == 1)	$active="1";

include("header.php");	
include("koperasiQry.php");	
date_default_timezone_set("Asia/Jakarta");

if (get_session("Cookie_groupID") <> 2 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>parent.location.href = "index.php";</script>';
}
$sFileName = "?vw=listKoperasi&mn=$mn";
$sFileRef  = "?vw=memberEdit&mn=mn";
$title     = "Senarai Koperasi";

/* //--- Begin : deletion based on checked box -------------------------------------------------------
if ($action == "delete") { 
	$sWhere = "";
	for ($i = 0; $i < count($pk); $i++) {
		$sSQL = '';
	    $sWhere = "userID=" . tosql($pk[$i], "Text");
		$sSQL = "Update users set isActive = 0 WHERE " . $sWhere;
		$rs = &$conn->Execute($sSQL);
	}
}
//--- End   : deletion based on checked box -------------------------------------------------------

//--- Begin : reset based on checked box -------------------------------------------------------
if ($action == "reset") {
	$sSQL = '';
	$sWhere = "";
	$sWhere = ' userID = ' . tosql($pk[0],"Text");
	$sSQL	= ' UPDATE users SET ' .
	          ' password=' . tosql(strtoupper(md5("staf123")), "Text") ;
	$sSQL .= ' WHERE ' . $sWhere;
	$rs = &$conn->Execute($sSQL);
	print '<script>alert("Katalaluan staf ini telah diset semula kepada \"staf123\"\n.");</script>';
}
//--- End   : reser based on checked box ------------------------------------------------------- */
//--- Prepare status MSS list
$MSSList = array("","(MSS)");
//--- Prepare department list
$deptList = Array();
$deptVal  = Array();
$sSQL = "	SELECT a.departmentID, b.code as deptCode, b.name as deptName
			FROM userdetails a, general b
			WHERE a.departmentID = b.ID
			AND   a.status = 1 
			GROUP BY a.departmentID";

$rs = &$conn->Execute($sSQL);
if ($rs->RowCount() <> 0){
	while (!$rs->EOF) {
		array_push ($deptList, $rs->fields(deptName));
		array_push ($deptVal, $rs->fields(departmentID));
		$rs->MoveNext();
	}
}
   
$sSQL = "";
$sWhere = " a.userID = b.userID AND b.status = " . tosql($filter,"Number") . " AND a.isActive = " . tosql($active,"Number");
if ($dept <> "") 	{
	$sWhere .= " AND b.departmentID = " . tosql($dept,"Number");
}
//search value
if ($q <> "") 	{
	if ($by == 1) {
		$sWhere .= " AND b.kopNum like '%" .$q ."%'";			
	} else if ($by == 2) {
		$sWhere .= " AND a.name like '%" . $q. "%'";
	} else if ($by == 3) {
		//$sWhere .= " AND b.jenis like '%" . $q. "%'";
		$sWhere .= " AND ((b.jenis = 0 AND 'Kredit' = '" . $q . "') OR (b.jenis = 1 AND 'Bukan Kredit' = '" . $q . "'))";
	}
}

$SQLdpt = "";
/* $SQLdpt = "SELECT *
           FROM users a, userdetails b, general c
           WHERE a.userID = b.userID
		   AND b.departmentID = c.ID
           AND a.groupID IN (0)";
          AND a.isActive = 1"; */
		  $SQLdpt = "SELECT a.departmentID, b.code as deptCode, b.name as deptName 
		  FROM userdetails a, general b
		  WHERE a.departmentID = b.ID
		  AND a.isActive = 1";

if ($groupID != "superadmin") {
   $SQLdpt .= " AND a.userID IN (SELECT userID FROM users WHERE groupID IN (0,2))";
}

$SQLdpt .= " GROUP BY a.departmentID";	  

$sWhere = " WHERE (" . $sWhere . ")";
$SQLdpt = "SELECT	DISTINCT a.*, b.*
		 FROM 	users a, userdetails b";
$SQLdpt = $SQLdpt . $sWhere;
$SQLdpt = $SQLdpt . "order by CAST( b.kopNum AS SIGNED INTEGER ) asc";


$GetAdmin = &$conn->Execute($SQLdpt);
$GetAdmin->Move($StartRec-1);

$TotalRec = $GetAdmin->RowCount();
$TotalPage =  ($TotalRec/$pg);

print '
<form name="MyForm" action=' .$sFileName . ' method="post">
<input type="hidden" name="action">
<input type="hidden" name="pk" value="<?=$pk?>">
<input type="hidden" name="filter" value="'.$filter.'">
<div class="table-responsive">
<table border="0" cellspacing="3" cellpadding="3" width="100%" align="center">
<h5 class="card-title">' . strtoupper($title) . '<h5>';

		print
    '<tr valign="top" class="Header">'
	   	.'<td align="left" >'
			.'Carian Melalui '
			.'<select name="by" class="form-select-sm">'; 
			if ($by == 1)	print '<option value="1" selected>No./ID Koperasi</option>'; 	else print '<option value="1">No./ID Koperasi</option>';				
			if ($by == 2)	print '<option value="2" selected>Nama Koperasi</option>'; 	else print '<option value="2">Nama Koperasi</option>';				
			if ($by == 3)	print '<option value="3" selected>Jenis Koperasi</option>'; 	else print '<option value="3">Jenis Koperasi</option>';							
			print
 			'</select>
			<input type="text" name="q" value="" maxlength="50" size="20" class="form-control-sm">
 			<input type="submit" class="btn btn-secondary btn-sm" value="Cari">&nbsp;&nbsp;&nbsp;		
			Zon
			<select name="dept" class="form-select-sm" onchange="document.MyForm.submit();">
				<option value="">- Semua -';
			for ($i = 0; $i < count($deptList); $i++) {
				print '	<option value="'.$deptVal[$i].'" ';
				if ($dept == $deptVal[$i]) print ' selected';
				print '>'.$deptList[$i];
			}		
			print	'</select>
		</td>
	</tr>
	<tr valign="top">
		<td align="left">&nbsp;
			Jenis
			<select name="filter" class="form-select-sm" onchange="document.MyForm.submit();">';
			// Loop through your statusList array
            for ($i = 0; $i < count($statusList); $i++) {
				if($i==1 || $i==3){
					if ($statusVal[$i] < 4) {
						print '	<option value="'.$statusVal[$i].'" ';
						if ($filter == $statusVal[$i]) print ' selected';
						print '>'.$statusList[$i];
					}
				}
			}
            
        echo '</select>&nbsp;';

			/* if ($filter == 3) {
				print 'Status Login Sistem
				<select name="active" class="form-select-xs" onchange="document.MyForm.submit();">';
				for ($i = 0; $i < count($activeList); $i++) {
					if ($activeVal[$i] < 4) {
						print '	<option value="'.$activeVal[$i].'" ';
					if ($active == $activeVal[$i]) print ' selected';
						print '>'.$activeList[$i];
					}
				}
				print '	</select>&nbsp;<input type="button" class="btn btn-primary btn-sm" value="Ubah" onClick="ITRActionButtonClickStatus(\'ubah\');">';
			} */
			print '	</td>
	</tr>
	<tr valign="top" class="textFont">
		<td>
			<table width="100%">
				<tr>';
					//if ($filter == 3) {
					/*if ($filter) {
						print
						'<td  class="textFont"><input type="checkbox" onClick="ITRViewSelectAll()" class="form-check-input"> Select All</td>
						<td align="right" class="textFont">';
					} */
						print
						'<td class="textFont">&nbsp;</td><td align="right" class="textFont">';

					print
					'&nbsp;&nbsp;';
                                                                                            echo papar_ms($pg);
                                                                    print '</td>
				</tr>
			</table>
		</td>
	</tr>';
	
	if ($GetAdmin->RowCount() <> 0) {  
		$bil = $StartRec;
		$cnt = 1;
		print '
	    <tr valign="top" >
			<td valign="top">
				<table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped">
					<tr class="table-danger">
						<td nowrap>&nbsp;</td>
						<td nowrap><b>Nama Koperasi</b></td>
						<td nowrap align="center"><b>No./ID Koperasi</b></td>
						<td nowrap align="center"><b>Singkatan Koperasi</b></td>
						<td nowrap align="center"><b>Emel</b></td>
						<td nowrap align="center"><b>Jenis Koperasi</b></td>
						<td nowrap align="center"><b>Zon</b></td>
                        <td nowrap align="center"><b>Status</b></td>
						<td nowrap align="center"><b>Tarikh SST Diterima</b></td>
					</tr>';	
		while (!$GetAdmin->EOF && $cnt <= $pg) {
			$status = dlookup("userdetails", "status", "userID=" . tosql($GetAdmin->fields(userID), "Text"));
			$colorStatus = "Data";
			if ($status == 1) $colorStatus = "greenText";
			if ($status == 3) $colorStatus = "redText";
			print ' <tr class="table-light">
						<td class="Data" align="right">' . $bil . '</td>
						<td class="Data" style="text-transform:uppercase"><input type="hidden" class="form-check-input" name="pk[]" value="'.tohtml($GetAdmin->fields(userID)).'">
							<a class="text-danger" href="'.$sFileRef.'&pk='.tohtml($GetAdmin->fields(userID)).'">
							'.$GetAdmin->fields(name).'</td>
						<td class="Data" align="center">'.$GetAdmin->fields(kopNum).'</td>
						<td class="Data" align="center">'.$GetAdmin->fields(loginID).'</td>
						<td class="Data" align="center">'.$GetAdmin->fields(email).'</td>
						<td align="center">'.$jenisList[$GetAdmin->fields(jenis)].'</a></td>
						<td class="Data" align="center">&nbsp;'.dlookup("general", "name", "ID=" . tosql($GetAdmin->fields('departmentID'), "Number")).'</td>
						<td class="Data" align="center">&nbsp;<font class="'.$colorStatus.'">'.$statusList[$status].' '.$MSSList[$MSS].'</font></td>';
						print '</td>
						<td class="Data" align="center">'.toDate("d/m/Y",$GetAdmin->fields(applyDate)).'</td>
					</tr>';
				$cnt++;
				$bil++;
			$GetAdmin->MoveNext();
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
					print '<tr><td class="textFont" valign="top" align="left" ">Rekod Dari : <br>';
					for ($i=1; $i <= $numPage; $i++) {
						print '<A href="'.$sFileName.'&StartRec='.(($i * $pg) + 1 - $pg).'&pg='.$pg.'&filter='.$filter.'">';
						print '<b><u>'.(($i * $pg) - $pg + 1).'-'.($i * $pg).'</u></b></a> &nbsp; &nbsp; ';
					}
					print '</td>
						</tr>
					</table>';
				}				
		print '
			</td>
		</tr>
		<tr>
			<td class="textFont">Jumlah Rekod : <b>' . $GetAdmin->RowCount() . '</b></td>
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
</table>
</div>
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
	          window.location.href ="memberStatus.php?pk=" + strStatus;
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
		document.location = "' . $sFileName . '&StartRec=1&pg=" + c.options[c.selectedIndex].value;
	}

    function ITRActionButtonReset() {
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
				alert(\'Sila pilih satu rekod sahaja untuk reset kala laluan\');
			} else {
	          if(confirm(\' Rekod ini akan diresetkan kata laluan?\')) {
	            e.action.value = \'reset\';
				//e.dept.value = "'.$dept.'";
				//e.by.value = "'.$by.'";
				//e.q.value = "'.$q.'";
	            e.submit();
	          }
			}
		}
	}
</script>';
?>