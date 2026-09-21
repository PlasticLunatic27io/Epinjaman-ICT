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
$current_page = 'borrow_approved';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Applications | ePinjamICT</title>
    
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

        .pagination-link {
            @apply px-4 py-2 text-xs font-bold transition-all border border-gray-100 bg-white text-gray-500 rounded-lg mx-0.5 inline-block;
        }
        .pagination-link.active {
            @apply bg-blue-900 text-white border-blue-900 shadow-lg shadow-blue-900/20;
        }
        .pagination-link:hover:not(.active) {
            @apply bg-gray-50 border-gray-200;
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
                <h1 class="text-xs font-bold text-gray-600 italic">Approved Applications</h1>
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
            
            <div class="flex items-center justify-between mb-8">
                <div class="space-y-1">
                    <h2 class="text-2xl font-black text-blue-900 tracking-tighter uppercase italic">Success Records</h2>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <i class="fa-solid fa-calendar-day mr-1"></i> <?php echo date('l, F jS, Y'); ?>
                    </p>
                </div>

                <div class="bg-blue-50 text-blue-900 px-4 py-2 rounded-xl border border-blue-100/50 font-black text-[11px] uppercase tracking-widest">
                    Total Lulus: <?php 
                        $c_total = mysqli_query($conn,"select * from borrowing where status='Lulus'");
                        echo mysqli_num_rows($c_total);
                    ?>
                </div>
            </div>

            <div class="table-container overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <form action="" method="post" class="w-1/3 relative group">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-blue-900 transition-colors"></i>
                        <input type="text" name="search" placeholder="Search ID or Name..." 
                               class="w-full bg-gray-50 border-none pl-11 pr-4 py-3 rounded-2xl text-xs font-semibold focus:ring-2 focus:ring-blue-900 transition-all outline-none"
                               onChange="this.form.submit()">
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th>Personnel Info</th>
                                <th>Schedule</th>
                                <th>Approved By</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Logic Pagination
                                if (isset($_GET['page_no']) && $_GET['page_no']!="") { $page_no = $_GET['page_no']; } else { $page_no = 1; }
                                $total_records_per_page = 5;
                                $offset = ($page_no-1) * $total_records_per_page;
                                $adjacents = "2";

                                // Base Query
                                $query_string = "FROM borrowing WHERE status='Lulus'";
                                if(isset($_POST['search']) && !empty($_POST['search'])){
                                    $search = mysqli_real_escape_string($conn, $_POST['search']);
                                    $query_string .= " AND (pemohon_college_id LIKE '%$search%' OR borrow_item LIKE '%$search%')";
                                }

                                $result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records $query_string");
                                $total_records = mysqli_fetch_array($result_count)['total_records'];
                                $total_no_of_pages = ceil($total_records / $total_records_per_page);

                                $device_query = mysqli_query($conn,"SELECT * $query_string ORDER BY approved_date DESC LIMIT $offset, $total_records_per_page");
                                
                                if(mysqli_num_rows($device_query) > 0){
                                    while($row = mysqli_fetch_array($device_query)){
                                        $id = $row['borrow_id'];
                                        $id2= $row['pemohon_college_id'];
                                        
                                        $q_p = mysqli_query($conn, "SELECT pemohon_name FROM pemohon WHERE pemohon_college_id = '$id2'");
                                        $r_p = mysqli_fetch_assoc($q_p);
                                ?>
                                <tr class="hover:bg-gray-50/50 transition-all group">
                                    <td>
                                        <p class="font-black text-blue-900 uppercase text-xs"><?php echo $r_p['pemohon_name']; ?></p>
                                        <p class="text-[10px] text-gray-400 font-mono font-bold tracking-tighter"><?php echo $row['pemohon_college_id']; ?></p>
                                    </td>
                                    <td>
                                        <div class="space-y-1">
                                            <p class="text-[10px] font-bold text-gray-400 uppercase leading-none">Applied: <?php echo date('d M Y', strtotime($row['request_date'])); ?></p>
                                            <p class="text-[10px] font-black text-emerald-600 uppercase leading-none">Approved: <?php echo date('d M Y', strtotime($row['approved_date'])); ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-[11px] font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">
                                            <i class="fa-solid fa-user-tie mr-1 opacity-40"></i><?php echo $row['user_name']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-500 text-[9px] font-black uppercase tracking-widest border border-gray-200/50">
                                            <?php echo $row['type']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase ring-1 ring-emerald-100">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="print_borrow2.php?id=<?php echo $id; ?>" target="_blank" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-900 flex items-center justify-center hover:bg-blue-900 hover:text-white transition-all shadow-sm" title="Print Slip">
                                                <i class="fa-solid fa-print text-xs"></i>
                                            </a>
                                            <?php if($row['type'] == "Luaran"): ?>
                                            <a href="print_akuan.php?id=<?php echo $id; ?>" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Print Perakuan">
                                                <i class="fa-solid fa-file-contract text-xs"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php } 
                                } else {
                                    echo "<tr><td colspan='6' class='text-center py-20 text-gray-400 font-bold uppercase tracking-widest text-xs'>No records found.</td></tr>";
                                }
                                ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-gray-50/50 flex justify-between items-center">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Page <?php echo $page_no; ?> of <?php echo $total_no_of_pages; ?>
                    </p>
                    
                    <div class="pagination flex gap-1">
                        <a href="<?php echo ($page_no > 1) ? "?page_no=".($page_no-1) : "#"; ?>" class="pagination-link <?php if($page_no <= 1) echo 'opacity-30 pointer-events-none'; ?>"><i class="fa-solid fa-chevron-left"></i></a>

                        <?php 
                        if ($total_no_of_pages <= 7){       
                            for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
                                echo "<a href='?page_no=$counter' class='pagination-link ".($counter == $page_no ? 'active' : '')."'>$counter</a>";
                            }
                        } else {
                            echo "<a href='?page_no=1' class='pagination-link ".($page_no == 1 ? 'active' : '')."'>1</a>";
                            if($page_no > 3) echo "<span class='px-2 text-gray-300'>...</span>";
                            
                            $start = max(2, $page_no - 1);
                            $end = min($total_no_of_pages - 1, $page_no + 1);
                            for($i = $start; $i <= $end; $i++) {
                                echo "<a href='?page_no=$i' class='pagination-link ".($i == $page_no ? 'active' : '')."'>$i</a>";
                            }

                            if($page_no < $total_no_of_pages - 2) echo "<span class='px-2 text-gray-300'>...</span>";
                            echo "<a href='?page_no=$total_no_of_pages' class='pagination-link ".($page_no == $total_no_of_pages ? 'active' : '')."'>$total_no_of_pages</a>";
                        }
                        ?>

                        <a href="<?php echo ($page_no < $total_no_of_pages) ? "?page_no=".($page_no+1) : "#"; ?>" class="pagination-link <?php if($page_no >= $total_no_of_pages) echo 'opacity-30 pointer-events-none'; ?>"><i class="fa-solid fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-12 py-8 border-t border-gray-200/50 flex justify-between items-center opacity-40">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-[0.4em]">KPTM BP • 2026</p>
                <p class="text-[10px] font-black text-blue-900 uppercase">AHA Studio</p>
            </footer>
        </div>
    </main>

</body>
</html>