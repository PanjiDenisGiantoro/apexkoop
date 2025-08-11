<?php
/*********************************************************************************
*		   Project		:iKOOP.com.my
*		   Filename		:
*		   Date			:
*		   Amended		:
*********************************************************************************/
session_start();
if (!isset($StartRec))	$StartRec= 1;
if (!isset($pg))		$pg= 50;
if (!isset($q))			$q="";
if (!isset($by))		$by="0";
if (!isset($filter))	$filter="ALL";
if (!isset($dept))		$dept="";
if (!isset($mm))	$mm=date("n");//date("m");
if (!isset($yy))	$yy=date("Y");

include("header.php");
include("koperasiQry.php");	
date_default_timezone_set("Asia/Kuala_Lumpur");

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <>	2 OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'");parent.location.href = "index.php";</script>';
}
$IDName = get_session("Cookie_userName");

$sFileName = "?vw=rpthutanglapuk&mn=$mn";
$sFileRef  = "?vw=rpthutanglapuk&mn=$mn";
$sFileRefRenew  = 'rpthutanglapuk.php';
$title	   = "Laporan Tertunggak yang Belum DiHapuskira";

//--- Prepare department list
$deptList =	Array();
$deptVal  =	Array();
$sSQL =	"	SELECT a.departmentID, b.code as deptCode, b.name as deptName
			FROM userdetails a,	general	b
			WHERE a.departmentID = b.ID
			AND	  a.status = 1
			GROUP BY a.departmentID";
$rs	= &$conn->Execute($sSQL);
if ($rs->RowCount()	<> 0){
	while (!$rs->EOF) {
		array_push ($deptList, $rs->fields(deptName));
		array_push ($deptVal, $rs->fields(departmentID));
		$rs->MoveNext();
	}
}

//$GetLoan = ctLoanStatusDept($q,$by,$filter,$dept);

//function ctLoanStatusDept($q,$by,$status,$dept,$id = 0) {
$status = $filter;

if($mm!="" && $yy!=""){
	
	$sSQL = "";
    $sSQL = "SELECT  a.name, b.*, c.* FROM users a, userdetails b, accounthl c where b.statusHL = 1 AND a.userID = b.userID AND a.userID = c.userID  ";

	//if ($q <> "") {
	//$sSQL = $sSQL .' where b.statusHL = 1 AND a.userID = b.userID ';
	//} else if ($mm == "ALL"){
//	$sSQL = $sSQL .' where b.statusHL =1 ';
	//} else {$sSQL = $sSQL .' where b.statusHL =1 ';};

	
	
	$GetListIns = &$conn->Execute($sSQL);	
	$GetListIns->Move($StartRec-1);

$TotalRec =	$GetListIns->RowCount();
$TotalPage =  ($TotalRec/$pg);
}

print '<div class="table-responsive">
<form name="MyForm" action=' .$sFileName . ' method="post">
<input type="hidden" name="action">
<h5 class="card-title">'.strtoupper($title).' </h5>
<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">

	
	<tr	valign="top" class="textFont">
		<td>
			<table width="100%">
				<tr>
					<td	 class="textFont" align ="left">&nbsp;';
				//	Pilihan Proses : 
				//	<select	name="filter" class="Data" onchange="document.MyForm.submit();">';
				
				/*	print '<option value="ALL">Semua';
					for	($i	= 0; $i	< count($biayaList); $i++) {
					//if($i	<> 3 ||	$i<>4 ){
					print '	<option	value="'.$biayaVal[$i].'" ';
					if ($filter	== $biayaVal[$i]) print	' selected';
					print '>'.$biayaList[$i];
					//}
					}
					print '</select>&nbsp;';*/
					
					if($filter==3) print '&nbsp;&nbsp;Cetak dokumen proses :&nbsp;<input type="button" class="btn btn-sm btn-secondary" value="Cetak" onClick="ITRActionButtonDoc();">&nbsp;';
					
					if ($filter	== 4) print 'Ubah ke proses kembali &nbsp;<input type="button" class="btn btn-sm btn-primary" value="Ubah" onClick="ITRActionButtonUbah();">';
			
					print '</td>
					<td	align="right" class="textFont">

					<!--input 4ype="button" class="but" value="Status" onClick="ITBActionButtonStatus();"-->';
                                                                                                        echo papar_ms($pg);
                                                                                print '</td>
				</tr>';
	if(get_session("Cookie_groupID")==2 && $filter==3){
			print '<tr>
			<td	 class="textFont" align ="left">Batal Kelulusan :&nbsp;<input type="button" class="btn btn-sm btn-danger" value="Batal" onClick="ITRActionButtonClick(\'batal\');">&nbsp;Sebab:&nbsp;<input type="text" name="sebab" value="" maxlength="60" size="50" class="Data"></td>
			</tr>';
	}
	print '	</table>
		</td>
	</tr>';
	if ($GetListIns->RowCount() <>	0) {
		$bil = $StartRec;
		$cnt = 1;
		print '
		<tr	valign="top" >
			<td	valign="top">
				<table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm table-striped">
					<tr class="table-primary">
						<td	nowrap><b>Bil</b></td>											
						<td	width="20" nowrap><b>Nama</b></td>					
						<td width="20" nowrap><b>No. Ahli</b></td>
						<td	nowrap><b>Baki Pinjaman (RM)</b></td>
						<td	nowrap><b>Tolak Agihan Yuran (RM)</b></td>
						<td	nowrap align="center"><b>Baki Selepas Agihan (RM)</b></td>
					    <td	nowrap align="center"><b>Denda Lewat Sebulan (RM)</b></td>						
						<td	nowrap align="center"><b>Premium<br>Jumlah Bulan Terdahulu (RM)</b></td>
						<td	nowrap align="center"><b>Jumlah Denda Lewat (RM)</b></td>
						<td	nowrap align="center"><b>Jumlah Bulan Terkini (RM)</b></td>
						<td	nowrap align="center"><b>Jumlah Denda Lewat Terkini (RM)</b></td>						
						<td	nowrap align="center"><b>Baki Pinjaman (RM)</b></td>
						<td	nowrap align="center"><b>Tindakan</b></td>
						<td	nowrap align="center"><b>Catatan</b></td>
				';
		$amtLoan = 0;
		while (!$GetListIns->EOF && $cnt <= $pg) {
		$nama = $GetListIns->fields(name);
		$userID = $GetListIns->fields(userID);
				
			print '	<tr>
						<td	class="Data" align="right">' . $bil	. '&nbsp;</td>
					    <td	class="Data">'.$nama.'</td>
					    <td	class="Data" align="left">'.$userID.'</td>						
						<td	class="Data" align="left">&nbsp;</td>
						<td	class="Data"></td>	
						<td	class="Data"></td>
						<td	class="Data"></td>
						<td	class="Data"></td>	
						<td	class="Data"></td>						
						<td	class="Data"></td>
						<td	class="Data"></td>
						<td	class="Data"></td>
						<td	class="Data"></td>	
						<td	class="Data"></td>							
						</tr>';
				$cnt++;
				$bil++;
			$GetListIns->MoveNext();
		}
		$GetListIns->Close();
		print '
				</table>
			</td>
		</tr>
		<tr>
			<td>';
				if ($TotalRec >	$pg) {
					print '
					<table border="0" cellspacing="5" cellpadding="0"  class="textFont"	width="100%">';
					if ($TotalRec %	$pg	== 0) {
						$numPage = $TotalPage;
					} else {
						$numPage = $TotalPage +	1;
					}
					print '<tr><td class="textFont"	valign="top" align="left">Rekod	Dari : <br>';
					for	($i=1; $i <= $numPage; $i++) {
						if(is_int($i/10)) print	'<br />';
						print '<A href="'.$sFileName.'&StartRec='.(($i	* $pg) + 1 - $pg).'&pg='.$pg.'&q='.$q.'&by='.$by.'&filter='.$filter.'">';
						print '<b><u>'.(($i	* $pg) - $pg + 1).'-'.($i *	$pg).'</u></b></a>&nbsp;&nbsp;';
					}
					print '</td>
						</tr>
					</table>';
				}
		print '
			</td>
		</tr>
		<tr>
			<td	class="textFont">Jumlah	Rekod :	<b>' . $GetListIns->RowCount()	. '</b></td>
		</tr>';
		
	} else {
		if ($q == "") {
			print '
			<tr><td	align="center"><hr size=1"><b class="textFont">- Tiada Rekod Untuk '.$title.'  -</b><hr	size=1"></td></tr>';
		} else {
			print '
			<tr><td	align="center"><hr size=1"><b class="textFont">- Carian	rekod "'.$q.'" tidak jumpa	-</b><hr size=1"></td></tr>';
		}
	}
print '
</table>
</form></div>';

include("footer.php");

print '
<script	language="JavaScript">
	var	allChecked=false;
	function ITRViewSelectAll()	{
		e =	document.MyForm.elements;
		allChecked = !allChecked;
		for(c=0; c<	e.length; c++) {
		  if(e[c].type=="checkbox" && e[c].name!="all")	{
			e[c].checked = allChecked;
		  }
		}
	}

	function ITRActionButtonClick(v) {
		  e	= document.MyForm;
		  if(e==null) {
			alert(\'Sila pastikan nama form	diwujudkan.!\');
		  }	else {
			count=0;
			for(c=0; c<e.elements.length; c++) {
			  if(e.elements[c].name=="pk[]"	&& e.elements[c].checked) {
				count++;
			  }
			}

			if(count==0) {
			  alert(\'Sila pilih rekod yang hendak di\'	+ v	+\'kan.\');
			} else {
			  if(confirm(count + \'	rekod hendak di\' +	v +\'kan. Adakah anda pasti?\')) {
				e.action.value = v;
				e.submit();
			  }
			}
		  }
		}

	function ITRActionButtonStatus() {
		e =	document.MyForm;
		if(e==null)	{
			alert(\'Sila pastikan nama form	diwujudkan.!\');
		} else {
			count=0;
			for(c=0; c<e.elements.length; c++) {
				if(e.elements[c].name=="pk[]" && e.elements[c].checked)	{
					count++;
					pk = e.elements[c].value;
				}
			}

			if(count !=	1) {
				alert(\'Sila pilih satu	rekod sahaja untuk kemaskini status\');
			} else {
				window.open(\'loanStatus.php?pk=\' + pk,\'status\',\'top=50,left=50,width=500,height=250,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');
			}
		}
	}

	function ITRActionButtonUbah() {
		e =	document.MyForm;
		if(e==null)	{
			alert(\'Sila pastikan nama form	diwujudkan.!\');
		} else {
			count=0;
			for(c=0; c<e.elements.length; c++) {
				if(e.elements[c].name=="pk[]" && e.elements[c].checked)	{
					count++;
					pk = e.elements[c].value;
				}
			}

			if(count !=	1) {
				alert(\'Sila pilih satu	rekod sahaja untuk proses kembali\');
			} else {
				e.action.value = \'ubah\';
				e.submit();
			}
		}
	}
	
	function ITRActionButtonDoc() {
		e =	document.MyForm;
		if(e==null)	{
			alert(\'Sila pastikan nama form	diwujudkan.!\');
		} else {
			count=0;
			for(c=0; c<e.elements.length; c++) {
				if(e.elements[c].name=="pk[]" && e.elements[c].checked)	{
					count++;
					pk = e.elements[c].value;
				}
			}

			if(count !=	1) {
				alert(\'Sila pilih satu	rekod cetakan dokumen proses!\');
			} else {
				window.open(\'biayaDokumenPrint.php?action=print&pk=\' + pk,\'status\',\'top=50,left=50,width=850,height=550,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');
			}
		}
	}

	function doListAll() {
		c =	document.forms[\'MyForm\'].pg;
		document.location =	"' . $sFileName	. '&StartRec=1&pg=" + c.options[c.selectedIndex].value+"&filter='.$filter.'";
	}

	function ITRActionButtonClickStatus(v) {
		  var strStatus="";
		  e	= document.MyForm;
		  if(e==null) {
			alert(\'Sila pastikan nama form	diwujudkan.!\');
		  }	else {
			count=0;
			j=0;
			for(c=0; c<e.elements.length; c++) {
			  if(e.elements[c].name=="pk[]"	&& e.elements[c].checked) {
				pk = e.elements[c].value;
				strStatus =	strStatus +	":"	+ pk;
				count++;
			  }
			}

			if(count==0) {
			  alert(\'Sila pilih rekod yang	hendak di\'	+ v	+ \'kan.\');
			} else {
			  if(confirm(count + \'	rekod hendak di\' +	v +	\'kan?\')) {
			  //e.submit();
			  window.location.href ="memberAktif.php?pk=" +	strStatus;
			  }
			}
		  }
		}
		
		function CheckField(act) {
	    e = document.MyForm;
		count = 0;	
		for(c=0; c<e.elements.length; c++) {
		 	  
		  if( e.elements[c].value==\'\') {		
            count++;
		  }
		  }		

		//if(count==0) {
			e.action.value = \'hantarpengesahan\';
			e.submit();
		//}else{
		//		alert(\'Ruang amaun perlu diisi!\');
		//}

	}

</script>';
?>
