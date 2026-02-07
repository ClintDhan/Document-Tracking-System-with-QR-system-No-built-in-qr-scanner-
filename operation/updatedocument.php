<?php 
session_start();
require_once '../db.php'; 

if(isset($_POST['submit'])) {
    $updatedby = $_SESSION['user_id'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $department = $_POST['department'];
    $released_to = $_POST['released_to'];
    $qr_id = $_POST['qr_id'];
    $document_id = $_POST['document_id'];
    $control =  $_POST['control_num'];


    $sql = "SELECT * FROM document WHERE type = '$type'";
    $result = $conn->query($sql);

    if(empty($released_to)) {
    
    $sql1 = "UPDATE document 
                    SET type = '$type',
                        description = '$description',
                        status = '$status'
                    WHERE qr_id = '$qr_id'";
    $result1 = $conn->query($sql1);

    $sql2 ="INSERT into document_log(document_id, action,performed_by) 
                        VALUES('$document_id', '$status', '$updatedby')";
    $conn->query($sql2);
    $_SESSION['success'] = "Document successfully updated.";
    header("Location: ../user/user-home.php?control=" . urlencode($control));
    exit();
    }
    elseif(!empty($released_to)) {
     $sql3 = "UPDATE document 
                SET type = '$type',
                    description = '$description',
                    status = '$status',
                    released_to = '$released_to'
                    WHERE qr_id = '$qr_id'";
    $result3 = $conn->query($sql3);

    $sql4 ="INSERT into document_log(document_id, action,performed_by) 
                        VALUES('$document_id', '$status', '$updatedby')";
    $conn->query($sql4);
    $_SESSION['success'] = "Document successfully updated.";
    header("Location: ../user/user-home.php?control=" . urlencode($control));
    exit();
    }

    else {
        echo "NAAY ERROR DO!";
    }


}



?>