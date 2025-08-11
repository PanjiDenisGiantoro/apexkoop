<?php

/*********************************************************************************
 *          Project		:	iKOOP.com.my
 *          Filename		: 	general.php
 *          Date 		: 	13/7/06
 *********************************************************************************/
$xlink = "";
if (@$_POST['selCodeACC'] != '') {
	$cat = @$_POST['selCodeACC'];
	$xlink = "&selCodeACC=" . @$_POST['selCodeACC'];
} else {
	$cat = @$_REQUEST['cat'];
	$xlink = "&selCodeACC=" . @$_REQUEST['cat'];
}

if (!isset($StartRec))	$StartRec = 1;
if (!isset($cat))		$cat = "";

include("header.php");

if (get_session("Cookie_groupID") <> 2 and get_session("Cookie_groupID") <> 4) {
	print '<script>alert("' . $errPage . '");parent.location.href = "index.php";</script>';
}
if (!(in_array($cat, $basicValACC))) {
	print '	<script>
				alert ("' . $cat . ' - Kategori ini tidak wujud...!");
				window.location = "index.php";
			</script>';
}

$sFileName = '?vw=generalACC&mn=904' . $xlink;
$sFileRef  = 'generalAddUpdateACC.php';
$title     =  $basicListACC[array_search($cat, $basicValACC)];

//--- Begin : deletion based on checked box -------------------------------------------------------
if ($action == "hapus") {
	$sWhere = "";
	for ($i = 0; $i < count($pk); $i++) {
		$sWhere = "ID=" . tosql($pk[$i], "Number");
		$sSQL = "DELETE FROM generalacc WHERE " . $sWhere;
		$rs = &$conn->Execute($sSQL);

		$sWhere = "parentID=" . tosql($pk[$i], "Number");
		$sSQL = "DELETE FROM generalacc WHERE " . $sWhere;
		$rs = &$conn->Execute($sSQL);
	}
}
//--- End   : deletion based on checked box -------------------------------------------------------

$GetGeneral = ctGeneralACC("ALL", $cat);
$GetGeneral->Move($StartRec - 1);

print '
<form name="ITRViewResults" action=' . $sFileName . ' method="post">
<input type="hidden" name="action">
<input type="hidden" name="cat" value="' . $cat . '">
<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">
<h5 class="card-title">' . strtoupper($title) . '</h5>';
if ($GetGeneral->RowCount() <> 0) {
	print '    
	    <tr valign="top" class="Header">
		   	<td align="left">';
	//if ($cat <> 'O') {
	print '
				<input type="button" value="Tambah" class="btn btn-sm btn-primary" onClick="ITRAddButtonClick(\'tambah\');">
		        <input type="button" class="btn btn-sm btn-danger" value="Hapus" onClick="ITRActionButtonClick(\'hapus\');"> ';
	//}
	print '           
			</td>
		</tr>
	    <tr valign="top" >
	    	<td>
				<table border="0" cellspacing="0" cellpadding="3" width="100%"><tr><td>
						<table border="0" cellspacing="0" cellpadding="3" width="100%">
						<tr>
							<td class="textFont">';
	listGeneral("ALL", 1);
	print '				</td>
						</tr>
					</table>
				</td></tr></table>
			</td>
		</tr>
		<tr>
			<td class="textFont">Jumlah Rekod : <b>' . $RecNum . '</b></td>
		</tr>';
} else {
	print '
	    <tr valign="top" class="Header">
		   	<td align="center" >
				<input type="button" value="tambah" class="but" onclick=Javascript:window.open("' . $sFileRef . '?action=tambah&selCodeACC=' . $cat . '&cat=' . $cat . '","pop","top=50,left=50,width=700,height=450,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");>
			</td>
		</tr>		
		<tr><td align="center"><hr size=1"><b class="textFont">- Tiada Rekod -</b><hr size=1"></td></tr>';
}
print ' 
</table>
</form>';

include("footer.php");

print '
<script language="JavaScript">
	var allChecked=false;
	function ITRViewSelectAll() {
	    e = document.ITRViewResults.elements;
	    allChecked = !allChecked;
	    for(c=0; c< e.length; c++) {
	      if(e[c].type=="checkbox" && e[c].name!="all") {
	        e[c].checked = allChecked;
	      }
	    }
	}

	function ITRActionButtonClick(v) {
	      e = document.ITRViewResults;
	      if(e==null) {
	        alert(\'Cannot \' + v + \'. Find must return some result to perform the operation.\');
	      } else {
	        count=0;
	        for(c=0; c<e.elements.length; c++) {
	          if(e.elements[c].name=="pk[]" && e.elements[c].checked) {
	            count++;
	          }
	        }
	        
	        if(count==0) {
	          alert(\'Select the row(s) to \' + v + \'.\');
	        } else {
	          if(confirm(v + \' \' + count + \' rekod ?\')) {
	            e.action.value = v;
	            e.submit();
	          }
	        }
	      }
	    }	   
		
	function ITRAddButtonClick(v) {
	      e = document.ITRViewResults;
		  pk = "";
	      if(e==null) {
	        alert(\'Cannot \' + v + \'. Find must return some result to perform the operation.\');
	      } else {
	        count=0;
	        for(c=0; c<e.elements.length; c++) {
	          if(e.elements[c].name=="pk[]" && e.elements[c].checked) {
	            count++;
				pk = e.elements[c].value;
	          }
	        }
	        
	        if(count > 1) {
	          alert(\'Select one row only to \' + v + \'.\');
	        } else {
				window.open("' . $sFileRef . '?action=tambah&cat=' . $cat . '&sub=" + pk,"sort","top=50,left=50,width=700,height=650,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");
	        }
	      }
	    }
</script>';

function ctGeneralACC($id, $cat)
{
	global $conn;
	$sSQL = "";
	$sWhere = "";
	$sWhere = "category = " . tosql($cat, "Text");
	if ($id == "ALL") {
		$sWhere .= " AND parentID = 0";
	} else {
		$sWhere .= " AND parentID = " . tosql($id, "Number");
	}
	$sWhere = " WHERE (" . $sWhere . ")";
	$sSQL = "SELECT	 * FROM generalacc";
	$sSQL = $sSQL . $sWhere . ' ORDER BY code';
	$rs = &$conn->Execute($sSQL);
	return $rs;
}

function listGeneral($id, $level)
{
	global $setLevel;
	global $sFileName;
	global $sFileRef;
	global $RecNum;
	global $cat;

	$GetGeneral	= '$GetGeneral' . $level;
	$generalID	= '$generalID' . $level;

	$generalID = array();
	$generalCode = array();
	$generalName = array();
	$generalParentID = array();
	$GetGeneral = ctGeneralACC($id, $cat);

	if ($GetGeneral->RowCount() <> 0) {
		$RecNum = $RecNum + $GetGeneral->RowCount();
		while (!$GetGeneral->EOF) {
			array_push($generalID, $GetGeneral->fields(ID));
			array_push($generalCode, $GetGeneral->fields(code));
			array_push($generalName, $GetGeneral->fields(name));
			array_push($generalParentID, $GetGeneral->fields(parentID));
			$GetGeneral->MoveNext();
		}
	}

	print '<ul>';
	$level++;
	$i = '$i' . $level;
	for ($i = 0; $i < count($generalID); $i++) {
		if ($id == "ALL") print '<li id="foldlist"><b>';
		else print '<li id="node"><b>';
		//if ($level <= $setLevel) {
		print '<input type="checkbox" class="form-check-input" name="pk[]" value="' . $generalID[$i] . '">';
		//} else {
		//	print '&nbsp;&nbsp;&nbsp;';
		//}
		print '
		 <font class="redText">' . $generalCode[$i] . '</font>&nbsp;-&nbsp;
		 <a onclick=Javascript:window.open("' . $sFileRef . '?action=kemaskini&cat=' . $cat . '&pk=' . $generalID[$i] . '&sub=' . $generalParentID[$i] . '","pop","top=50,left=50,width=700,height=650,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no");>
		 <font class="blueText">' . $generalName[$i] . '</font></a></b></li>';
		//if 	($level <= $setLevel){
		listGeneral($generalID[$i], $level);
		//}
	}
	print '</ul>';
}
