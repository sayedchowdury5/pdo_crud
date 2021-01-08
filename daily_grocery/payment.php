<?php
    require_once ('connect.php');

    if (isset($_POST['submit'])) {
        if (!empty($_POST['paid_amount'] && $_FILES['payment_receipt']['name'])) {
            $amount = $_POST['paid_amount'];

            $target_dir = "images/";
            $file_database_name = '';

            foreach ($amount as $key => $value) {
                //get img extension for original image
				$img_ori_extension = pathinfo($_FILES['payment_receipt']['name'][$key], PATHINFO_EXTENSION);
				//set image extension which only allowed
                $img_allowed_extension = array('jpg','png','jpeg','gif');

                //compare original image and allowed image extensions
				if (in_array($img_ori_extension, $img_allowed_extension)) {
                    $file_server_name = $target_dir.uniqid().basename($_FILES['payment_receipt']['name'][$key]);
                    $file_database_name = $file_server_name;
					//insert image to database
					$sql = "INSERT INTO payment_record (amount, receipt_img) VALUES (?, ?)";
					$prepare = $conn->prepare($sql);
					$prepare->execute([$value, $file_database_name]);

					//check if image move to the target directory or not
					if (move_uploaded_file($_FILES['payment_receipt']['tmp_name'][$key], $file_server_name)) {
						echo '<script>alert("Successfully added item(s)!"); window.location.href="payment.php";</script>';
					} else {
						echo "<script>alert('Something Wrong!'); window.location.href = 'payment.php';</script>";
					}
				} else {
					echo "<script>alert('Only JPG or JPEG, PNG and GIF files allowed'); window.location.href = 'test.php';</script>";
				}
            }
        } else {
            echo '<script>alert("Please Add Paid Amount and Payment Receipt.");</script>';
        }
    }

    $query = "SELECT SUM(amount) as total_paid FROM payment_record";
    $payment = $conn->prepare($query);
    $payment->execute();
    $total = $payment->fetch(PDO::FETCH_OBJ);
    //print_r($total->total_paid);
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
        <a href="item_in.php" class="btn btn-primary mt-2">Go to Add Item Page</a>
        <a href="view_food_supply.php" class="btn btn-primary mt-2">View Food Supply</a>
        <div>
            <h3 class="text-center mt-3">Add Payment Record</h3>
            <form id="itemform" action="payment.php" method="POST" enctype="multipart/form-data">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-info mt-3" id="additem">Add Item</button>
                    <button type="button" class="btn btn-danger mt-3 ml-2" id="deleteitem">Delete</button>
                </div>
                <div id="add_more">
                    <div class="row mt-3 mb-2">
                        <div class="col-12 col-sm-12 col-md col-lg mt-1">
                        <label for="paid_amount">Amount Paid</label>
                            <input type="number" min="0" step="any" class="form-control" name="paid_amount[]" placeholder="How much paid?" required>
                        </div>
                        <div class="col-12 col-sm-12 col-md col-lg mt-1">
                        <label for="payment_receipt">Upload Receipt</label>
                            <input type="file" class="form-control" name="payment_receipt[]" required>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <input type="submit" class="form-control btn btn-success mb-2" name="submit" onclick="return confirm('Are you confrim? Once submitted, you can not edit anymore!');">
                    </div>
                </div>
            </form>
        </div><hr>

        <div>
            <h3 class="text-center mb-5">View Payment History</h3>
            <div>
                <h4 class="text-info">Total Amount Paid: <?php echo $total->total_paid ;?></h4>
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount Paid</th>
                            <th>Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT * FROM payment_record ORDER BY payment_date DESC";
                    $prepare = $conn->prepare($sql);
                    $prepare->execute();
                    $record = $prepare->fetchAll(PDO::FETCH_OBJ);
                    //print_r($record);
                    foreach ($record as $payment) {
                        ?>
                        <tr>
                            <td><?php echo $payment->payment_date ;?></td>
                            <td><?php echo $payment->amount ;?></td>
                            <td><img src="<?php echo $payment->receipt_img ;?>" alt="payment receipt" style="max-width:200px; max-height:150px;" class="payment_proof"></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#additem').click(function() {
                var add_field = '<div><hr class="border-danger"><div class="row mt-3 mb-2"><div class="col-12 col-sm-12 col-md col-lg mt-1"><label for="paid_amount">Amount Paid</label><input type="number" min="0" step="any" class="form-control" name="paid_amount[]" placeholder="How much paid?" required></div><div class="col-12 col-sm-12 col-md col-lg mt-1"><label for="payment_receipt">Upload Receipt</label><input type="file" class="form-control" name="payment_receipt[]" required></div></div></div>';
                $('#add_more').append(add_field);
            });
            $('#deleteitem').click(function() {
                //$(this).remove();
                $('#add_more').children().last().remove()
            });

            $('.payment_proof').click(function() {
                $(this).css({"max-width": "300px", "max-height": "250px"});
            });
        });
    </script>
</body>
</html>