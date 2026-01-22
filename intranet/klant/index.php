<?php
    include('../../partials/header.php');
    include('../../partials/nav-bar.php');
// Check if user is logged in
// Code for checking if the session is not logged in
    if (!in_array(29, $_SESSION["permissions"])) {
        header('Location: /404.php');
        die();
    }
    $connection = SQLconnect();
// Main content
?>
<main class="container-fluid">
    <article class="main justify-content-center">
        <h1 class="text-center">Hello, <?= $_SESSION["userName"]?></h1>
        <div class="container">
            <h2>Type Toggle</h2>
            <div class="btn-group" role="group" aria-label="Type Toggle">
                <form method="post">
                    <button type="submit" class="btn btn-primary" name="type" value="Elektriciteit">Elektriciteit</button>
                    <button type="submit" class="btn btn-primary" name="type" value="Gas">Gas</button>
                </form>
            </div>
            <br/>
            <div class="btn-group mb-3" role="group">
                <button type="button" class="btn btn-primary" id="tableButton">Table</button>
                <button type="button" class="btn btn-primary" id="graphButton">Standard Graph</button>
                <button type="button" class="btn btn-primary" id="compareButton">Compare Graph</button>
            </div>
            <?php

            $type = 'Elektriciteit';


            if (isset($_POST['type'])) {
                $type = $_POST['type'];
            }

            echo "<p>Selected Type: $type</p>";


            if ($type === 'Elektriciteit') {
                echo "<p>Laad content gerelateerd aan Electriciteit</p>";
            } elseif ($type === 'Gas') {
                echo "<p>Laad content gerelateerd aan Gas</p>";
            }
            ?>
        </div>
        <!-- Meter readings section -->
        <div class="container">
            <h2 class="text-center">Meterstanden</h2>
            <div class="table-responsive">
                <table id="meterTable" class="table table-bordered"> <!-- Added ID to table -->
                    <thead>
                    <tr>
                        <th>Meterstand</th>
                        <th>Datum</th>
                        <th>Tijd</th>
                        <th>Meter type</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $k_idKlant = $_SERVER['AUTHENTICATE_UID'];
                    $searchResult = getuserinfo($k_idKlant);
                    if (!$searchResult) {
                        die("Fout bij zoeken naar gebruikers");
                    }
                    // Controleren en weergeven van alle uid-waarden
                    $aid = $searchResult[4];
                    echo 'kut';
                    var_dump($aid);
                    echo 'kut?';
                    //$type = 'Elektriciteit';
                    //$type = 'Gas';
                    $searchResult = getmetertelwerkid($aid, $type);
                    $metertelwerkid = $searchResult[0];
                    $searchResult = getmeterstanden($metertelwerkid);

                    for ($i = 0; $i < count($searchResult); $i++) {
                        ?>
                        <tr>
                            <td><?php echo $searchResult[$i][2]?></td>
                            <td><?php echo $searchResult[$i][3]?></td>
                            <td><?php echo $searchResult[$i][4]?></td>
                            <td><?php echo $type?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="container">
            <canvas id="myChart" width="400" height="200"></canvas>
            <div class="mt-4">
                <h2 class="text-center">Compare Dates</h2>
                <div class="form-group">
                    <label for="startDate">Start Date:</label>
                    <input type="date" class="form-control" id="startDate">
                </div>
                <div class="form-group">
                    <label for="endDate">End Date:</label>
                    <input type="date" class="form-control" id="endDate">
                </div>
                <div class="mt-4">
                    <canvas id="comparisonChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </article>
</main>
<?php include_once '../../partials/footer.php'; // Include footer ?>
<script src="../../node_modules/chart.js/dist/chart.umd.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var table = document.getElementById('meterTable');
        var meterstandData = [];
        var datumData = [];
        var tijdData = [];

        for (var i = 1; i < table.rows.length; i++) {
            meterstandData.push(parseInt(table.rows[i].cells[0].innerHTML));
            datumData.push(table.rows[i].cells[1].innerHTML);
            tijdData.push(table.rows[i].cells[2].innerHTML);
        }

        var standardGraphContainer = document.getElementById('myChart');
        var compareGraphContainer = document.getElementById('comparisonChart');
        var tableContainer = document.getElementById('meterTable').parentElement;

        var standardGraphButton = document.getElementById('graphButton');
        var compareGraphButton = document.getElementById('compareButton');
        var tableButton = document.getElementById('tableButton');

        // Function to toggle display of elements
        function toggleDisplay(elementToShow, elementToHide1, elementToHide2) {
            elementToShow.style.display = 'block';
            elementToHide1.style.display = 'none';
            elementToHide2.style.display = 'none';
        }

        // Event listeners for buttons
        standardGraphButton.addEventListener('click', function() {
            toggleDisplay(standardGraphContainer, compareGraphContainer, tableContainer);
        });

        compareGraphButton.addEventListener('click', function() {
            toggleDisplay(compareGraphContainer, standardGraphContainer, tableContainer);
        });

        tableButton.addEventListener('click', function() {
            toggleDisplay(tableContainer, standardGraphContainer, compareGraphContainer);
        });

        // Chart.js
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart;
        myChart = new Chart(ctx,
            {
                type: 'line',
                data: {
                    labels: datumData, //x-axis
                    datasets: [{
                        label: 'Meterstand',
                        data: meterstandData, // y-axis
                        backgroundColor: 'rgba(35, 99, 132, 0.2)',
                        borderColor: 'rgba(2, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var table = document.getElementById('meterTable');

        // Function to extract data from the table
        function extractData() {
            var meterstandData = [];
            var datumData = [];

            for (var i = 1; i < table.rows.length; i++) {
                meterstandData.push(parseInt(table.rows[i].cells[0].innerHTML));
                datumData.push(table.rows[i].cells[1].innerHTML);
            }

            return { meterstandData, datumData };
        }

        // Function to compare selected dates
        function compareDates(startDate, endDate) {
            var { meterstandData, datumData } = extractData();
            var selectedMeterstandData = [];
            var selectedDatumData = [];

            for (var i = 0; i < datumData.length; i++) {
                var currentDate = new Date(datumData[i]);
                if (currentDate >= startDate && currentDate <= endDate) {
                    selectedMeterstandData.push(meterstandData[i]);
                    selectedDatumData.push(datumData[i]);
                }
            }

            return { selectedMeterstandData, selectedDatumData };
        }

        // Event listener for compare button click
        document.getElementById('compareButton').addEventListener('click', function() {
            var startDate = new Date(document.getElementById('startDate').value);
            var endDate = new Date(document.getElementById('endDate').value);

            if (startDate && endDate && startDate <= endDate) {
                var { selectedMeterstandData, selectedDatumData } = compareDates(startDate, endDate);

                // Chart.js
                var ctx = document.getElementById('comparisonChart').getContext('2d');
                var comparisonChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: selectedDatumData,
                        datasets: [{
                            label: 'Meterstand',
                            data: selectedMeterstandData,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            } else {
                alert('Please select valid start and end dates.');
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Elements to toggle
        var standardGraphContainer = document.getElementById('myChart');
        var compareGraphContainer = document.getElementById('comparisonChart');
        var tableContainer = document.getElementById('meterTable').parentElement;

        // Toggle buttons
        //var toggleButtons = document.querySelectorAll('.toggle-btn');

        // Function to toggle display of elements
        function toggleDisplay(element) {
            if (element.style.display === 'none' || element.style.display === '') {
                element.style.display = 'block';
            } else {
                element.style.display = 'none';
            }
        }

        // Event listener for toggle buttons
        toggleButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var targetId = button.getAttribute('data-target');
                var targetElement = document.getElementById(targetId);
                toggleDisplay(targetElement);
            });
        });
    });
</script>
<?php include_once '../../partials/footer.php'; // Include footer ?>
