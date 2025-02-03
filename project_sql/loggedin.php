<?php
session_start();
include('connecting.php');

$diaryContent = "";

if (array_key_exists("id", $_SESSION)) {
    $query = "SELECT diary FROM users WHERE id = " . 
             mysqli_real_escape_string($conn, $_SESSION['id']) . " LIMIT 1";

    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);

    $diaryContent = $row['diary'];
} else {
    header("Location: secret_diary.php");
    exit();
}

include('header.php');
?>

<div class="container-fluid">
    <textarea id="diary" class="form-control">
        <?php echo $diaryContent; ?>
    </textarea>
</div>

<?php include('footer.php'); ?>
