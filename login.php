<!DOCTYPE html>
<html lang="en">

<head>

  <title>iKOOP : Sistem Koperasi kewangan bagi Pembiayaan Peribadi, Pelaburan, Takaful, Insuran dan Daftar Anggota Koperasi </title>
  <link href="images/favicon.png" rel="icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/fontello.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Merriweather:300,300i,400,400i,700,700i" rel="stylesheet">
</head>

<style>
.dropdown {
  position: relative;
  display: inline-block;  
}

#searchInput {
  width: 100%;
  padding: 10px;
  box-sizing: border-box;  
  border-radius: 5px;
  border: 2px;
}

.dropdown-list {
  list-style-type: none;
  padding: 0;
  margin: 0;
  display: none;
  position: absolute;
  width: 100%;
  border: 1px solid #ccc;
  max-height: 150px;
  overflow-y: auto;  
  border-radius: 5px;
  text-align: left;
}

.dropdown-list li {
  padding: 10px;
}

.dropdown-list li a {
  display: block;
  text-decoration: none;
  color: #000000;
}

.dropdown-list li a:hover {
  background-color: #f0f0f0;
}
</style>

<body>
    <div class="header">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                    <a href="index.php" id="branding">
                        <img class="w-25 p-100" src="logo/IKOOP-rect2.png" alt="Company Name" class="logo">
                    </a>
                    <!-- #branding -->
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right"> <a href="index.php" style="background-color: #39b54a" class="btn btn-primary btn-xs mt20">Kembali</a></div>
            </div>
        </div>
        <!-- .container -->
    </div>
    
        <!-- DROPDOWN BUTTON -->
    <section id="call-to-action" class="wow fadeIn">
        <div class="container" style="height: 200px">
        <div class="row"></div>
        <div align="center">
        <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default" align="center">
        <div class="panel-heading" align="center">
        <h3 class="panel-title">LOG MASUK</h3>
        </div>

        <div class="dropdown ">
        <input type="text" id="searchInput" placeholder="Search..." size="100%">
        <ul class="dropdown-list" id="dropdownList">
            <li><a href="http://sekatarakyat.bankrakyat.com.my/sekata/">Koperasi Kakitangan Bank Rakyat Berhad (SEKATARAKYAT)</a></li>
            <li><a href="https://app.ikoop.com.my/kofrim">Koperasi FRIM Berhad (KOFRIM)</a></li>
            <li><a href="http://ikoop.com.my/kobatim/index.php">Koperasi Pribumi Batang Padang Dan Muallim Berhad (KOBATIM)</a></li>
            <li><a href="https://app.ikoop.com.my/kokuis/">Koperasi Kolej Universiti Islam Antarabangsa Selangor Berhad (KOKUIS)</a></li>
            <li><a href="https://app.ikoop.com.my/kohijrah/">Koperasi Warga Hijrah Selangor Berhad (KOHIJRAH)</a></li>
            <li><a href="https://app.ikoop.com.my/koprojaya/">Koperasi Profesional Putrajaya Berhad (KOPROJAYA)</a></li>
            <li><a href="https://app.ikoop.com.my/kopama/">Koperasi Alumni MKM WPKL (KOPAMA)</a></li>
            <li><a href="http://ikoop.com.my/kllmb/">Koperasi Lembaga Lebuhraya Malaysia Berhad (KLLMB)</a></li>
            <li><a href="https://app.ikoop.com.my/kounisem/">Koperasi Pekerja Pekerja Unisem (M) Berhad (KOUNISEM)</a></li>
            <li><a href="https://app.ikoop.com.my/kopubi/">Koperasi Usahawan Bersatu Ipoh Perak Berhad (KOPUBI)</a></li>
            <li><a href="https://app.ikoop.com.my/kagum/">Koperasi Guru Melayu Perak Berhad (KAGUM)</a></li>
            <li><a href="http://ikoop.com.my/koppim/">Koperasi Pembangunan Pengguna Islam Malaysia Berhad (KoPPIM)</a></li>
            <li><a href="https://app.ikoop.com.my/koopjpagro/">Koperasi Pegawai Pegawai Melayu Jabatan Pertanian Berhad (KOOPJPAGRO)</a></li>
            <li><a href="https://app.ikoop.com.my/kobumira/">Koperasi Bumira Malaysia Berhad (KOBUMIRA)</a></li>
            <li><a href="https://app.ikoop.com.my/koikma/">Koperasi Warga Institut Koperasi Malaysia Berhad (KOIKMA)</a></li>
            <li><a href="https://app.ikoop.com.my/karisma/">Koperasi Anggota Risda Malaysia Berhad (KARISMA)</a></li>
            <li><a href="https://app.ikoop.com.my/komas/">Koperasi Kariah Masjid Sungai Ramal Luar Kajang Selangor Berhad (KOMAS)</a></li>
            <li><a href="https://app.ikoop.com.my/kkb/">Koperasi Keselamatan Berhad (KKB)</a></li>
            <li><a href="http://ikoop.com.my/kocidb/">Koperasi CIDB Malaysia Berhad (KOCIDB)</a></li>
            <li><a href="https://app.ikoop.com.my/kompob/">Koperasi Pegawai MPOB Berhad (KOMPOB)</a></li>
            <li><a href="https://app.ikoop.com.my/koopkwsp/">Koperasi Kakitangan KWSP Malaysia Berhad (KOOPKWSP)</a></li>
            <li><a href="https://app.ikoop.com.my/koponas/">Koperasi Pos Malaysia Berhad (KOPONAS)</a></li>
            <li><a href="https://app.ikoop.com.my/kopkenem/">Koperasi Pekebun Getah Negeri Melaka (KOPKENEM)</a></li>
            <li><a href="https://app.ikoop.com.my/kobi/">Koperasi Bimbingan Insan Pahang Berhad (KOBI)</a></li>
            <li><a href="https://app.ikoop.com.my/kogupa/">Koperasi Guru dan Pegawai Agama Pahang Berhad (KOGUPABHD)</a></li>
            <li><a href="https://app.ikoop.com.my/koguwam/">Koperasi Guru-Guru Wanita Melayu Berhad (KOGUWAM)</a></li>
            <li><a href="https://app.ikoop.com.my/koist/">Koperasi Kakitangan Istana Terengganu Berhad (KOIST)</a></li>
            <li><a href="https://app.ikoop.com.my/kopsawit/">Koperasi Sawit Sepuluh Kuantan Berhad (KOPSAWIT)</a></li>
            <li><a href="https://app.ikoop.com.my/coopmpt/">Koperasi Kakitangan Majlis Perbandaran Temerloh Berhad (COOPMPT)</a></li>
            <li><a href="https://app.ikoop.com.my/kowbmas/">Koperasi Wawasan Mas Kelantan Berhad (KOWBMAS)</a></li>
            <li><a href="https://app.ikoop.com.my/kopdesa/">Koperasi Pembangunan Desa Saradan Tenghilan Berhad (KOPDESA)</a></li>
            <li><a href="https://app.ikoop.com.my/koniaga/">Koperasi Padu Niaga Kota Kinabalu Berhad (KONIAGA)</a></li>
            <li><a href="https://app.ikoop.com.my/kokilimu/">Koperasi Kilimu Jaya Ranau Berhad (KOKILIMU)</a></li>
            <li><a href="https://app.ikoop.com.my/koptansau/">Koperasi SMK Tansau Berhad (KOPTANSAU)</a></li>
            <li><a href="https://app.ikoop.com.my/kowani/">Koperasi Wanita Kota Kinabalu Berhad (KOWANI)</a></li>
            <li><a href="https://app.ikoop.com.my/kprm/">Koperasi Peserta Rancangan Felcra Kawasan Mukah Berhad (KPRM)</a></li>
            <li><a href="https://app.ikoop.com.my/kokasako/">Koperasi Kakitangan Sawit dan Koko Miri Berhad (KOKASAKO)</a></li>
            <li><a href="https://app.ikoop.com.my/kosimun/">Koperasi Kampung Lubok Punggor Simunjan Berhad (KOSIMUN)</a></li>
            <li><a href="https://app.ikoop.com.my/kopusa/">Koperasi Perpaduan Ummah Barakah Sri Aman Berhad (KOPUSA)</a></li>
            <li><a href="https://app.ikoop.com.my/kalam/">Koperasi Alumni Kemahiran MARA Berhad (KALAM)</a></li>
            <li><a href="https://app.ikoop.com.my/koopusim/">Koperasi Kakitangan USIM Berhad (KOOPUSIM)</a></li>
            <li><a href="https://app.ikoop.com.my/kobku/">Koperasi Bekas Kakitangan UDA Berhad (KOBKU)</a></li>
            <li><a href="https://app.ikoop.com.my/kopesa/">Koperasi Peneroka Felda Sungai Sayong Kulaijaya Berhad (KOPESA)</a></li>
            <li><a href="https://app.ikoop.com.my/kopalhasanah/">Koperasi Masjid Al Hasanah Bandar Baru Bangi (KOPALHASANAH)</a></li>
            <li><a href="https://app.ikoop.com.my/kosera/">Koperasi Serasot Bau Berhad (KOSERA)</a></li>
            <li><a href="https://app.ikoop.com.my/koperwaris/">Koperasi Persatuan Warga Islam Saribas (Perwaris) Sarawak Berhad (KOPERWARIS)</a></li>
            <li><a href="https://app.ikoop.com.my/kopborneo/">Koperasi Pelancongan North Borneo Semporna Berhad (KOPBORNEO)</a></li>
            <li><a href="https://app.ikoop.com.my/kpdlb/">Koperasi Pembangunan Daerah Langkawi Berhad (KPDLB)</a></li>
            <li><a href="https://app.ikoop.com.my/coopkilim/">Koperasi Komuniti Kampung Kilim Langkawi Berhad (COOPKILIM)</a></li>
            <li><a href="https://app.ikoop.com.my/kotamar/">Koperasi Keluarga Haji Taib Omar Kedah Berhad (KOTAMAR)</a></li>
            <li><a href="https://app.ikoop.com.my/komuda/">Koperasi Masjid Jamek Bandar Baru Uda (KOMUDA)</a></li>
            <li><a href="https://app.ikoop.com.my/kgfb/">Koperasi Generasi Felda Baru Berhad (KGFB)</a></li>
            <li><a href="https://app.ikoop.com.my/wcb/">Koperasi Wanita Malaysia Berhad (WCB)</a></li>
            <li><a href="https://app.ikoop.com.my/komsah/">Koperasi Masjid Saidina Hamzah (KOMSAH)</a></li>
            <li><a href="https://app.ikoop.com.my/kopekerti/">Koperasi Pembangunan Ekonomi Rakyat Terengganu Berhad (KOPEKERTI)</a></li>
            <li><a href="https://app.ikoop.com.my/koextel/">Koperasi Persatuan Bekas Anggota Telekom Malaysia Selangor Berhad (KOEXTEL)</a></li>
            <li><a href="https://app.ikoop.com.my/kosibu/">Koperasi Kaum Ibu Selangor dan Wilayah Persekutuan Berhad (KOSIBU)</a></li>
            <li><a href="https://app.ikoop.com.my/kopgen/">Koperasi Kangen Selangor Berhad (KOPGEN)</a></li>
            <li><a href="https://app.ikoop.com.my/kkbb/">Koperasi Kakitangan Bernas Berhad (KKBB)</a></li>
            <li><a href="https://app.ikoop.com.my/komilenia/">Koperasi Usahawan Bina Milenia Berhad (KoMilenia)</a></li>
            <li><a href="https://app.ikoop.com.my/koppema/">Koperasi Kakitangan Kementerian Pelancongan dan Kebudayaan Malaysia Berhad (KOPPEMA)</a></li>
            <li><a href="https://app.ikoop.com.my/coopmbbp/">Koperasi Kariah Masjid Bandar Bukit Puchong (CoopMBBP)</a></li>
            <li><a href="https://app.ikoop.com.my/komtdc/">Koperasi Malaysian Technology Development Corporation (KOMTDC)</a></li>
            <li><a href="https://app.ikoop.com.my/kpdp/">Koperasi Pembangunan Daerah Petaling Berhad (KPDP)</a></li>
            <li><a href="https://app.ikoop.com.my/kopis/">Koperasi Perumahan Impian Selangor Berhad (KOPIS)</a></li>
            <li><a href="https://app.ikoop.com.my/kkpb/">Koperasi Kakitangan Puspati Berhad (KKPB)</a></li>
            <li><a href="https://app.ikoop.com.my/kkpnppb/">Koperasi Kerjaya Permatang Nibong Pulau Pinang Berhad (KKPNPPB)</a></li>
            <li><a href="https://app.ikoop.com.my/kski/">Koperasi Serbaguna Kaum Ibu Kubang Pasu Berhad (KSKI)</a></li>
            <li><a href="https://app.ikoop.com.my/kosada/">Koperasi Kakitangan Sada Kedah Berhad (KOSADA)</a></li>
            <li><a href="https://app.ikoop.com.my/kodaya/">Koperasi Pekebun Kecil Daerah Kuala Muda/Yan Kedah Berhad (KODAYA)</a></li>
            <li><a href="https://app.ikoop.com.my/kofarma/">Koperasi Farmasi Komuniti Kedah Berhad (KOFARMA)</a></li>
            <li><a href="https://app.ikoop.com.my/kopaba/">Koperasi Pengkalan Batu Melaka Berhad (KOPABA)</a></li>
            <li><a href="https://app.ikoop.com.my/kopketa/">Koperasi Kemajuan Tanah Negeri Johor Berhad (KOPKETA)</a></li>
            <li><a href="https://app.ikoop.com.my/koprat/">Koperasi Peserta Rancangan Tanah Bukit Apit 1 Masjid Tanah Melaka Berhad (KOPRAT)</a></li>
            <li><a href="https://app.ikoop.com.my/kfbm/">Koperasi Felda Bukit Mendi Pahang Berhad (KFBM)</a></li>
            <li><a href="https://app.ikoop.com.my/kopkem/">Koperasi Pekebun Kecil Daerah Kemaman Berhad (KOPKEM)</a></li>
            <li><a href="https://app.ikoop.com.my/kokitar/">Koperasi Kakitangan RISDA Terengganu (KOKITAR) Berhad (KOKITAR)</a></li>
            <li><a href="https://app.ikoop.com.my/koptech/">Koperasi Pengguna Teknologi Malaysia Berhad (KOPTECH)</a></li>
            <li><a href="https://app.ikoop.com.my/kppjb/">Koperasi Pesara Polis Johor Berhad (KPPJB)</a></li>
            <li><a href="https://app.ikoop.com.my/kobr2/">Koperasi Rakyat BR2.0 Malaysia Berhad (KOBR2)</a></li>
            <li><a href="https://app.ikoop.com.my/koopmdbberhad/">Koperasi Kakitangan Majlis Daerah Bera Berhad (KOOPMDBBERHAD)</a></li>
            <li><a href="https://app.ikoop.com.my/kooppms/">Koperasi Politeknik Muadzam Shah Rompin Pahang Berhad (KOOPPMS)</a></li>
            <li><a href="https://app.ikoop.com.my/kowas10/">Koperasi Wawasan Felcra Sungai Sepuluh Paloh (KOWAS10)</a></li>
            <li><a href="https://app.ikoop.com.my/kfbbkb/">Koperasi Felda Bukit Batu Kulaijaya Berhad (KFBBKB)</a></li>
            <li><a href="https://app.ikoop.com.my/kpmmhe/">Koperasi Pekerja MMHE Pasir Gudang Johor Berhad (KPMMHE)</a></li>
            <li><a href="https://app.ikoop.com.my/kuputra/">Koperasi Universiti Putra Malaysia Berhad (KUPUTRA)</a></li>
            <li><a href="https://app.ikoop.com.my/kodamai/">Koperasi Damai Indah Selangor Berhad (KODAMAI)</a></li>
            <li><a href="https://app.ikoop.com.my/kolaguna/">Koperasi Komuniti Islam Bandar Laguna Merbok (KOLAGUNA)</a></li>
            <li><a href="https://app.ikoop.com.my/koguna/">Koperasi Pekerja Felda Malaysia Berhad (KOGUNA)</a></li>
            <li><a href="https://app.ikoop.com.my/fedkew/">Federasi Koperasi Perkhidmatan Kewangan Malaysia Berhad (FedKew)</a></li>
            <li><a href="https://app.ikoop.com.my/kotfp/">Koperasi TFP Solutions Berhad (KOTFP)</a></li>
            <li><a href="https://app.ikoop.com.my/kobopem/">Koperasi Pekerja Bomba Dan Penyelamat Malaysia Berhad (KOBOPEM)</a></li>
            <li><a href="https://app.ikoop.com.my/kihtberhad/">Koperasi Rumpun Kesihatan Terengganu Berhad (KIHT BERHAD)</a></li>
            <li><a href="https://app.ikoop.com.my/fkcapital/">Koperasi F.K Capital Berhad (FKCAPITAL)</a></li>
            <li><a href="https://app.ikoop.com.my/koyaqin/">Koperasi Masjid Nurul Yaqin Kelana Jaya Berhad (KOYAQIN)</a></li>
            <li><a href="https://app.ikoop.com.my/cooputhm/">Koperasi UTHM Berhad (COOPUTHM)</a></li>
            <li><a href="https://app.ikoop.com.my/imuara/">Koperasi iMuara Muar Berhad (iMuara)</a></li>
            <li><a href="https://app.ikoop.com.my/kkbs/">Koperasi Komuniti Bangi Selangor Berhad (KKBS)</a></li>
            <li><a href="https://app.ikoop.com.my/kohalagel/">Koperasi Kumpulan Halagel Kedah Berhad (KOHALAGEL)</a></li>
            <li><a href="https://app.ikoop.com.my/kobangi/">Koperasi Penduduk Bandar Baru Bangi (KOBANGI)</a></li>
            <li><a href="https://app.ikoop.com.my/koopgtr/">Koperasi Kelab Generasi Transformasi Ruminan Malaysia Berhad (KOOPGTR)</a></li>
            <li><a href="https://app.ikoop.com.my/kowari/">Koperasi Wawasan Bestari Berhad (KOWARI)</a></li>
            <li><a href="https://app.ikoop.com.my/komac/">Koperasi Macrotech Berhad (KOMAC)</a></li>
            <li><a href="https://app.ikoop.com.my/kosiswausim/">Koperasi Siswa Universiti Sains Islam Malaysia (KOSISWAUSIM)</a></li>
            <li><a href="https://app.ikoop.com.my/kusga/">Koperasi Usahawan Gading Mekar Berhad (KUSGA)</a></li>
            <li><a href="https://app.ikoop.com.my/kocats/">Koperasi Cats Berhad (KOCATS)</a></li>
            <li><a href="https://app.ikoop.com.my/kojerosabhd/">Koperasi Jelupang Roban Seratok Berhad (KOJEROSABHD)</a></li>
            <li><a href="https://app.ikoop.com.my/kgansb/">Koperasi Guru-guru Agama Negeri Sembilan Berhad (KGANSB)</a></li>
            <li><a href="https://app.ikoop.com.my/kimb/">Koperasi IKRAM Miri Sdn Bhd (KIMb)</a></li>
            <li><a href="https://app.ikoop.com.my/coopadmar/">Koperasi Keluarga Hj Adaham Dan Hajah Maria Miri Bhd (CoopADMAR)</a></li>
            <li><a href="https://app.ikoop.com.my/kkksmb/">Koperasi Kampung Kuala Sibuti Miri Berhad (KKKSMB)</a></li>
            <li><a href="https://app.ikoop.com.my/kkmbcoopmart/">Koperasi Kariah Masjid Binjai Kemaman Berhad (KKMBCOOPMART)</a></li>
            <li><a href="https://app.ikoop.com.my/kpfp/">Koperasi Peserta Felcra Paloh Berhad (KPFP)</a></li>
            <li><a href="https://app.ikoop.com.my/koopbait/">Koperasi Al  Bait Sarawak Berhad (KOOPBAIT)</a></li>
            <li><a href="https://app.ikoop.com.my/kuscoop/">Koperasi Kuskop (Kuscoop) Berhad (KUSCOOP)</a></li>
            <li><a href="https://app.ikoop.com.my/kohidmas/">Koperasi Perkhidmatan Berhad (KOHIDMAS)</a></li>
            <li><a href="https://app.ikoop.com.my/kobb/">Koperasi Bumipeda Berhad (KOBB)</a></li>
            <li><a href="https://app.ikoop.com.my/kopakat/">Koperasi Muafakat Mara Kebangsaan Berhad (KOPAKAT)</a></li>
            <li><a href="https://app.ikoop.com.my/koasmara/">Koperasi Anak Sains Mara Berhad (KOASMARA)</a></li>
        </ul>
        </div>

        <!-- <div class="panel-body">
        <form role="form">
        <fieldset>
        <div class="container mt-3">
        <select id="menu" class="custom-select mb-3">
        <option value="">Pilih Koperasi</option>
        
<option value="http://sekatarakyat.bankrakyat.com.my/sekata/">Koperasi Kakitangan Bank Rakyat (SEKATARAKYAT)</option>
<option value="http://ikoop.com.my/kofrim/index.php">Koperasi FRIM Berhad (KOFRIM)</option>
<option value="http://ikoop.com.my/kobizz/index.php">Koperasi Bekas Pelajar-Pelajar Izzuddin Shah Ipoh (KOBIZZ)</option>
<option value="http://ikoop.com.my/kobatim/index.php">Koperasi Pribumi Batang Padang Dan Muallim Berhad (KOBATIM)</option>
<option value="http://ikoop.com.my/kokuis/index.php">Koperasi Kolej Universiti Islam Antarabangsa Selangor (KOKUIS)</option>
<option value="http://ikoop.com.my/kohijrah/index.php">Koperasi Warga Hijrah Selangor Berhad (KOHIJRAH)</option>
<option value="http://ikoop.com.my/kopama/index.php">Koperasi Alumni MKM WPKL (KOPAMA)</option>
<option value="http://ikoop.com.my/koprojaya/index.php">Koperasi Profesional Putrajaya Berhad (KOPROJAYA)</option>
<option value="http://ikoop.com.my/kojahus/index.php">Koperasi Jabatan Perhutanan Negeri Selangor (KOJAHUS)</option>
<option value="http://ikoop.com.my/kopubi/index.php">Koperasi Usahawan Bersatu Ipoh Perak Berhad (KOPUBI)</option>
<option value="http://ikoop.com.my/kagum/index.php">Koperasi Guru Melayu Perak Berhad (KAGUM)</option>
<option value="http://ikoop.com.my/kllmb/index.php">Koperasi Lembaga Lebuhraya Malaysia Berhad (KLLMB)</option>
<option value="http://ikoop.com.my/koppim/index.php">Koperasi Pembangunan Pengguna Islam Berhad (KoPPIM)</option>
<option value="http://ikoop.com.my/koopjpagro/index.php">Koperasi Pegawai-Pegawai Melayu Jabatan Pertanian Berhad (KoopJPAGRO)</option>
<option value="http://ikoop.com.my/kobumira/index.php">Koperasi Bumira Malaysia Berhad (KOBUMIRA)</option>
<option value="http://ikoop.com.my/koopmkm/index.php">Koperasi Kakitangan Maktab Koperasi Malaysia Berhad (KOOPMKM)</option>
<option value="http://ikoop.com.my/karisma/index.php">Koperasi Anggota Risda Malaysia Berhad (KARISMA)</option>
<option value="http://ikoop.com.my/komas/index.php">Koperasi Kariah Masjid Sungai Ramal Luar Kajang Selangor Berhad (KOMAS)</option>
<option value="http://ikoop.com.my/kkb/index.php">Koperasi Keselamatan Berhad (KKB)</option>
<option value="http://ikoop.com.my/kocidb/index.php">Koperasi Staf CIDB Malaysia Berhad (KoCIDB)</option>
<option value="http://ikoop.com.my/kounisem/index.php">Koperasi Pekerja-Pekerja Unisem (M) Berhad (KOUNISEM)</option>
<option value="http://ikoop.com.my/kompob/index.php">Koperasi Pegawai MPOB Berhad (KOMPOB)</option>
<option value="http://ikoop.com.my/koepf/index.php">Koperasi Kakitangan KWSP Malaysia Berhad
 (KOEPF)</option>
<option value="http://ikoop.com.my/koagrossb/index.php">Koperasi Agromakanan Sehat Selangor Berhad
(KOAGROSSB)</option>
<option value="http://ikoop.com.my/kopkenem/index.php">Koperasi Pekebun Getah Negeri Melaka
(KOPKENEM)</option>
<option value="http://ikoop.com.my/kkpb/index.php">Koperasi Kakitangan Puspati Berhad
(KKPB)</option>

    </select>
<a name="go" class="btn btn-default" id="go" onClick="gotosite()" style="background-color: #39b54a; color: #fff; border-radius: 12px;border: 1px solid white; padding: 13px 25px;">SETERUSNYA</a>
</div>
</fieldset>
</form> -->
</div></div></div></div></div>
</section>
<!-- #DROPDOWN BUTTON -->
               
                  <?php include ('footer.php'); ?>

                  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
                  <script src="js/jquery.min.js "></script>
                  <!-- Include all compiled plugins (below), or include individual files as needed -->
                  <script src="js/bootstrap.min.js "></script>
                  <script type="text/javascript " src="js/menumaker.js "></script>

                  <!-- sticky header -->
                  <script type="text/javascript " src="js/jquery.sticky.js "></script>
                  <script type="text/javascript " src="js/sticky-header.js "></script>
                  <!-- Back to top script -->
                  <script src="js/back-to-top.js " type="text/javascript "></script>
                  <script src="js/accordion.js" type="text/javascript"></script>
                  <script src="js/jquery-ui.js"></script>
                  <script>
                    $(function() {
                        $("#slider-range-min").slider({
                            range: "min",
                            value: 3000,
                            min: 1000,
                            max: 5000,
                            slide: function(event, ui) {
                                $("#amount").val("$" + ui.value);
                            }
                        });
                        $("#amount").val("$" + $("#slider-range-min").slider("value"));
                    });
                </script>
                <script>
                    $(function() {
                        $("#slider-range-max").slider({
                            range: "max",
                            min: 1,
                            max: 10,
                            value: 2,
                            slide: function(event, ui) {
                                $("#j").val(ui.value);
                            }
                        });
                        $("#j").val($("#slider-range-max").slider("value"));
                    });
                </script>
                <script>
                    function gotosite() {
                             window.location = document.getElementById("menu").value; // JQuery:  $("#menu").val();

                            // Jika pengguna telah memilih suatu nilai
                            if (selectedValue !== "") {
                                // Membuka laman web yang sesuai dengan nilai yang dipilih
                                window.open(selectedValue);
                            } else {
                                alert("Sila pilih koperasi terlebih dahulu.");
                            }
                         }
                     </script>

                    <script>
                    const searchInput = document.getElementById('searchInput');
                    const dropdownList = document.getElementById('dropdownList');

                    searchInput.addEventListener('input', function () {
                    const searchTerm = searchInput.value.toLowerCase();
                    const items = dropdownList.getElementsByTagName('li');

                    for (let i = 0; i < items.length; i++) {
                        const itemText = items[i].textContent.toLowerCase();
                        if (itemText.includes(searchTerm)) {
                        items[i].style.display = 'block';
                        } else {
                        items[i].style.display = 'none';
                        }
                    }

                    // Display or hide the dropdown based on search input
                    dropdownList.style.display = searchTerm ? 'block' : 'none';
                    });

                    // Toggle dropdown visibility when clicking the search input
                    searchInput.addEventListener('click', function () {
                    dropdownList.style.display = dropdownList.style.display === 'block' ? 'none' : 'block';
                    });

                        </script>

                 </body>

                 </html>
