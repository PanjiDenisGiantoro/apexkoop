<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	komoditi_list.php
*          Date 		: 	15/04/2017
*********************************************************************************/
session_start();
if (!isset($StartRec))	$StartRec= 1; 
if (!isset($pg))		$pg= 50;
if (!isset($q))			$q="";
if (!isset($by))		$by="1";
if (!isset($filter))	$filter="ALL";
if (!isset($dept))		$dept="";

include("header.php");	
include("koperasiQry.php"); 
date_default_timezone_set("Asia/Jakarta");	
if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}
$sFileName = '?vw=komoditi_list&mn=907';
$sFileRef  = '?vw=komoditi_edit&mn=907';
$title     = "Senarai Sijil Komoditi";
//--- Begin : deletion based on checked box -------------------------------------------------------
if ($action == "delete") {
	$sWhere = "";
	for ($i = 0; $i < count($pk); $i++) {
	$sSQL = '';
    $sWhere = "komoditi_ID=" . tosql($pk[$i], "Number");
	$sSQL = "DELETE FROM komoditi WHERE " . $sWhere;
	$rs = &$conn->Execute($sSQL);
	}
}
//--- End   : deletion based on checked box -------------------------------------------------------
if ($q <> "") 	{
	if ($by == 1) {			
	$sWhere .= " a.loanID = b.loanID AND a.no_sijil LIKE '%".$q."%'";			
	}
	if ($by == 2) {			
	$sWhere .= " a.loanID = b.loanID AND a.userID LIKE '%".$q."%'";			
	}
}
	$sWhere = " WHERE (" . $sWhere . ")";
	
	$sSQL = "";
    $sSQL = "SELECT a.*,b.* FROM komoditi a, loans b";
	if ($q <> "") {
	$sSQL = $sSQL . $sWhere . ' ORDER BY a.tarikh_beli DESC';
	}else{
	$sSQL = $sSQL .' WHERE a.loanID = b.loanID ORDER BY a.tarikh_beli DESC';
	}
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
<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">
<tr valign="top" class="Header"><td align="left" >
	Carian Melalui 
<select name="by" class="form-select-sm">'; 
if ($by == 1)	print '<option value="1" selected>No. Sijil</option>'; 	else print '<option value="1">No. Sijil</option>';
if ($by == 2)	print '<option value="2" selected>No. Anggota</option>'; else print '<option value="2">No. Anggota</option>';							
print '</select>
	<input type="text" name="q" value="" maxlength="50" size="20" class="form-control-sm">
 	<input type="submit" class="btn btn-sm btn-secondary" value="Cari">&nbsp;&nbsp;&nbsp;';
print '</select>&nbsp;</td></tr><tr valign="top"><td align="left">&nbsp;';
print '</select>&nbsp;';
print '<input type="button" class="btn btn-sm btn-danger" value="Hapus" onClick="ITRActionButtonClick(\'delete\');">';
print '</td></tr><tr valign="top" class="textFont"><td>
<table width="100%">
<tr>
	<td  class="textFont"><input type="checkbox" onClick="ITRViewSelectAll()" class="form-check-input"> Select All</td>					
	<td align="right" class="textFont">Paparan <SELECT name="pg" class="form-select-xs" onchange="doListAll();">';
	if ($pg == 5)	print '<option value="5" selected>5</option>'; 	 	else print '<option value="5">5</option>';				
	if ($pg == 10)	print '<option value="10" selected>10</option>'; 	else print '<option value="10">10</option>';				
	if ($pg == 20)	print '<option value="20" selected>20</option>'; 	else print '<option value="20">20</option>';				
	if ($pg == 30)	print '<option value="30" selected>30</option>'; 	else print '<option value="30">30</option>';				
	if ($pg == 40)	print '<option value="40" selected>40</option>'; 	else print '<option value="40">40</option>';				
	if ($pg == 50)	print '<option value="50" selected>50</option>';	else print '<option value="50">50</option>';				
	if ($pg == 100)	print '<option value="100" selected>100</option>';	else print '<option value="100">100</option>';				
print '</select> setiap mukasurat.</td></tr></table></td></tr>';	
	if ($GetMember->RowCount() <> 0) {  
	$bil = $StartRec;
	$cnt = 1;
	print '
	<tr valign="top" >
	<td valign="top">
	<table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm">
	<tr class="table-primary">
	<td nowrap>&nbsp;</td>
	<td nowrap><b>No. Sijil</b></td>
	<td nowrap align="center"><b>No. Anggota</b></td>
	<td nowrap><b>No. Loan</b></td>
	<td nowrap><b>Barang Komoditi</b></td>
	<td nowrap align="right"><b>Jumlah Pembelian Komoditi (RM)</b></td>
	<td nowrap align="center"><b>Tarikh Pembelian Komoditi</b></td>
</tr>';	
	while (!$GetMember->EOF && $cnt <= $pg) {
	print '<tr>
	<td class="Data" align="right">' . $bil . '&nbsp;</td>
	<td class="Data"><input type="checkbox" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(komoditi_ID)).'">
	<a href="'.$sFileRef.'&pk='.tohtml($GetMember->fields(komoditi_ID)).'">
	'.$GetMember->fields(no_sijil).'</a> 
	<td class="Data" align="center">'.$GetMember->fields(userID).'</td>
	<td class="Data">'.$GetMember->fields(loanNo).'</td>
	<td class="Data">'.dlookup("general", "name", "ID=" . tosql($GetMember->fields('itemType'), "Number")).'</td>
	<td class="Data" align="right">'.number_format($GetMember->fields(amount),2, '.', ',').'</td>
	<td class="Data" align="center">'.toDate("d/m/Y",$GetMember->fields(tarikh_beli)).'</td>
	</tr>';
	$cnt++;
	$bil++;
	$GetMember->MoveNext();
	}
	$GetMember->Close();
print '</table></td></tr><tr><td>';
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
	print '<A href="'.$sFileName.'?&StartRec='.(($i * $pg) + 1 - $pg).'&pg='.$pg.'&q='.$q.'&by='.$by.'&dept='.$dept.'&filter='.$filter.'">';
	print '<b><u>'.(($i * $pg) - $pg + 1).'-'.($i * $pg).'</u></b></a> &nbsp; &nbsp;';
	}
print '</td></tr></table>';
}				
print '</td></tr>
<tr><td class="textFont">Jumlah Rekod : <b>'.$GetMember->RowCount().'</b></td></tr>';
} else {
if ($q == "") {
print '<tr><td align="center"><hr size=1"><b class="textFont">- Tiada Rekod Untuk '.$title.'  -</b><hr size=1"></td></tr>';
} else {
print '<tr><td align="center"><hr size=1"><b class="textFont">- Carian rekod "'.$q.'" tidak jumpa  -</b><hr size=1"></td></tr>';
}
}
print '</table></td></tr></table></form>';
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
		document.location = "' . $sFileName . '?&StartRec=1&pg=" + c.options[c.selectedIndex].value;
	}
</script>';
?>