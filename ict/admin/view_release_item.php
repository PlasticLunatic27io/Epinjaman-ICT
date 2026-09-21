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
$current_page = 'view_release';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Released Items | ePinjamICT</title>
    
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

        .table-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0,0,0,0.02);
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

        .filter-btn {
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
            border: 2px solid #F3F4F6;
            background: white;
            color: #9CA3AF;
        }

        .filter-btn.active {
            background: #1E3A8A;
            color: white;
            border-color: #1E3A8A;
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.2);
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        html { font-size: 14px; }
    </style>
</head>
<body class="flex">

    <?php include('sidebar_component.php'); ?>

    <main class="flex-1 flex flex-col overflow-hidden">
        
        <header class="h-20 glass-panel border-b border-gray-200 flex items-center justify-between px-10">
            <div class="flex items-center gap-2">
                <span class="text-xs uppercase tracking-widest font-black text-blue-900/40">Records</span>
                <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
                <h1 class="text-xs font-bold text-gray-600 italic">View Released Items</h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-black text-blue-900 leading-none"><?php echo $user_row['name']; ?></p>
                    <p class="text-[10px] font-bold text-amber-500 uppercase tracking-tighter italic">BP-ICT-2026</p>
                </div>
                <img src="<?php echo $user_row['adminthumbnails']; ?>" class="w-10 h-10 rounded-xl object-cover ring-2 ring-white shadow-md">
            </div>
        </header>

        <div class="flex-1 p-10 overflow-y-auto no-scrollbar">
            
            <div class="flex items-center justify-between mb-8">
                <div class="space-y-1">
                    <h2 class="text-2xl font-black text-blue-900 tracking-tighter uppercase italic">Released Items Log</h2>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <i class="fa-solid fa-calendar-day mr-1"></i> <?php echo date('l, F jS, Y'); ?>
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="export_release_manual.php" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl shadow-lg hover:bg-emerald-700 transition-all flex items-center gap-2 font-black text-[10px] uppercase tracking-widest">
                        <i class="fa-solid fa-file-excel"></i> Export to Excel
                    </a>

                    <div class="bg-amber-50 text-amber-600 px-4 py-2 rounded-xl border border-amber-100 font-black text-[11px] uppercase tracking-widest">
                        Records Found: <?php 
                            $st_f = isset($_GET['status']) ? $_GET['status'] : 'all';
                            $cat_f = isset($_GET['cat']) ? $_GET['cat'] : 'all';
                            
                            $count_query = "SELECT count(DISTINCT bi.borrow_item_id) as total 
                                           FROM borrowing_item bi 
                                           INNER JOIN borrowing b ON bi.borrow_id = b.borrow_id 
                                           WHERE b.status='Lulus' AND bi.status IN ('Borrowed','Returned')";
                            
                            if($st_f != 'all') { $count_query .= " AND bi.status = '$st_f'"; }
                            if($cat_f != 'all') { $count_query .= " AND b.type = '$cat_f'"; }
                            
                            $c_total = mysqli_query($conn, $count_query);
                            $c_row = mysqli_fetch_array($c_total);
                            echo $c_row['total'];
                        ?>
                    </div>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status:</span>
                    <form method="GET" class="flex gap-2">
                        <input type="hidden" name="cat" value="<?php echo isset($_GET['cat']) ? $_GET['cat'] : 'all'; ?>">
                        <button type="submit" name="status" value="all" class="filter-btn <?php echo (!isset($_GET['status']) || $_GET['status'] == 'all') ? 'active' : ''; ?>">All Status</button>
                        <button type="submit" name="status" value="Borrowed" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status'] == 'Borrowed') ? 'active' : ''; ?>">Borrowed</button>
                        <button type="submit" name="status" value="Returned" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status'] == 'Returned') ? 'active' : ''; ?>">Returned</button>
                    </form>
                </div>
                
                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Category:</span>
                    <form method="GET" class="flex gap-2">
                        <input type="hidden" name="status" value="<?php echo isset($_GET['status']) ? $_GET['status'] : 'all'; ?>">
                        <button type="submit" name="cat" value="all" class="filter-btn <?php echo (!isset($_GET['cat']) || $_GET['cat'] == 'all') ? 'active' : ''; ?>">All Types</button>
                        <button type="submit" name="cat" value="Dalaman" class="filter-btn <?php echo (isset($_GET['cat']) && $_GET['cat'] == 'Dalaman') ? 'active' : ''; ?>">Dalaman</button>
                        <button type="submit" name="cat" value="Luaran" class="filter-btn <?php echo (isset($_GET['cat']) && $_GET['cat'] == 'Luaran') ? 'active' : ''; ?>">Luaran</button>
                        <button type="submit" name="cat" value="Luaran Berkala" class="filter-btn <?php echo (isset($_GET['cat']) && $_GET['cat'] == 'Luaran Berkala') ? 'active' : ''; ?>">Recurring</button>
                    </form>
                </div>
            </div>

            <div class="table-container overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <form action="" method="post" class="w-1/3 relative group">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-blue-900 transition-colors"></i>
                        <input type="text" name="search" placeholder="Search by ID or Item..." 
                               class="w-full bg-gray-50 border-none pl-11 pr-4 py-3 rounded-2xl text-xs font-semibold focus:ring-2 focus:ring-blue-900 transition-all outline-none"
                               onChange="this.form.submit()">
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th>App ID</th>
                                <th>Personnel Info</th>
                                <th>Category</th>
                                <th>Item (Tag)</th>
                                <th>Period</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $st_f = isset($_GET['status']) ? $_GET['status'] : 'all';
                                $cat_f = isset($_GET['cat']) ? $_GET['cat'] : 'all';
                                
                                $query_string = "SELECT bi.status as item_status, bi.item_tag, b.* FROM borrowing_item bi 
                                               INNER JOIN borrowing b ON bi.borrow_id = b.borrow_id 
                                               WHERE b.status='Lulus' AND bi.status IN ('Borrowed','Returned')";
                                
                                if($st_f != 'all') { $query_string .= " AND bi.status = '$st_f'"; }
                                if($cat_f != 'all') { $query_string .= " AND b.type = '$cat_f'"; }

                                if(isset($_POST['search']) && !empty($_POST['search'])){
                                    $search = mysqli_real_escape_string($conn, $_POST['search']);
                                    $query_string .= " AND (bi.pemohon_college_id LIKE '%$search%' OR bi.item_tag LIKE '%$search%' OR b.borrow_item LIKE '%$search%')";
                                }

                                $query_string .= " ORDER BY b.approved_date DESC";
                                $release_query = mysqli_query($conn, $query_string) or die(mysqli_error($conn));
                                
                                if(mysqli_num_rows($release_query) > 0){
                                    while($row = mysqli_fetch_array($release_query)){
                                        $pemohon_id = $row['pemohon_college_id'];
                                        $p_q = mysqli_query($conn, "SELECT pemohon_name FROM pemohon WHERE pemohon_college_id = '$pemohon_id'");
                                        $p_row = mysqli_fetch_assoc($p_q);
                            ?>
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="font-mono font-bold text-blue-900">#<?php echo $row['borrow_id']; ?></td>
                                <td>
                                    <p class="font-black text-blue-900 uppercase text-xs"><?php echo $p_row['pemohon_name'] ?? 'Unknown'; ?></p>
                                    <p class="text-[10px] text-gray-400 font-mono font-bold"><?php echo $row['pemohon_college_id']; ?></p>
                                </td>
                                <td>
                                    <span class="px-2 py-1 rounded-lg bg-blue-50 text-blue-600 text-[9px] font-black uppercase"><?php echo $row['type']; ?></span>
                                </td>
                                <td>
                                    <p class="font-black text-gray-700 uppercase text-xs"><?php echo $row['borrow_item']; ?></p>
                                    <span class="text-[9px] text-gray-400 font-mono bg-gray-100 px-1 rounded"><?php echo $row['item_tag']; ?></span>
                                </td>
                                <td>
                                    <p class="text-[10px] font-bold text-emerald-600"><?php echo date('d M', strtotime($row['start_date'])); ?></p>
                                    <p class="text-[10px] font-bold text-red-400"><?php echo date('d M Y', strtotime($row['end_date'])); ?></p>
                                </td>
                                <td>
                                    <?php 
                                        $badge = ($row['item_status'] == 'Borrowed') ? 'bg-amber-50 text-amber-600 ring-amber-100' : 'bg-emerald-50 text-emerald-600 ring-emerald-100';
                                        $dot = ($row['item_status'] == 'Borrowed') ? 'bg-amber-500' : 'bg-emerald-500';
                                    ?>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full <?php echo $badge; ?> text-[9px] font-black uppercase ring-1">
                                        <span class="w-1.5 h-1.5 <?php echo $dot; ?> rounded-full"></span>
                                        <?php echo $row['item_status']; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php 
                                    } 
                                } else {
                                    echo "<tr><td colspan='6' class='text-center py-20 text-gray-400 font-bold uppercase tracking-widest text-xs'>No records found matching criteria.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer class="mt-auto py-8 border-t border-gray-200/50 flex justify-between items-center opacity-40 px-10">
            <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.4em]">KPTM BP • 2026 Log</p>
            <p class="text-[10px] font-black text-blue-900 uppercase">AHA Studio</p>
        </footer>
    </main>

</body>
</html>