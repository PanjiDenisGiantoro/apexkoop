<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	memberStatusT.php
*		   Description	:   Update member status
*          Date 		: 	29/03/2006
*********************************************************************************/
include("header.php");	
include("koperasiQry.php");	
include ("koperasiList.php");
date_default_timezone_set("Asia/Kuala_Lumpur");
if (get_session('Cookie_userID') == "" OR get_session("Cookie_koperasiID") <> 0) {
	print '<script>alert("'.$errPage.'"); parent.location.href = "index.php";</script>';
}

if(get_session("Cookie_groupID") == 0) $member = 0; else $member = 1;
if (!isset($strDate))	$strDate=date("d/m/Y"); 	
$groupid = get_session("Cookie_userID");
$title = "Status Permohonan Berhenti";

if ($action == 'Kemaskini') {
$pk = explode(",",$pk);
$str = array();
foreach($pk as $val){
 if($val<> '')$str[] = "'".$val."'";
}
$pk = implode("," , $str);
	
	if ($selStatus <> 0) {
		$strDate = 	substr($strDate,6,4) . '-' . 
					substr($strDate,3,2) . '-' . 
					substr($strDate,0,2);
		$sSQL = '';
		$sWhere = '';

   	 	$sWhere = ' userID  in (' .$pk .')';
   		$sSQL	= ' UPDATE userterminate ' ;
		if ($selStatus == 1) {
	 	   $sSQL1	= $sSQL . ' SET isApproved = 1 ' .
	 				   ' ,approvedDate=' . tosql($strDate, "Text").
					   ' ,updatedBy=' . tosql($groupid, "Text").
	 				   ' ,remark=' . tosql($remark, "Text").
					   ' ,status= 3 ';
	 	   $sSQL1	= $sSQL1 . ' WHERE type = 0 and ' . $sWhere;
		   
		   $rs = &$conn->Execute($sSQL1);

	 	   $sSQL1	= $sSQL . ' SET isApproved = 1 ' .
	 				   ' ,approvedDate=' . tosql($strDate, "Text").
					   ' ,updatedBy=' . tosql($groupid, "Text").
	 				   ' ,remark=' . tosql($remark, "Text").
					   ' ,status= 4 ';
	 	   $sSQL1	= $sSQL1 . ' WHERE type = 1 and ' . $sWhere;
		   
		   $rs = &$conn->Execute($sSQL1);

			for($j=0;$j<count($str);$j++){
   	 		$pk = $str[$j];
			$type = dlookup("userterminate", "type", "userID=" .$pk);
			if($type==0) $status = 3; else $status = 4; 
			
			$sWhere = ' userID  = ' .$pk ;
			$sSQL	= ' UPDATE userdetails
						SET status = '. $status ;
			$sSQL .= ' WHERE ' . $sWhere;
			$rs = &$conn->Execute($sSQL);
			}
			   
		}
		if ($selStatus == 2) {
    
	   	 $sSQL	.= ' SET isRejected = 1 ' .
	 				   ' ,rejectedDate=' . tosql($strDate, "Text").
					   ' ,updatedBy=' . tosql($groupid, "Text").
		 			   ' ,remark=' . tosql($remark, "Text").
					   ' ,status=' . tosql($selStatus, "Number");
			$sSQL .= ' WHERE ' . $sWhere;
			$rs = &$conn->Execute($sSQL);
		}
		print 	'
		<script>
			window.location = "?vw=memberT&mn=905";
		</script>';
		exit;
	}
}
if($member){
if(isset($pk)) $pkall = explode(":",$pk);
unset($pk);
}
?>
<h5 class="card-title"><?php echo strtoupper($title)?></h5>
<div style="width: 350px; text-align:left">
<form name="MyForm" action="" method="post">
<input type="hidden" name="action">
<input type="hidden" name="pk" value="<? print implode(",",$pkall);?>">
<table class="lightgrey" border="0" cellspacing="0" cellpadding="0" width="100%" align="left">
	<!-- <tr>
		<td class="borderallteal" align="left" valign="middle"><div class="headerteal"><b>STATUS PERMOHONAN BERHENTI KOPERASI</b></div></td>
	</tr> -->
	<tr class="card-body bg-light">
	<td class="borderleftrightbottomteal">
	<table border="0" cellspacing="6" cellpadding="6" width="100%" align="center">
	<?
if($member){
	for($s=0;$s<count($pkall);$s++){
	if($s>0){
		$pk = $pkall[$s];
		$GetUser = ctMemberTerminate("",$pk);
		if ($GetUser->RowCount() == 0) {
	?>
	<tr><td>&nbsp;</td></tr>
		<tr>
			<td	colspan="3" align="center" height="70" valign="middle"><b>- Tiada Maklumat Mengenai Permohonan Berhenti Anggota -</b></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	<?
	} else {
$status		= $GetUser->fields('status');
$kopNum	= dlookup("userdetails", "kopNum", "userID=" . tosql($GetUser->fields('userID'), "Text"));
$username	= dlookup("users", "name", "userID=" . tosql($GetUser->fields('userID'), "Text"));
$approvedDate	= $GetUser->fields('approvedDate');
$rejectedDate	= $GetUser->fields('rejectedDate');
$remark			= $GetUser->fields('remark');
		?>
		<tr>
			<td>No./ID Koperasi</td>
			<td></td>
			<td><b><? print $kopNum;?></b></td>
		</tr>
		<tr>
			<td>Nama Koperasi</td>
			<td></td>
			<td><b><? print $username;?></b></td>
		</tr>								
		<tr>
			<td>Tarikh Daftar</td>
			<td></td>
			<td><b><? print toDate("d/m/Y",$GetUser->fields(applyDate));?></b></td>
		</tr>
<td colspan="3"><hr class="mt-1"></td></tr>
		<? }
}//end if
}//end foreach
}
//------------------------
if($member){
if (count($berhentiList) <> 0) {  
				if ($status == 0) {
				  if(get_session("Cookie_groupID") == "0"){
		?>
		<div class="alert alert-info"><? print $berhentiList[$status];?></div>
		<?
	} else {
		?>
		<tr>
		<td>Status</td>
		<td></td>
		<td>
		<select name="selStatus" class="form-select-xs">
		<?
			for ($i = 0; $i < count($berhentiList); $i++) {
				if ($berhentiVal[$i] <> 3 AND $berhentiVal[$i] <> 4)
					print '<option value="'.$berhentiVal[$i].'">'.$berhentiList[$i];
			}
		?>
		</select>
		</td>
		</tr>
		<?
		}
	} else {
		if ($status == 1) {
			?>
			<div class="alert alert-primary"><? print $berhentiList[$status];?><br/>
			Tarikh Diluluskan : <? print toDate("d/m/Y",$approvedDate);?></div>
			<?
		}
		if ($status == 2) {
			?>
			<div class="alert alert-danger"><? print $berhentiList[$status];?><br/>
			Tarikh Ditolak : <? print toDate("d/m/Y",$rejectedDate);?></div>
			<?
		}
		if ($status == 3 OR $status == 4) {
			?>
			<div class="alert alert-warning"><? print $berhentiList[$status];?></div>
			<?
			}
		}
		if ($status == 0) {
		  if(get_session("Cookie_groupID") <> "0"){
			?>
			<tr>
				<td>Tarikh </td>
				<td></td>
				<td><input type="text" class="form-control-sm" name="strDate" value="<? print $strDate;?>" size="15" maxlength="10"></td>
			</tr>	
			<tr>
				<td>Catatan</td>
				<td></td>
				<td><input type="text" name="remark" class="form-control-sm" value="" size="60" maxlength="100"></td>
			</tr>
				<tr>
				<td colspan="3" align="center">
				<div>&nbsp;</div>
				<input type="submit" name="action" value="Kemaskini" class="btn btn-primary">
				<div>&nbsp;</div>
				</td>
			</tr>
			<?
			}
		} else {
		?>
		<tr>
			<td>Catatan</td>
			<td>:</td>
			<td><? print $remark;?></td>
		</tr>
		<?
	}
		} else { 
			?>
			<tr>
			<td colspan="3"	align="center">
			<hr size="1"><b>- Tiada rekod mengenai status  -</b><hr size="1">
			</td>
			</tr>
			<?
		}
}
if(!$member){
$uid=get_session('Cookie_userID'); 	
$status	= dlookup("userterminate", "status", "userID=" . tosql($uid, "Text"));
if ($status == 1) {
	?>
	<div class="alert alert-primary"><? print $berhentiList[$status];?><br/>
	Tarikh Diluluskan : <? print toDate("d/m/Y",$approvedDate);?></div>
	<?
}
if ($status == 2) {
	?>
	<div class="alert alert-danger"><? print $berhentiList[$status];?><br/>
	Tarikh Ditolak : <? print toDate("d/m/Y",$rejectedDate);?></div>
	<?
}
if ($status == 3 OR $status == 4) {
	?>
	<div class="alert alert-warning"><? print $berhentiList[$status];?></div>
	<?
	}
}
// print '	<div class="alert alert-success">'.$berhentiList[$status].'</div>';
// }
	?>
	</table>
	</td>
	</tr>
</table>
</form>
</div>
<?
include("footer.php");	
?>