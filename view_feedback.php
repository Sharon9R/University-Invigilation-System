
<?php include('head.php');?>
<?php include('header.php');?>
<?php include('sidebar.php');
 date_default_timezone_set('Asia/Kolkata');
 $current_date = date('Y-m-d');
 ?>
<div class="page-wrapper">
            
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary"> Feedbacks</h3> </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">View Feedback</li>
                    </ol>
                </div>
            </div>
          
            <div class="container-fluid">                    
               <div class="card">
                            <div class="card-body">
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Teacher</th>
                                                <th>Feedback</th>
                                                 
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            include 'connect.php';

                                            $sql1 = "SELECT * FROM feedback order by id desc";
                                            $result1 = $conn->query($sql1);

                                            if ($result1->num_rows > 0) {
                                                $feedbackRows = array();

                                                while ($row = $result1->fetch_assoc()) {
                                                    $feedbackRows[] = $row;
                                                }

                                                foreach ($feedbackRows as $row) {
                                                    $teacherId = $row['teacher_id'];
                                                    $s2 = "SELECT * FROM tbl_teacher WHERE id = '$teacherId'";
                                                    $sr1 = $conn->query($s2);

                                                    if ($sr1->num_rows > 0) {
                                                        $sres2 = $sr1->fetch_assoc();

                                                        echo "<tr>";
                                                        echo "<td>".$sres2['tfname']." ".$sres2['tlname']."</td>";
                                                        echo "<td>".$row['feedback']."</td>";
                                                        echo "</tr>";
                                                    }
                                                }
                                            } else {
                                                echo "No feedback found.";
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> 
        </div>
               
            

<?php include('footer.php');?>

