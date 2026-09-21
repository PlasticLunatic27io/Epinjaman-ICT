<?php 
require_once __DIR__ . "/dbcon.php";
include('session.php'); 

$conn = dbcon();
$session_id = $_SESSION['id']; 

// Ambil info pemohon login
$user_query = mysqli_query($conn, "SELECT pemohon_college_id FROM pemohon WHERE pemohon_id = '$session_id'");
$user_data = mysqli_fetch_array($user_query);
$college_id = $user_data['pemohon_college_id'];

// Logik Tab
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'in_process';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rekod Pinjaman | ePinjamICT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html { font-size: 13px; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #FDFCF7; color: #1F2937; height: 100vh; overflow: hidden; }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.5); }
        .tab-active { color: #1E3A8A; border-bottom: 3px solid #F59E0B; font-weight: 800; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
        td { padding: 14px 12px; background: white; vertical-align: middle; }
        td:first-child { border-radius: 12px 0 0 12px; }
        td:last-child { border-radius: 0 12px 12px 0; }
        .tr-row:hover { transform: scale(1.002); transition: all 0.2s ease; }
    </style>
</head>

<body class="flex no-scrollbar">

    <?php include('sidebar.php'); ?>

    <main class="flex-1 p-6 lg:p-8 overflow-y-auto no-scrollbar">
        
        <header class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-2xl font-black text-blue-900 tracking-tight italic">Rekod Pinjaman.</h1>
                <p class="text-gray-400 text-xs font-medium italic">Log aktiviti bagi ID: <?php echo $college_id; ?></p>
            </div>
        </header>

        <div class="flex gap-6 border-b border-gray-200 mb-6 overflow-x-auto no-scrollbar">
            <a href="?tab=in_process" class="pb-3 px-2 text-xs font-bold whitespace-nowrap <?php echo $tab == 'in_process' ? 'tab-active' : 'text-gray-400'; ?>">In Process</a>
            <a href="?tab=approved" class="pb-3 px-2 text-xs font-bold whitespace-nowrap <?php echo $tab == 'approved' ? 'tab-active' : 'text-gray-400'; ?>">Approved</a>
            <a href="?tab=pending_return" class="pb-3 px-2 text-xs font-bold whitespace-nowrap <?php echo $tab == 'pending_return' ? 'tab-active' : 'text-gray-400'; ?>">Belum Dipulangkan</a>
            <a href="?tab=returned" class="pb-3 px-2 text-xs font-bold whitespace-nowrap <?php echo $tab == 'returned' ? 'tab-active' : 'text-gray-400'; ?>">Telah Pulang</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left border-collapse">
                <thead>
                    <tr class="text-[10px] uppercase text-gray-400 font-black tracking-widest">
                        <?php if($tab == 'returned'): ?>
                            <th class="px-3">Item Tag No</th>
                            <th class="px-3">Kuantiti</th>
                            <th class="px-3 text-center">Tarikh Pulang</th>
                            <th class="px-3">Nota Kondisi</th>
                        <?php else: ?>
                            <th class="px-3">Tarikh Mohon</th>
                            <th class="px-3">Maklumat Aset</th>
                            <th class="px-3">Type</th>
                            <th class="px-3">Status</th>
                        <?php endif; ?>
                        <th class="text-center px-3">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($tab == 'returned') {
                        $query_str = "SELECT * FROM borrowing_item WHERE pemohon_college_id = '$college_id' AND status = 'Returned' ORDER BY return_date DESC";
                    } else {
                        if($tab == 'approved') {
                            // Tunjuk hanya yang masih status 'Borrowed' dalam table bi
                            $query_str = "SELECT b.* FROM borrowing b 
                                          JOIN borrowing_item bi ON b.borrow_id = bi.borrow_id 
                                          WHERE b.pemohon_college_id = '$college_id' 
                                          AND b.status = 'Lulus' 
                                          AND bi.status IN ('Borrowed','Reserved') 
                                          GROUP BY b.borrow_id 
                                          ORDER BY b.request_date DESC";
                        } 
                        elseif($tab == 'pending_return') {
                            $query_str = "SELECT b.* FROM borrowing b 
                                          JOIN borrowing_item bi ON b.borrow_id = bi.borrow_id 
                                          WHERE b.pemohon_college_id = '$college_id' 
                                          AND bi.status IN ('Borrowed','Reserved') 
                                          GROUP BY b.borrow_id 
                                          ORDER BY b.request_date DESC";
                        } 
                        else {
                            $query_str = "SELECT * FROM borrowing WHERE pemohon_college_id = '$college_id' AND status IN ('Baru','Menunggu Kelulusan') ORDER BY request_date DESC";
                        }
                    }

                    $result = mysqli_query($conn, $query_str) or die(mysqli_error($conn));
                    
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result)) {
                            $b_id = isset($row['borrow_id']) ? $row['borrow_id'] : '';
                    ?>
                        <tr class="tr-row shadow-sm">
                            <?php if($tab == 'returned'): ?>
                                <td class="text-xs font-black text-blue-900 uppercase"><?php echo $row['item_tag']; ?></td>
                                <td class="text-xs font-bold text-gray-600 text-center"><?php echo $row['item_qty']; ?></td>
                                <td class="text-[11px] font-medium text-emerald-600 text-center">
                                    <?php echo ($row['return_date'] != '0000-00-00 00:00:00') ? date('d/m/Y H:i', strtotime($row['return_date'])) : '-'; ?>
                                </td>
                                <td class="text-xs italic text-gray-500 font-medium">"<?php echo $row['notes']; ?>"</td>
                            <?php else: ?>
                                <td class="text-xs font-bold text-gray-500"><?php echo date('d/m/Y', strtotime($row['request_date'])); ?></td>
                                <td>
                                    <p class="text-xs font-black text-blue-900 uppercase"><?php echo $row['borrow_item']; ?></p>
                                    <p class="text-[9px] text-gray-400 italic"><?php echo $row['purpose']; ?></p>
                                </td>
                                <td>
                                    <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-500 text-[9px] font-black uppercase tracking-widest border border-gray-200/50">
                                        <?php echo $row['type']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php $st = $row['status']; $bg = ($st == 'Lulus') ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'; ?>
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase <?php echo $bg; ?>"><?php echo $st; ?></span>
                                </td>
                            <?php endif; ?>

                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <?php if($tab == 'pending_return') { ?>
                                        <span class="text-[10px] font-extrabold text-red-600 tracking-tighter animate-pulse">
                                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                                            PLEASE RETURN IMMEDIATELY
                                        </span>
                                    <?php } elseif($tab == 'approved' || $tab == 'returned') { ?>
                                        <a href="admin/print_borrow.php?id=<?php echo $b_id; ?>" target="_blank" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-900 flex items-center justify-center hover:bg-blue-900 hover:text-white transition-all shadow-sm group" title="Cetak Pinjaman">
                                            <i class="fa-solid fa-print text-xs"></i>
                                        </a>

                                        <?php if(isset($row['type']) && $row['type'] == "Luaran"): ?>
                                        <a href="admin/print_akuan.php?id=<?php echo $b_id; ?>" target="_blank" class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-sm group" title="Cetak Perakuan">
                                            <i class="fa-solid fa-file-contract text-xs"></i>
                                        </a>
                                        <?php endif; ?>

                                    <?php } else { ?>
                                        <i class="fa-solid fa-hourglass-half text-gray-300 animate-pulse"></i>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        } 
                    } else { ?>
                        <tr>
                            <td colspan="6" class="py-20 text-center text-gray-300 italic font-bold">Kategori ini kosong buat masa ini.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>