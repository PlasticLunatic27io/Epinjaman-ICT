<?php
require_once __DIR__ . "/dbcon.php";
$conn = dbcon();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ePinjamICT | KPTM Batu Pahat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        /* Fit to Screen Scaling */
        html { font-size: 14px; } /* Global scale down */
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FCF9F1;
            height: 100vh;
            overflow: hidden;
        }

        .color-blue { color: #2D63A7; }
        .bg-blue { background-color: #2D63A7; }
        .color-yellow { color: #F1A028; }
        .bg-yellow { background-color: #F1A028; }
        
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        #contactModal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            z-index: 50;
            align-items: center;
            justify-content: center;
        }

        .hero-shape {
            position: absolute;
            bottom: 0;
            right: 0;
            z-index: -1;
            width: 45vw;
            height: 100vh;
            background: linear-gradient(135deg, transparent 25%, #2D63A7 25.1%, #2D63A7 45%, transparent 45.1%),
                        linear-gradient(45deg, transparent 65%, #27AE60 65.1%);
            opacity: 0.1;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-up { animation: fadeInUp 0.8s ease-out forwards; }
    </style>
</head>
<body class="flex flex-col">

    <div id="contactModal" onclick="closeModal(event)">
        <div class="glass p-6 rounded-[32px] shadow-2xl w-full max-w-md mx-4 animate-up border-white relative" onclick="event.stopPropagation()">
            <button onclick="document.getElementById('contactModal').style.display='none'" class="absolute top-5 right-6 text-gray-400 hover:text-red-500 font-bold">✕</button>
            <h3 class="text-xl font-extrabold color-blue mb-1">Hubungi Kami</h3>
            <p class="text-xs text-gray-500 mb-4 font-semibold tracking-wide italic">Pasukan Unit ICT KPTM Batu Pahat</p>
            <div class="space-y-4 overflow-y-auto max-h-[50vh] pr-2">
                <div class="border-l-4 border-yellow-400 pl-4 py-0.5">
                    <p class="font-bold text-gray-800 text-sm">En Azizan</p>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Penolong Pengurus</p>
                    <a href="https://wa.me/60197635704" class="text-xs color-blue font-semibold hover:underline">019-763 5704</a>
                </div>
                <div class="border-l-4 border-blue-500 pl-4 py-0.5">
                    <p class="font-bold text-gray-800 text-sm">En Fouzi</p>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Juruteknik Komputer Kanan</p>
                    <a href="https://wa.me/60137001100" class="text-xs color-blue font-semibold hover:underline">013-700 1100</a>
                </div>
                <div class="border-l-4 border-blue-500 pl-4 py-0.5">
                    <p class="font-bold text-gray-800 text-sm">En Haziq</p>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Juruteknik Komputer Kanan</p>
                    <a href="https://wa.me/60167228842" class="text-xs color-blue font-semibold hover:underline">016-722 8842</a>
                </div>
                <div class="border-l-4 border-green-500 pl-4 py-0.5">
                    <p class="font-bold text-gray-800 text-sm text-green-700">Danish Haiqal</p>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Pelajar Juruteknik Perisian</p>
                    <a href="https://wa.me/601140551729" class="text-xs color-blue font-semibold hover:underline">011-4055 1729</a>
                </div>
            </div>
            <div class="mt-6">
                <button onclick="document.getElementById('contactModal').style.display='none'" class="w-full bg-blue text-white py-3 rounded-xl font-bold shadow-lg shadow-blue-200 text-sm transition-all active:scale-95">Tutup</button>
            </div>
        </div>
    </div>

    <div class="fixed top-[-5%] left-[-5%] w-64 h-64 bg-yellow rounded-full opacity-10 blur-[80px]"></div>
    <div class="fixed bottom-0 right-0 w-full h-16 bg-blue opacity-5"></div>

    <nav class="flex justify-between items-center px-10 py-4 relative z-10">
        <div class="flex items-center gap-4">
            <img src="logokptm.png" alt="Logo KPTM" class="h-12 w-auto object-contain mix-blend-multiply">
            <div class="h-8 w-[1.5px] bg-gray-300 hidden md:block"></div>
            <div class="text-lg font-extrabold tracking-tighter flex items-center gap-1">
                <span class="color-blue uppercase italic text-base">ePinjam<span class="text-gray-400">ICT</span></span>
            </div>
        </div>
        <div class="hidden md:flex space-x-8 text-[10px] font-bold uppercase tracking-widest color-blue opacity-70">
            <a href="#" class="hover:text-yellow-600 transition-colors">Manual Pengguna</a>
            <button onclick="openModal()" class="hover:text-yellow-600 transition-colors cursor-pointer outline-none">Bantuan ICT</button>
        </div>
    </nav>

    <main class="flex-1 flex items-center px-10 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 w-full items-center">
            <div class="space-y-6 animate-up">
                <div>
                    <h2 class="text-blue-900 text-[10px] font-bold tracking-[0.4em] uppercase opacity-50 mb-2">KPTM Batu Pahat • E-PINJAM ICT</h2>
                    <h1 class="text-6xl font-extrabold leading-[1.05] color-blue">
                        Urus Pinjaman <br>
                        <span class="color-yellow">Peralatan ICT</span> <br>
                        Tanpa Kertas.
                    </h1>
                </div>
                <p class="text-gray-500 max-w-sm text-base font-light leading-relaxed">
                    Sistem pengurusan inventori dan pinjaman aset ICT Kolej Poly-Tech MARA Batu Pahat yang lebih pantas, telus, dan sistematik.
                </p>
                <div class="flex gap-4 pt-2">
                    <a href="login_form.php" class="bg-blue text-white px-8 py-4 rounded-xl font-bold shadow-xl shadow-blue-300 hover:scale-105 transition-all text-sm">Log Masuk Sistem</a>
                    <a href="signup.php" class="bg-white border border-blue-100 text-blue-700 px-8 py-4 rounded-xl font-bold hover:bg-blue-50 transition-all text-sm">Daftar Staff/Pelajar</a>
                </div>
            </div>

            <div class="hidden lg:flex justify-end relative animate-up" style="animation-delay: 0.2s;">
                <div class="glass p-8 rounded-[40px] shadow-[0_40px_80px_-15px_rgba(45,99,167,0.15)] w-72 -rotate-3 border-white">
                    <div class="flex gap-1.5 mb-6">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                    </div>
                    <div class="h-5 w-40 bg-gray-200 rounded-full mb-3"></div>
                    <div class="mt-10 py-3 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-[9px] font-black uppercase tracking-tighter color-blue">STATUS : APPROVE BY DANISH</span>
                        <div class="h-1.5 w-10 bg-yellow rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="px-10 py-6 text-[8px] uppercase tracking-[0.3em] text-gray-400 font-bold flex justify-end">
        ALL RIGHT RESERVED KPTMBP POWERED BY AHA 2024
    </footer>

    <div class="hero-shape"></div>

    <script>
        const modal = document.getElementById('contactModal');
        function openModal() { modal.style.display = 'flex'; }
        function closeModal(event) {
            if (event.target === modal) { modal.style.display = 'none'; }
        }
    </script>
</body>
</html>