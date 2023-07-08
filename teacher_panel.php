<?php
include('head.php');
include('header2.php');
include('teacher_sidebar.php');

date_default_timezone_set('Asia/Kolkata');
$current_date = date('Y-m-d');
include('connect.php');

$sql_currency = "select * from manage_website";
$result_currency = $conn->query($sql_currency);
$row_currency = mysqli_fetch_array($result_currency);

if (isset($_GET['teacher_id'])) {
    $q1 = "UPDATE `allot_student` SET `ok`=1 WHERE `exam_id`='" . $_GET['id'] . "' and `teacher_id`='" . $_GET['teacher_id'] . "'";
    if ($conn->query($q1)) {
        $_SESSION['success'] = 'Marked your unavailability';
        ?>
<script type="text/javascript">
window.location="teacher_panel.php";
</script>
<?php
} else {
      $_SESSION['error']='Something Went Wrong';
?>
<script type="text/javascript">
window.location="teacher_panel.php";
</script>
<?php
}
}
?>

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
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
                                <th>Date</th>
                                <th>Time</th>
                                <th>Room Name</th>
                                <th>Availability</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'connect.php';
                            $sql1 = "SELECT * FROM `allot_student` WHERE teacher_id='" . $_SESSION['id'] . "' and exam_date>='" . date('Y-m-d') . "' GROUP BY exam_date";
                            $result1 = $conn->query($sql1);
                            while ($row = $result1->fetch_assoc()) {
                                $s1 = "SELECT * FROM `exam` WHERE id='" . $row['exam_id'] . "'";
                                $sr = $conn->query($s1);
                                $sres = mysqli_fetch_array($sr);

                                $s2 = "SELECT * FROM `room` WHERE id='" . $row['room_id'] . "'";
                                $sr1 = $conn->query($s2);
                                $sres1 = mysqli_fetch_array($sr1);

                                if ($row['ok'] == '1') {
                                    $ans = "Busy";
                                } else {
                                    $ans = "Available";
                                }
                                ?>
                                <tr>
                                    <td><?php echo $sres['name']; ?></td>
                                    <td><?php echo $sres['exam_date']; ?></td>
                                    <td><?php echo $row['start_time'] . '-' . $row['end_time']; ?></td>
                                    <td><?php echo $sres1['name']; ?></td>
                                    <td><?php echo $ans; ?></td>
                                    <td>
                                        <a href="teacher_panel.php?id=<?= $row['exam_id'] ?>&teacher_id=<?= $_SESSION['id'] ?>">
                                            <button type="button" class="btn btn-xs btn-success">
                                                <i class="fa fa-exchange"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
<link rel="stylesheet" href="popup_style.css">
<?php if (isset($_SESSION['success'])) { ?>
    <div class="popup popup--icon -success js_success-popup popup--visible">
        <div class="popup__background"></div>
        <div class="popup__content">
            <h3 class="popup__content__title">Success</h3>
            <p><?php echo $_SESSION['success']; ?></p>
            <p>
                <button class="button button--success" data-for="js_success-popup">Close</button>
            </p>
        </div>
    </div>
    <?php unset($_SESSION['success']);
} ?>
<?php if (isset($_SESSION['error'])) { ?>
    <div class="popup popup--icon -error js_error-popup popup--visible">
        <div class="popup__background"></div>
        <div class="popup__content">
            <h3 class="popup__content__title">Error</h3>
            <p><?php echo $_SESSION['error']; ?></p>
            <p>
                <button class="button button--error" data-for="js_error-popup">Close</button>
            </p>
        </div>
    </div>
    <?php unset($_SESSION['error']);
} ?>

<script>
    var addButtonTrigger = function addButtonTrigger(el) {
        el.addEventListener('click', function () {
            var popupEl = document.querySelector('.' + el.dataset.for);
            popupEl.classList.toggle('popup--visible');
        });
    };

    Array.from(document.querySelectorAll('button[data-for]')).forEach(addButtonTrigger);
</script>
