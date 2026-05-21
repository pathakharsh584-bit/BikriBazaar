<?php
session_start();
require_once __DIR__ . '/../../shared/db.php';
require_once __DIR__ . '/../../shared/config.php';

use Cloudinary\Api\Upload\UploadApi;

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

// Helper Function: Extracts Cloudinary Public ID from the secure URL
function extractCloudinaryPublicId($url) {
    $parts = explode('/upload/', $url);
    if(isset($parts[1])) {
        $pathWithoutVersion = preg_replace('/^v\d+\//', '', $parts[1]);
        $pathInfo = pathinfo($pathWithoutVersion);
        $dir = $pathInfo['dirname'] === '.' ? '' : $pathInfo['dirname'] . '/';
        return $dir . $pathInfo['filename'];
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    
    $user_id = (int)$_SESSION['user_id'];
    $name    = mysqli_real_escape_string($conn, trim($_POST['name']));
    $about   = mysqli_real_escape_string($conn, trim($_POST['about']));
    
    $image_sql_append = ""; 

    function deleteOldImage($conn, $user_id) {
        $q = "SELECT profile_image FROM users WHERE id = $user_id";
        $res = mysqli_query($conn, $q);
        if ($row = mysqli_fetch_assoc($res)) {
            if (!empty($row['profile_image'])) {
                $imageUrl = $row['profile_image'];
                try {
                    $publicId = extractCloudinaryPublicId($imageUrl);
                    if($publicId) {
                        (new UploadApi())->destroy($publicId);
                    }
                } catch (Exception $e) {
                    error_log("Failed to delete Cloudinary profile asset: " . $e->getMessage());
                }
            }
        }
    }

    // Check if the user clicked the Trash Icon
    if (isset($_POST['delete_image_flag']) && $_POST['delete_image_flag'] === '1') {
        deleteOldImage($conn, $user_id);
        $image_sql_append = ", profile_image = NULL"; // Remove from DB
    } 
    // Otherwise, check if they uploaded a NEW image
    elseif (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        
        $file_tmp_path = $_FILES['profile_image']['tmp_name'];
        $file_extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($file_extension, $allowed_extensions)) { 
            deleteOldImage($conn, $user_id);

            try {
                // Upload to Cloudinary under a 'profiles' folder
                $uploadResult = (new UploadApi())->upload($file_tmp_path, [
                    'folder' => 'olx_replica/profiles',
                    'resource_type' => 'image'
                ]);

                // Grab the secure URL
                $new_file_url = $uploadResult['secure_url'];
                $escapedUrl = mysqli_real_escape_string($conn, $new_file_url);
                $image_sql_append = ", profile_image = '$escapedUrl'";

            } catch (Exception $e) {
                error_log("Profile Upload Failed: " . $e->getMessage());
            }
        }
    }

    // Execute the final update query
    $sql = "UPDATE users SET name = '$name', about = '$about' $image_sql_append WHERE id = $user_id";
                
    if (mysqli_query($conn, $sql)) {
        // Sync session data
        $_SESSION['user_name'] = $name; 

        // Sync the newly uploaded Cloudinary URL directly to the session
        if (isset($new_file_url)) { $_SESSION['profile_image'] = $new_file_url; }
        
        // If they clicked the trash can, clear it from the session
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