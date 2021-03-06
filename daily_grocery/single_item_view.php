<?php
    require_once ('connect.php');

    $item_id = $_GET['id'];
    $sql = "SELECT * FROM item_record WHERE id = ?";
    $prepare = $conn->prepare($sql);
    $prepare->execute([$item_id]);
    $row = $prepare->fetch(PDO::FETCH_ASSOC);
    //print_r($result);

    if (isset($_POST['single_item_update'])) {
        $item_id = $_POST['item_id'];
        $item_name = $_POST['item_name'];
        $item_remark = $_POST['item_remark'];
        $item_price = $_POST['item_price'];

        $sql = "UPDATE item_record SET item_name = ?, item_remark = ?, item_price = ? WHERE id = ? ";
        $prepare = $conn->prepare($sql);
        $prepare->execute([$item_name, $item_remark, $item_price, $item_id]);
        if (!$prepare->execute()) {
            echo '<script>alert("Something Wrong!");</script>';
        } else {
            echo '<script>alert("Successfully Updated!"); window.location.href="index.php";</script>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body style="background-color:lightblue;">
    <div class="container mt-3 mb-3" style="background-color:white; border: 2px dashed green;">
        <a href="index.php" class="btn btn-primary mb-3 mt-2">Back to Home</a>
        <div class="single_item_update_form">
            <form action="single_item_view.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" class="form-control border-success" name="item_id" value="<?php echo $item_id; ?>">
                <label for="item_name">Item Name</label>
                <input type="text" class="form-control border-success" name="item_name" id="item_name" value="<?php echo $row['item_name']; ?>" required>
                <label for="item_name">Item Remark</label>
                <input type="text" class="form-control border-success" name="item_remark" id="item_remark" value="<?php echo $row['item_remark']; ?>" required>
                <label for="item_price">Item Price</label>
                <input type="number" class="form-control border-success" min="0" step="any" name="item_price" id="item_price" value="<?php echo $row['item_price']; ?>" required>
                <div class="d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-success form-control mb-2" data-dismiss="modal" name="single_item_update" id="single_item_update">Save</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>