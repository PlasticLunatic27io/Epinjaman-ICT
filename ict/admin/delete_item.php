<?php
require_once __DIR__ . "/../dbcon.php";
$conn = dbcon();
session_start();
require_once __DIR__ . '/admin_auth_guard.php';
$activeAdminAccount = adminRequireActiveAccount($conn ?? null);

// Check if user is logged in
if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '')) {
    header("location:index.php");
    exit();
}

// Check if delete button was clicked and items were selected
if(isset($_POST['delete']) && isset($_POST['selector'])){
    $selected_items = $_POST['selector'];
    
    // Check if any items were actually selected
    if(count($selected_items) > 0){
        // Loop through each selected item and delete
        foreach($selected_items as $item_id){
            $item_id = mysqli_real_escape_string($conn, $item_id);
            mysqli_query($conn, "DELETE FROM item WHERE item_id = '$item_id'") or die(mysqli_error($conn));
        }
        
        // Success message and redirect
        echo "<script>
                alert('Selected items have been deleted successfully!');
                window.location = 'item.php';
              </script>";
    } else {
        // No items selected
        echo "<script>
                alert('Please select at least one item to delete!');
                window.location = 'item.php';
              </script>";
    }
} else {
    // If accessed directly without POST data
    header("location:item.php");
    exit();
}
?>