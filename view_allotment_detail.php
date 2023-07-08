<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include('head.php');
include('header.php');
include('sidebar.php');

// ... Existing code ...


if (isset($_POST["btn_allocate_teachers"])) {
  // Retrieve distinct room IDs for the given 'allot_id'
  $sql = "SELECT DISTINCT room_id FROM allot_student WHERE allot_id = '".$_GET['id']."'";
  $result = $conn->query($sql);
  $roomIDs = [];
  while ($row = mysqli_fetch_array($result)) {
      $roomIDs[] = $row['room_id'];
  }
  
  $sql = "SELECT teacher_id, COUNT(*) as count FROM allot_student GROUP BY teacher_id ORDER BY count ASC";
  $result = $conn->query($sql);
  $teacherIDs = [];
  $i=0;
  
  $numRooms = count($roomIDs);
  while ($row = mysqli_fetch_array($result)) {
    $roomID = $roomIDs[$i];
    $updateQuery = "UPDATE allot_student SET teacher_id = '$row[0]', ok = 0 WHERE room_id = '$roomID' AND allot_id = '".$_GET['id']."'";

    $conn->query($updateQuery);
    $i = $i+1;
    if ($i >= $numRooms){
      break;
    }
  }
    if (TRUE) {
      $_SESSION['success']=' Successully updated';
     ?>
<script type="text/javascript">
window.location="view_allotment_detail.php?id=<?=$_GET['allot_id']?>";
</script>
<?php
} else {
      $_SESSION['error']='Something Went Wrong';
?>
<script type="text/javascript">
window.location="view_allotment_detail.php?id=<?=$_GET['allot_id']?>";
</script>
<?php
  
}

}
if(isset($_POST["btn_update"]))
{

    $sql1 = "SELECT * FROM  `allot_student` WHERE id='".$_GET['teacher_id']."'";
   $result1 = $conn->query($sql1);
   $row = $result1->fetch_assoc();

   $room_id=$row['room_id'];
   $teacher_id=$_POST['teacher_id'];
   $allot_id=$row['allot_id'];

    extract($_POST);
    $q1="UPDATE `allot_student` SET `teacher_id`='$teacher_id', ok = 0 WHERE `room_id`='".$room_id."' and `allot_id`='".$allot_id."'";
   
    if ($conn->query($q1) === TRUE) {
      $_SESSION['success']=' Record Successfully Updated';

      ##here it is
      $take="SELECT temail FROM tbl_teacher where id='".$teacher_id."'";
      $takeresult=$conn->query($take);
      $s = "select * from tbl_email_config";
      $r = $conn->query($s);
      $rr = mysqli_fetch_array($r);

      $mail_host = $rr['mail_driver_host'];
      $mail_name = $rr['name'];
      $mail_username = $rr['mail_username'];
      $mail_password = $rr['mail_password'];
      $mail_port = $rr['mail_port'];

      require_once('vendor/autoload.php');
      $mail = new PHPMailer(TRUE);
      $mail->isSMTP();   

      $mail->Host = $mail_host;  
      $mail->SMTPAuth = true;                               
      $mail->Username = $mail_username;                 
      $mail->Password = $mail_password;     
      
      $row1 = mysqli_fetch_array($takeresult);

      $mail->SMTPSecure = 'tls';
      $mail->Port = $mail_port;           
      $mail->setFrom($mail_username, $mail_name);
      $mail->addAddress($row1[0], $personname);

      $taketime = "SELECT exam_date FROM allot_student where id='".$_GET['teacher_id']."'";
      $taketimeresult=$conn->query($taketime);
      $row2 = mysqli_fetch_array($taketimeresult);
      $mail->Subject = 'Exam duty on ';
      $mail->Body    = "Hello, exam duty is there for you on  :'$row2[0]' ";
      $mail->send();

      
     ?>
<script type="text/javascript">
window.location="view_allotment_detail.php?id=<?=$_GET['id']?>";
</script>
<?php
} else {
      $_SESSION['error']='Something Went Wrong';
?>
<script type="text/javascript">
window.location="view_allotment_detail.php?id=<?=$_GET['id']?>";
</script>
<?php
}

}


if(isset($_GET['teacher_id']))
{ ?>
<div class="popup popup--icon -question js_question-popup popup--visible">
  <div class="popup__background"></div>
  <div class="popup__content">
    <h3 class="popup__content__title">
      Assign Teacher
    </h1>
   <form method="post">
       <div class="form-group">
            <div class="row">
                <label class="col-sm-3 control-label">Teacher</label>
                <div class="col-sm-9">
                    <select type="text" name="teacher_id" id="teacher_id" class="form-control"   placeholder="Teacher" required>
                        <option value="">--Select Teacher--</option>
                            <?php  
                            $c1 = "SELECT * FROM `tbl_teacher`";
                            $result = $conn->query($c1);
                                while ($row = mysqli_fetch_array($result)) {?>
                                    <option value="<?php echo $row["id"];?>" >
                                        <?php echo $row['tfname'].' '.$row['tlname'];?>
                                    </option>
                                    <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" name="btn_update" class="btn btn-primary btn-flat m-b-30 m-t-30">Submit</button>
   </form>
    <!-- <p>
      <a href="del_allot.php?id=<?php echo $_GET['id']; ?>" class="button button--success" data-for="js_success-popup">Yes</a>
      <a href="view_allotment.php" class="button button--error" data-for="js_success-popup">No</a>
    </p> -->
  </div>
</div>
<?php } ?>


        <div class="page-wrapper">
            
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary"> View Allotment</h3> </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">View Allotment</li>
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
                                                <th>Exam Name</th>
                                                <th>Room Name</th>
                                                <th>Teacher Name</th>
                                                <th>Availability</th>
                                                <th>Action</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php 
                                    include 'connect.php';
                                  $sql1 = "SELECT * FROM  `allot_student` WHERE allot_id='".$_GET['id']."' GROUP BY room_id";
                                   $result1 = $conn->query($sql1);
                                   while($row = $result1->fetch_assoc()) {
                                   $s1 = "SELECT * FROM `exam` WHERE id='".$row['exam_id']."'";
                                    $sr = $conn->query($s1);
                                    $sres = mysqli_fetch_array($sr);

                                    $s2 = "SELECT * FROM `room` WHERE id='".$row['room_id']."'";
                                    $sr1 = $conn->query($s2);
                                    $sres1 = mysqli_fetch_array($sr1); 

                                    $s3 = "SELECT * FROM `tbl_teacher` WHERE id='".$row['teacher_id']."'";
                                    $sr2 = $conn->query($s3);
                                    $sres2 = mysqli_fetch_array($sr2);  
                                    
                                    if ($row["ok"]==0){
                                      $ans="available";

                                    }
                                    else{
                                      $ans="busy";
                                    }

                                      ?>
                                            <tr>
                                                <td><?php echo $sres['name']; ?></td>
                                                <td><?php echo $sres1['name']; ?></td>
                                                <td><?php echo isset($sres2['tfname'])? $sres2['tfname'].' '.$sres2['tlname']:"N/A"; ?></td>
                                                <td><?php echo $ans; ?></td>
                                                <td>
                                                <a href="view_allotment_detail.php?id=<?=$_GET['id']?>&&teacher_id=<?=$row['id'];?>"><button type="button" class="btn btn-xs btn-success" ><i class="fa fa-exchange"></i></button></a>
                                                </td>
                                            </tr>
                                          <?php } ?>
                                        </tbody>
                                    </table>
                                    <?php echo '<form method="post">';
                                          echo '<button type="submit" name="btn_allocate_teachers" class="btn btn-primary btn-flat m-b-30">Allocate Teachers</button>';
                                          echo '</form>';?>
                                </div>
                            </div>
                        </div>
               
                

<?php include('footer.php');?>

<link rel="stylesheet" href="popup_style.css">
<?php if(!empty($_SESSION['success'])) {  ?>
<div class="popup popup--icon -success js_success-popup popup--visible">
  <div class="popup__background"></div>
  <div class="popup__content">
    <h3 class="popup__content__title">
      Success 
    </h1>
    <p><?php echo $_SESSION['success']; ?></p>
    <p>
      <button class="button button--success" data-for="js_success-popup">Close</button>
    </p>
  </div>
</div>
<?php unset($_SESSION["success"]);  
} ?>
<?php if(!empty($_SESSION['error'])) {  ?>
<div class="popup popup--icon -error js_error-popup popup--visible">
  <div class="popup__background"></div>
  <div class="popup__content">
    <h3 class="popup__content__title">
      Error 
    </h1>
    <p><?php echo $_SESSION['error']; ?></p>
    <p>
      <button class="button button--error" data-for="js_error-popup">Close</button>
    </p>
  </div>
</div>
<?php unset($_SESSION["error"]);  } ?>
    <script>
      var addButtonTrigger = function addButtonTrigger(el) {
  el.addEventListener('click', function () {
    var popupEl = document.querySelector('.' + el.dataset.for);
    popupEl.classList.toggle('popup--visible');
  });
};

Array.from(document.querySelectorAll('button[data-for]')).
forEach(addButtonTrigger);
    </script>