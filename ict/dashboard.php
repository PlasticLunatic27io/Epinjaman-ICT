<?php
require_once __DIR__ . "/dbcon.php";
include('session.php'); 

$conn = dbcon(); 
$session_id = $_SESSION['id'];

$query = mysqli_query($conn, "SELECT * FROM pemohon WHERE pemohon_id = '$session_id'") or die(mysqli_error($conn));
$row = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | ePinjamICT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-blue: #1E3A8A;
            --accent-yellow: #F59E0B;
            --soft-white: #FAF9F6;
            --glass: rgba(255, 255, 255, 0.75);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(at top left, #FDFCF7 0%, #F3F4F6 100%);
            color: #1F2937;
            height: 100vh;
        }

        .mesh-gradient {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(at 0% 0%, rgba(245, 158, 11, 0.05) 0, transparent 40%),
                radial-gradient(at 100% 100%, rgba(30, 58, 138, 0.05) 0, transparent 40%);
            z-index: -1;
        }

        .glass-sidebar {
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.5);
        }

        .aesthetic-card {
            background: var(--glass);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }

        .aesthetic-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.08);
        }

        .active-link {
            background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
            box-shadow: 0 8px 15px -5px rgba(30, 58, 138, 0.3);
        }

        /* Unit scaling for better fit */
        html { font-size: 14px; } /* Global font scale down */

        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="flex no-scrollbar overflow-hidden">

    <div class="mesh-gradient"></div>

    <aside class="w-64 glass-sidebar hidden lg:flex flex-col p-6 h-screen sticky top-0">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-8 h-8 bg-blue-900 rounded-lg flex items-center justify-center shadow-lg">
                <i class="fa-solid fa-bolt text-white text-sm"></i>
            </div>
            <span class="text-lg font-extrabold tracking-tighter text-blue-900 uppercase italic">ePinjam<span class="text-amber-500">ICT</span></span>
        </div>

        <nav class="flex-1 space-y-2">
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 px-2">Utama</p>
            <a href="dashboard.php" class="active-link flex items-center gap-3 p-3 rounded-xl text-white font-bold text-sm transition-all">
                <i class="fa-solid fa-grip-vertical text-xs"></i> Dashboard
            </a>
            <a href="mohon_aset.php" class="flex items-center gap-3 p-3 rounded-xl text-gray-500 font-semibold text-sm hover:bg-white/50 transition-all">
                <i class="fa-solid fa-plus-circle text-xs"></i> Mohon Aset
            </a>
            <a href="rekod_pinjaman.php" class="flex items-center gap-3 p-3 rounded-xl text-gray-500 font-semibold text-sm hover:bg-white/50 transition-all">
                <i class="fa-solid fa-box-archive text-xs"></i> Rekod Pinjaman
            </a>
        </nav>

        <div class="pt-4 border-t border-gray-200/50">
            <a href="logout.php" class="flex items-center gap-3 p-3 rounded-xl text-red-500 font-bold text-sm hover:bg-red-50 transition-all group">
                <i class="fa-solid fa-power-off text-xs"></i> Log Keluar
            </a>
        </div>
    </aside>

    <main class="flex-1 p-6 lg:p-8 overflow-y-auto no-scrollbar">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-black text-blue-900 tracking-tight">Katalog Pintar.</h1>
                <p class="text-gray-400 text-xs font-medium">Hello, <span class="text-blue-600 font-bold"><?php echo $row['pemohon_name']; ?></span>.</p>
            </div>
            
            <div class="flex items-center gap-3 p-1.5 pr-4 bg-white/60 rounded-2xl border border-white shadow-sm backdrop-blur-md">
                <div class="w-10 h-10 bg-gradient-to-tr from-blue-900 to-blue-600 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-md">
                    <?php echo substr($row['pemohon_name'], 0, 1); ?>
                </div>
                <div>
                    <p class="text-[9px] font-black text-blue-900 uppercase tracking-widest leading-none"><?php echo $row['pemohon_college_id']; ?></p>
                    <p class="text-[8px] font-bold text-amber-500 uppercase"><?php echo $row['pemohon_position']; ?></p>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            
            <div class="xl:col-span-2 aesthetic-card rounded-[32px] p-8 overflow-hidden relative">
                <span class="absolute -bottom-6 -right-6 text-[80px] font-black text-blue-900/5 select-none uppercase">Profile</span>
                
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                    <h2 class="text-xl font-extrabold text-blue-900">Butiran Perkhidmatan</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 relative z-10">
                    <div class="space-y-0.5">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.1em]">Unit Jabatan</p>
                        <p class="text-base font-bold text-blue-900"><?php echo $row['pemohon_dept']; ?></p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.1em]">Hubungan</p>
                        <p class="text-base font-bold text-blue-900"><?php echo $row['pemohon_no_tel']; ?></p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.1em]">Status Akaun</p>
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full border border-emerald-100">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                            <span class="text-[10px] font-bold uppercase tracking-wider"><?php echo $row['pemohon_status']; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-blue-900 rounded-[32px] p-8 shadow-xl shadow-blue-900/20 relative overflow-hidden group min-h-[220px]">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 group-hover:scale-125 transition-transform"></div>
                
                <div class="relative z-10 h-full flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center mb-4 shadow-md">
                            <i class="fa-solid fa-paper-plane text-white text-sm"></i>
                        </div>
                        <h3 class="text-lg font-bold text-white leading-tight">Mula Pinjaman <br> Baru.</h3>
                        <p class="text-blue-200/70 text-[11px] mt-2 leading-relaxed">
                            Proses digital 100% tanpa kertas.
                        </p>
                    </div>
                    
                    <a href="mohon_aset.php" class="mt-4 w-full bg-white py-3 rounded-xl text-center font-black text-blue-900 text-[10px] uppercase tracking-widest hover:bg-amber-500 hover:text-white transition-all">
                        Mohon Sekarang
                    </a>
                </div>
            </div>

            <div class="xl:col-span-3 aesthetic-card rounded-[32px] p-8">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-blue-900 rounded-full"></div>
                        <h2 class="text-xl font-extrabold text-blue-900">Aktiviti Semasa</h2>
                    </div>
                    <a href="#" class="text-[10px] font-bold text-blue-600 hover:text-amber-500 uppercase tracking-widest">Lihat Semua</a>
                </div>

                <div class="flex flex-col items-center justify-center py-10 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                    <i class="fa-solid fa-inbox text-gray-200 text-2xl mb-3"></i>
                    <p class="text-gray-400 font-bold italic text-xs">Tiada rekod pinjaman aktif buat masa ini.</p>
                </div>
            </div>

        </div>

        <footer class="mt-10 py-6 border-t border-gray-200/30 flex flex-col md:flex-row justify-between items-center gap-2">
            <p class="text-[8px] uppercase tracking-[0.3em] text-gray-400 font-bold">
                KPTM BP • AHA 2026
            </p>
            <div class="flex gap-4 text-[8px] font-black text-blue-900/30 uppercase tracking-widest">
                <a href="#">Made with love</a>
                <a href="#">Danish</a>
            </div>
        </footer>
    </main>

</body>
</html>