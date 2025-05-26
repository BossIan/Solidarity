<html lang="en">
<?php
include './database/checkAdmin.php';
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM responses");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalcount = $row['total'];
$stmt = $conn->prepare('SELECT * FROM categories');
$stmt->execute();
$result = $stmt->get_result();
$categories = $result->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare('SELECT question FROM questions WHERE status = "Active"');
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare('SELECT * FROM accounts');
$stmt->execute();
$result = $stmt->get_result();
$accounts = $result->fetch_all(MYSQLI_ASSOC);

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Header</title>
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="styles/reportgeneration.css">
    <link rel="stylesheet" href="styles/admin.css">
    <link rel="stylesheet" href="styles/adminheader.css">
    </script>
</head>

<body>
    <div class="dashboard">
        <?php
        include './components/adminSidebar.php'
            ?>
        <div class="mainTab">
            <?php
            include './components/adminHeader.php'
                ?>
            <div class="report-gen">
                <h1>Report Generation</h1>
                <div class="overflow">
                    <table class="response-table" id="responses-summarized">
                        <tr>
                            <th>Name</th>
                            <?php
                            $stmt = $conn->prepare("SELECT DISTINCT category FROM questions");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $category = $result->fetch_all(MYSQLI_ASSOC);
                            foreach ($category as $key => $value):
                                ?>
                                <th><?php echo $value['category'] ?></th>
                            <?php endforeach ?>
                        </tr>
                        <!-- <tr>
                            <td>Total</td>
                            <?php foreach ($category as $key => $value): ?>
                                <?php
                                $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM questions WHERE category = ?");
                                $stmt->bind_param("s", $value['category']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                $total = $row['total'];
                                ?>
                                <td><?php echo $total * 5 ?></td>
                            <?php endforeach ?>
                        </tr> -->
                        <?php foreach ($accounts as $account): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($account['name']); ?></td>
                                <?php
                                foreach ($category as $key => $value): ?>
                                    <?php
                                    $stmt = $conn->prepare("
                                    SELECT r.response_data, r.question_id
                                    FROM responses r
                                    JOIN questions q ON r.question_id = q.question_id
                                    WHERE r.account_id = ? AND q.status = 'Active' AND q.category = ?
                                ");
                                    $stmt->bind_param("is", $account['id'], $value['category']);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $responses = $result->fetch_all(MYSQLI_ASSOC);
                                    $ave = 0;
                                    foreach ($responses as $response) {
                                        $ave += $response['response_data'];
                                    }
                                    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM questions WHERE category = ?");
                                    $stmt->bind_param("s", $value['category']);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $row = $result->fetch_assoc();
                                    $total = $row['total'];
                                    ?>
                                    <td><?=  number_format($ave / $total, 1) ?></td>
                                <?php endforeach ?>
                            </tr>
                        <?php endforeach ?>
                    </table>
                    <table class="response-table" id="responses" style="display: none">
                        <tr>
                            <th>Name</th>
                            <?php foreach ($questions as $key => $value):
                                ?>
                                <th><?php echo $value['question'] ?></th>

                            <?php endforeach ?>
                        </tr>
                        <?php foreach ($accounts as $account): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($account['name']); ?></td>
                                <?php
                                $stmt = $conn->prepare("
                                    SELECT r.response_data, r.question_id
                                    FROM responses r
                                    JOIN questions q ON r.question_id = q.question_id
                                    WHERE r.account_id = ? AND q.status = 'Active'
                                ");
                                $stmt->bind_param("i", $account['id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $responses = $result->fetch_all(MYSQLI_ASSOC);
                                foreach ($responses as $response): ?>
                                    <td><?= $response['response_data'] ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <button onclick="download()">
                    Download Table
                </button>
                <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

                <script>
                    function download() {
                        TableToExcel.convert(document.getElementById("responses"), {
                            name: "Responses.xlsx",
                            sheet: {
                                name: "Responses"
                            }
                        });
                    }
                </script>
                <div class="graph">
                    <div class="graph-text">
                        <p>Generate Report</p>
                        <p id="duration">This week</p>

                    </div>
                    <p id="responses"><?php echo $totalcount ?> Reponses</p>
                    <canvas id="barChart"></canvas>
                </div>
                <div class="graph doughdiv">
                    <canvas id="doughnut"></canvas>
                    <div class="labels">
                        <?php
                        foreach ($categories as $category): ?>
                            <div class="label">
                                <p><?= $category['name'] ?></p>
                                <p class="totalcount"></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php

    $q1 = [];
    $q2 = [];
    $q3 = [];
    $q4 = [];
    foreach ($categories as $category) {
        $q1[] = getResponses($conn, 1, $category['id']);
        $q2[] = getResponses($conn, 2, $category['id']);
        $q3[] = getResponses($conn, 3, $category['id']);
        $q4[] = getResponses($conn, 4, $category['id']);
    }
    function getResponses($conn, $part, $id)
    {
        $validParts = [
            1 => "IN (1, 2)",
            2 => "IN (3, 4)",
            3 => "IN (5, 6)",
            4 => "= 7"
        ];

        if (!isset($validParts[$part])) {
            return [];
        }

        $sql = "SELECT * FROM responses 
                WHERE DAYOFWEEK(date_created) {$validParts[$part]} 
                AND YEARWEEK(date_created, 1) = YEARWEEK(CURDATE(), 1)";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $question_ids = array_column($result, 'question_id');
        $count = 0;
        foreach ($question_ids as $question_id) {
            $sql = "SELECT * FROM questions WHERE question_id = ? AND category_id = ? AND status = 'Active'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $question_id, $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            if ($result) {
                $count++;
            }
        }
        return $count;
    }
    ?>
    <script>
        const categories = <?php echo json_encode($categories); ?>;
        const categoryNames = categories.map(cat => cat.name);
        const categoryValues = [
            <?php echo json_encode($q1) ?>, <?php echo json_encode($q2) ?>, <?php echo json_encode($q3) ?>, <?php echo json_encode($q4) ?>
        ];

        const bardata = categoryNames.map((category, index) => ({
            label: category,
            backgroundColor: '#FFD396',
            data: categoryValues.map(quarter => quarter[index])
        }));
        var totalq = []
        let q1 = <?php echo json_encode($q1); ?>;
        let q2 = <?php echo json_encode($q2); ?>;
        let q3 = <?php echo json_encode($q3); ?>;
        let q4 = <?php echo json_encode($q4); ?>;
        for (let i = 0; i < categories.length; i++) {
            totalq[i] = q1[i] + q2[i] + q3[i] + q4[i];
        }

        const doughdata = [{
            backgroundColor: ['#B30C0C', '#5388D8', ' #F4BE37'],
            data: totalq
        }]

        new Chart("barChart", {
            type: "bar",
            data: {
                labels: ["Q1", "Q2", "Q3", "Q4"],
                datasets: bardata
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: "bottom"
                    }
                },
                scales: {
                    x: { stacked: false },
                    y: {
                        stacked: false, beginAtZero: true,

                        ticks: {
                            maxTicksLimit: 5
                        }
                    }
                }
            }
        });
        //labels doughnut
        document.querySelectorAll('.label p:first-child').forEach((el, index) => {
            el.style.setProperty('--marker-color', doughdata[0].backgroundColor[index]);
        });
        document.querySelectorAll('.label p:last-child').forEach((el, index) => {
            el.innerText = totalq[index].toLocaleString();
        });

        new Chart("doughnut", {
            type: 'doughnut',
            data: {
                labels: categoryNames,
                datasets: doughdata
            },
            options: {
                plugins: {
                    legend: {
                        display: false,
                    },
                }
            }
        });
    </script>
</body>

</html>