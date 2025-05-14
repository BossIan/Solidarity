<?php
include 'db.php';
$orderBy = "question_id";
$orderDir = "ASC";

if (isset($_GET['sort'])) {
    if ($_GET['sort'] == "status") {
        $orderBy = "status";
    } elseif ($_GET['sort'] == "category") {
        $orderBy = "category";
    }
}

if (isset($_GET['order']) && $_GET['order'] == "descend") {
    $orderDir = "DESC";
}

$fromDate = $_GET['from_date'] ?? '';
$toDate = $_GET['to_date'] ?? '';
$tab = $_GET['tab'] ?? 'Active'; //Show archived or active

$sql = "SELECT * FROM questions WHERE status = ? ";
$params = [$tab];

if (!empty($fromDate) && !empty($toDate)) {
    $sql .= "AND date_created BETWEEN ? AND ? ";
    array_push($params, $fromDate, $toDate);
}

$sql .= "ORDER BY $orderBy $orderDir";

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat("s", count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();

$questions = $result->fetch_all(MYSQLI_ASSOC);
?>
<div className="table-container">
    <div class="filter-bar">
        <div class="sortDiv">
            <svg width="22" height="24" viewBox="0 0 22 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fillRule="evenodd" clipRule="evenodd"
                    d="M11.2959 9.75C16.7031 9.75 21.0865 7.73528 21.0865 5.25C21.0865 2.76472 16.7031 0.75 11.2959 0.75C5.88866 0.75 1.50525 2.76472 1.50525 5.25C1.50525 7.73528 5.88866 9.75 11.2959 9.75Z"
                    stroke="black" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                <path
                    d="M1.50525 5.25C1.50779 9.76548 4.62433 13.688 9.0365 14.729V21C9.0365 22.2426 10.0481 23.25 11.2959 23.25C12.5437 23.25 13.5552 22.2426 13.5552 21V14.729C17.9674 13.688 21.084 9.76548 21.0865 5.25"
                    stroke="black" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
            </svg>

        </div>
        <div class="sortDiv">Filter By</div>
        <div class="sortDiv">
            <label htmlFor="from">From</label>
            <input id="from" type="date" onchange=""/>
        </div>
        <div class="sortDiv">
            <label htmlFor="to">To</label>
            <input id="to" type="date" onchange="" />
        </div>
        <div class="categoryDiv sortDiv">
            <a href="?sort=category&order=<?= ($orderDir == 'ASC') ? 'descend' : 'ascend' ?>">
                <p>Category</p>
                <svg id="Arrow - Down 2" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.25 8.5L12.25 15.5L5.25 8.5" stroke="#000000" strokeWidth="1.5" strokeLinecap="square">
                    </path>
                </svg>
            </a>
        </div>

        <button class="reset-filter sortDiv" onClick={resetFilter}>
            <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M9.15427 3.75V0.75L5.38865 4.5L9.15427 8.25V5.25C11.6471 5.25 13.673 7.2675 13.673 9.75C13.673 12.2325 11.6471 14.25 9.15427 14.25C6.66143 14.25 4.63552 12.2325 4.63552 9.75H3.12927C3.12927 13.065 5.82546 15.75 9.15427 15.75C12.4831 15.75 15.1793 13.065 15.1793 9.75C15.1793 6.435 12.4831 3.75 9.15427 3.75Z"
                    fill="#EA0234" />
            </svg>
            Reset Filter</button>
    </div>
    <table>
        <thead>
            <tr>
                <th><input type="checkbox" onclick="toggleAll(this)"></th>
                <th>ID</th>
                <th>Date Created</th>
                <th>Question</th>
                <th>Category</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($questions as $row): ?>
                <tr>
                    <td><input type="checkbox" name="selected[]" value="<?= $row['question_id'] ?>"></td>
                    <td>#<?= $row['question_id'] ?></td>
                    <td><?= $row['date_created'] ?></td>
                    <td><?= $row['question'] ?></td>
                    <td><?= $row['category'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><button onclick="archiveQuestion(<?= $row['question_id'] ?>)">Archive</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>