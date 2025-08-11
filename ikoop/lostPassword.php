<?php
/*********************************************************************************
*          Project		:	iKOOP.com.my
*          Filename		: 	lostPassword.php
*          Date 		: 	05/10/2022
*********************************************************************************/
include ("header.php");
$title	= "Lupa Kata Laluan";
$detail	= "";

?>
<div align="center" class="card-body">
<p align="center"><h5 class="card-title"><i class="mdi mdi-onepassword"></i>&nbsp;<b>LUPA KATA LALUAN?</b></h5></p>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-1 border-bottom"></div>
<tr>
    <td>&nbsp;</td>
</tr>
<p align="justify"> 	 
                            1. Emelkan nama penuh dan no kad pengenalan ke <b>helpdesk@ikoop.com.my</b> untuk pentadbir semak status keahlian.</br>

                            <br>2. Sila tukar kata laluan selepas mendapat akses kata laluan sementara.</br>

                            <br>3. Bagi mengelakkan ID anda dicerobohi, sila kemaskini kata laluan yang baru setelah berjaya login ke dalam sistem, Bagi pengguna yang mendaftar menggunakan sistem ini bolehlah login seperti biasa dengan menggunakan katalaluan yang telah didaftarkan.</br>

                            <br>4. Talian Hotline untuk dihubungi adalah <b> +6011 - 74648313.</b> Waktu operasi pentadbiran adalah 8AM - 5PM.</br>
                            </p>
                            <br>
                            <td class="textFont" align="center" colspan="3"><input type="button" class="btn btn-secondary w-md waves-effect waves-light" onClick="window.location.href='index.php'" value="KEMBALI" size="50"></td>	

<?php
print '<div class="row m-4">'.$detail.'</div>'; 
?>
</div>
<?php
include("footer.php");
?>