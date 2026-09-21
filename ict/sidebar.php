<aside class="w-64 glass-sidebar hidden lg:flex flex-col p-6 h-screen sticky top-0">
    <div class="flex items-center gap-3 mb-10">
        <div class="w-8 h-8 bg-blue-900 rounded-lg flex items-center justify-center shadow-lg">
            <i class="fa-solid fa-bolt text-white text-sm"></i>
        </div>
        <span class="text-lg font-extrabold tracking-tighter text-blue-900 uppercase italic">ePinjam<span class="text-amber-500">ICT</span></span>
    </div>

    <nav class="flex-1 space-y-2">
        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2 px-2">Utama</p>
        
        <a href="dashboard.php" class="flex items-center gap-3 p-3 rounded-xl transition-all <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active-link text-white font-bold' : 'text-gray-500 font-semibold hover:bg-white/50'; ?>">
            <i class="fa-solid fa-grip-vertical text-xs"></i> 
            <span class="text-sm">Dashboard</span>
        </a>

        <a href="mohon_aset.php" class="flex items-center gap-3 p-3 rounded-xl transition-all <?php echo (basename($_SERVER['PHP_SELF']) == 'mohon_aset.php') ? 'active-link text-white font-bold' : 'text-gray-500 font-semibold hover:bg-white/50'; ?>">
            <i class="fa-solid fa-plus-circle text-xs"></i> 
            <span class="text-sm">Mohon Aset</span>
        </a>

        <a href="rekod_pinjaman.php" class="flex items-center gap-3 p-3 rounded-xl transition-all <?php echo (basename($_SERVER['PHP_SELF']) == 'rekod_pinjaman.php') ? 'active-link text-white font-bold' : 'text-gray-500 font-semibold hover:bg-white/50'; ?>">
            <i class="fa-solid fa-box-archive text-xs"></i> 
            <span class="text-sm">Rekod Pinjaman</span>
        </a>
    </nav>

    <div class="pt-4 border-t border-gray-200/50">
        <a href="logout.php" class="flex items-center gap-3 p-3 rounded-xl text-red-500 font-bold text-sm hover:bg-red-50 transition-all group">
            <i class="fa-solid fa-power-off text-xs"></i> Log Keluar
        </a>
    </div>
</aside>

<style>
    .glass-sidebar {
        background: rgba(255, 255, 255, 0.45);
        backdrop-filter: blur(20px);
        border-right: 1px solid rgba(255, 255, 255, 0.5);
    }
    .active-link {
        background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);
        box-shadow: 0 8px 15px -5px rgba(30, 58, 138, 0.3);
    }
</style>