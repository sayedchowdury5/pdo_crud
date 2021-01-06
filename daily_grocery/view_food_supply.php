<?php
    require_once ('connect.php');

    $sql = "SELECT * FROM food_supply ORDER BY record_date";
    $prepare = $conn->prepare($sql);
    $prepare->execute();
    $result = $prepare->fetchAll(PDO::FETCH_OBJ);
    //$json = json_encode($result);
    //print_r($result);

    $sql1 = "SELECT record_date FROM food_supply GROUP BY record_date ORDER BY record_date ASC ";
    $prepare1 = $conn->prepare($sql1);
    $prepare1->execute();
    //$total_day = $prepare->rowCount();
    $row = $prepare1->fetchAll(PDO::FETCH_OBJ);
    $total_day = 0;
    foreach ($row as $day) {
        $total_day++;
    }
    //print_r($total_day);

    $sql2 = "SELECT record_date FROM food_supply ORDER BY record_date ASC";
    $prepare2 = $conn->prepare($sql2);
    $prepare2->execute();
    $first_date = $prepare2->fetch(PDO::FETCH_COLUMN);
    //print_r($result_date);

    $sql3 = "SELECT record_date FROM food_supply ORDER BY record_date DESC";
    $prepare3 = $conn->prepare($sql3);
    $prepare3->execute();
    $last_date = $prepare3->fetch(PDO::FETCH_COLUMN);
    //print_r($last_date);
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
        <a href="index.php" class="btn btn-primary mt-2">Back to Home</a>
        <a href="item_in.php" class="btn btn-primary mt-2">Go to Add Item Page</a>
        <div>
            <h3 class="text-center">All Records for Food Supply</h3>
            <div class="table-responsive">
                <table id="all_record" class="table text-center" style="width:100%">
                    
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Item Name</th>
                            <th>Item Remark</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                
                    <tbody>
                        <?php
                            foreach ($result as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row->id ?></td>
                            <td><?php echo $row->item_name; ?></td>
                            <td><?php echo $row->item_remark; ?></td>
                            <td><?php echo $row->record_date; ?></td>
                        </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <h5 class="text-info">Date: <?php echo $first_date; ?> - <?php echo $last_date; ?> / <span>Total Supply: <?php echo $total_day; ?> Day(s).</span></h5>
        </div><hr>
        <!-- All Record display end -->

        <div class="mt-5">
            <h3 class="text-center">View Specific Date Record</h3>
            <form action="view_food_supply.php" method="POST" enctype="multipart/form-data">
                <label for="datepick">Select Date</label>
                <input type="date" class="form-control" name="datepick" required>
                <input type="submit" class="btn btn-success mt-3 mb-2" name="single_date">
            </form>
            <?php
                if (isset($_POST['single_date'])) {
                    $select_date = $_POST['datepick'];
                    $item_supply = 0;
            
                    $sql = "SELECT * FROM food_supply WHERE record_date = ? ";
                    $prepare = $conn->prepare($sql);
                    $prepare->execute([$select_date]);
                    $result2 = $prepare->fetchAll(PDO::FETCH_OBJ);
                    //print_r($result2);
            ?>
            <div class="mt-5 table-responsive">
                <form action="index.php" method="POST" enctype="multipart/form-data">
                    <table class="table text-center" id="specific_record" style="width:100%">    
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Item Name</th>
                                <th>Item Remark</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($result2 as $row2) {
                                    $item_supply++;
                            ?>
                            <tr>
                                <td><?php echo $row2->id; ?></td>
                                <td><?php echo $row2->item_name; ?></td>
                                <td><?php echo $row2->item_remark; ?></td>
                                <td><?php echo $row2->record_date; ?></td>
                                <td>
                                    <a href="single_food_view.php?id=<?php echo $row2->id; ?>" class="text-success" style="text-decoration:none"><i class="fa fa-edit" title="Edit"></i></a>
                                    <a href="single_food_delete.php?id=<?php echo $row2->id; ?>" class="ml-1 text-danger" style="text-decoration:none" onclick="return confirm('Are you confirm want to delete this item?');"><i class="fa fa-trash" title="Delete"></i></a>
                                </td>
                            </tr>
                            <?php
                                }
                            ?>
                            <h5 class="text-info">Date: <?php echo $select_date; ?> /<span> Total Supply: <?php echo $item_supply; ?> item(s).</span></h5>
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
                            columns: [ 1, 2, 3 ]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 1, 2, 3 ]
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
                            columns: [ 1, 2, 3 ]
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