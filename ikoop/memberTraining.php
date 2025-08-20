<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	member.php
*		   Description	:   Update member status
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
	
	if ($selTraining == 0) {
		$strDate = 	substr($strDate,6,4) . '-' . 
					substr($strDate,3,2) . '-' . 
					substr($strDate,0,2);
		$sSQL = '';
		$sWhere = '';
   	 	$sWhere = ' userID  in (' .$pk .')';
   		$sSQL	= ' UPDATE userdetails ' ;
		if ($selTraining == 0) {
//			$approvedDate = date("Y-m-d H:i:s");        
	   	 	$sSQL	.= ' SET training = 1, ' .
	 				  /*  ' ,approvedDate=' . tosql($strDate, "Text").
	 				   ' ,memberDate =' . tosql($strDate, "Text"). */
					   ' ,training= 1' . tosql($training, "Number");
	 				   ' ,remark=' . tosql($remark, "Text");
		}
		
		/* if ($selTraining == 1) {
			$rejectedDate = date("Y-m-d H:i:s");        
//			$approvedDate = date("Y-m-d H:i:s");        
	    	$sSQL	.= ' SET isRejected = 1 ' .
	 				   ' ,rejectedDate=' . tosql($strDate, "Text").
	 				   ' ,remark=' . tosql($remark, "Text");
		}
		$sSQL .= ' ,training=' . tosql($selTraining, "Number");
		$sSQL .= ' WHERE ' . $sWhere;
		$rs = &$conn->Execute($sSQL);
		 */
		if($selTraining <> 1) {
		$updatedBy 	= get_session("Cookie_userName");
		$updatedDate = date("Y-m-d H:i:s");               
		$sSQL = '';
		$sWhere = '';
   	 	$sWhere = ' userID  in (' .$pk .')';
   		$sSQL	= ' UPDATE users ' ;
	   	$sSQL	.= ' SET ' .
			       ' isActive =' . tosql(1, "Number").
				   ' ,fasa =' . tosql($fasa, "Number").
			   	   ' ,updatedBy =' . tosql($updatedBy, "Text").
	 			   ' ,updatedDate=' . tosql($updatedDate, "Text");
		$sSQL .= ' WHERE ' . $sWhere;
		$rs = &$conn->Execute($sSQL);
		}
	}
		print'
		<script>
		
			window.location.href = "?vw=training";
		</script>';
		exit;
}

$title = "Status Latihan Koperasi";

if(isset($pk)) $pkall = explode(":",$pk);
unset($pk);
?>

<div class="table-responsive">
<h5 class="card-title"><?php echo strtoupper($title);?></h5>
<form name="MyForm" action="?vw=memberStatus&mn=905" method="post">
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
	$training		= dlookup("userdetails", "training", "userID=" . tosql($pk, "Text"));
    $fasa		    = dlookup("userdetails", "fasa", "userID=" . tosql($pk, "Text"));
	$memberID		= dlookup("userdetails", "kopNum", "userID=" . tosql($pk, "Text"));
	$approvedDate	= dlookup("userdetails", "approvedDate", "userID=" . tosql($pk, "Text"));
	$rejectedDate	= dlookup("userdetails", "rejectedDate", "userID=" . tosql($pk, "Text"));
	$remark			= dlookup("userdetails", "remark", "userID=" . tosql($pk, "Text"));
?>
<tr>
<td>No./ID Koperasi</td>
<td>&nbsp;<b><? print $memberID;?></b></td>
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

if (count($trainingList) <> 0) {  

	if ($training == 0) {
?>
				<td>Status</td><td>
				<select class="form-selectx" name="selTraining">
<?
		for ($i = 0; $i < count($trainingList); $i++) {
			if ($trainingVal[$i] <> 3 AND $trainingVal[$i] <> 4)
				print '<option value="'.$trainingVal[$i].'">'.$trainingList[$i]. '</opton>';
		}
?>
                </select>
    </tr>
	<?
		if(!($training == 0)){
				print '<td>Fasa</td><td>
                <select class="form-selectx" name="selFasa">';
		}
?>
                
<? 
for ($i = 0; $i < count($fasaList); $i++) {
    if ($fasaVal[$i] <> 3 AND $fasaVal[$i] <> 4)
        print '<option value="'.$fasaVal[$i].'">'.$fasaList[$i].'</option>';
}

?>
</select>
</td>
    
</tr>
<td>Catatan</td>
<td><input type="text" class="form-controlx" name="remark" value="" size="50" maxlength="100"></td>
<tr>
<td colspan="2" align="center">
</td>
<?
	}

    else {
		if ($training == 1) {
?>
<tr>
<td>Status</td>
<td>:
&nbsp;<font class="greenText"><? print $trainingList[$training];?></font>
</td>
</tr>
<tr>
<td>Fasa</td>
<td>:

<?
		if(!($training == 0)){
				print '
                <select class="form-selectx" name="selFasa">';
		}
?>
                
<? 
for ($i = 0; $i < count($fasaList); $i++) {
    if ($fasaVal[$i] <> 2 OR $fasaVal[$i] <> 3 OR $fasaVal[$i] <> 4)
        print '<option value="'.$fasaVal[$i].'">'.$fasaList[$i].'</option>';

}

?>
</select>

</td>
</tr>
<tr>
<td>Tarikh Dilatih</td>
<td>:&nbsp;<? print toDate("d/m/Y",$approvedDate);?></td>
</tr>
<?
		}

/*        if ($status == 2) {
?>
<tr>
<td>Status</td>
<td>:&nbsp;<font class="redText"><? print $statusList[$status];?></font></td>
</tr>
<tr>
<td>Tarikh Ditolak</td>
<td>:&nbsp;<? print toDate("d/m/Y",$rejectedDate);?></td>
</tr>
<?
		}
		if ($status == 3 OR $status == 4) {
?>
<tr>
<td>Status</td>
<td>:&nbsp;<? print $statusList[$status];?></td>
</tr>
<?
		}
	} */
	if ($training == 0) {
?>
<tr>
<td>Tarikh Mesyuarat</td>
<td><input type="date" class="form-controlx" name="strDate" value="<? print date('Y-m-d');?>" size="15" maxlength="10"></td>
</tr>	
<tr>
<td>Catatan</td>
<td><input type="text" class="form-controlx" name="remark" value="" size="50" maxlength="100"></td>
</tr>
<tr>
<td colspan="2" align="center">
</td>
</tr>
<?
} 
else {
?>
<tr>
<td>Catatan</td>
<td>:</td>
<td><? print $remark;?></td>
</tr>

<?
	}
}
} 


else { 
?>
<tr>
<td colspan="3"	align="center"><hr size="1"><b>- Tiada rekod mengenai status  -</b><hr size="1"></td>
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

<div>&nbsp;</div>
<input type="submit" name="action" class="btn btn-md btn-primary" value="Kemaskini">
&nbsp;
<input type="button" name="batal" value="Batal"  class="btn btn-md btn-danger" onclick= "Javascript:(window.location.href='?vw=training')">
<div>&nbsp;</div>

<?
include("footer.php");	
?>