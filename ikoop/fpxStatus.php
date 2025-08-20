<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	fpxStatus.php
*		   Description	:   Update fpx status
*          Date 		: 	29/03/2006
*********************************************************************************/
include("header.php");	
include("koperasiQry.php");	
include ("koperasiList.php");	
date_default_timezone_set("Asia/Jakarta");

if (get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'"); parent.location.href = "index.php";</script>';
}


if (!isset($strDate))	$strDate=date("d/m/Y"); 
if ($action == 'Kemaskini') {
$pk = explode(",",$pk);
$str = array();
foreach($pk as $val){
 if($val) $str[] = "'".$val."'";
}
$pk = implode("," , $str);
$strDate = 	substr($strDate,6,4) . '-' . substr($strDate,3,2) . '-' . substr($strDate,0,2);
	
	if ($selStatus <> 0) {
		$sSQL = '';
		$sWhere = '';
   	 	$sWhere = ' userID  in (' .$pk .')';
   		$sSQL	= ' UPDATE fpx ' ;
		if ($selStatus == 1) {
		$approvedDate = date("Y-m-d H:i:s");        
	   	 	$sSQL	.= ' SET fpxStatus = 1 ' .
	 				   ' ,approvedDate=' . tosql($strDate, "Text").
	 				   ' ,remark=' . tosql($remark, "Text");
		}
		if ($selStatus == 2) {
			$rejectedDate = date("Y-m-d H:i:s");        
			//$approvedDate = date("Y-m-d H:i:s");        
	    	$sSQL	.= ' SET fpxStatus = 2 ' .
	 				   ' ,rejectedDate=' . tosql($strDate, "Text").
	 				   ' ,remark=' . tosql($remark, "Text");
		}
		$sSQL .= ' ,fpxStatus=' . tosql($selStatus, "Number");
		$sSQL .= ' WHERE ' . $sWhere;
		$rs = &$conn->Execute($sSQL);
		
		if($selStatus <> 2) {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");               
		$sSQL = '';
		$sWhere = '';
   	 	$sWhere = ' userID  in (' .$pk .')';
   		$sSQL	= ' UPDATE users ' ;
	   	$sSQL	.= ' SET ' .
			       ' isActive =' . tosql(1, "Number").
			   	   ' ,updatedBy =' . tosql($updatedBy, "Text").
	 			   ' ,updatedDate=' . tosql($updatedDate, "Text");
		$sSQL .= ' WHERE ' . $sWhere;
		$rs = &$conn->Execute($sSQL);
		}

	}
		print 	'
		<script>
			window.location = "?vw=fpx_list";
		</script>';
		exit;
}

$title = "Status Permohonan FPX";

if(isset($pk)) $pkall = explode(":",$pk);
unset($pk);
?>

<div class="table-responsive">
<h5 class="card-title"><?php echo strtoupper($title);?></h5>
<form name="MyForm" action="?vw=fpxStatus&mn=907" method="post">
<input type="hidden" name="action">
<input type="hidden" name="pk" value="<? print implode(",",$pkall);?>">
<table class="table" border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
<h6 class="card-subtitle"><b>STATUS KOPERASI</h6>
<tr>
<!-- <td class="borderleftrightbottomteal"> -->
<table border="0" cellspacing="6" cellpadding="3" width="100%" align="center">
<?php
for($s=0;$s<count($pkall);$s++){
//foreach($pkall as $pk) {
if($s>0){
	$pk = $pkall[$s];
	$GetUser = ctMember("",$pk);
	if ($GetUser->RowCount() == 0) {
?>
<tr>
<td	colspan="3" align="center" height="50" valign="middle">- Tiada Maklumat Mengenai Permohonan Menjadi Koperasi -</b></td>
</tr>
<?php
	}else{
    $fpxStatus		= dlookup("fpx", "fpxStatus", "userID=" . tosql($pk, "Text"));  
    $kopNum		    = dlookup("userdetails", "kopNum", "userID=" . tosql($pk, "Text"));
	$approvedDate	= dlookup("fpx", "approvedDate", "userID=" . tosql($pk, "Text"));
	$rejectedDate	= dlookup("fpx", "rejectedDate", "userID=" . tosql($pk, "Text"));
	$remark			= dlookup("fpx", "remark", "userID=" . tosql($pk, "Text"));
?>
<tr>
<td>No./ID Koperasi</td>
<td>&nbsp;<b><? print $kopNum;?></b></td>
</tr>
<tr>
<td>Nama Koperasi</td>
<td>&nbsp;<b><? print $GetUser->fields(name);?></b></td>
</tr>								
<tr>
<td>Tarikh Permohonan</td>
<td>&nbsp;<b><? print toDate("d/m/Y",$GetUser->fields(applyDate));?></b></td>
</tr>
<tr>
<td colspan="2"><hr size=1></td></tr>
<?}
}//end if
}//end foreach
//------------------------

if (count($fpxList) <> 0) {  

	if ($fpxStatus == 0) {
?>
				<td>Status</td><td>
				<select class="form-selectx" name="selStatus">
<?
		for ($i = 0; $i < count($fpxList); $i++) {
			if ($fpxVal[$i] <> 3 AND $fpxVal[$i] <> 4)
				print '<option value="'.$fpxVal[$i].'">'.$fpxList[$i];
		}
?>
					</select>
		        	</td>
				</tr>
<?
	} else {
		if ($fpxStatus == 1) {
?>
<tr>
<td>Status</td>
<td>:
&nbsp;<font class="greenText"><? print $fpxList[$fpxStatus];?></font>
</td>
</tr>
<tr>
<td>Tarikh Diluluskan</td>
<td>:&nbsp;<? print toDate("d/m/Y",$approvedDate);?></td>
</tr>
<?
		}
		if ($fpxStatus == 2) {
?>
<tr>
<td>Status</td>
<td>:&nbsp;<font class="redText"><? print $fpxList[$fpxStatus];?></font></td>
</tr>
<tr>
<td>Tarikh Ditolak</td>
<td>:&nbsp;<? print toDate("d/m/Y",$rejectedDate);?></td>
</tr>
<?
		}
		if ($fpxStatus == 3 OR $fpxStatus == 4) {
?>
<tr>
<td>Status</td>
<td>:&nbsp;<? print $fpxList[$fpxStatus];?></td>
</tr>
<?
		}
	}
	if ($fpxStatus == 0) {
?>
<tr>
<td>Tarikh Mesyuarat</td>
<td><input type="date" class="form-controlx" name="strDate" value="<? print $strDate;?>" size="15" maxlength="10"></td>
</tr>	
<tr>
<td>Catatan</td>
<td><input type="text" class="form-controlx" name="remark" value="" size="50" maxlength="100"></td>
</tr>
<tr>
<td colspan="2" align="center">
<div>&nbsp;</div>
<input type="submit" name="action" class="btn btn-md btn-primary" value="Kemaskini">
&nbsp;
<input type="button" name="batal" value="Batal"  class="btn btn-md btn-danger" onclick= "Javascript:(window.location.href='?vw=fpx_list')">
<div>&nbsp;</div>
</td>
</tr>
<?
	} else {
?>
<tr>
<td>Catatan</td>
<td>: <? print $remark;?></td>
</tr>
<?
	}
} else { 
?>
<tr>
<td colspan="3"	align="center"><hr size="1"><b>- Tiada rekod mengenai status fpx  -</b><hr size="1"></td>
</tr>
<?
}
?>
<!-- </table> -->
</td>
</tr>
</table>
</form>
</div>

<?
include("footer.php");	
?>