<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	tugasanStatus.php
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
$title = "Status Permohonan Tugasan";


if ($action == 'Kemaskini') {
    $pk = explode(",", $pk);
	$status = dlookup("task", "status", "userID = " . tosql($groupid, "Text"));
    $str = array();
    foreach ($pk as $val) {
        if ($val <> '') $str[] = "'" . $val . "'";
    }
    $pk = implode(",", $str);

    if ($selStatus <> 0) {
        $strDate = substr($strDate, 6, 4) . '-' . 
                   substr($strDate, 3, 2) . '-' . 
                   substr($strDate, 0, 2);
        
        $sWhere = 'userID IN (' . $pk . ')';
        $sSQL = 'UPDATE task ';
        
		if ($selStatus == 2) {
			$sSQL .= 'SET isApproved = 1, ' .
					 'approvedDate = ' . tosql($strDate, "Text") . ', ' .
					 'updatedBy = ' . tosql($groupid, "Text") . ', ' .
					 'status = 2 '; 
			$sSQL .= 'WHERE ' . $sWhere;
		
			$rs = &$conn->Execute($sSQL);
		
		} elseif ($selStatus == 3) {
			$sSQL .= 'SET isRejected = 1, ' .
					 'rejectedDate = ' . tosql($strDate, "Text") . ', ' .
					 'updatedBy = ' . tosql($groupid, "Text") . ', ' .
					 'status = 3 '; 
			$sSQL .= 'WHERE ' . $sWhere;
		
			$rs = &$conn->Execute($sSQL);
        }
        if ($rs === false) {
            echo "Error in SQL execution: " . $conn->ErrorMsg();
        } else {
            echo "Status updated successfully!";
        }
        
		echo '<script>window.location = "?vw=taskList&mn=920";</script>';
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
		<td class="borderallteal" align="left" valign="middle"><div class="headerteal"><b>STATUS PERMOHONAN BERHENTI KOPERASI </b></div></td>
	</tr> -->
	<tr class="card-body bg-light">
	<td class="borderleftrightbottomteal">
	<table border="0" cellspacing="6" cellpadding="6" width="100%" align="center">
	<?
if($member){
	for($s=0;$s<count($pkall);$s++){
	if($s>0){
		$pk = $pkall[$s];
		$GetUser = ctTugasanKoperasi("",$pk);
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
$title_problem = $GetUser->fields('title_problem');
$person_in_charge = $GetUser->fields('person_in_charge');
$keterangan = $GetUser->fields('keterangan');
$doc_tugasan = $GetUser->fields('doc_tugasan');
$approvedDate	= $GetUser->fields('approvedDate');
$rejectedDate	= $GetUser->fields('rejectedDate');
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
			<td>Dokumen Tugasan</td>
			<td></td>
			<td>
				<?php 
				$doc_tugasan = $GetUser->fields('doc_tugasan'); // Get the document field from the user

				// Check if the document field is not empty
				if (!empty($doc_tugasan)) {
					$docPath = 'upload_tugasan/' . $doc_tugasan; // Construct the document path
					// Display the link to view the document
					echo '<a href="' . $docPath . '" target="_blank" class="btn btn-primary">Lihat Dokumen</a>';
				} else {
					echo "Dokumen tidak tersedia."; // Message when the document is not available
				}
				?>
			</td>
		</tr>
		</tr>
		<tr>
			<td>Masalah</td>
			<td></td>
			<td><b><? print $title_problem;?></b></td>
		</tr>
		<tr>
			<td>Orang Yang Bertugasan</td>
			<td></td>
			<td><b><? print $person_in_charge;?></b></td>
		</tr>
		<tr>
			<td>Keterangan</td>
			<td></td>
			<td><b><? print $keterangan;?></b></td>
		</tr>										
		<tr>
			<td>Tarikh Daftar</td>
			<td></td>
			<td><b><? print toDate("d/m/Y",$GetUser->fields(startDate));?></b></td>
		</tr>
		<tr>
			<td>Tarikh Anggaran</td>
			<td></td>
			<td><b><? print toDate("d/m/Y",$GetUser->fields(estimatedDate));?></b></td>
		</tr>
<td colspan="3"><hr class="mt-1"></td></tr>
		<? }
}//end if
}//end foreach
}
//------------------------
if($member){
if (count($tugasanList) <> 0) {  
				if ($status == 0) {
				  if(get_session("Cookie_groupID") == "0"){
		?>
		<div class="alert alert-info"><? print $tugasanList[$status];?></div>
		<?
	} else {
		?>
		<?
		}
	} else {
		if ($status == 2) {
			?>
			<div class="alert alert-primary"><? print $tugasanList[$status];?><br/>
			Tarikh Diluluskan : <? print toDate("d/m/Y",$approvedDate);?></div>
			<?
		}
		if ($status == 3) {
			?>
			<div class="alert alert-danger"><? print $tugasanList[$status];?><br/>
			Tarikh Ditolak : <? print toDate("d/m/Y",$rejectedDate);?></div>
			<?
		}
		if ( $status == 0) {
			?>
			<div class="alert alert-warning"><? print $tugasanList[$status];?></div>
			<? 
			}
		}
		if ($status == 1) {
		  if(get_session("Cookie_groupID") <> "0"){
			?>
			<tr>
				<td>Status</td>
				<td></td>
				<td>
					<select name="selStatus" class="form-select-xs">
			<?
				for ($i = 0; $i < count($tugasanList); $i++) {
					if ($tugasanVal[$i] <> 0 AND $tugasanVal[$i] <> 4)
						print '<option value="'.$tugasanVal[$i].'">'.$tugasanList[$i];
				}
			?>
			</select>
			</td>
			</tr>
			<tr>
				<td>Tarikh </td>
				<td></td>
				<td><input type="text" class="form-control-sm" name="strDate" value="<? print $strDate;?>" size="15" maxlength="10"></td>
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
$status	= dlookup("task", "status", "userID=" . tosql($uid, "Text"));
if ($status == 2) {
	?>
	<div class="alert alert-primary"><? print $tugasanList[$status];?><br/>
	Tarikh Diluluskan : <? print toDate("d/m/Y",$approvedDate);?></div>
	<?
}
if ($status == 3) {
	?>
	<div class="alert alert-danger"><? print $tugasanList[$status];?><br/>
	Tarikh Ditolak : <? print toDate("d/m/Y",$rejectedDate);?></div>
	<?
}
if ($status == 0 OR $status == 1) {
	?>
	<div class="alert alert-warning"><? print $tugasanList[$status];?></div>
	<?
	}
}
// print '	<div class="alert alert-success">'.$tugasanList[$status].'</div>';
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