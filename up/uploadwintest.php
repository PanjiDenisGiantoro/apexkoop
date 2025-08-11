<?php

if(!isset($pk)) $pk = 0;

$title     = 'maklumat terkini';
$max_size = "1048576"; // Max size in BYTES (1MB)

echo "action ".$action; 
if ($action == 'upload' && $security == 'mynet4122')
{
	$strFile = $_FILES["filename"]["name"];
	if($strFile == ''){
		print '<script>
					alert ("Tiada fail dimasukkan.");
					window.location.href="uploadwintest.php";
				</script>';
	}
	
	if ($_FILES["filename"]["size"] > $max_size) die ("<b>File Terlalu Besar!  Sila cuba lagi...</b>");
	copy($_FILES["filename"]["tmp_name"],"../".$_FILES["filename"]["name"]) or die("<b>Unknown error!</b>");
	echo "<b>Fail Sudah Diterima.</b>"; 
//	print '</script>';

}


print '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>'.$emaNetis.'</title>
	<LINK rel="stylesheet" href="images/default.css" >	
</head>
<body leftmargin="5" topmargin="5" class="bodyBG">

<input type="hidden" name="action">
<table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">
	<tr>
		<td	 colspan="2"><b class="maroonText">' . strtoupper($title) . '</b></td>
	</tr>
	<tr>
		<td><br>
		<table border="0" cellpadding="3" cellspacing="1" width="100%" align="center" class="lineBG">
			<tr>
				<td class=Header>Sila Masukkan Foto anda:</td>
			</tr>
			<tr>
				<td class="Data">
					<table width="100%">
						<tr>
							<form action="uploadwintest.php?action=upload" method=post  enctype="multipart/form-data">
							File (max size: '.$max_size.' bytes/'.($max_size/1024).' kb):<br>
							<input type="file" name="filename"><br>
							<input type="hidden" name="action" value="upload">
							<input type="hidden" name="pk" value="'.$pk.'">
							<input type="hidden" name="update" value="'.$up.'">
							<input type="text" name="security" ><br>
							<input type="submit" value="cancel">
							</form>					
						</tr>			
					</table>
				</td>
			</tr>
		</table>		
		</td>
	</tr>
</table>';

print $detail;
//include("footer.php");

?>	