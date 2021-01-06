<?php
    require_once ('connect.php');

        $item_id = $_GET['id'];
        //$alert = "Item $item_id Deleted Successfully!";
        $sql = "DELETE FROM item_record WHERE id = ?";
        $prepare = $conn->prepare($sql);
        
        if ($prepare->execute([$item_id])) {
            echo '<script>alert("Item '.$item_id.' Deleted Successfully!"); window.location.href="index.php";</script>';
        }
?>