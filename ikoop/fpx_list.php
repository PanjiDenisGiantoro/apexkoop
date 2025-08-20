<?php
/*********************************************************************************
*           Project: iKOOP.com.my
*           Filename: fpx_list.php
*           Date: 07/11/2023
*********************************************************************************/
session_start();
if (!isset($StartRec)) $StartRec = 1;
if (!isset($pg)) $pg = 50;
if (!isset($q)) $q = "";
if (!isset($by)) $by = "1";
if (!isset($filter)) $filter = "0";
if (!isset($dept)) $dept = "";

include("header.php");
include("koperasiQry.php");
date_default_timezone_set("Asia/Jakarta");
if (get_session('Cookie_userID') == "" OR get_session("Cookie_koperasiID") <> 0) {
    print '<script>alert("' . $errPage . '");parent.location.href = "index.php";</script>';
}

$sFileName = '?vw=fpx_list&mn=907';
$sFileRef = '?vw=memberEdit&mn=907';
$title = "Senarai Mohon FPX";

// --- Begin: deletion based on checked box -------------------------------------------------------
if ($action == "delete") {
    $sWhere = "";
    for ($i = 0; $i < count($pk); $i++) {
        $sSQL = '';
        $updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");	

        $sWhere = "userID=" . tosql($pk[$i], "Number");
        $sSQL = "DELETE FROM fpx WHERE " . $sWhere;
        $rs = &$conn->Execute($sSQL);
        //log aktiviti
        $sqlAct = "INSERT INTO activitylog (`report`, `sqlType`, `sql`, `byID`, `activityDate`, `activityBy`)".
        " VALUES ('Hapus senarai fpx koperasi - " . implode(', ', $loginID) . "', 'UPDATE', '" . str_replace( "'", "", $sSQL ) . "', '".get_session('Cookie_userID')."','".$updatedDate."', '".$updatedBy."')";
        $rs = &$conn->Execute($sqlAct);
    } 
}
// --- End: deletion based on checked box -------------------------------------------------------
//$sWhere = "";
//$sWhere = " c.fpxStatus = " . tosql($filter,"Number");
$sWhere = " a.userID = b.userID AND a.fpxStatus = " . tosql($filter,"Number");

if ($q <> "") {
    if ($by == 1) {			
        $sWhere .= " AND a.userID = b.userID AND b.name LIKE '%".$q."%'";			
    }
    if ($by == 2) {			
        $sWhere .= " AND a.userID = b.userID AND b.loginID LIKE '%".$q."%'";			
    }
}

$sSQL = "";
$sWhere2 = " WHERE (" . $sWhere . ")";
// $sSQL = "SELECT DISTINCT a.*
//             FROM fpx a, users b, userdetails c";
// $sSQL = $sSQL .' ORDER BY a.tarikh_fpx DESC';

$sSQL = "SELECT DISTINCT a.*, b.*
         FROM fpx a, users b";

    if($filter == '1'){
        $sSQL = $sSQL . $sWhere2 .' ORDER BY a.tarikh_fpx DESC';
    }
    else{
        $sSQL = $sSQL . $sWhere2 .' ORDER BY a.tarikh_fpx DESC';
    }
$GetMember = &$conn->Execute($sSQL);
$GetMember->Move($StartRec - 1);

$TotalRec = $GetMember->RowCount();
$TotalPage =  ($TotalRec / $pg);

print '
<form name="MyForm" action=' . $sFileName . ' method="post">
<input type="hidden" name="action">
<input type="hidden" name="pk" value="' . $pk . '">
<input type="hidden" name="filter" value="' . $filter . '">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="card-title">' . strtoupper($title) . '</h5>
    <input type="button" class="btn btn-md btn-primary" value="+ Mohon Baru" onClick="window.location.href=\'?vw=fpx_add&mn=907\'"/>
</div>


<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">
<tr valign="top" class="Header">
    <td align="left">
        Carian Melalui
        <select name="by" class="form-select-sm">
            ';
if ($by == 1) print '<option value="1" selected>Nama Koperasi</option>';
else print '<option value="1">Nama Koperasi</option>';
if ($by == 2) print '<option value="2" selected>Singkatan Koperasi</option>';
else print '<option value="2">Singkatan Koperasi</option>';
print '
        </select>
        <input type="text" name="q" value="" maxlength="50" size="25" class="form-control-sm">&nbsp;&nbsp;
        <input type="submit" class="btn btn-sm btn-secondary" value="Cari">&nbsp;&nbsp;&nbsp;&nbsp;';
        if(get_session('Cookie_groupID') <> 2){
            //nothing
        }
        else{
            print'<input type="button" class="btn btn-sm btn-danger" value="Hapus" onClick="ITRActionButtonClick(\'delete\');">
            <input type="button" class="btn btn-sm btn-primary" value="Proses" onClick="ITRActionButtonClickStatus(\'proses\');">';
        }
        print'
        <div><br>
        Jenis
                <select name="filter" class="form-select-sm" onchange="document.MyForm.submit();">';
                //print '<option value="ALL">Semua';
                for ($i = 0; $i < count($fpxList); $i++) {
                    if($i == 0 ||$i == 1||$i == 2){
                    if ($fpxVal[$i] < 3) {
                        print '	<option value="'.$fpxVal[$i].'" ';
                        if ($filter == $fpxVal[$i]) print ' selected';
                        print '>'.$fpxList[$i];
                    }
                }
            }
			print' </select>';
print '</select>&nbsp;</td></tr><tr valign="top">&nbsp;';
print '</select>';
print '</tr><tr valign="top" class="textFont"><td>
<table width="100%">
<tr><br>';
    if(get_session('Cookie_groupID') <> 2){
        //nothing
    }
    else{
        print'<td  class="textFont"><input type="checkbox" onClick="ITRViewSelectAll()" class="form-check-input"> Select All</td>';
    }
    print'<td align="right" class="textFont">Paparan <SELECT name="pg" class="form-select-xs" onchange="doListAll();">';
if ($pg == 5) print '<option value="5" selected>5</option>';
else print '<option value="5">5</option>';
if ($pg == 10) print '<option value="10" selected>10</option>';
else print '<option value="10">10</option>';
if ($pg == 20) print '<option value="20" selected>20</option>';
else print '<option value="20">20</option>';
if ($pg == 30) print '<option value="30" selected>30</option>';
else print '<option value="30">30</option>';
if ($pg == 40) print '<option value="40" selected>40</option>';
else print '<option value="40">40</option>';
if ($pg == 50) print '<option value="50" selected>50</option>';
else print '<option value="50">50</option>';
if ($pg == 100) print '<option value="100" selected>100</option>';
else print '<option value="100">100</option>';
print '</select> setiap mukasurat.</td></tr></table></td></tr>';
if ($GetMember->RowCount() <> 0) {
    $bil = $StartRec;
    $cnt = 1;

    //fpx diterima
    if($filter == '1'){
        print '<tr valign="top" >
        <td valign="top">
        <table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm">
        <tr class="table-danger">
        <td nowrap>&nbsp;</td>
        <td nowrap><b>No./Nama Koperasi</b></td>
        <td nowrap align="center"><b>Singkatan Koperasi</b></td>
        <td nowrap align="center"><b>&nbsp;Dokumen FPX</b></td>
        <td nowrap align="center"><b>Status</b></td>
        <td nowrap align="center"><b>Tarikh Mohon FPX</b></td>
        </tr>';
        while (!$GetMember->EOF && $cnt <= $pg) {
            $fpxStatus = dlookup("fpx", "fpxStatus", "userID=" . tosql($GetMember->fields(userID), "Text"));
            $name = dlookup("users", "name", "userID=" . tosql($GetMember->fields(userID), "Text"));
            $kopNum = dlookup("userdetails", "kopNum", "userID=" . tosql($GetMember->fields(userID), "Text"));
            $loginID = dlookup("users", "loginID", "userID=" . tosql($GetMember->fields(userID), "Text"));
            $colorStatus = "Data";
				if ($fpxStatus == 0) $colorStatus = "text-success";
				if ($fpxStatus == 1) $colorStatus = "text-primary";
				if ($fpxStatus == 2) $colorStatus = "text-warning";

            print '<tr>
            <td class="Data" align="right">' . $bil . '&nbsp;</td>
            <td class="Data">';
            if(get_session('Cookie_groupID') <> 2){
                //nothing
            }
            else{
                print'<input type="checkbox" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(userID)).'">';
            }
            print'<a class="text-danger" href="'.$sFileRef.'&pk='.tohtml($GetMember->fields(userID)).'">
            '.$kopNum.' - '.strtoupper($name).'</a>
            <td class="Data" align="center">&nbsp;'.$loginID.'</td>';
            if($GetMember->fields(dokumen_fpx) == NULL){
                print '<td class="Data">&nbsp;</td>';
            }
            else {
                print '<td class="Data" align="center"><button type="button" class="btn btn-outline-secondary" onClick="window.open(\'upload_fpx/' .$GetMember->fields(dokumen_fpx). '\', \'pop\', \'top=70,left=70,width=900,height=650,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');"><i class="far fa-file-pdf text-secondary"></i> Paparan Fail</button>&nbsp;
                <input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Muat Naik Semula"  onclick= "Javascript:window.location.href=\'?vw=reuploadFpx&pk='.$GetMember->fields(userID).'\';">';
            }
            print '<td class="Data" align="center">&nbsp;<font class="'.$colorStatus.'">'.$fpxList[$fpxStatus].'</font></td>
            <td class="Data" align="center">&nbsp;'.toDate("d/m/Y",$GetMember->fields(tarikh_fpx)).'</td>
            </tr>';
            $cnt++;
            $bil++;
            $GetMember->MoveNext();
        }
    }
    
    //bukan fpx diterima
    else{
        print '<tr valign="top" >
        <td valign="top">
        <table border="0" cellspacing="1" cellpadding="2" width="100%" class="table table-sm">
        <tr class="table-danger">
        <td nowrap>&nbsp;</td>
        <td nowrap><b>No./Nama Koperasi</b></td>
        <td nowrap align="center"><b>Singkatan Koperasi</b></td>
        <td nowrap align="center"><b>Dokumen FPX</b></td>
        <td nowrap align="center"><b>Status</b></td>
        <td nowrap align="center"><b>Tarikh Mohon FPX</b></td>
        </tr>';
        while (!$GetMember->EOF && $cnt <= $pg) {
            $fpxStatus = dlookup("fpx", "fpxStatus", "userID=" . tosql($GetMember->fields(userID), "Text"));
            $name = dlookup("users", "name", "userID=" . tosql($GetMember->fields(userID), "Text"));
            $kopNum = dlookup("userdetails", "kopNum", "userID=" . tosql($GetMember->fields(userID), "Text"));
            $loginID = dlookup("users", "loginID", "userID=" . tosql($GetMember->fields(userID), "Text"));
            $colorStatus = "Data";
				if ($fpxStatus == 0) $colorStatus = "text-success";
				if ($fpxStatus == 1) $colorStatus = "text-primary";
				if ($fpxStatus == 2) $colorStatus = "text-warning";

            print '<tr>
            <td class="Data" align="right">' . $bil . '&nbsp;</td>
            <td class="Data">';
            if(get_session('Cookie_groupID') <> 2){
                //nothing
            }
            else{
                print'<input type="checkbox" class="form-check-input" name="pk[]" value="'.tohtml($GetMember->fields(userID)).'">';
            }
            print'<a class="text-danger" href="'.$sFileRef.'&pk='.tohtml($GetMember->fields(userID)).'">
            '.$kopNum.' - '.strtoupper($name).'</a>
            <td class="Data" align="center">&nbsp;'.$loginID.'</td>';
            if($GetMember->fields(dokumen_fpx) == NULL){
                print '<td class="Data">&nbsp;</td>';
            }
            else {
                print '<td class="Data" align="center"><button type="button" class="btn btn-outline-secondary" onClick="window.open(\'upload_fpx/' .$GetMember->fields(dokumen_fpx). '\', \'pop\', \'top=70,left=70,width=900,height=650,scrollbars=yes,resizable=yes,toolbars=no,location=no,menubar=no\');"><i class="far fa-file-pdf text-secondary"></i> Paparan Fail</button>
                <!-- <input type="button" class="btn btn-secondary waves-effect" name="GetPicture" value="Muat Naik Semula"  onclick= "Javascript:window.location.href=\'?vw=reuploadFpx&pk='.$GetMember->fields(userID).'\';"> -->';
            }
            print '<td class="Data" align="center">&nbsp;<font class="'.$colorStatus.'">'.$fpxList[$fpxStatus].'</font></td>
            <td class="Data" align="center">&nbsp;'.toDate("d/m/Y",$GetMember->fields(tarikh_fpx)).'</td>
            </tr>';
            $cnt++;
            $bil++;
            $GetMember->MoveNext();
        }
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
        for ($i = 1; $i <= $numPage; $i++) {
            print '<A class="text-danger" "href="' . $sFileName . '?&StartRec=' . (($i * $pg) + 1 - $pg) . '&pg=' . $pg . '&q=' . $q . '&by=' . $by . '&dept=' . $dept . '&filter=' . $filter . '">';
            print '<b><u>' . (($i * $pg) - $pg + 1) . '-' . ($i * $pg) . '</u></b></a> &nbsp; &nbsp;';
        }
        print '</td></tr></table>';
    }
    print '</td></tr>
    <tr><td class="textFont">Jumlah Rekod : <b>' . $GetMember->RowCount() . '</b></td></tr>';
} else {
    if ($q == "") {
        print '<tr><td align="center"><hr size=1"><b class="textFont">- Tiada Rekod Untuk ' . $title . '  -</b><hr size=1"></td></tr>';
    } else {
        print '<tr><td align="center"><hr size=1"><b class="textFont">- Carian rekod "' . $q . '" tidak jumpa  -</b><hr size=1"></td></tr>';
    }
}
print '</table></td></tr></table></form>';
include("footer.php");
print '
<script language="JavaScript">
    var allChecked = false;
    function ITRViewSelectAll() {
        e = document.MyForm.elements;
        allChecked = !allChecked;
        for (c = 0; c < e.length; c++) {
            if (e[c].type == "checkbox" && e[c].name != "all") {
                e[c].checked = allChecked;
            }
        }
    }

    function ITRActionButtonClick(v) {
        e = document.MyForm;
        if (e == null) {
            alert(\'Sila pastikan nama form diwujudkan.!\');
        } else {
            count = 0;
            for (c = 0; c < e.elements.length; c++) {
                if (e.elements[c].name == "pk[]" && e.elements[c].checked) {
                    count++;
                }
            }

            if (count == 0) {
                alert(\'Sila pilih rekod yang hendak di\' + v + \'kan.\');
            } else {
                if (confirm(count + \' rekod hendak di\' + v + \'kan?\')) {
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
          } 
          else if (count > 1){
              alert(\'Sila pilih hanya satu rekod yang hendak di\' + v + \'kan.\');
          }
              else {
            if(confirm(count + \' rekod hendak di\' + v + \'kan?\')) {
            //e.submit();
            window.location.href ="?vw=fpxStatus&pk=" + strStatus;
            }
          }
        }
      }

    function ITRActionButtonStatus() {
        e = document.MyForm;
        if (e == null) {
            alert(\'Sila pastikan nama form diwujudkan.!\');
        } else {
            count = 0;
            for (c = 0; c < e.elements.length; c++) {
                if (e.elements[c].name == "pk[]" && e.elements[c].checked) {
                    count++;
                    pk = e.elements[c].value;
                }
            }
            if (count != 1) {
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

    function ITRActionButtonClickStatus2(v) {
        var selectedCheckbox;
        var e = document.MyForm;
      
        if (e == null) {
          alert(\'Sila pastikan nama form diwujudkan.!\');
        } else {
          var count = 0;
      
          for (var c = 0; c < e.elements.length; c++) {
            if (e.elements[c].name == "pk[]" && e.elements[c].checked) {
              selectedCheckbox = e.elements[c];
              count++;
            }
          }
      
          if (count === 0) {
            alert(\'Sila pilih rekod yang hendak di\' + v + \'kan.\');
          } else if (count > 1) {
            alert(\'Sila pilih hanya satu rekod yang hendak di\' + v + \'kan.\');
          } else {
            var pkValue = selectedCheckbox.value;
            if (confirm(\'1 rekod hendak di\' + v + \'kan?\')) {
              window.location.href = "?vw=memberLatihan&pk=" + pkValue;
            }
          }
        }
      }
</script>';
?>
