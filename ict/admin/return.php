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
$current_page = 'return';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Items | ePinjamICT</title>
    
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
            background-image: radial-gradient(at 0% 0%, rgba(245, 158, 11, 0.03) 0px, transparent 40%);
            color: #1F2937;
            overflow: hidden;
            height: 100vh;
        }

        .glass-panel {
            background: var(--macos-glass);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        .table-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
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

        input[type="text"] {
            @apply w-full bg-gray-50 border-none px-4 py-3 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-blue-900 transition-all outline-none;
        }

        th {
            font-size: 10px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.1em !important;
            color: #9CA3AF !important;
            padding: 15px !important;
            border-bottom: 1px solid #F3F4F6 !important;
        }

        td {
            padding: 15px !important;
            font-size: 13px !important;
            border-bottom: 1px solid #F9FAFB !important;
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
                <h1 class="text-xs font-bold text-gray-600 italic">Item Return Processing</h1>
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
            
            <div class="max-w-xl mx-auto mb-10 text-center">
                <h2 class="text-2xl font-black text-blue-900 tracking-tighter uppercase mb-2">Identify Borrower</h2>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Enter Staff or Student ID to list active loans</p>
                
                <form method="post" class="relative group">
                    <i class="fa-solid fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-blue-900 transition-colors"></i>
                    <input type="text" name="pemohon_college_id" id="pemohon_college_id" 
                           value="<?php echo isset($_POST['pemohon_college_id']) ? htmlspecialchars($_POST['pemohon_college_id']) : ''; ?>" 
                           placeholder="BPN231210136..." 
                           class="pl-12 pr-4 py-4 rounded-2xl shadow-sm border border-gray-100"
                           onChange="this.form.submit()" required>
                </form>
            </div>

            <?php 
            if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pemohon_college_id'])){
                $search = mysqli_real_escape_string($conn, $_POST['pemohon_college_id']);
                $status_loan = "Borrowed";
                
                $query = mysqli_query($conn,"SELECT * FROM borrowing_item WHERE pemohon_college_id = '$search' AND status='$status_loan' ") or die(mysqli_error($conn));
                $count = mysqli_num_rows($query);

                if ($count > 0){ ?>
                    <div class="table-container overflow-hidden">
                        <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-emerald-50/30">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-6 bg-emerald-500 rounded-full"></div>
                                <span class="text-xs font-black text-emerald-700 uppercase tracking-widest">Active Loans Found: <?php echo $count; ?></span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr>
                                        <th>Ref ID</th>
                                        <th>Item Detail</th>
                                        <th>Qty</th>
                                        <th>Current Status</th>
                                        <th>Condition Note</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_array($query)){ 
                                        $item_tag = $row['item_tag'];
                                        $query2 = mysqli_query($conn, "SELECT item_name FROM item WHERE item_tag = '$item_tag'");
                                        $row2 = mysqli_fetch_assoc($query2);
                                    ?>
                                    <form method="post" action="">
                                        <tr class="hover:bg-gray-50/50 transition-all">
                                            <td class="font-mono font-bold text-gray-400">#<?php echo $row['borrow_id']; ?></td>
                                            <td>
                                                <p class="font-black text-blue-900 uppercase text-xs"><?php echo $row2['item_name']; ?></p>
                                                <p class="text-[10px] text-gray-400 font-mono"><?php echo $row['item_tag']; ?></p>
                                                <input type="hidden" name="item_tag" value="<?php echo htmlspecialchars($row['item_tag'], ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="borrow_item_id" value="<?php echo (int)$row['borrow_item_id']; ?>">
                                            </td>
                                            <td class="font-bold text-gray-600"><?php echo $row['item_qty']; ?></td>
                                            <td>
                                                <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[9px] font-black uppercase ring-1 ring-amber-100">
                                                    <?php echo $row['status']; ?>
                                                </span>
                                            </td>
                                            <td class="w-1/4">
                                                <input type="text" name="note" placeholder="E.g. Good condition..." class="bg-white border border-gray-100 py-2 text-xs">
                                            </td>
                                            <td class="text-center">
                                                <button name="return" type="submit" class="bg-emerald-600 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-100 hover:bg-emerald-700 hover:scale-105 transition-all">
                                                    Confirm Return
                                                </button>
                                            </td>
                                        </tr>
                                    </form>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="flex flex-col items-center justify-center py-20 opacity-30">
                        <i class="fa-solid fa-box-open text-5xl mb-4"></i>
                        <p class="text-xs font-black uppercase tracking-widest">No active borrowed items found for this ID.</p>
                    </div>
                <?php }
            } else { ?>
                <div class="flex flex-col items-center justify-center py-20 opacity-20">
                    <i class="fa-solid fa-magnifying-glass-chart text-6xl mb-4"></i>
                    <p class="text-xs font-black uppercase tracking-[0.3em]">Waiting for search input...</p>
                </div>
            <?php } ?>

            <?php
if (isset($_POST['return'])){
    $item_tag = mysqli_real_escape_string($conn, $_POST['item_tag']);
    $borrow_item_id = (int)($_POST['borrow_item_id'] ?? 0);
    $note = mysqli_real_escape_string($conn, $_POST['note']);
    $returnOfficer = mysqli_real_escape_string($conn, (string)($user_row['name'] ?? ''));
    
    // 1. Update status barang dalam inventory
    mysqli_query($conn,"UPDATE item SET item_status = 'Available' WHERE item_tag = '$item_tag' AND item_status = 'Borrowed' ");
    
    // 2. Update status pinjaman DAN masukkan tarikh return (NOW())
    mysqli_query($conn,"UPDATE borrowing_item SET status='Returned', notes='$note', return_date=NOW(), returned_by=$session_id, returned_by_name='$returnOfficer' WHERE borrow_item_id=$borrow_item_id AND item_tag='$item_tag' AND status='Borrowed'");
    
    echo "<script>
            alert('Item #$item_tag successfully returned.');
            window.location = 'return.php';
          </script>";
}
?>

            <!-- Footer -->
            <footer class="mt-auto py-8 border-t border-gray-200/50 flex justify-between items-center opacity-40">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.4em]">KPTM BP • 2026</p>
                <p class="text-[10px] font-black text-blue-900 uppercase">AHA Studio</p>
            </footer>
        </div>
    </main>

</body>
</html>
