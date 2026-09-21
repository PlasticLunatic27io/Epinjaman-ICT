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
$current_page = 'add_borrower';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Application | ePinjamICT</title>
    
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

        input, select, textarea {
            background: #F9FAFB !important;
            border: 1.5px solid #F3F4F6 !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            width: 100%;
        }

        input:focus, select:focus, textarea:focus {
            outline: none !important;
            border-color: var(--primary-blue) !important;
            background: white !important;
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.05) !important;
        }

        label {
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            color: #9CA3AF !important;
            margin-bottom: 8px !important;
            display: block;
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
                <span class="text-xs uppercase tracking-widest font-black text-blue-900/40">Transactions</span>
                <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
                <h1 class="text-xs font-bold text-gray-600 italic">Create New Application</h1>
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
                
                <!-- Step 1: Applicant Details -->
                <div class="form-card p-10 mb-8 border-l-[6px] border-amber-500">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-user-magnifying-glass"></i>
                        </div>
                        <h2 class="text-xl font-black text-blue-900 uppercase italic">1. Applicant Details</h2>
                    </div>

                    <form method="post" id="applicantForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end">
                            <div class="space-y-2">
                                <label>Personnel ID (Staff/Student)</label>
                                <input type="text" name="pemohon_college_id" id="pemohon_college_id" 
                                       value="<?php echo isset($_POST['pemohon_college_id']) ? htmlspecialchars($_POST['pemohon_college_id']) : ''; ?>" 
                                       placeholder="e.g. BPN231210136" 
                                       onChange="this.form.submit()" required>
                            </div>
                            <div>
                                <a href="add_pemohon.php" class="inline-flex items-center gap-2 text-[11px] font-black text-blue-600 uppercase tracking-widest hover:text-amber-500 transition-all">
                                    <i class="fa-solid fa-user-plus"></i> Not registered? Add here
                                </a>
                            </div>
                        </div>
                    </form>

                    <?php 
                    $form_active = false;
                    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pemohon_college_id'])){
                        $search = mysqli_real_escape_string($conn, $_POST['pemohon_college_id']);
                        $q_p = mysqli_query($conn,"select * from pemohon where pemohon_college_id = '$search' ");
                        if (mysqli_num_rows($q_p) > 0){
                            $form_active = true;
                            $p_data = mysqli_fetch_array($q_p);
                            echo "<div class='mt-6 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-4 animate-pulse'>
                                    <i class='fa-solid fa-circle-check text-emerald-500'></i>
                                    <div>
                                        <p class='text-[10px] font-black text-emerald-600 uppercase leading-none mb-1'>Applicant Verified</p>
                                        <p class='text-sm font-bold text-emerald-900'>".$p_data['pemohon_name']."</p>
                                    </div>
                                  </div>";
                        } else {
                            echo "<div class='mt-6 p-4 bg-red-50 rounded-2xl border border-red-100 text-red-600 font-bold text-xs flex items-center gap-3'>
                                    <i class='fa-solid fa-circle-xmark'></i> ID not found in database. Please register first.
                                  </div>";
                        }
                    }
                    ?>
                </div>

                <!-- Step 2: Application Info -->
                <div class="form-card p-10 <?php echo $form_active ? '' : 'opacity-30 pointer-events-none'; ?> border-l-[6px] border-blue-900">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-blue-50 text-blue-900 rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-clipboard-list"></i>
                        </div>
                        <h2 class="text-xl font-black text-blue-900 uppercase italic">2. Application Info</h2>
                    </div>

                    <form method="post" class="space-y-8">
                        <input type="hidden" name="pemohon_college_id" value="<?php echo @$_POST['pemohon_college_id']; ?>">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label>Booking Type</label>
                                <select name="type" required>
                                    <option value="">-- Choose Type --</option>
                                    <option value="Dalaman">Dalaman (Internal)</option>
                                    <option value="Luaran">Luaran (External)</option>
                                    <option value="Luaran Berkala">Luaran Berkala (External Recurring)</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label>Need Technician?</label>
                                <select name="technician" required>
                                    <option value="">-- Choose Option --</option>
                                    <option value="Ya">Yes / Ya</option>
                                    <option value="Tidak">No / Tidak</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label>Items to Borrow (Name & Quantity)</label>
                            <textarea name="borrow_item" rows="2" placeholder="e.g. 2x Laptop HP, 1x Projector" required></textarea>
                        </div>

                        <div class="space-y-2">
                            <label>Purpose of Booking</label>
                            <input type="text" name="purpose" placeholder="e.g. Presentation for Final Project" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label>Start Date & Time</label>
                                <input type="datetime-local" name="start_date" required>
                            </div>
                            <div class="space-y-2">
                                <label>End Date & Time</label>
                                <input type="datetime-local" name="end_date" required>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label>Exact Location of Use</label>
                            <input type="text" name="location" placeholder="e.g. Auditorium Hall A" required>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex justify-end">
                            <button name="save" type="submit" class="bg-blue-900 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-blue-900/30 hover:bg-amber-500 hover:scale-105 transition-all">
                                Submit Application <i class="fa-solid fa-paper-plane ml-2"></i>
                            </button>
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['save'])){
                        date_default_timezone_set('Asia/Kuala_Lumpur');    
                        $pid = mysqli_real_escape_string($conn, $_POST['pemohon_college_id']);    
                        $item = mysqli_real_escape_string($conn, $_POST['borrow_item']);
                        $start = $_POST['start_date'];
                        $end = $_POST['end_date'];
                        $status = "Baru";
                        $type = $_POST['type'];  
                        $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);
                        $location = mysqli_real_escape_string($conn, $_POST['location']);  
                        $tech = $_POST['technician'];
                        $now = date('Y-m-d H:i:s');
                        
                        $profile = mysqli_fetch_assoc(mysqli_query($conn,"SELECT pemohon_name,pemohon_dept,pemohon_no_tel FROM pemohon WHERE pemohon_college_id='$pid' LIMIT 1"));
                        $appName = mysqli_real_escape_string($conn,$profile['pemohon_name'] ?? '');
                        $appDept = mysqli_real_escape_string($conn,$profile['pemohon_dept'] ?? '');
                        $appPhone = mysqli_real_escape_string($conn,$profile['pemohon_no_tel'] ?? '');
                        mysqli_query($conn,"insert into borrowing (pemohon_college_id,applicant_name,applicant_department,applicant_phone,borrow_item,request_date,start_date,end_date,status,type,purpose,location,need_technician) values('$pid','$appName','$appDept','$appPhone','$item','$now','$start','$end','$status','$type','$purpose','$location','$tech')") or die(mysqli_error($conn));      
                        
                        echo "<script>
                                alert('Application Successfully Submitted!');
                                window.location = 'borrow.php';
                             </script>";
                    }
                    ?>
                </div>

                <!-- Footer -->
                <footer class="mt-12 py-8 border-t border-gray-200/50 flex justify-between items-center opacity-40">
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.4em]">KPTM BP • 2026 Edition</p>
                    <p class="text-[10px] font-black text-blue-900 uppercase">AHA STUDIO</p>
                </footer>

            </div>
        </div>
    </main>

</body>
</html>