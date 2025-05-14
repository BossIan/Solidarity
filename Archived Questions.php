<html lang="en">
<?php
include './database/checkAdmin.php';

$orderBy = "question_id";
$orderDir = "ASC";
$rotation = 0;
if (isset($_GET['sort'])) {
    if ($_GET['sort'] == "category") {
        $orderBy = "category";
    }
}

if (isset($_GET['order']) && $_GET['order'] == "descend") {
    $orderDir = "DESC";
    $rotation = 180;
}

$stmt = $conn->prepare('SELECT * FROM categories');
$stmt->execute();
$result = $stmt->get_result();

$categories = $result->fetch_all(MYSQLI_ASSOC);
$fromDate = $_GET['from_date'] ?? '';
$toDate = $_GET['to_date'] ?? '';
$tab = $_GET['tab'] ?? 'Archived'; //Show archived or active

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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solidarity</title>
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="styles/admin.css">
    <link rel="stylesheet" href="styles/adminheader.css">
    <link rel="stylesheet" href="styles/questionmng.css">
    <style>
        .categoryfilter {
            transform: rotate(<?php echo $rotation; ?>deg);
        }
    </style>
    <script>
        function updateToDate() {
            const fromDate = document.getElementById("from");
            const toDate = document.getElementById("to");

            if (fromDate && toDate) {
                const date = new Date(fromDate.value);
                date.setDate(date.getDate() + 1);
                toDate.min = date.toISOString().split("T")[0];
                updateURL()
            }
        }

        function updateFromDate() {
            const toDate = document.getElementById("to");
            const fromDate = document.getElementById("from");
            if (fromDate && toDate) {
                const date = new Date(toDate.value);
                date.setDate(date.getDate() - 1);
                fromDate.max = date.toISOString().split("T")[0];
                updateURL()
            }
        }
        function updateURL() {
            const fromDate = document.getElementById("from").value;
            const toDate = document.getElementById("to").value;
            if (fromDate && toDate) {
                const newUrl = `${window.location.pathname}?from_date=${fromDate}&to_date=${toDate}`;
                window.location.href = newUrl;
            }
        }
        function toggleAll() {
            const checkboxes = document.querySelectorAll("td input[type='checkbox']");
            if (document.querySelector('#tmp1').checked) {
                for (let checkbox of checkboxes) {
                    checkbox.checked = true;
                }
                return
            }
            for (let checkbox of checkboxes) {
                checkbox.checked = false;
            }
        }
        function toggle(checkbox) {
            const svg = document.querySelector('label[for=\'tmp1\'] svg:last-child');
            if (!checkbox.checked) {
                document.getElementById("tmp1").checked = false;
                svg.classList.add('displaynone')
            } else {
                const checkboxes = document.querySelectorAll("td input[type='checkbox']");
                var allchecked = true
                for (let checkbox of checkboxes) {
                    if (!checkbox.checked) {
                        allchecked = false
                    }
                }
            }
            if (allchecked) {
                document.getElementById("tmp1").checked = true;
                svg.classList.remove('displaynone')
            }
        }
        function changeCategoryDisplay(index) {
            for (let i = 0; i < 3; i++) {
                if (index == i) {
                    document.getElementsByClassName('selectNew')[i].classList.remove('displaynone')
                } else {
                    document.getElementsByClassName('selectNew')[i].classList.add('displaynone')
                }
            }
        }
        function addCategory() {
            changeCategoryDisplay(1)
        }
        function deleteCategory() {
            changeCategoryDisplay(0)
            setTimeout(() => {
                if (confirm("Are you sure you want to delete: " + document.getElementById("selected-category").innerText.trimStart().trimEnd() + "?")) {
                    deleteDatabaseCategory()
                } else {
                }
            }, 100);
        }
        function editCategory() {
            changeCategoryDisplay(2)
            document.getElementsByClassName('editInput')[0].value = document.getElementById("selected-category").innerText.trimStart().trimEnd()
        }
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
            <div class="questionmng">
                <div class="modal" id="modal">
                    <div class="modal-content">
                        <svg style="cursor: pointer" onclick="location.reload()" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.825 13L13.425 18.6L12 20L4 12L12 4L13.425 5.4L7.825 11H20V13H7.825Z"
                                fill="#1D1B20" />
                        </svg>
                        <div class="modal-text">
                            <p>Edit Question</p>
                            <div class="edit-question">
                                <input id="question-display" type="text" class="edit-question-input" disabled>
                                <svg onclick="changeQuestion()" style="cursor: pointer;" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5 19H6.425L16.2 9.225L14.775 7.8L5 17.575V19ZM3 21V16.75L16.2 3.575C16.4 3.39167 16.6208 3.25 16.8625 3.15C17.1042 3.05 17.3583 3 17.625 3C17.8917 3 18.15 3.05 18.4 3.15C18.65 3.25 18.8667 3.4 19.05 3.6L20.425 5C20.625 5.18333 20.7708 5.4 20.8625 5.65C20.9542 5.9 21 6.15 21 6.4C21 6.66667 20.9542 6.92083 20.8625 7.1625C20.7708 7.40417 20.625 7.625 20.425 7.825L7.25 21H3ZM15.475 8.525L14.775 7.8L16.2 9.225L15.475 8.525Z"
                                        fill="#852221" />
                                </svg>
                                <script>
                                    var focused = false
                                    function changeQuestion() {
                                        if (focused) {
                                            document.getElementsByClassName('edit-question-input')[0].disabled = true;
                                            focused = false
                                        } else {
                                            document.getElementsByClassName('edit-question-input')[0].disabled = false;
                                            document.getElementsByClassName('edit-question-input')[0].focus()
                                            focused = true
                                        }
                                    }
                                    document.getElementsByClassName('edit-question-input')[0].addEventListener("blur", () => {
                                        const id = document.getElementById("id-display").innerText.slice(4)
                                        const question = document.getElementsByClassName('edit-question-input')[0].value
                                        fetch("./database/question/editquestion.php", {
                                            method: "POST",
                                            headers: {
                                                "Content-Type": "application/json",
                                            },
                                            body: JSON.stringify({ question: question, id: id }),
                                        })
                                            .then(response => response.text())
                                            .then(data => {
                                                console.log(data);

                                            })
                                            .catch(error => console.error("Error:", error));
                                    });
                                </script>
                            </div>
                        </div>
                        <div class="modal-text">
                            <p>Change Category</p>
                            <h3></h3>
                            <div class="changeCateg" onclick="toggleDropdown2()">

                                <p style="color: black;     font-size: 1rem !important;" id="category-display">
                                </p>
                                <ul id="dropdown-menu2" class="hidden">
                                    <?php foreach ($categories as $category): ?>
                                        <li
                                            onclick="selectCategory2('<?php echo $category['name']; ?>','<?php echo $category['id']; ?>' )">
                                            <?php echo $category["name"]; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="select">
                                    <svg id="arrow" width="22" height="22" viewBox="0 0 22 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19.25 8.5L12.25 15.5L5.25 8.5" stroke="#000000" stroke-width="1.5"
                                            stroke-linecap="square"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="modal-text">
                            <p>ID</p>
                            <h3 id="id-display"></h3>
                        </div>
                        <div class="modal-text">
                            <p>Date Created</p>
                            <h3 id="date_created-display"></h3>
                        </div>
                        <div class="modal-text">
                            <p>Update Status</p>
                            <div class="status-modal ">
                                <svg onclick="changeStatus()" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.4 12L16 7.4L14.6 6L8.6 12L14.6 18L16 16.6L11.4 12Z" fill="#1D1B20" />
                                </svg>
                                <div id="status-text" class="">

                                </div>
                                <svg onclick="changeStatus()" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12.6 12L8 7.4L9.4 6L15.4 12L9.4 18L8 16.6L12.6 12Z" fill="#1D1B20" />
                                </svg>

                            </div>
                            <script>
                                function changeStatus() {
                                    const id = document.getElementById("id-display").innerText.slice(4)
                                    const status = document.getElementById('status-text')
                                    if (status.innerText == 'Active') {
                                        status.innerText = "Archived"
                                        status.classList.remove('activeStatus')
                                        archive(id)
                                    } else {
                                        status.innerText = "Active"
                                        status.classList.add('activeStatus')
                                        unarchive(id)
                                    }
                                }
                            </script>
                        </div>
                    </div>
                </div>
                <p style="padding-top:20px; font-weight:bold; font-size:25px; user-select: none;">Question Management
                </p>
                <p style="margin-top: 10px; font-family: Roboto, serif; font-size: 13px; user-select: none;">Category
                </p>
                <div class="category-div">
                    <div class="category">
                        <div class="selectNew" onclick="toggleDropdown()">
                            <p data-id="<?php echo $categories[0]["id"] ?>" id="selected-category">
                                <?php echo $categories[0]["name"] ?>
                            </p>
                            <ul id="dropdown-menu" class="hidden">
                                <?php foreach ($categories as $category): ?>
                                    <li
                                        onclick="selectCategory('<?php echo $category['name']; ?>','<?php echo $category['id']; ?>' )">
                                        <?php echo $category["name"]; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="select">
                                <svg id="arrow" width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.25 8.5L12.25 15.5L5.25 8.5" stroke="#000000" stroke-width="1.5"
                                        stroke-linecap="square"></path>
                                </svg>
                            </div>
                        </div>
                        <input class="selectNew addInput displaynone" type="text">
                        <input class="selectNew editInput displaynone" type="text">
                        <script>
                            document.getElementsByClassName("addInput")[0].addEventListener("keypress", function (event) {
                                if (event.key === "Enter") {
                                    event.preventDefault();
                                    if (confirm("Are you sure you want to add?")) {
                                        addDatabaseCategory()
                                    } else {
                                        changeCategoryDisplay(0)
                                    }
                                }
                            })
                            document.getElementsByClassName("addInput")[0].addEventListener("blur", function () {
                                changeCategoryDisplay(0)
                            });
                            document.getElementsByClassName("editInput")[0].addEventListener("keypress", function (event) {
                                if (event.key === "Enter") {
                                    event.preventDefault();
                                    if (confirm("Are you sure you want to edit?")) {
                                        editDatabaseCategory()
                                    } else {
                                        changeCategoryDisplay(0)
                                    }
                                }
                            })
                            document.getElementsByClassName("editInput")[0].addEventListener("blur", function () {
                                changeCategoryDisplay(0)
                            });
                        </script>
                    </div>
                    <div class="category-btns">
                        <div class="category-btn" onclick="addCategory()">Create New</div>
                        <div class="category-btn" onclick="deleteCategory()">Delete</div>
                        <div class="category-btn" onclick="editCategory()">Edit</div>
                    </div>
                </div>
                <div class="username">
                    <p style=" user-select: none;">Question</p>
                    <input style=" user-select: none;" type="text" id="add-question" placeholder="Enter Question" />
                </div>
                <div class="table-container">
                    <div class="filter-bar">
                        <div class="sortDiv sortIcon">
                            <svg width="22" height="24" viewBox="0 0 22 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
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
                            <label for="from">From</label>
                            <input id="from" type="date" value="<?= $fromDate ?>" onchange="updateToDate()" />
                        </div>
                        <div class="sortDiv">
                            <label for="to">To</label>
                            <input id="to" type="date" value="<?= $toDate ?>" onchange="updateFromDate()" />
                        </div>
                        <div>
                            <a class="categoryDiv sortDiv"
                                href="?sort=category&order=<?= ($orderDir == 'ASC') ? 'descend' : 'ascend' ?>">
                                <p>Category</p>
                                <svg class="categoryfilter" id="Arrow - Down 2" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19.25 8.5L12.25 15.5L5.25 8.5" stroke="#000000" strokeWidth="1.5"
                                        strokeLinecap="square">
                                    </path>
                                </svg>
                            </a>
                        </div>

                        <button>
                            <a class="sortDiv reset-filter" href="?">
                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.15427 3.75V0.75L5.38865 4.5L9.15427 8.25V5.25C11.6471 5.25 13.673 7.2675 13.673 9.75C13.673 12.2325 11.6471 14.25 9.15427 14.25C6.66143 14.25 4.63552 12.2325 4.63552 9.75H3.12927C3.12927 13.065 5.82546 15.75 9.15427 15.75C12.4831 15.75 15.1793 13.065 15.1793 9.75C15.1793 6.435 12.4831 3.75 9.15427 3.75Z"
                                        fill="#EA0234" />
                                </svg>
                                Reset Filter
                            </a></button>
                    </div>
                    <div class="table">
                        <table class="top-table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" onclick="toggleAll()" id="tmp1" onchange="
                                        const svg = document.querySelector('label[for=\'tmp1\'] svg:last-child');
                                        svg.classList.toggle('displaynone')
                                        ">
                                        <label for="tmp1">
                                            <svg width="22" height="23" viewBox="0 0 22 23" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M8 21.5269H14C19 21.5269 21 19.5269 21 14.5269V8.52686C21 3.52686 19 1.52686 14 1.52686H8C3 1.52686 1 3.52686 1 8.52686V14.5269C1 19.5269 3 21.5269 8 21.5269Z"
                                                    stroke="#ADA7A7" strokeWidth="1.5" strokeLinecap="round"
                                                    strokeLinejoin="round" />
                                            </svg>
                                            <svg id="Tick Square" class="displaynone" style="position: absolute;"
                                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g id="Iconly/Two-tone/Tick-Square" stroke="none" strokeWidth="1.5"
                                                    fill="none" fillRule="evenodd" strokeLinecap="round"
                                                    strokeLinejoin="round">
                                                    <g id="Tick-Square" transform="translate(2.000000, 2.000000)"
                                                        stroke="#000000" strokeWidth="1.5">
                                                        <polyline id="Stroke-3" opacity="0.400000006"
                                                            points="6.4399 10.0002 8.8139 12.3732 13.5599 7.6272">
                                                        </polyline>
                                                    </g>
                                                </g>
                                            </svg>
                                        </label>
                                    </th>
                                    <th>ID</th>
                                    <th>Date Created</th>
                                    <th>Question</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        <div class="table-div">
                        <table style="width: calc(100% - 5px);">
                            <tbody>
                                <?php foreach ($questions as $row): ?>
                                    <tr>
                                        <td><input id="<?= $row['question_id'] ?>" type="checkbox" onchange="toggle(this)">
                                            <label for="<?= $row['question_id'] ?>">
                                                <svg width="22" height="23" viewBox="0 0 22 23" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8 21.5269H14C19 21.5269 21 19.5269 21 14.5269V8.52686C21 3.52686 19 1.52686 14 1.52686H8C3 1.52686 1 3.52686 1 8.52686V14.5269C1 19.5269 3 21.5269 8 21.5269Z"
                                                        stroke="#ADA7A7" strokeWidth="1.5" strokeLinecap="round"
                                                        strokeLinejoin="round" />
                                                </svg>
                                                <svg id="Tick Square" class="displaynone" width="24px" height="24px"
                                                    viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                                    <g id="Iconly/Two-tone/Tick-Square" stroke="none" strokeWidth="1.5"
                                                        fill="none" fillRule="evenodd" strokeLinecap="round"
                                                        strokeLinejoin="round">
                                                        <g id="Tick-Square" transform="translate(2.000000, 2.000000)"
                                                            stroke="#000000" strokeWidth="1.5">
                                                            <polyline id="Stroke-3" opacity="0.400000006"
                                                                points="6.4399 10.0002 8.8139 12.3732 13.5599 7.6272">
                                                            </polyline>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </label>
                                        </td>
                                        <td>#<?php echo sprintf("%03d%03d", $row['category_id'], $row['question_id']); ?>
                                        </td>
                                        <td><?= $row['date_created'] ?></td>
                                        <td><?= $row['question'] ?></td>
                                        <td><?= $row['category'] ?></td>
                                        <td>
                                            <div class="status"><?= $row['status'] ?></div>
                                        </td>
                                        <td
                                            onclick="openModal('#<?php echo sprintf('%03d%03d', $row['category_id'], $row['question_id']); ?>', '<?php echo $row['question'] ?>', '<?= $row['category'] ?>','<?= $row['date_created'] ?>', '<?= $row['status'] ?>')">
                                            <svg width="28" height="18" viewBox="0 0 28 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M14.0146 10.5C14.8136 10.5 15.5798 10.1839 16.1448 9.62132C16.7097 9.05871 17.0271 8.29565 17.0271 7.5C17.0271 6.70435 16.7097 5.94129 16.1448 5.37868C15.5798 4.81607 14.8136 4.5 14.0146 4.5C13.9676 4.5 13.9261 4.51125 13.8805 4.51359C14.0256 4.91171 14.0538 5.3428 13.9616 5.75628C13.8693 6.16976 13.6606 6.54847 13.3599 6.84797C13.0591 7.14746 12.6788 7.35532 12.2636 7.44715C11.8484 7.53898 11.4155 7.51097 11.0158 7.36641C11.0158 7.41328 11.0021 7.45453 11.0021 7.5C11.0021 7.89397 11.08 8.28407 11.2314 8.64805C11.3828 9.01203 11.6047 9.34274 11.8845 9.62132C12.4494 10.1839 13.2157 10.5 14.0146 10.5ZM27.4071 8.31562C24.8545 3.35578 19.801 0 14.0146 0C8.22826 0 3.17337 3.35813 0.622153 8.31609C0.514476 8.52821 0.458374 8.76256 0.458374 9.00023C0.458374 9.23791 0.514476 9.47226 0.622153 9.68438C3.17478 14.6442 8.22826 18 14.0146 18C19.801 18 24.8559 14.6419 27.4071 9.68391C27.5148 9.47179 27.5709 9.23744 27.5709 8.99977C27.5709 8.76209 27.5148 8.52774 27.4071 8.31562ZM14.0146 1.5C15.2063 1.5 16.3711 1.85189 17.3619 2.51118C18.3527 3.17047 19.125 4.10754 19.581 5.2039C20.037 6.30026 20.1563 7.50666 19.9239 8.67054C19.6914 9.83443 19.1176 10.9035 18.275 11.7426C17.4323 12.5818 16.3588 13.1532 15.19 13.3847C14.0213 13.6162 12.8099 13.4974 11.709 13.0433C10.608 12.5892 9.66705 11.8201 9.00501 10.8334C8.34297 9.84673 7.98961 8.68669 7.98961 7.5C7.99136 5.90923 8.62669 4.38412 9.75622 3.25928C10.8858 2.13444 12.4172 1.50174 14.0146 1.5ZM14.0146 16.5C8.96115 16.5 4.34354 13.6261 1.9646 9C3.30254 6.38433 5.4422 4.26095 8.0734 2.93766C7.09104 4.20422 6.48336 5.77359 6.48336 7.5C6.48336 9.48912 7.27683 11.3968 8.68922 12.8033C10.1016 14.2098 12.0172 15 14.0146 15C16.012 15 17.9276 14.2098 19.34 12.8033C20.7524 11.3968 21.5459 9.48912 21.5459 7.5C21.5459 5.77359 20.9382 4.20422 19.9559 2.93766C22.5871 4.26095 24.7267 6.38433 26.0646 9C23.6862 13.6261 19.0681 16.5 14.0146 16.5Z"
                                                    fill="#B30C0C" />
                                            </svg>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <div class="buttons">
                    <div onclick="add()" class="bottom-button">Add</div>
                    <div onclick="unarchive()" class="bottom-button">Unarchive</div>
                    <div class="bottom-button">pdf</div>
                </div>
            </div>

        </div>
    </div>
    </div>
    <script>
        function toggleDropdown() {
            document.getElementById("dropdown-menu").classList.toggle("hidden");
        }
        function toggleDropdown2() {
            document.getElementById("dropdown-menu2").classList.toggle("hidden");
        }
        function selectCategory(category, id) {
            document.getElementById("selected-category").innerText = category;
            document.getElementById("selected-category").setAttribute('data-id', id)
            toggleDropdown();
            toggleDropdown();

        }
        function selectCategory2(category, id) {
            document.getElementById("category-display").innerText = category;
            const question_id = document.getElementById("id-display").innerText.slice(4)
            console.log(id);
            fetch("./database/question/editcategory.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ category: category, id: id, question_id: question_id }),
            })
                .then(response => response.text())
                .then(data => {
                    console.log(data);

                })
                .catch(error => console.error("Error:", error));
            toggleDropdown();
            toggleDropdown();

        }
        const modal = document.getElementById("modal");
        function openModal(id, question, category, date_created, status) {
            document.getElementById('question-display').value = question
            console.log(question);

            document.getElementById('id-display').innerText = id
            document.getElementById('category-display').innerText = category
            document.getElementById('date_created-display').innerText = date_created
            document.getElementById('status-text').innerText = status
            modal.classList.add("show");
        }
        modal.addEventListener("click", function (event) {
            if (event.target === modal) {
                location.reload()

            }
        });
        document.addEventListener("click", function (event) {
            const dropdown = document.querySelector(".selectNew");
            if (!dropdown.contains(event.target)) {
                document.getElementById("dropdown-menu").classList.add("hidden");
            }
        });
        function togglePopover() {
            var popover = document.getElementById('popover');
            if (popover.style.display === 'none' || popover.style.display === '') {
                popover.style.display = 'block';
            } else {
                popover.style.display = 'none';
            }
        }
        function logout() {
            window.location.href = '/';
        }
        function add() {
            const category = document.getElementById("selected-category").innerText.trim();
            const id = document.getElementById("selected-category").getAttribute("data-id")

            const question = document.getElementById("add-question").value;
            if (!question) {
                alert("Please enter a question.");
                return;
            }
            fetch("./database/add.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ category: category, question: question, id: id }),
            })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
        }
        function addDatabaseCategory() {
            const category = document.getElementsByClassName("addInput")[0].value.trim()
            if (category == "") {
                alert("Please enter a category.");
                return;
            }
            fetch("./database/addCategory.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ category: category }),
            })
                .then(response => response.text())
                .then(data => {
                    alert('Category Successfully added!');
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
        }
        function deleteDatabaseCategory() {
            const category = document.getElementById("selected-category").innerHTML.trimStart();
            fetch("./database/deleteCategory.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ category: category }),
            })
                .then(response => response.text())
                .then(data => {
                    alert('Category Successfully deleted!');
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
        }
        function editDatabaseCategory() {
            const category = document.getElementsByClassName("editInput")[0].value.trim()
            const id = document.getElementById("selected-category").getAttribute("data-id")
            if (category == "") {
                alert("Please enter a category.");
                return;
            }
            fetch("./database/editCategory.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ category: category, id: id }),
            })
                .then(response => response.text())
                .then(data => {
                    alert('Category Successfully edited!');
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
        }
        function archive(id) {
            var selectedIds = [];
            if (!id) {
                const checkboxes = document.querySelectorAll("td input[type='checkbox']:checked");

                checkboxes.forEach((checkbox) => {
                    selectedIds.push(checkbox.id);
                });
            } else {
                selectedIds = [id];
            }


            if (selectedIds.length === 0) {
                alert("No checkboxes selected!");
                return;
            }

            fetch("./database/archive.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ ids: selectedIds }),
            })
                .then(response => response.text())
                .then(data => {
                    if (id) return
                    alert(data);
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
        }
        function unarchive(id) {
            var selectedIds = [];
            if (!id) {
                const checkboxes = document.querySelectorAll("td input[type='checkbox']:checked");

                checkboxes.forEach((checkbox) => {
                    selectedIds.push(checkbox.id);
                });
            } else {
                selectedIds = [id];
            }


            if (selectedIds.length === 0) {
                alert("No checkboxes selected!");
                return;
            }

            fetch("./database/unarchive.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ ids: selectedIds }),
            })
                .then(response => response.text())
                .then(data => {
                    if (id) return
                    alert(data);
                    location.reload();
                })
                .catch(error => console.error("Error:", error));
        }
    </script>
</body>

</html>