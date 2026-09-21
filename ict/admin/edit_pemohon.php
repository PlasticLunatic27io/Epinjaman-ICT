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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personnel Management | ePinjamICT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { --cream-bg: #FDFCF7; --macos-glass: rgba(255, 255, 255, 0.65); --primary-blue: #1E3A8A; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--cream-bg); color: #1F2937; overflow: hidden; height: 100vh; }
        .glass-panel { background: var(--macos-glass); backdrop-filter: blur(20px) saturate(180%); border: 1px solid rgba(255, 255, 255, 0.7); }
        .table-container { background: white; border-radius: 24px; border: 1px solid rgba(0,0,0,0.02); }
        
        /* FIX MODAL MENYEMAK: Paksa dia sorok dulu */
        .modal { display: none; position: fixed; z-index: 9999; left: 50%; top: 50%; transform: translate(-50%, -50%); width: 400px; }
        .modal.in { display: block; }
        .modal-backdrop { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.2); backdrop-filter: blur(4px); z-index: 9998; }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="flex">

    <aside class="w-72 glass-panel h-screen flex flex-col p-6 z-50">
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-9 h-9 bg-blue-900 rounded-xl flex items-center justify-center shadow-lg transform rotate-3"><i class="fa-solid fa-bolt text-white text-sm"></i></div>
            <span class="text-xl font-black text-blue-900 uppercase italic">ePinjam<span class="text-amber-500">ICT</span></span>
        </div>
        <nav class="flex-1 space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 p-3 rounded-xl text-gray-500 font-semibold hover:bg-white/50 transition-all"><i class="fa-solid fa-grid-2 text-xs"></i> Dashboard</a>
            <a href="item.php" class="flex items-center gap-3 p-3 text-gray-500 font-semibold rounded-xl hover:bg-white/50 transition-all"><i class="fa-solid fa-laptop text-xs"></i> All Items</a>
            <a href="pemohon.php" class="flex items-center gap-3 p-3 bg-white shadow-sm rounded-xl text-blue-900 font-bold border border-blue-100/50"><i class="fa-solid fa-users text-xs"></i> Staff & Students</a>
        </nav>
        <div class="pt-6 border-t border-gray-200/50">
            <a href="logout.php" class="flex items-center gap-3 p-3 rounded-xl text-red-500 font-bold text-sm hover:bg-red-50 transition-all"><i class="fa-solid fa-power-off text-xs"></i> Log Out</a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-20 glass-panel border-b border-gray-200 flex items-center justify-between px-10">
            <h1 class="text-xl font-black text-blue-900 tracking-tight italic"> personnel records.</h1>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-black text-blue-900 leading-none"><?php echo $user_row['name']; ?></p>
                    <p class="text-[10px] font-bold text-amber-500 uppercase tracking-tighter">Admin Access</p>
                </div>
                <img src="<?php echo $user_row['adminthumbnails']; ?>" class="w-10 h-10 rounded-xl object-cover ring-2 ring-white shadow-md">
            </div>
        </header>

        <div class="flex-1 p-10 overflow-y-auto no-scrollbar">
            <div class="flex items-center justify-between mb-8">
                <a href="add_pemohon.php" class="bg-blue-900 text-white px-6 py-3 rounded-2xl text-xs font-bold shadow-lg hover:bg-blue-800 flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle"></i> Add New Person
                </a>
            </div>

            <div class="table-container p-6">
                <form action="delete_pemohon.php" method="post">
                    <div class="flex justify-between mb-6">
                        <button type="button" onclick="openModal()" class="text-red-500 text-[11px] font-black uppercase hover:bg-red-50 px-4 py-2 rounded-xl transition-all flex items-center gap-2 border border-red-100">
                            <i class="fa-solid fa-trash-can"></i> Delete Selected
                        </button>
                    </div>

                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] uppercase text-gray-400 font-black tracking-widest border-b border-gray-100">
                                <th class="pb-4 w-10"></th>
                                <th class="pb-4">Name</th>
                                <th class="pb-4">College ID</th>
                                <th class="pb-4">Position</th>
                                <th class="pb-4">Status</th>
                                <th class="pb-4 text-center">Modify</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $pemohon_query = mysqli_query($conn,"select * from pemohon")or die(mysqli_error($conn));
                            while($row = mysqli_fetch_array($pemohon_query)){
                                $id = $row['pemohon_id'];
                            ?>
                            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-all">
                                <td class="py-4"><input type="checkbox" name="selector[]" value="<?php echo $id; ?>" class="rounded text-blue-900"></td>
                                <td class="py-4"><p class="font-black text-blue-900 uppercase text-xs"><?php echo $row['pemohon_name']; ?></p></td>
                                <td class="py-4 font-mono font-bold text-[10px] text-gray-500"><?php echo $row['pemohon_college_id']; ?></td>
                                <td class="py-4 text-[11px] font-bold text-gray-600"><?php echo $row['pemohon_position']; ?></td>
                                <td class="py-4">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase <?php echo ($row['pemohon_status'] == 'Active') ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'; ?>">
                                        <?php echo $row['pemohon_status']; ?>
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <a href="edit_pemohon.php<?php echo '?id='.$id; ?>" class="text-blue-900 hover:text-amber-500"><i class="fa-solid fa-pen-to-square"></i></a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <div id="deleteModal" class="modal">
                        <div class="bg-white rounded-[30px] p-10 shadow-2xl text-center border border-gray-100">
                            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-blue-900 tracking-tighter uppercase">Hapus Rekod?</h3>
                            <p class="text-gray-400 text-xs font-medium mt-2">Tindakan ini tidak boleh diundur. Semua data yang dipilih akan dibuang.</p>
                            <div class="flex gap-4 mt-8">
                                <button type="button" onclick="closeModal()" class="flex-1 py-3 text-[10px] font-black uppercase text-gray-400 hover:bg-gray-50 rounded-xl transition-all">Batal</button>
                                <button name="delete_pemohon" class="flex-1 py-3 bg-red-500 text-white rounded-xl text-[10px] font-black uppercase shadow-lg shadow-red-200 hover:bg-red-600">Ya, Hapus</button>
                            </div>
                        </div>
                    </div>
                    <div id="backdrop" class="modal-backdrop hidden" onclick="closeModal()"></div>
                </form>
            </div>
        </div>
    </main>

    <script>
        function openModal() {
            document.getElementById('deleteModal').classList.add('in');
            document.getElementById('backdrop').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('deleteModal').classList.remove('in');
            document.getElementById('backdrop').classList.add('hidden');
        }
    </script>
</body>
</html>