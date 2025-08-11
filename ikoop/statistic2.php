<?php
date_default_timezone_set("Asia/Kuala_Lumpur");
if (!isset($q))            $q = "";
if (!isset($dept))        $dept = "";
if (!isset($by))        $by = "1";
include("header.php");
include("koperasiQry.php");

if (get_session("Cookie_groupID") <> 1 AND get_session("Cookie_groupID") <> 2 OR get_session("Cookie_koperasiID") <> 0) {
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

$sSQL = "";

// Count the total number of cooperatives
$totalCooperatives = $conn->Execute("SELECT COUNT(*) AS total FROM users")->fields['total'];

// Calculate the distribution by department
$departmentStats = array();
$departmentQuery = $conn->Execute("SELECT departmentID, COUNT(*) AS count FROM userdetails GROUP BY departmentID");
while (!$departmentQuery->EOF) {
    $departmentID = $departmentQuery->fields['departmentID'];
    $count = $departmentQuery->fields['count'];
    $departmentStats[$departmentID] = $count;
    $departmentQuery->MoveNext();
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
$statusQuery = $conn->Execute("SELECT status, COUNT(*) AS count FROM userdetails GROUP BY status");
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
    $fasaStatus = $fasaQuery->fields['fasa'];
    $count = $fasaQuery->fields['count'];
    $fasaStats[$fasaStatus] = $count;
    $fasaQuery->MoveNext();
}

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

/* //Status statistik
//echo '<br><h6>Cooperatives by Status:</h6>';
$statusLabels = array();
foreach ($statusList as $statusKey => $statusName) {
    $count = isset($statusStats[$statusKey]) ? $statusStats[$statusKey] : 0;
    $statusLabels[] = $statusName;
    //echo '<p>' . $statusName . ': ' . $count . '</p>';
} */

$statusLabels = array();
foreach ($statusStats as $statusStatus => $count) {
    if ($statusList[$statusStatus] === null) {
        $statusList[$statusStatus] = "Unknown";
    }
    $statusLabels[] = $statusList[$statusStatus];
}


//Kod statistik --gotta recheck the $count
//echo '<br><h6>Cooperatives by Kod:</h6>';
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


//Jenis koperasi statistik
//echo '<br><h6>Cooperatives by Jenis:</h6>';
$jenisLabels = array();
foreach ($jenisStats as $jenisStatus => $count) {
    if ($jenisList[$jenisStatus] === null) {
        $jenisList[$jenisStatus] = "Unknown";
    }
    $jenisLabels[] = $jenisList[$jenisStatus];
    //echo '<p>' . $jenisList[$jenisStatus] . ': ' . $count . '</p>';
}


//Training statistik
$trainingLabels = array();
//echo '<br><h6>Cooperatives by Training Status:</h6>';
foreach ($trainingStats as $trainingStatus => $count) {
    $trainingLabels[] = $trainingList[$trainingStatus];
    //echo '<p>' . $trainingList[$trainingStatus] . ': ' . $count . '</p>';
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
    //echo '<p>' . $jenisCodeName . ': ' . $count . '</p>';
} 

print'<h3 align="center">'.strtoupper($title).'</h3><hr>';
include("footer.php");
?>


<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body><br>
    <div class="title">Jumlah Koperasi: <?php echo $totalCooperatives; ?></div><br><br>
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
        margin: 1px;
        padding: 10px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(202, 202, 202, 0.7);
        border-radius: 50px;
    }

    .chart canvas {
        width: 100%;
        height: 100%;
    }
</style>
</head>

<body>
<div class="chart-container">
    <!-- left -->
    <div class="chart" style="width: calc(36% - 1px); text-align: center;"><br>
        <h4>Zon Koperasi</h4><br><br><br><br>
        <canvas id="zonPieChart" width="910" style="display: block; margin: 0px;"></canvas>
    </div>

    <!-- mid -->
    <div style="width: calc(25% - 1px);"> 
        <div class="chart" style="margin-bottom: 22px;"> 
            <h4 align="center">Jenis Koperasi</h4><br>
            <canvas id="jenisPieChart" width="290" height="65" style="display: block; margin: 15px;"></canvas>
        </div>
        <div class="chart"> 
            <h4 align="center">Status Koperasi</h4><br>
            <canvas id="statusBarChart" width="290" height="65" style="display: block; margin: 15px;"></canvas>
        </div>
    </div>
    <!-- right -->
    <div style="width: calc(35% - 1px);"> 
        <div class="chart" style="margin-bottom: 22px;"> 
            <h4 align="center">Latihan Koperasi</h4><br>
            <canvas id="trainingPieChart" height="350" style="display: block; margin: 30px;"></canvas>
        </div>
        <div class="chart"> 
            <h4 align="center">Fasa Latihan Koperasi</h4><br>
            <canvas id="fasaBarChart" height="348" style="display: block; margin: 30px;"></canvas>
        </div>
    </div>
    <div class="" style="width: calc(100% - 10px); margin-bottom: 29px;"></div>
    <!-- bottom -->
    <div class="chart" style="width: calc(100% - 700px); margin: 0 auto;">
        <h4 align="center">Kod Koperasi</h4><br>
        <canvas id="kodBarChart" height="450" style="display: block; margin: 30px;"></canvas>
    </div>
</div>


    <script>
        //zon
        var zonData = <?php echo json_encode(array_values($departmentStats)); ?>;
        var zonLabels = <?php echo json_encode($zonLabels); ?>;
        // var zonPieChartCanvas = document.getElementById("zonPieChart").getContext("2d");
        // var zonTotalElement = document.getElementById("zonTotal");

        //status
        var statusData = <?php echo json_encode(array_values($statusStats)); ?>;
        var statusLabels = <?php echo json_encode($statusLabels); ?>;

        //kod
        var kodData = <?php echo json_encode(array_values($jenisCodeStats)); ?>;
        var jenisCodeLabels = <?php echo json_encode($jenisCodeLabels); ?>;
        
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
        var statusBarChartCanvas = document.getElementById("statusBarChart").getContext("2d");
        var kodBarChartCanvas = document.getElementById("kodBarChart").getContext("2d");
        var jenisPieChartCanvas = document.getElementById("jenisPieChart").getContext("2d");
        var trainingPieChartCanvas = document.getElementById("trainingPieChart").getContext("2d");
        var fasaBarChartCanvas = document.getElementById("fasaBarChart").getContext("2d");

        Chart.register(ChartDataLabels);

        //stats 1
        var zonPieChart = new Chart(zonPieChartCanvas, {
            type: 'pie',
            data: {
                labels: zonLabels,
                datasets: [{
                    data: zonData,
                    backgroundColor: [
                        'rgba( 186, 186, 186, 0.7)',
                        'rgba( 117, 205, 81, 0.8)',
                        'rgba( 205, 81, 113, 0.7)',
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
                        position: 'bottom'
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

        //stats 3
        var statusBarChart = new Chart(statusBarChartCanvas, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: ['rgba( 54, 225, 23, 0.6)', 
                    'rgba(125, 199, 32, 0.6)', 
                    'rgba(222, 106, 186, 0.6)', 
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


        //stats 4
        var kodBarChart = new Chart(kodBarChartCanvas, {
            type: 'bar',
            data: {
                labels: jenisCodeLabels,
                datasets: [{
                    data: kodData,
                    backgroundColor: ['rgba(255, 199, 132, 0.8)',
                                'rgba(125, 199, 32, 0.8)',
                                'rgba(222, 16, 186, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(177, 152, 152, 0.8)',],
                    borderColor: 'rgba(0, 0, 0, 1)', // Change the border color
                    borderWidth: 1
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
            },
        });


        //stats 2
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


        //stats 4
        var trainingPieChart = new Chart(trainingPieChartCanvas, {
            type: 'bar',
            data: {
                labels: trainingLabels,
                datasets: [{
                    data: trainingData,
                    backgroundColor: ['rgba( 205, 31, 67 , 0.8)', 
                    'rgba( 31, 97, 205, 0.8)'],
                    borderColor: 'rgba(0, 0, 0, 1)', // Change the border color
                    borderWidth: 1
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
            },
        });

        //stats 5
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
                    borderWidth: 1
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
            },
        });
    </script>
    
</body>
</html>
