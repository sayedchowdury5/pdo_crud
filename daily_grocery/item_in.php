<?php
    require_once ('connect.php');

    if (isset($_POST['submit'])) {
        if (!empty($_POST['item_name'])) {
            $record_date = $_POST['datein'];
            $item_name = $_POST['item_name'];
            $item_remark = $_POST['item_remark'];
            $item_price = $_POST['item_price'];

            foreach ($item_name as $key => $value) {
                $sql = "INSERT INTO item_record (item_name, item_remark, item_price, record_date) VALUES (?, ?, ?, ?)";
                $prepare = $conn->prepare($sql);
                $prepare->execute([$value, $item_remark[$key], $item_price[$key], $record_date]);

                if (!$prepare) {
                    echo '<script>alert("Something Wrong!")</script>';
                } else {
                    echo '<script>alert("Successfully added item(s)!"); window.location.href="index.php";</script>';
                }
            }
        } else {
            echo '<script>alert("Please Add Item");</script>';
        }
    }


    if (isset($_POST['submit_food'])) {
        if (!empty($_POST['item_name'])) {
            $record_date = $_POST['datein'];
            $item_name = $_POST['item_name'];
            $item_remark = $_POST['item_remark'];

            foreach ($item_name as $key => $value) {
                $sql = "INSERT INTO food_supply (item_name, item_remark, record_date) VALUES (?, ?, ?)";
                $prepare = $conn->prepare($sql);
                $prepare->execute([$value, $item_remark[$key], $record_date]);

                if (!$prepare) {
                    echo '<script>alert("Something Wrong!")</script>';
                } else {
                    echo '<script>alert("Successfully added item(s)!"); window.location.href="view_food_supply.php";</script>';
                }
            }
        } else {
            echo '<script>alert("Please Add Item");</script>';
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
        <a href="index.php" class="btn btn-primary mt-2">Back to Home</a>
        <a href="view_food_supply.php" class="btn btn-primary mt-2">View Food Supply</a>
        <div>
            <h3 class="text-center">Add Record For Your Shopping</h3>
            <form id="itemform" action="item_in.php" method="POST" enctype="multipart/form-data">
                <label for="datein">Select Date</label>
                <input type="date" class="form-control" name="datein" required>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-info mt-3" id="additem">Add Item</button>
                    <button type="button" class="btn btn-danger mt-3 ml-2" id="deleteitem">Delete</button>
                </div>
                <div id="add_more">
                    <div class="row mt-3 mb-2">
                        <div class="col-12 col-sm-12 col-md col-lg mt-1">
                            <input type="text" class="form-control" name="item_name[]" placeholder="Item Name" required>
                        </div>
                        <div class="col-12 col-sm-12 col-md col-lg mt-1">
                            <input type="number" min="0" step="any" class="form-control" name="item_price[]" placeholder="Price" required>
                        </div>
                        <div class="col-12 col-sm-12 col-md col-lg mt-1">
                            <input type="text" class="form-control" name="item_remark[]" placeholder="Remark (i.e. weight, shop name)" required>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <input type="submit" class="form-control btn btn-success mb-2" name="submit">
                    </div>
                </div>
            </form>
        </div><hr>


        <div>
            <h3 class="text-center">Add Record For Food Supply</h3>
            <form id="itemform" action="item_in.php" method="POST" enctype="multipart/form-data">
                <label for="datein">Select Date</label>
                <input type="date" class="form-control" name="datein" required>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-info mt-3" id="add_fooditem">Add Item</button>
                    <button type="button" class="btn btn-danger mt-3 ml-2" id="delete_fooditem">Delete</button>
                </div>
                <div id="add_moreitem">
                    <div class="row mt-3 mb-2">
                        <div class="col-12 col-sm-12 col-md col-lg mt-1">
                            <input type="text" class="form-control" name="item_name[]" placeholder="Item Name" required>
                        </div>
                        <div class="col-12 col-sm-12 col-md col-lg mt-1">
                            <input type="text" class="form-control" name="item_remark[]" placeholder="Remark (i.e. ingredients, quantity)" required>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <input type="submit" class="form-control btn btn-success mb-2" name="submit_food">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#additem').click(function() {
                var add_field = '<div><hr class="border-danger"><div class="row mt-3"><div class="col-12 col-sm-12 col-md col-lg mt-1"><input type="text" class="form-control" name="item_name[]" placeholder="Item Name" required></div><div class="col-12 col-sm-12 col-md col-lg mt-1"><input type="number" min="0" step="any" class="form-control" name="item_price[]" placeholder="Price" required></div><div class="col-12 col-sm-12 col-md col-lg mt-1"><input type="text" class="form-control" name="item_remark[]" placeholder="Remark (i.e. weight, shop name)" required></div></div></div>';
                $('#add_more').append(add_field);
            });
            $('#deleteitem').click(function() {
                //$(this).remove();
                $('#add_more').children().last().remove()
            });

            $('#add_fooditem').click(function() {
                var add_field = '<div><hr class="border-danger"><div class="row mt-3"><div class="col-12 col-sm-12 col-md col-lg mt-1"><input type="text" class="form-control" name="item_name[]" placeholder="Item Name" required></div><div class="col-12 col-sm-12 col-md col-lg mt-1"><input type="text" class="form-control" name="item_remark[]" placeholder="Remark (i.e. ingredients, quantity)" required></div></div></div>';
                $('#add_moreitem').append(add_field);
            });
            $('#delete_fooditem').click(function() {
                //$(this).remove();
                $('#add_moreitem').children().last().remove()
            });
        });
    </script>
</body>
</html>
