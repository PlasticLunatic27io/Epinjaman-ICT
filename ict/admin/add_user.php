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

// Set current page for sidebar
$current_page = 'add_user';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add System User | ePinjamICT</title>
    
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

        input {
            background: #F9FAFB !important;
            border: 1.5px solid #F3F4F6 !important;
            border-radius: 12px !important;
            padding: 14px 18px !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            width: 100%;
        }

        input:focus {
            outline: none !important;
            border-color: var(--primary-blue) !important;
            background: white !important;
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.05) !important;
        }

        label {
            font-size: 11px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            color: #9CA3AF !important;
            margin-bottom: 8px !important;
            display: block;
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
            <div class="flex items-center gap-2">
                <span class="text-xs uppercase tracking-widest font-black text-blue-900/40">Administration</span>
                <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
                <h1 class="text-xs font-bold text-gray-600 italic">Add New System User</h1>
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
            
            <div class="max-w-xl mx-auto">
                
                <!-- Form Card -->
                <div class="form-card p-10 border-l-[6px] border-blue-900">
                    
                    <!-- Card Header -->
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-gray-100">
                        <div class="w-14 h-14 bg-blue-50 text-blue-900 rounded-2xl flex items-center justify-center">
                            <i class="fa-solid fa-user-plus text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-blue-900 uppercase italic leading-none mb-1">Add System User</h2>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Create New Administrator</p>
                        </div>
                    </div>

                    <!-- Form -->
                    <form method="post" class="space-y-6">
                        
                        <!-- Full Name -->
                        <div class="space-y-2">
                            <label for="name">
                                <i class="fa-solid fa-user mr-1"></i> Full Name
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   placeholder="Enter full name" 
                                   required>
                        </div>

                        <!-- Username -->
                        <div class="space-y-2">
                            <label for="username">
                                <i class="fa-solid fa-at mr-1"></i> Username
                            </label>
                            <input type="text" 
                                   name="username" 
                                   id="username" 
                                   placeholder="Enter username" 
                                   required>
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label for="password">
                                <i class="fa-solid fa-lock mr-1"></i> Password
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   placeholder="Enter password" 
                                   required>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-6 border-t border-gray-100">
                            <button type="submit" 
                                    name="save" 
                                    class="w-full bg-blue-900 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-blue-900/30 hover:bg-amber-500 hover:scale-105 transition-all">
                                <i class="fa-solid fa-floppy-disk mr-2"></i>
                                Save System User
                            </button>
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['save'])){
                        $name = $_POST['name'];
                        $username = $_POST['username'];
                        $password = $_POST['password'];

                        $query = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' AND password = '$password' AND name = '$name'") or die(mysqli_error($conn));
                        $count = mysqli_num_rows($query);

                        if ($count > 0){ 
                    ?>
                        <script>
                            alert('Data Already Exist');
                        </script>
                    <?php
                        } else {
                            mysqli_query($conn, "INSERT INTO user(name, username, password, adminthumbnails) VALUES('$name', '$username', '$password', 'images/NO-IMAGE-AVAILABLE.jpg')") or die(mysqli_error($conn));
                    ?>
                        <script>
                            alert('System User Successfully Added');
                            window.location = 'add_user.php';
                        </script>
                    <?php
                        }
                    }
                    ?>
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