<?php 
require_once __DIR__ . "/../dbcon.php"; 
$conn = dbcon(); 
session_start();
require_once __DIR__ . '/admin_auth_guard.php';
$activeAdminAccount = adminRequireActiveAccount($conn ?? null);

if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '')) {
    header("location:index.php");
    exit();
}
$session_id = $_SESSION['id'];

$query_user = mysqli_query($conn, "SELECT * FROM user WHERE user_id = '$session_id'") or die(mysqli_error($conn));
$user_row = mysqli_fetch_array($query_user);

// Get item ID from URL
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($item_id == 0) {
    header("location:item.php");
    exit();
}

// Fetch item data
$query_item = mysqli_query($conn, "SELECT * FROM item WHERE item_id = '$item_id'") or die(mysqli_error($conn));
$item = mysqli_fetch_array($query_item);

if (!$item) {
    header("location:item.php");
    exit();
}

// Handle form submission
if (isset($_POST['update'])) {
    $item_name = mysqli_real_escape_string($conn, strtoupper(trim($_POST['item_name'])));
    $item_tag = mysqli_real_escape_string($conn, trim($_POST['item_tag']));
    $item_category = mysqli_real_escape_string($conn, strtoupper(trim($_POST['item_category'])));
    $brand = mysqli_real_escape_string($conn, strtoupper(trim($_POST['brand'])));
    $model = mysqli_real_escape_string($conn, strtoupper(trim($_POST['model'])));
    $item_siri = mysqli_real_escape_string($conn, trim($_POST['item_siri']));
    $item_quantity = intval($_POST['item_quantity']);
    $item_status = mysqli_real_escape_string($conn, trim($_POST['item_status']));
    $item_description = mysqli_real_escape_string($conn, trim($_POST['item_description']));
    
    // Check if tag already exists (excluding current item)
    $check_tag = mysqli_query($conn, "SELECT * FROM item WHERE item_tag = '$item_tag' AND item_id != '$item_id'");
    if (mysqli_num_rows($check_tag) > 0) {
        $error_message = "Item tag already exists! Please use a different tag.";
    } else {
        $update_query = "UPDATE item SET 
            item_name = '$item_name',
            item_tag = '$item_tag',
            item_category = '$item_category',
            brand = '$brand',
            model = '$model',
            item_siri = '$item_siri',
            item_quantity = '$item_quantity',
            item_status = '$item_status',
            item_description = '$item_description'
            WHERE item_id = '$item_id'";
        
        if (mysqli_query($conn, $update_query)) {
            $success_message = "Item updated successfully!";
            // Refresh item data
            $query_item = mysqli_query($conn, "SELECT * FROM item WHERE item_id = '$item_id'");
            $item = mysqli_fetch_array($query_item);
        } else {
            $error_message = "Error updating item: " . mysqli_error($conn);
        }
    }
}

// Set current page for sidebar
$current_page = 'item';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item | ePinjamICT</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --cream-bg: #FDFCF7;
            --macos-glass: rgba(255, 255, 255, 0.65);
            --primary-blue: #1E3A8A;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--cream-bg);
            background-image: radial-gradient(at 0% 0%, rgba(30, 58, 138, 0.03) 0px, transparent 40%);
            color: #1F2937;
            overflow: hidden;
            height: 100vh;
        }

        .glass-panel {
            background: var(--macos-glass);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        .form-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.02);
        }

        .sidebar-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            font-size: 13px;
        }

        .sidebar-item:hover {
            background: rgba(30, 58, 138, 0.06);
            transform: translateX(4px);
        }

        .sidebar-active {
            background: white !important;
            box-shadow: 0 4px 20px -5px rgba(0,0,0,0.05);
            color: #1E3A8A !important;
            font-weight: 800;
        }

        .form-group label {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #9CA3AF;
            display: block;
            margin-bottom: 8px;
        }

        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #F3F4F6;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            outline: none;
            transition: all 0.3s;
        }

        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
            border-color: #1E3A8A;
            background: white;
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        html { font-size: 14px; }
    </style>
</head>
<body class="flex">

    <?php include('sidebar_component.php'); ?>

    <main class="flex-1 flex flex-col overflow-hidden">
        
        <!-- Header -->
        <header class="h-20 glass-panel border-b border-gray-200 flex items-center justify-between px-10">
            <div class="flex items-center gap-3">
                <a href="item.php" class="text-gray-400 hover:text-blue-900 transition-all">
                    <i class="fa-solid fa-circle-arrow-left text-xl"></i>
                </a>
                <div class="flex items-center gap-2">
                    <span class="text-xs uppercase tracking-widest font-black text-blue-900/40">Inventory</span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
                    <h1 class="text-xs font-bold text-gray-600 italic">Edit Item</h1>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-black text-blue-900 leading-none"><?php echo $user_row['name']; ?></p>
                    <p class="text-[10px] font-bold text-amber-500 uppercase tracking-tighter italic">BP-ICT-2026</p>
                </div>
                <img src="<?php echo $user_row['adminthumbnails']; ?>" class="w-10 h-10 rounded-xl object-cover ring-2 ring-white shadow-md">
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex-1 p-10 overflow-y-auto no-scrollbar">
            
            <div class="max-w-4xl mx-auto">
                
                <!-- Success/Error Messages -->
                <?php if (isset($success_message)): ?>
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-xl">
                    <p class="font-bold text-emerald-900">
                        <i class="fa-solid fa-circle-check mr-2"></i>
                        <?php echo $success_message; ?>
                    </p>
                </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl">
                    <p class="font-bold text-red-900">
                        <i class="fa-solid fa-circle-exclamation mr-2"></i>
                        <?php echo $error_message; ?>
                    </p>
                </div>
                <?php endif; ?>

                <!-- Form Card -->
                <div class="form-card p-10 border-l-[6px] border-amber-500">
                    
                    <!-- Card Header -->
                    <div class="flex items-center justify-between mb-10 pb-6 border-b border-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
                                <i class="fa-solid fa-pen-to-square text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-blue-900 uppercase italic leading-none mb-1">Edit Item</h2>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Update Item Details</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Item ID</p>
                            <p class="text-2xl font-black text-amber-500">#<?php echo $item_id; ?></p>
                        </div>
                    </div>

                    <!-- Form -->
                    <form method="post" class="space-y-6">
                        
                        <!-- Row 1: Item Name & Tag -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="item_name">Item Name *</label>
                                <input type="text" id="item_name" name="item_name" 
                                       value="<?php echo htmlspecialchars($item['item_name']); ?>" 
                                       placeholder="e.g., LAPTOP HP ELITEBOOK" required>
                            </div>

                            <div class="form-group">
                                <label for="item_tag">Item Tag (Unique) *</label>
                                <input type="text" id="item_tag" name="item_tag" 
                                       value="<?php echo htmlspecialchars($item['item_tag']); ?>" 
                                       placeholder="e.g., ICT-LP-001" required>
                            </div>
                        </div>

                        <!-- Row 2: Category & Brand -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="item_category">Category *</label>
                                <select id="item_category" name="item_category" required>
                                    <option value="">-- Select Category --</option>
                                    <?php
                                    $categories = ['LAPTOP', 'PROJECTOR', 'SPEAKER', 'MICROPHONE', 'CAMERA', 'TABLET', 'MONITOR', 'PRINTER', 'OTHER'];
                                    foreach ($categories as $cat) {
                                        $selected = ($item['item_category'] == $cat) ? 'selected' : '';
                                        echo "<option value='$cat' $selected>$cat</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <input type="text" id="brand" name="brand" 
                                       value="<?php echo htmlspecialchars($item['brand']); ?>" 
                                       placeholder="e.g., HP, DELL, EPSON">
                            </div>
                        </div>

                        <!-- Row 3: Model & Serial Number -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="model">Model</label>
                                <input type="text" id="model" name="model" 
                                       value="<?php echo htmlspecialchars($item['model']); ?>" 
                                       placeholder="e.g., ELITEBOOK 840 G7">
                            </div>

                            <div class="form-group">
                                <label for="item_siri">Serial Number (item_siri)</label>
                                <input type="text" id="item_siri" name="item_siri" 
                                       value="<?php echo htmlspecialchars($item['item_siri']); ?>" 
                                       placeholder="e.g., SN123456789">
                            </div>
                        </div>

                        <!-- Row 4: Quantity & Status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="item_quantity">Quantity</label>
                                <input type="number" id="item_quantity" name="item_quantity" 
                                       value="<?php echo $item['item_quantity']; ?>" 
                                       min="1" placeholder="1">
                            </div>

                            <div class="form-group">
                                <label for="item_status">Status *</label>
                                <select id="item_status" name="item_status" required>
                                    <?php
                                    $statuses = ['Available', 'Reserved', 'Borrowed', 'Maintenance', 'Damage'];
                                    foreach ($statuses as $status) {
                                        $selected = ($item['item_status'] == $status) ? 'selected' : '';
                                        echo "<option value='$status' $selected>$status</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Row 5: Description -->
                        <div class="form-group">
                            <label for="item_description">Description / Notes</label>
                            <textarea id="item_description" name="item_description" rows="3" 
                                      placeholder="Additional details about the item..."><?php echo htmlspecialchars($item['item_description']); ?></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4 pt-6 border-t border-gray-100">
                            <button type="submit" name="update" 
                                    class="flex-1 bg-blue-900 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-blue-900/30 hover:bg-amber-500 hover:scale-105 transition-all">
                                <i class="fa-solid fa-save mr-2"></i>
                                Update Item
                            </button>
                            <a href="item.php" 
                               class="px-8 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all flex items-center justify-center">
                                <i class="fa-solid fa-xmark mr-2"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <footer class="mt-12 py-8 border-t border-gray-200/50 flex justify-between items-center opacity-40">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.4em]">KPTM BP • 2026</p>
                    <p class="text-[10px] font-black text-blue-900 uppercase">AHA Studio</p>
                </footer>

            </div>
        </div>
    </main>

</body>
</html>