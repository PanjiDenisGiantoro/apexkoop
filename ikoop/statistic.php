<?php
date_default_timezone_set("Asia/Jakarta");
if (!isset($q))            $q = "";
if (!isset($dept))        $dept = "";
if (!isset($by))        $by = "1";
include("header.php");
include("koperasiQry.php");

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 AND get_session("Cookie_groupID") <> 3 AND get_session("Cookie_groupID") <> 4 AND get_session("Cookie_groupID") <> 5 OR get_session("Cookie_koperasiID") <> 0) {
    print '<script>alert("' . $errPage . '");parent.location.href = "index.php";</script>';
}

$sFileName = '?vw=statistic2&mn=900';
$sFileRef  = '?vw=statistic2&mn=900';
$title     = "Statistik Koperasi ikoop";

$IDName = get_session("Cookie_userName");

//--- Prepare department list
$deptList = Array();
$deptVal  = Array();
$jenisCodeList = Array();
$jenisCodeVal  = Array();
$fasaList = Array();
$fasaVal  = Array();

$sSQL = "";

// ----------------------------- fetch query -------------------------------------
// Count the total number of cooperatives
$totalCooperatives = $conn->Execute("SELECT COUNT(*) AS total FROM userdetails /* WHERE status = 1  */")->fields['total'];

// Calculate the distribution by department
$departmentStats = array();
$departmentQuery = $conn->Execute("SELECT departmentID, COUNT(*) AS count FROM userdetails GROUP BY departmentID");
while (!$departmentQuery->EOF) {
    $departmentID = $departmentQuery->fields['departmentID'];
    $count = $departmentQuery->fields['count'];
    $departmentStats[$departmentID] = $count;
    $departmentQuery->MoveNext();
}

// Calculate the distribution by state
$stateStats = array();
$stateQuery = $conn->Execute("SELECT stateID, COUNT(*) AS count FROM userdetails GROUP BY stateID");
while (!$stateQuery->EOF) {
    $stateID = $stateQuery->fields['stateID'];
    $count = $stateQuery->fields['count'];
    $stateStats[$stateID] = $count;
    $stateQuery->MoveNext();
}

// Calculate the distribution by jenisCode
$jenisCodeStats = array();
$jenisCodeQuery = $conn->Execute("SELECT jenisCode, COUNT(*) AS count FROM userdetails GROUP BY jenisCode");
while (!$jenisCodeQuery->EOF) {
    $jenisCode = $jenisCodeQuery->fields['jenisCode'];
    $count = $jenisCodeQuery->fields['count'];
    $jenisCodeStats[$jenisCode] = $count;
    $jenisCodeQuery->MoveNext();
}

// Calculate the distribution by pakej
$pakejStats = array();
$pakejQuery = $conn->Execute("SELECT pakej, COUNT(*) AS count FROM userdetails GROUP BY pakej");
while (!$pakejQuery->EOF) {
    $pakej = $pakejQuery->fields['pakej'];
    $count = $pakejQuery->fields['count'];
    $pakejStats[$pakej] = $count;
    $pakejQuery->MoveNext();
}

// Calculate the distribution by kategori
$kategoriStats = array();
$kategoriQuery = $conn->Execute("SELECT kategori, COUNT(*) AS count FROM userdetails GROUP BY kategori");
while (!$kategoriQuery->EOF) {
    $kategori = $kategoriQuery->fields['kategori'];
    $count = $kategoriQuery->fields['count'];
    $kategoriStats[$kategori] = $count;
    $kategoriQuery->MoveNext();
}

// Calculate the distribution by jenis koperasi
$jenisStats = array();
$jenisQuery = $conn->Execute("SELECT jenis, COUNT(*) AS count FROM userdetails GROUP BY jenis");
while (!$jenisQuery->EOF) {
    $jenisStatus = $jenisQuery->fields['jenis'];
    $count = $jenisQuery->fields['count'];
    $jenisStats[$jenisStatus] = $count;
    $jenisQuery->MoveNext();
}

// Calculate the distribution by status koperasi
$statusStats = array();
$statusQuery = $conn->Execute("SELECT status, COUNT(*) AS count FROM userdetails WHERE status IN (1,3) GROUP BY status");
while (!$statusQuery->EOF) {
    $statusStatus = $statusQuery->fields['status'];
    $count = $statusQuery->fields['count'];
    $statusStats[$statusStatus] = $count;
    $statusQuery->MoveNext();
}

// Calculate the distribution by training status
$trainingStats = array();
$trainingQuery = $conn->Execute("SELECT training, COUNT(*) AS count FROM userdetails GROUP BY training");
while (!$trainingQuery->EOF) {
    $trainingStatus = $trainingQuery->fields['training'];
    $count = $trainingQuery->fields['count'];
    $trainingStats[$trainingStatus] = $count;
    $trainingQuery->MoveNext();
}

// Calculate the distribution by fasa status
$fasaStats = array();
$fasaQuery = $conn->Execute("SELECT fasa, COUNT(*) AS count FROM userdetails GROUP BY fasa");
while (!$fasaQuery->EOF) {
    $fasa = $fasaQuery->fields['fasa'];
    $count = $fasaQuery->fields['count'];
    $fasaStats[$fasa] = $count;
    $fasaQuery->MoveNext();
}


// ----------------------------- store in array -------------------------------------
// Display the total counts
//echo '<h5 class="card-title">Total Cooperative Statistics</h5>';
//echo '<p>Total Number of Cooperatives: ' . $totalCooperatives . '</p>';

//Zon statistik
//echo '<br><h6>Cooperatives by Zon:</h6>';
$zonLabels = array();
foreach ($departmentStats as $departmentID => $count) {
    $departmentName = dlookup("general", "name", "ID=" . tosql($departmentID, "Number"));
    if ($departmentName === null) {
        $departmentName = "Unknown";
    } 
    $zonLabels[] = $departmentName;
    //echo '<p>' . $departmentName . ': ' . $count . '</p>';
}

//state statistik
$stateLabels = array();
foreach ($stateStats as $stateID => $count) {
    $stateName = dlookup("general", "name", "ID=" . tosql($stateID, "Number"));
    if ($stateName === null) {
        $stateName = "Unknown";
    } 
    $stateLabels[] = $stateName;
}

/* //Status statistik
//echo '<br><h6>Cooperatives by Status:</h6>';
$statusLabels = array();
foreach ($statusList as $statusKey => $statusName) {
    $count = isset($statusStats[$statusKey]) ? $statusStats[$statusKey] : 0;
    $statusLabels[] = $statusName;
    //echo '<p>' . $statusName . ': ' . $count . '</p>';
} */

//status statistik
$statusLabels = array();
foreach ($statusStats as $statusStatus => $count) {
    if ($statusList[$statusStatus] === null) {
        $statusList[$statusStatus] = "Unknown";
    }
    $statusLabels[] = $statusList[$statusStatus];
}


//Kod statistik 
$jenisCodeLabels = array();
foreach ($jenisCodeStats as $jenisCode => $count) {
    $jenisCodeName = dlookup("general", "name", "ID=" . tosql($jenisCode, "Number"));
    if ($jenisCodeName === null) {
        $jenisCodeName = "Unknown";
    }
    $count = isset($jenisCodeStats[$jenisCodeKey]) ? $jenisCodeStats[$jenisCodeKey] : 0;
    $jenisCodeLabels[] = $jenisCodeName;
    //echo '<p>' . $jenisCodeName . ': ' . $count . '</p>';
}


//pakej
$pakejLabels = array();
foreach ($pakejStats as $pakej => $count) {
    $pakejName = dlookup("general", "name", "ID=" . tosql($pakej, "Number"));
    if ($pakejName === null) {
        $pakejName = "Unknown";
    }
    $count = isset($pakejStats[$pakejKey]) ? $pakejStats[$pakejKey] : 0;
    $pakejLabels[] = $pakejName;
}

//kategori
$kategoriLabels = array();
foreach ($kategoriStats as $kategori => $count) {
    $kategoriName = dlookup("general", "name", "ID=" . tosql($kategori, "Number"));
    if ($kategoriName === null) {
        $kategoriName = "Unknown";
    }
    $count = isset($kategoriStats[$kategoriKey]) ? $kategoriStats[$kategoriKey] : 0;
    $kategoriLabels[] = $kategoriName;
}

//Jenis koperasi statistik
$jenisLabels = array();
foreach ($jenisStats as $jenisStatus => $count) {
    if ($jenisList[$jenisStatus] === null) {
        $jenisList[$jenisStatus] = "Unknown";
    }
    $jenisLabels[] = $jenisList[$jenisStatus];
}


//Training statistik
$trainingLabels = array();
foreach ($trainingStats as $trainingStatus => $count) {
    $trainingLabels[] = $trainingList[$trainingStatus];
}

// Fasa statistik
//echo '<br><h6>Cooperatives by Training Fasa Status:</h6>';
/* $fasaLabels = array();
foreach ($fasaStats as $fasaStatus => $count) {
    //$count = isset($fasaStats[$fasaStatus]) ? $fasaStats[$fasaStatus] : 0;
    $fasaLabels[] = $fasaList[$fasaStatus];
    //echo '<p>' . $fasaName . ': ' . $count . '</p>';
}
 */

$fasaLabels = array();
foreach ($fasaStats as $fasa => $count) {
    $fasaName = dlookup("general", "name", "ID=" . tosql($fasa, "Number"));
    if ($fasaName === null) {
        $fasaName = "NULL";
    }
    $fasaName = ($fasa == 0) ? "TIADA FASA" : dlookup("general", "name", "ID=" . tosql($fasa, "Number"));
    $count = isset($fasaStats[$fasaKey]) ? $fasaStats[$fasaKey] : 0;
    $fasaLabels[] = $fasaName;
} 


print'<h3 class="title" align="center">'.strtoupper($title).'</h3><hr>';
include("footer.php");
?>



<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body>
    <div class="title">Jumlah Koperasi: <?php echo $totalCooperatives; ?></div>
    <style>
    .title {
        text-align: center;
        font-size: 21px;
        color: #495057;
        text-transform: uppercase;
        font-family: 'Arial', sans-serif;
        font-weight: bold;
    }

    .chart-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .chart {
        width: calc(33.33% - 20px);
        margin: 10px;
        padding: 10px;
        background-color: #fff;
        /* box-shadow: 0 0 10px rgba(202, 202, 202, 0.7); */
        border-radius: 30px;
    }

    .chart canvas {
        width: 100%;
        height: 100%;
    }

    .category-container {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-top: 20px;
        box-shadow: 0 0 10px rgba(202, 202, 202, 1);
    }

    .category-title {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    font-weight: bold;
    font-size: 18px;
    background-color: #730b33;
    color: #fff;
    padding: 10px;
    border-radius: 100px;
    margin: 0 auto 10px;
}


    .centered-charts {
        display: flex;
        width: 100%;
        justify-content: center;
    }

    .category-container.chart .centered-charts .chart {
    width: 600px;
}
</style>
</head>

<body>
<div class="category-container chart">
    <!-- Category 1 -->
    <div class="category-title">Statistik Asas Koperasi</div>
    <div class="centered-charts">
        <div class="chart" >
            <h4 align="center">Zon Koperasi</h4><br>
            <canvas id="zonPieChart" style="display: block;"></canvas>
        </div>
        <div class="chart">
            <h4 align="center">Negeri Koperasi</h4><br>
            <canvas id="statePieChart" style="display: block;"></canvas>
        </div>
    </div>

    <div class="chart" style="width: calc(100%);"></div>

    <div class="chart" style="width: calc(32.33% - 40px);">
            <h4 align="center">Status Koperasi</h4><br>
            <canvas id="statusBarChart" style="display: block;"></canvas>
        </div>
    <div class="chart" style="width: calc(32.33% - 40px);">
        <h4 align="center">Jenis Koperasi</h4><br>
        <canvas id="jenisPieChart" height="200px" style="display: block;"></canvas>
    </div>
    <div class="chart" style="width: calc(37.33% - 20px);">
        <h4 align="center">Kod Koperasi</h4><br><br>
        <canvas id="kodBarChart" height="400px" style="display: block;"></canvas>
    </div>
</div>

<div class="category-container chart">
    <!-- Category 2 -->
    <div class="category-title">Latihan & Fasa</div>
    <div class="centered-charts">
    <div class="chart">
        <h4 align="center">Latihan Koperasi</h4>
        <canvas id="trainingPieChart" style="display: block;"></canvas>
    </div>

    <div class= "chart">
        <h4 align="center">Fasa Latihan Koperasi</h4>
        <canvas id="fasaBarChart" style="display: block;"></canvas>
    </div>
    </div>
</div>

<div class="category-container chart">
    <!-- Category 3 -->
    <div class="category-title">Pakej & Kategori</div>
    <div class="centered-charts">
        <div class="chart">
            <h4 align="center">Pakej Koperasi</h4>
            <canvas id="pakejBarChart" style="display: block;"></canvas>
        </div>
        <div class="chart">
            <h4 align="center">Kategori Koperasi</h4>
            <canvas id="kategoriBarChart"  style="display: block;"></canvas>
        </div>
    </div>
</div>
</div>



    <script>
        //zon
        var zonData = <?php echo json_encode(array_values($departmentStats)); ?>;
        var zonLabels = <?php echo json_encode($zonLabels); ?>;

        //state
        var stateData = <?php echo json_encode(array_values($stateStats)); ?>;
        var stateLabels = <?php echo json_encode($stateLabels); ?>;

        //status
        var statusData = <?php echo json_encode(array_values($statusStats)); ?>;
        var statusLabels = <?php echo json_encode($statusLabels); ?>;

        //kod
        var kodData = <?php echo json_encode(array_values($jenisCodeStats)); ?>;
        var jenisCodeLabels = <?php echo json_encode($jenisCodeLabels); ?>;

        //pakej
        var pakejData = <?php echo json_encode(array_values($pakejStats)); ?>;
        var pakejLabels = <?php echo json_encode($pakejLabels); ?>;

        //kategori
        var kategoriData = <?php echo json_encode(array_values($kategoriStats)); ?>;
        var kategoriLabels = <?php echo json_encode($kategoriLabels); ?>;
        
        //jenis koperasi
        var jenisData = <?php echo json_encode(array_values($jenisStats)); ?>;
        var jenisLabels = <?php echo json_encode($jenisLabels); ?>;

        //training
        var trainingData = <?php echo json_encode(array_values($trainingStats)); ?>;
        var trainingLabels = <?php echo json_encode($trainingLabels); ?>;

        //fasa
        var fasaData = <?php echo json_encode(array_values($fasaStats)); ?>;
        var fasaLabels = <?php echo json_encode($fasaLabels); ?>;
        
        var zonPieChartCanvas = document.getElementById("zonPieChart").getContext("2d");
        var statePieChartCanvas = document.getElementById("statePieChart").getContext("2d");
        var statusBarChartCanvas = document.getElementById("statusBarChart").getContext("2d");
        var kodBarChartCanvas = document.getElementById("kodBarChart").getContext("2d");
        var pakejBarChartCanvas = document.getElementById("pakejBarChart").getContext("2d");
        var kategoriBarChartCanvas = document.getElementById("kategoriBarChart").getContext("2d");
        var jenisPieChartCanvas = document.getElementById("jenisPieChart").getContext("2d");
        var trainingPieChartCanvas = document.getElementById("trainingPieChart").getContext("2d");
        var fasaBarChartCanvas = document.getElementById("fasaBarChart").getContext("2d");

        Chart.register(ChartDataLabels);

        //stats zon
        var zonPieChart = new Chart(zonPieChartCanvas, {
            type: 'pie',
            data: {
                labels: zonLabels,
                datasets: [{
                    data: zonData,
                    backgroundColor: [
                        'rgba( 186, 186, 186, 0.7)',
                        'rgba( 117, 205, 81, 0.9)',
                        'rgba( 205, 81, 113, 1)',
                        'rgba(164, 137, 248, 0.7)',
                        'rgba(54, 97, 196, 0.7)',
                        'rgba( 54, 196, 177  , 0.7)',
                        'rgba(236, 213, 95 , 0.7)',
                    ],     
                }],
            },
            options: {
                plugins: {
                    legend: {
                        display: true,   
                        position: 'bottom',
                        labels: {
                            fontSize: 10,
                            usePointStyle: true
                        }
                    },
                    datalabels: {
                        color: 'black',
                        formatter: (value, context) => {
                            // Convert array of strings to an array of numbers and then calculate the total
                            const total = zonData.map(Number).reduce((accumulator, currentValue) => accumulator + currentValue, 0);
                            //zonTotalElement.innerHTML = "Jumlah: " + total + " Koperasi";
                            const percentage = ((value / total) * 100).toFixed(2) + '%';
                            return value + ' (' + percentage + ')';    
                        },
                        font: {
                            weight: 'bold',
                            size: 13,
                        },
                    },
                },
            },
        });

         //stats state
         var statePieChart = new Chart(statePieChartCanvas, {
            type: 'doughnut',
            data: {
                labels: stateLabels,
                datasets: [{
                    data: stateData,
                    backgroundColor: [
                        'rgba( 186, 186, 186, 0.7)', //unknown
                        'rgba(255, 99, 132, 0.7)',    // Red
                        'rgba(54, 162, 235, 0.7)',   // Blue
                        'rgba(255, 206, 86, 0.7)',   // Yellow
                        'rgba(75, 192, 192, 0.7)',   // Teal
                        'rgba(153, 102, 255, 0.7)',  // Purple
                        'rgba(255, 159, 64, 0.7)',   // Orange
                        'rgba(139, 69, 19, 0.7)',    // Brown
                        'rgba(0, 128, 0, 0.7)',      // Green
                        'rgba(0, 0, 128, 0.7)',      // Navy
                        'rgba(128, 0, 128, 0.7)',    // Magenta
                        'rgba(128, 128, 0, 0.7)',    // Olive
                        'rgba(128, 0, 10, 0.7)',      // Maroon
                        'rgba(0, 128, 128, 0.7)',    // Teal
                        'rgba(215, 0, 255, 0.5)',    // Fuchsia
                    ],
                }],
            },
            options: {
                title: {
                    display: true,
                    text: 'Cooperatives by State',
                },
                plugins: {
                    legend: {
                        display: true, // Hide the legend
                        position: 'bottom',
                        labels: {
                            fontSize: 10,
                            usePointStyle: true
                        }
                    },
                    datalabels: {
                        color: 'black', // Set a contrasting color
                        formatter: (value, context) => {
                            const total = stateData.map(Number).reduce((accumulator, currentValue) => accumulator + currentValue, 0);
                            const percentage = ((value / total) * 100).toFixed(2) + '%';
                            return value + '('+percentage+')';
                        },
                        font: {
                            weight: 'bold',
                            size: 12,
                        },
                    },
                },
                animation: {
                    animateRotate: true, // Enables rotation animation
                    animateScale: true, // Enables scaling animation
                },
            },
        });


        //stats status
        var statusBarChart = new Chart(statusBarChartCanvas, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: ['rgba( 154, 5, 123, 0.6)', 
                    'rgba(125, 199, 32, 0.7)', 
                    'rgba(222, 106, 16, 0.6)', 
                    'rgba(75, 192, 192, 0.6)', 
                    'rgba(77, 152, 152, 0.6)'],
                }],
            },
            options: {
                title: {
                    display: true,
                    text: 'Cooperatives by Kod',
                },
                plugins: {
                    legend: {
                        display: true, // Hide the legend
                        position: 'bottom',
                        labels: {
                            fontSize: 10,
                            usePointStyle: true
                        }
                    },
                    datalabels: {
                        color: 'black', // Set a contrasting color
                        formatter: (value, context) => {
                            const total = statusData.map(Number).reduce((accumulator, currentValue) => accumulator + currentValue, 0);
                            const percentage = ((value / total) * 100).toFixed(2) + '%';
                            return value + ' (' + percentage + ')';
                        },
                        font: {
                            weight: 'bold',
                            size: 12,
                        },
                    },
                },
            },
        });


        //stats pakej
        var pakejBarChart = new Chart(pakejBarChartCanvas, {
            type: 'bar',
            data: {
                labels: pakejLabels,
                datasets: [{
                    data: pakejData,
                        backgroundColor: ['rgba( 186, 186, 186, 0.7)',
                                'rgba(0, 0, 128, 0.7)',      // Navy
                                    'rgba(128, 0, 128, 0.7)',    // Magenta
                                    'rgba(128, 128, 0, 0.7)',    // Olive
                                    'rgba(128, 0, 0, 0.7)',      // Maroon
                                    'rgba(0, 128, 128, 0.7)',    // Teal
                                'rgba(11, 221, 122, 0.8)'],
                    borderColor: 'rgba(0, 0, 0, 1)', // Change the border color
                    borderWidth: 0
                }],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombor Koperasi',
                        },
                        grid: {
                            display: true
                        }
                    },
                    x:{
                        grid: {
                            display: false
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Cooperatives by Pakej',
                },
                plugins: {
                    legend: {
                        display: false, // Hide the legend
                    },
                    datalabels: {
                        color: 'black', // Set a contrasting color
                        formatter: (value, context) => {
                            return value;
                        },
                        font: {
                            weight: 'bold',
                            size: 12,
                        },
                    },
                },
                animation: {
                    duration: 1800, // Animation duration in milliseconds
                    easing: 'easeOutBounce', // Easing function for animation (e.g., 'linear', 'easeInOutQuart', etc.)
                },
            },
        });

        //stats kategori
        var kategoriBarChart = new Chart(kategoriBarChartCanvas, {
            type: 'bar',
            data: {
                labels: kategoriLabels,
                datasets: [{
                    data: kategoriData,
                    backgroundColor: ['rgba( 186, 186, 186, 0.7)',
                                'rgba(125, 199, 32, 0.7)',
                                'rgba(222, 16, 186, 0.6)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(177, 152, 152, 0.8)',
                                'rgba(11, 221, 122, 0.8)'],
                    borderColor: 'rgba(0, 0, 0, 1)', // Change the border color
                    borderWidth: 0
                }],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombor Koperasi',
                        },
                        grid: {
                            display: true
                        }
                    },
                    x:{
                        grid: {
                            display: false
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Cooperatives by Kategori',
                },
                plugins: {
                    legend: {
                        display: false, // Hide the legend
                    },
                    datalabels: {
                        color: 'black', // Set a contrasting color
                        formatter: (value, context) => {
                            return value;
                        },
                        font: {
                            weight: 'bold',
                            size: 12,
                        },
                    },
                },
                animation: {
                    duration: 1800, // Animation duration in milliseconds
                    easing: 'easeOutBounce', // Easing function for animation (e.g., 'linear', 'easeInOutQuart', etc.)
                },
            },
        });


        //stats kod
        var kodBarChart = new Chart(kodBarChartCanvas, {
            type: 'bar',
            data: {
                labels: jenisCodeLabels,
                datasets: [{
                    data: kodData,
                    backgroundColor: ['rgba( 186, 186, 186, 0.7)',
                                'rgba(225, 199, 32, 0.7)',
                                'rgba(255, 10, 255, 0.5)',
                                'rgba(0, 128, 128, 0.7)',
                                'rgba(177, 152, 152, 0.8)',
                                'rgba(128, 128, 0, 0.7)'],
                    borderColor: 'rgba(0, 0, 0, 1)', // Change the border color
                    borderWidth: 0
                }],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombor Koperasi',
                        },
                        grid: {
                            display: true
                        }
                    },
                    x:{
                        grid: {
                            display: false
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Cooperatives by Kod',
                },
                plugins: {
                    legend: {
                        display: false, // Hide the legend
                    },
                    datalabels: {
                        color: 'black', // Set a contrasting color
                        formatter: (value, context) => {
                            return value;
                        },
                        font: {
                            weight: 'bold',
                            size: 12,
                        },
                    },
                },
                animation: {
                    duration: 1800, // Animation duration in milliseconds
                    easing: 'easeOutBounce', // Easing function for animation (e.g., 'linear', 'easeInOutQuart', etc.)
                },
            },
        });


        //stats jenis koperasi
        var jenisPieChart = new Chart(jenisPieChartCanvas, {
            type: 'doughnut',
            data: {
                labels: jenisLabels,
                datasets: [{
                    data: jenisData,
                    backgroundColor: [
                        'rgba( 186, 186, 186, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(248, 255, 40, 0.5)',
                    ],
                }],
            },
            options: {
                title: {
                    display: true,
                    text: 'Cooperatives by Jenis',
                },
                plugins: {
                    legend: {
                        display: true, 
                        position: 'bottom',
                        labels: {
                            fontSize: 10,
                            usePointStyle: true
                        }   
                    },
                    datalabels: {
                        color: 'black',
                        
                        formatter: (value, context) => {
                            const total = jenisData.map(Number).reduce((accumulator, currentValue) => accumulator + currentValue, 0);
                            const percentage = ((value / total) * 100).toFixed(2) + '%';
                            return value + ' (' + percentage + ')';
                        },
                        font: {
                            weight: 'bold',
                            size: 12,
                        },
                    },
                },
            },
        });


        //stats latihan
        var trainingPieChart = new Chart(trainingPieChartCanvas, {
            type: 'bar',
            data: {
                labels: trainingLabels,
                datasets: [{
                    data: trainingData,
                    backgroundColor: ['rgba( 205, 31, 67 , 0.9)', 
                    'rgba( 31, 97, 205, 0.8)'],
                    borderColor: 'rgba(0, 0, 0, 1)', // Change the border color
                    borderWidth: 0
                }],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombor Koperasi',
                        }
                    },
                    x:{
                        grid: {
                            display: false
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Cooperatives by Training Status',
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    datalabels: {   
                        color: 'black',
                        formatter: (value, context) => {
                            return value;
                        },
                        font: {
                            weight: 'bold',
                            size: 13,
                        },
                    },  
                },
                animation: {
                    duration: 1800, // Animation duration in milliseconds
                    easing: 'easeOutBounce', // Easing function for animation (e.g., 'linear', 'easeInOutQuart', etc.)
                },
            },
        });

        //stats fasa
        var fasaBarChart = new Chart(fasaBarChartCanvas, {
            type: 'bar',
            data: {
                labels: fasaLabels,
                datasets: [{
                    data: fasaData,
                    backgroundColor: ['rgba( 176, 35, 35 , 0.8)', 
                    'rgba(46, 176, 35, 0.8)',
                    'rgba(35, 176, 157, 0.8)',
                    'rgba(35, 106, 176, 0.8)'],
                    borderColor: 'rgba(0, 0, 0, 1)',
                    borderWidth: 0
                }],
            },
            options: {
                scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Nombor Koperasi',
                    }
                },
                x:{
                        grid: {
                            display: false
                        }
                    }
            },
                title: {
                    display: true,
                    text: 'Cooperatives by Training Fasa Status',
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    
                    datalabels: {
                        color: 'black',
                        formatter: (value, context) => {
                            return value;
                        },
                        font: {
                            weight: 'bold',
                            size: 12,
                        },
                    },
                    
                },
                animation: {
                    duration: 1800, // Animation duration in milliseconds
                    easing: 'easeOutBounce', // Easing function for animation
                },
            },
        });

        // Function to rotate the chart
        /* function rotateChart() {
            var angle = 0; // Initial angle

            function updateChart() {
                angle += 20; // Increment the rotation angle
                statePieChart.options.rotation = (angle * Math.PI) / 180; // Convert to radians
                statePieChart.update(); // Update the chart
                zonPieChart.options.rotation = (angle * Math.PI) / 180; // Convert to radians
                zonPieChart.update(); // Update the chart
                requestAnimationFrame(updateChart); // Request the next frame
            }

            updateChart(); // Start the continuous rotation
        }

        rotateChart(); // Call the function to start the rotation */

    </script>
    
</body>
</html>
