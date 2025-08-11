<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	selYear.php
*		   Description	:	Selection Year Option
*		   Parameter	:   $rpt, $id
*          Date 		: 	15/12/2003
*********************************************************************************/
include("common.php");	
	

$today = date("F j, Y, g:i a");                 

if ($rpt == "") {
	print '	<script>
				alert ("Pengguna tidak boleh akses mukasurat ini...!");
				window.close();
			</script>';
			exit;
}

if (!isset($yr)) $yr	= date("Y");                 		

if ($action == "Jana Laporan") {
	$msg	= "";
	if ($yr == "") $msg = "Tiada Tahun dimasukkan...";
	if ($msg <> "") {
		print '<script>alert("'.$msg.'");</script>';
	} else {
		print '
		<script>
			var rptURL;
			rptURL = "'.$rpt.'.php?yr='.$yr.'&pk='.$id.'&id='.$id.'";
			window.open (rptURL, "mthyear","scrollbars=yes,resizable=yes,toolbars=yes,location=no,menubar=yes");
			window.close();
		</script>	';
	}
}

print '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>'.$emaNetis.'</title>
<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
</head>
<body leftmargin="5" topmargin="5" class="bodyBG">';

print '
<form name="FrmSelection" action="'.$PHP_SELF.'" method="post">
	<input type="hidden" name="rpt" value="'.$rpt.'">
	<input type="hidden" name="id" value="'.$id.'">
	<table border="0" cellpadding="3" cellspacing="3" class="table table-sm" style="margin: 10px;font-size: 10pt;width:96% !important" align="center" height="100px">
		<tr class="table-light">
			<td class="textFont" align="right"><b>Tahun</b></td>
			<td class="textFont">
                                                                <select name="yr" class="form-select-sm" onchange="document.MyForm.submit();">';
                                                                for ($j = 2018; $j <= 2050; $j++) {
                                                                        print '	<option value="'.$j.'"';
                                                                        if ($yy == $j) print 'selected';
                                                                        print '>'.$j;
                                                                }
                                                                        print '</select>			
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" valign="middle"><input type="submit" name="action" value="Jana Laporan" class="btn btn-primary"></td>
		</tr>
	</table>
</form>


</body>
</html>';
?>