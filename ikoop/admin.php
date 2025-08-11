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

include("header.php");	
include("koperasiQry.php");	
date_default_timezone_set("Asia/Kuala_Lumpur");

if (get_session("Cookie_groupID") <> 2 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>parent.location.href = "index.php";</script>';
}
$sFileName = "?vw=admin&mn=$mn";
$sFileRef  = "?vw=adminEdit&mn=mn";
$title     = "Senarai Admin";

//--- Begin : deletion based on checked box -------------------------------------------------------
if ($action == "delete") { 
	$sWhere = "";
	$updatedBy 	= get_session("Cookie_userName");
	$updatedDate = date("Y-m-d H:i:s"); 
	for ($i = 0; $i < count($pk); $i++) {
		$sSQL = '';
	    $sWhere = "userID=" . tosql($pk[$i], "Text");
		$sSQL = "Update users set isActive = 0 WHERE " . $sWhere;
		$rs = &$conn->Execute($sSQL);

		//aktiviti log
		$sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)".
		" VALUES ('Hapus kakitangan - $loginID', 'UPDATE', '" . str_replace( "'", "", $sSQL ) . "', '".get_session('Cookie_userID')."','".$updatedDate."', '".$updatedBy."')";
		$rs = &$conn->Execute($sqlAct);
		
	}
}
//--- End   : deletion based on checked box -------------------------------------------------------

//--- Begin : reset based on checked box -------------------------------------------------------
if ($action == "reset") {
	$sSQL = '';
	$sWhere = "";
	$updatedBy 	= get_session("Cookie_userName");
	$updatedDate = date("Y-m-d H:i:s"); 
	$sWhere = ' userID = ' . tosql($pk[0],"Text");
	$sSQL	= ' UPDATE users SET ' .
	          ' password=' . tosql(strtoupper(md5("staf123")), "Text") ;
	$sSQL .= ' WHERE ' . $sWhere;
	$rs = &$conn->Execute($sSQL);

	//aktiviti log
	$sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)".
	" VALUES ('Reset password kakitangan - $loginID', 'UPDATE', '" . str_replace( "'", "", $sSQL ) . "', '".get_session('Cookie_userID')."','".$updatedDate."', '".$updatedBy."')";
	$rs = &$conn->Execute($sqlAct);

	print '<script>alert("Katalaluan staf ini telah diset semula kepada \"staf123\"\n.");</script>';
}
//--- End   : reser based on checked box -------------------------------------------------------

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

$SQLdpt = "";
$SQLdpt	= "SELECT * FROM `users` WHERE groupID in (1,2,3,4,5) and isActive = 1 and loginID <> 'superadmin'";
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
print '	<tr valign="">
		<td align="left">&nbsp;
		Batal kakitangan &nbsp;<input type="button" class="btn btn-sm btn-danger waves-effect waves-light" value="Batal" onClick="ITRActionButtonClick(\'delete\');">&nbsp;
		Set semula Kata Laluan &nbsp;<input type="button" class="btn btn-sm btn-warning waves-effect waves-light" value="Set Semula" onClick="ITRActionButtonReset();">
		<br><br></td>
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
						<td nowrap><b>Nama</b></td>
						<td nowrap><b>Id Log Masuk</b></td>
						<td nowrap><b>Emel</b></td>
						<td nowrap align="center"><b>Jenis Capaian</b></td>
						<!-- <td nowrap align="center"><b>Keanggotaan</b></td> -->
						<td nowrap align="center"><b>Tarikh Didaftar</b></td>
					</tr>';	
		while (!$GetAdmin->EOF && $cnt <= $pg) {
			$status = dlookup("userdetails", "status", "userID=" . tosql($GetAdmin->fields(userID), "Text"));
			$colorStatus = "Data";
			if ($status == 1) $colorStatus = "greenText";
			if ($status == 2) $colorStatus = "redText";

			print ' <tr class="table-light">
						<td class="Data" align="right">' . $bil . '</td>
						<td class="Data" style="text-transform:uppercase"><input type="checkbox" class="form-check-input" name="pk[]" value="'.tohtml($GetAdmin->fields(userID)).'">
							<a class="text-danger" href="'.$sFileRef.'&pk='.tohtml($GetAdmin->fields(userID)).'">
							'.$GetAdmin->fields(name).'</td>
						<td class="Data">'.$GetAdmin->fields(loginID).'</td>
						<td class="Data">'.$GetAdmin->fields(email).'</td>
						<td class="Data" align="center">';
						if($GetAdmin->fields(groupID)==1) print '<font class="text-secondary">Staf'; 
						else if ($GetAdmin->fields(groupID)==2) print '<font class="text-primary">Pengurus</font>';
						else if ($GetAdmin->fields(groupID)==3) print '<font class="text-success">Penyelia</font>';
						else if ($GetAdmin->fields(groupID)==4) print '<font class="text-info">Pengurus Kewangan</font>';
						/* print '</td>
						<td class="Data" align="center">';
						if($GetAdmin->fields(memberID)<>'') print 'Anggota'; else print 'Bukan'; */
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