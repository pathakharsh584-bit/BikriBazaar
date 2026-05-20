<?php
session_start();
require_once __DIR__ . '/../../shared/db.php';
require_once __DIR__ . '/../../shared/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    
    $user_id = (int)$_SESSION['user_id'];
    $name    = mysqli_real_escape_string($conn, trim($_POST['name']));
    $about   = mysqli_real_escape_string($conn, trim($_POST['about']));
    
    $image_sql_append = ""; 
    $upload_dir = __DIR__ . '/../../public/uploads/profiles/';

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    function deleteOldImage($conn, $user_id, $upload_dir) {
        $q = "SELECT profile_image FROM users WHERE id = $user_id";
        $res = mysqli_query($conn, $q);
        if ($row = mysqli_fetch_assoc($res)) {
            if (!empty($row['profile_image'])) {
                $old_file = $upload_dir . $row['profile_image'];
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }
        }
    }

    // Check if the user clicked the Trash Icon
    if (isset($_POST['delete_image_flag']) && $_POST['delete_image_flag'] === '1') {
        deleteOldImage($conn, $user_id, $upload_dir);
        $image_sql_append = ", profile_image = NULL"; // Remove from DB
    } 
    // Otherwise, check if they uploaded a NEW image
    elseif (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        
        $file_tmp_path = $_FILES['profile_image']['tmp_name'];
        $file_name = $_FILES['profile_image']['name'];
        
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($file_extension, $allowed_extensions)) { 
            deleteOldImage($conn, $user_id, $upload_dir);

            $new_file_name = "user_" . $user_id . "_" . uniqid() . "." . $file_extension;
            $dest_path = $upload_dir . $new_file_name;
            
            if (move_uploaded_file($file_tmp_path, $dest_path)) {
                $image_sql_append = ", profile_image = '$new_file_name'";
            }
        }
    }

    // Execute the final update query
    $sql = "UPDATE users SET name = '$name', about = '$about' $image_sql_append WHERE id = $user_id";
                
    if (mysqli_query($conn, $sql)) {
        // Sync session data
        $_SESSION['user_name'] = $name; 

        //Sync the newly uploaded image directly to the session!
        if (isset($new_file_name)) { $_SESSION['profile_image'] = $new_file_name; }
        
        // If they clicked the trash can, clear it from the session!
        if (isset($_POST['delete_image_flag']) && $_POST['delete_image_flag'] === '1') { $_SESSION['profile_image'] = null; }
        
        // Redirect to homepage with success flag
        header("Location: " . BASE_URL . "index.php?profile_updated=success");
        exit();
    } else {
        // Redirect to profile with error flag
        header("Location: " . BASE_URL . "profile.php?status=error");
        exit();
    }
}
?>