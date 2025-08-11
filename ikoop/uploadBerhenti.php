<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	uploadBerhenti.php
*          Date 		: 	15/12/2022
*********************************************************************************/
// include("common.php");	
include("setupinfo.php");	

$pk = get_session('Cookie_userID');
$title     = 'Muat Naik Dokumen Berhenti';
$max_size = "5048576"; // Max size in BYTES (1MB)

if ($action == 'upload')
{
	$filename = $_FILES["filename"]["name"];
	$file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
	$file_ext = substr($filename, strripos($filename, '.')); // get file name
	$filesize = $_FILES["filename"]["size"];
	$allowed_file_types = array('.pdf','.zip');	

	if (in_array($file_ext,$allowed_file_types) && ($filesize < 5048576))
	{	
		// Rename file
		$newfilename = md5($file_basename) . $file_ext;
		if (file_exists("upload_berhenti/" . $newfilename))
		{
			// file already exists error
			echo '<font color="red">Anda telah pun memuat naik fail ini.</font>';
		}
		else
		{		
			move_uploaded_file($_FILES["filename"]["tmp_name"], "upload_berhenti/".$newfilename);
		echo "Dokumen telah berjaya dimuat naik.";	

		print '<script language="javascript">';
	

if (get_session("Cookie_groupID") == 2) {
	if($pk) {
		print 'window.location.href="?vw=memberApplyT&mn=905&pk='.$pk.'&pic='.$newfilename.'&action=view"';
	}
}


	print '</script>';	
	}
}
	elseif (empty($file_basename))
	{	
	// file selection error
	echo "Sila pilih dokumen untuk dimuat naik.";
	} 
	elseif ($filesize > 5048576)
	{	
	// file size error
	echo "Saiz dokumen terlalu besar untuk dimuat naik.";
	}
	else
	{
	// file type error
	echo "Hanya dokument jenis ini yang boleh dimuat naik: " . implode(', ',$allowed_file_types);
	unlink($_FILES["file"]["tmp_name"]);
	}
}

print '<h4 class="card-title"><i class="fas fa-upload"></i>&nbsp;' . strtoupper($title) . '</h4>
<hr class="hr1 text-secondary">

<input type="hidden" name="action">
<div class="table-responsive">
                                                
            <table class="table mb-3">
                <tr class="table-danger">
                        <td class=Header><h6 class="card-subtitle">Sila Masukkan Dokumen Berhenti Koperasi:</h6></td>
                    </tr>
                    <tr class="table-light">
					<td class="Data">
							<form action="?vw=uploadBerhenti&mn=905&action=upload" method="post"  enctype="multipart/form-data">
							File (max size: '.$max_size.' bytes/'.($max_size/5024).' kb):<br>
							<input type="file" class="form-control" name="filename"><br>
							<input type="hidden" name="action" value="upload">
							<input type="hidden" name="pk" value="'.$pk.'">
							<input type="hidden" name="update" value="'.$up.'">
							<center>
							<input type="button" class="btn btn-secondary waves-effect waves-light" value="<<" onClick="window.location.href=\'?vw=memberApplyT&mn=905\';">
							<input type="submit" class="btn btn-primary w-md waves-effect waves-light" value="Muat Naik Fail">                                    
							</center>
							</form>					
						
				</td>
			</tr>
		</table>		
		</div>';

print $detail;
include("footer.php");

?>	