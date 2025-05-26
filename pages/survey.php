<?php
$stmt = $conn->prepare('SELECT * FROM categories');
$stmt->execute();
$result = $stmt->get_result();

$categories = $result->fetch_all(MYSQLI_ASSOC);
$categoryNames = array_column($categories, 'name');
foreach ($categoryNames as $key => $value) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS question_count FROM questions WHERE category = ? AND status = 'Active'");
    $stmt->bind_param("s",  $value);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row['question_count'] == 0) {
        unset($categoryNames[$key]);
    }
}
$categoryNames = array_values($categoryNames);

$category = $_GET['category'] ?? $categoryNames[0];
$index = array_search($category, $categoryNames);
if ($index !== false && $index < count($categoryNames) - 1) {
    $nextIndex = $index + 1;
    $prevIndex = $index - 1;
    $nextCategory = $categories[$nextIndex]['name'];
    if ($prevIndex >= 0) {
    $prevCategory = $categories[$prevIndex]['name'];
    } else {
    $prevCategory = $categories[$index]['name'];
}
    $nextLink = "?page=Survey&category=$nextCategory";
    $prevLink = "?page=Survey&category=$prevCategory";
} else {
    $nextLink = "?page=Survey";
    $prevLink = "?page=Survey";
}
$sql = "SELECT * FROM questions WHERE status = 'Active'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$questions = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey</title>
    <link rel="stylesheet" href="styles/survey.css">

</head>

<body>

    <div class="survey main">
        <h2>Solidarity</h2>
        <p>Instructions: Thank you for participating in this survey on solidarity. Your responses will help us better
            understand the level of cohesion, support, and collective action within your community. Please read the
            instructions carefully before you begin.</p>

        <?php if (!empty($questions)) { ?>
            <h1 class="surveyh1"><?= htmlspecialchars($category) ?></h1>
            <form id="form" action="./database/respond.php" method="POST">
            <?php
                if ($index >= count($categoryNames) -1): ?>
                    <input type="hidden" name="is_last_category" value="true">
                    <?php else: ?>
                        <input type="hidden" name="is_last_category" value="false">
                <?php endif; ?>
            <input type="hidden" name="nextLink" value="<?php echo $nextLink; ?>">
            <input type="hidden" name="prevLink" value="<?php echo $prevLink; ?>">
                <div class="questions">
                    <?php foreach ($questions as $row):
                        $stmt = $conn->prepare("SELECT * FROM responses WHERE account_id = ? AND question_id = ?");
                        $stmt->bind_param("ii", $_SESSION['user_id'], $row['question_id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $rowres = $result->fetch_assoc();
                        $rows = $result->num_rows;
                        if ($rows > 0) {
                            $answered = true;
                            if ($category == $row['category']) {
                                ?>
                                <script>
                                    document.getElementById('form').addEventListener('submit', function (e) {
                                        e.preventDefault();
                                        window.location.href = './' + '<?= $nextLink ?>'
                                    })
                                </script>
                                <div class="question">
                                    <p><?= htmlspecialchars($row['question']) ?></p>
                                    <div class="choices">
                                        <?php
                                        $choices = ["Strongly Disagree", "Disagree", "Neutral", "Agree", "Strongly Agree"];
                                        foreach ($choices as $key => $label): ?>
                                            <label>
                                                <input type="radio" name="choice[<?= $row['question_id'] ?>]" value="<?= $key + 1 ?>" required disabled <?= ($rowres['response_data'] == $key + 1) ? 'checked' : ''  ?>>
                                                <?= $label ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php }
                        } else {
                            $answered = false;
                            if ($category == $row['category']) {
                                ?>
                                <div class="question">
                                    <p><?= htmlspecialchars($row['question']) ?></p>
                                    <div class="choices">
                                        <?php
                                        $choices = ["Strongly Disagree", "Disagree", "Neutral", "Agree", "Strongly Agree"];
                                        foreach ($choices as $key => $label):
                                            ?>
                                            <label>
                                                <input type="radio" name="choice[<?= $row['question_id'] ?>]"
                                                    value="<?= $key + 1 ?>" required>
                                                <?= $label ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php }
                        }
                    endforeach; 
                    ?>
                </div>
                <div class="bottom-btn">
               
                <?php
                if ($index >= count($categoryNames) -1): ?>
                    <?php
                    if ($answered): ?>
                       <button type="submit" formnovalidate name="action" value="prev" class="next">
                                Previous
                        </button>
                    <?php else : ?>

                    <button type="submit" formnovalidate name="action" value="prev" class="next">
                            Previous
                    </button>
                    <button type="submit"  id="sub" class="next"  onclick="return confirm('Are you sure you want to submit?');">
                        Submit
                    </button>
                <?php endif; ?>

                <?php elseif ($index > 0) : ?>
                    <button type="submit" name="action" value="prev" class="next">
                            Previous
                    </button>
                    <button type="submit" name="action" value="next" class="next">
                            Next
                    </button>
                <?php else : ?>
                    <button type="submit" name="action" value="next" class="next">
                            Next
                    </button>
                <?php endif; ?>
                </div>
            </form>
            <script>
                function changeNextLink() {
                    document.getElementsByName('nextLink').value = './index.php'
                }
                
            </script>

        <?php } else { ?>
            <h1 class="w">No questions...</h1>
        <?php } ?>
    </div>

</body>

</html>