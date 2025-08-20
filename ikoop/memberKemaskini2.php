<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	memberKemaskini.php
*		   Description	:   Kemas kini maklumat koperasi
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
    
    if ($selStatus <> 0) {
        $strDate = 	substr($strDate,6,4) . '-' . 
                    substr($strDate,3,2) . '-' . 
                    substr($strDate,0,2);
        $sSQL = '';
        $sWhere = '';
        $sWhere = ' userID  in (' .$pk .')';
        $sSQL	= ' UPDATE userdetails ' ;
        if ($selStatus == 1) {
//          $approvedDate = date("Y-m-d H:i:s");        
            $sSQL	.= ' SET isApproved = 1 ' .
                       ' ,approvedDate=' . tosql($strDate, "Text").
                       ' ,memberDate =' . tosql($strDate, "Text").
                       ' ,remark=' . tosql($remark, "Text");
        }
        if ($selStatus == 2) {
            $rejectedDate = date("Y-m-d H:i:s");        
//          $approvedDate = date("Y-m-d H:i:s");        
            $sSQL	.= ' SET isRejected = 1 ' .
                       ' ,rejectedDate=' . tosql($strDate, "Text").
                       ' ,remark=' . tosql($remark, "Text");
        }
        $sSQL .= ' ,status=' . tosql($selStatus, "Number");
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
        window.location = "?vw=member";
    </script>';
    exit;
}

$title = "Status Permohonan Koperasi";

if(isset($pk)) $pkall = explode(":",$pk);
unset($pk);
?>

<div class="table-responsive">
<h5 class="card-title"><?php echo strtoupper($title);?></h5>
<form name="MyForm" action="?vw=memberKemaskini&mn=905" method="post">
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
            $status			= dlookup("userdetails", "status", "userID=" . tosql($pk, "Text"));
            $kopNum		= dlookup("userdetails", "kopNum", "userID=" . tosql($pk, "Text"));
            $migrasiAnggota		= dlookup("userdetails", "migrasiAnggota", "userID=" . tosql($pk, "Text"));
            $approvedDate	= dlookup("userdetails", "approvedDate", "userID=" . tosql($pk, "Text"));
            $rejectedDate	= dlookup("userdetails", "rejectedDate", "userID=" . tosql($pk, "Text"));
            $remark			= dlookup("userdetails", "remark", "userID=" . tosql($pk, "Text"));
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
        <?
        }
        }//end if
        }//end foreach
        //------------------------
        if ($status == 1 && count($statusList) <> 0) {
        ?>
        <tr>
        <tr>
            <td>Status</td>
            <td>:
            &nbsp;<font class="greenText"><?php echo $statusList[$status]; ?></font>
            </td>
        </tr>
            <td>Dropdown</td>
            <td>
                <select class="form-selectx" name="selStatus">
                <?php
                for ($i = 0; $i < count($statusList); $i++) {
                    if ($statusVal[$i] <> 3 && $statusVal[$i] <> 4) {
                        echo '<option value="'.$statusVal[$i].'">'.$statusList[$i].'</option>';
                    }
                }
                ?>
                </select>
            </td>
        </tr>

        <tr>
            <td>Tarikh Kemaskini</td>
            <td><input type="date" class="form-controlx" name="strDate" value="<?php echo date('Y-m-d'); ?>" size="15" maxlength="10"></td>
        </tr>
        <tr>
            <td>Migrasi Anggota</td>
            <td>&nbsp;<b><? print $migrasiAnggota;?></b></td>
            </td>
            
        </tr>

        <td colspan="2" align="center">
        <div>&nbsp;</div>
        <input type="submit" name="action" class="btn btn-md btn-primary" value="Kemaskini">
        &nbsp;
        <input type="button" name="batal" value="Batal"  class="btn btn-md btn-danger" onclick= "Javascript:(window.location.href='?vw=member')">
        <div>&nbsp;</div>
    </td>
        <?php
            }
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
