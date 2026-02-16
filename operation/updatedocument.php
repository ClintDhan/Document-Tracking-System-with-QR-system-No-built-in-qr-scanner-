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
    $returned_reason = $_POST['returned_reason'];


    if($status == 'Received') {
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
    elseif($status == 'Under Review') {
        $sql2 = "UPDATE document 
                        SET type = '$type',
                            description = '$description',
                            status = '$status'
                        WHERE qr_id = '$qr_id'";
        $result2 = $conn->query($sql2);

        $sql3 ="INSERT into document_log(document_id, action,performed_by) 
                            VALUES('$document_id', '$status', '$updatedby')";
        $conn->query($sql3);
        $_SESSION['success'] = "Document successfully updated.";
        header("Location: ../user/user-home.php?control=" . urlencode($control));
        exit();
    }
    elseif($status == 'Released') {
        $returned_reason = null;

        $sql4 = "UPDATE document 
                        SET type = '$type',
                            description = '$description',
                            status = '$status',
                            released_to = '$released_to',
                            returned_reason = '$returned_reason'
                        WHERE qr_id = '$qr_id'";
        $result3 = $conn->query($sql4);

        $sql5 ="INSERT into document_log(document_id, action,performed_by) 
                            VALUES('$document_id', '$status', '$updatedby')";
        $conn->query($sql5);
        $_SESSION['success'] = "Document successfully updated.";
        header("Location: ../user/user-home.php?control=" . urlencode($control));
        exit();
    }
    elseif($status == 'Returned') {
        $released_to = null;

        $sql6 = "UPDATE document 
                        SET type = '$type',
                            description = '$description',
                            status = '$status',
                            released_to = '$released_to',
                            returned_reason = '$returned_reason'
                        WHERE qr_id = '$qr_id'";
        $result4 = $conn->query($sql6);

        $sql7 ="INSERT into document_log(document_id, action,performed_by) 
                            VALUES('$document_id', '$status', '$updatedby')";
        $conn->query($sql7);
        $_SESSION['success'] = "Document successfully updated.";
        header("Location: ../user/user-home.php?control=" . urlencode($control));
        exit();
    }
    else {
        echo "hahahahahaha e chatgpt napud XD";
    }

}



?>