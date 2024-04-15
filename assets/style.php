<?php
    include '../db.php';
    $query = "SELECT * FROM quiz_design";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)){
        $text_align = $row['text_align'];
        $text_color = $row['text_color'];
    }
?>
<style>
    h1{
        text-align: <?php echo $text_align; ?>;
        color: <?php echo $text_color; ?>
    }
</style>