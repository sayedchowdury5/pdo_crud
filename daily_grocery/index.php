<?php
    require_once ('connect.php');

    $sql = "SELECT * FROM item_record ORDER BY record_date";
    $prepare = $conn->prepare($sql);
    $prepare->execute();
    $result = $prepare->fetchAll(PDO::FETCH_OBJ);
    //$json = json_encode($result);
    //print_r($result);

    $sql1 = "SELECT SUM(item_price) FROM item_record";
    $prepare1 = $conn->prepare($sql1);
    $prepare1->execute();
    $all_item_total_price = $prepare1->fetch(PDO::FETCH_COLUMN);
    $all_item_round_total_price = number_format($all_item_total_price, 2);
    //echo $all_item_total_price;
    //echo '<br>';
    //echo $all_item_round_total_price;

    $sql2 = "SELECT record_date FROM item_record ORDER BY record_date ASC";
    $prepare2 = $conn->prepare($sql2);
    $prepare2->execute();
    $first_date = $prepare2->fetch(PDO::FETCH_COLUMN);
    //print_r($result_date);

    $sql3 = "SELECT record_date FROM item_record ORDER BY record_date DESC";
    $prepare3 = $conn->prepare($sql3);
    $prepare3->execute();
    $last_date = $prepare3->fetch(PDO::FETCH_COLUMN);
    //print_r($last_date);

    $query = "SELECT SUM(amount) as total_paid FROM payment_record";
    $payment = $conn->prepare($query);
    $payment->execute();
    $total = $payment->fetch(PDO::FETCH_OBJ);
    //print_r($total->total_paid);

    $amount_due = $all_item_round_total_price - $total->total_paid;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/2c655b324d.js" crossorigin="anonymous"></script>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- DataTable library -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.22/api/sum().js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body style="background-color:lightblue;">
    <div class="container mt-3 mb-3" style="background-color:white; border: 2px dashed green;">
        <a href="item_in.php" class="btn btn-primary mt-2">Go to Add Item Page</a>
        <a href="view_food_supply.php" class="btn btn-primary mt-2">View Food Supply</a>
        <a href="payment.php" class="btn btn-primary mt-2">Payment Record</a>
        <div>
            <h3 class="text-center mt-3">All Records for Daily Shopping</h3>
            <div class="table-responsive">
                <table id="all_record" class="table text-center" style="width:100%">
                    
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Item Name</th>
                            <th>Item Remark</th>
                            <th>Item Price (RM)</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                
                    <tbody>
                        <?php
                            foreach ($result as $row) {
                                $all_date_round_price = number_format($row->item_price, 2);
                        ?>
                        <tr>
                            <td><?php echo $row->id ?></td>
                            <td><?php echo $row->item_name; ?></td>
                            <td><?php echo $row->item_remark; ?></td>
                            <td><?php echo $all_date_round_price; ?></td>
                            <td><?php echo $row->record_date; ?></td>
                        </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <h5 class="text-info">Date: <?php echo $first_date; ?> - <?php echo $last_date; ?> / 
                <span>Total Spent: <?php echo $all_item_round_total_price; ?></span> /
                <?php
                if ($total->total_paid <= 0) {
                    ?>
                    <span>Total Paid: <?php echo 0; ?></span> /
                    <?php
                } else {
                    ?>
                    <span>Total Paid: <?php echo $total->total_paid; ?></span> /
                    <?php
                }
                ?> 
                <?php
                if ($amount_due > 0) {
                    ?>
                    <span class="text-danger">Amount Due: <?php echo $amount_due; ?></span>
                    <?php
                } else {
                    ?>
                    <span class="text-danger">Extra Paid: <?php echo abs($amount_due); ?></span>
                    <?php
                }
                ?> 
            </h5>
        </div><hr>
        <!-- All Record display end -->

        <div class="mt-5">
            <h3 class="text-center">View Specific Date Record</h3>
            <form action="index.php" method="POST" enctype="multipart/form-data">
                <label for="datepick">Select Date</label>
                <input type="date" class="form-control" name="datepick" required>
                <input type="submit" class="btn btn-success mt-3 mb-2" name="single_date">
            </form>
            <?php
                if (isset($_POST['single_date'])) {
                    $select_date = $_POST['datepick'];
            
                    $sql = "SELECT * FROM item_record WHERE record_date = ? ";
                    $prepare = $conn->prepare($sql);
                    $prepare->execute([$select_date]);
                    $result2 = $prepare->fetchAll(PDO::FETCH_OBJ);
                    //print_r($result2);
                    $single_date_total_price = 0;
            ?>
            <div class="mt-5 table-responsive">
                <form action="index.php" method="POST" enctype="multipart/form-data">
                    <table class="table text-center" id="specific_record" style="width:100%">    
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Item Name</th>
                                <th>Item Remark</th>
                                <th>Item Price (RM)</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($result2 as $row2) {
                                    $single_date_row_price = number_format($row2->item_price, 2);
                                    $single_date_total_price += $single_date_row_price;
                                    $single_date_round_total = number_format($single_date_total_price, 2);
                            ?>
                            <tr>
                                <td><?php echo $row2->id; ?></td>
                                <td><?php echo $row2->item_name; ?></td>
                                <td><?php echo $row2->item_remark; ?></td>
                                <td><?php echo $single_date_row_price; ?></td>
                                <td><?php echo $row2->record_date; ?></td>
                                <td>
                                    <a href="single_item_view.php?id=<?php echo $row2->id; ?>" class="text-success" style="text-decoration:none"><i class="fa fa-edit" title="Edit"></i></a>
                                    <a href="single_item_delete.php?id=<?php echo $row2->id; ?>" class="ml-1 text-danger" style="text-decoration:none" onclick="return confirm('Are you confirm want to delete this item?');"><i class="fa fa-trash" title="Delete"></i></a>
                                </td>
                            </tr>
                            <?php
                                }
                            ?>
                            <h5 class="text-info"> <span>Date: <?php echo $select_date; ?>/</span> Total Spent: <?php echo $single_date_round_total; ?></h5>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div><hr>
        <!-- Specific Record display end -->
    </div>

    <script>
        $(document).ready(function() {
            var table1 = $('#all_record').DataTable({
                "ordering": false,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, ':visible' ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4 ]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4 ]
                        }
                    },
                    'colvis'
                ],
                drawCallback: function () {
                var api = this.api();
                $( api.table().footer() ).html(
                    api.column( 2, {page:'current'} ).data().sum()
                );
                }
                /*buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]*/
            });
            // Hide first column
            table1.columns( [0] ).visible( false );
            table1.columns.adjust().draw( false ); // adjust column sizing and redraw
            table1.column( 2 ).data().sum();

            // Insert the sum of a column into the columns footer, for the visible
            // data on each draw
            /*$('#all_record').DataTable( {
                drawCallback: function () {
                var api = this.api();
                $( api.table().footer() ).html(
                    api.column( 4, {page:'current'} ).data().sum()
                );
                }
            } );*/

            var table2 = $('#specific_record').DataTable({
                "ordering": false,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 0, ':visible' ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4 ]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4 ]
                        }
                    },
                    'colvis'
                ]
                /*buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]*/
            });
            // Hide first column
            table2.columns( [0] ).visible( false );
            table2.columns.adjust().draw( false ); // adjust column sizing and redraw
        });
    </script>
</body>
</html>