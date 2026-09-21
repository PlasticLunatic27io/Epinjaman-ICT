<?php
require_once __DIR__ . "/dbcon.php";
$conn = dbcon();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ePinjamICT KPTMBP</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Fit to Screen Scaling */
        html { font-size: 14px; } /* Menurunkan skala asas teks global */

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FCF9F1;
            overflow: hidden; /* Mengelakkan scrollbar pada zoom 100% */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 20px 40px -10px rgba(45, 99, 167, 0.12);
        }

        .color-blue { color: #2D63A7; }
        .bg-blue { background-color: #2D63A7; }
        .bg-yellow { background-color: #F1A028; }

        .input-focus:focus {
            border-color: #F1A028;
            box-shadow: 0 0 0 4px rgba(241, 160, 40, 0.1);
            outline: none;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 4s ease-in-out infinite; }
    </style>
</head>

<body class="relative px-4">

    <div class="fixed top-[-5%] left-[-5%] w-80 h-80 bg-yellow rounded-full opacity-10 blur-[80px]"></div>
    <div class="fixed bottom-[-5%] right-[-5%] w-80 h-80 bg-blue rounded-full opacity-5 blur-[80px]"></div>

    <div class="w-full max-w-4xl grid lg:grid-cols-2 gap-0 glass-card rounded-[32px] overflow-hidden relative z-10 max-h-[90vh]">
        
        <div class="bg-blue p-10 hidden lg:flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
            
            <div class="relative z-10">
                <img src="images/logo.png" alt="Logo" class="h-12 mb-6 brightness-0 invert opacity-90">
                <h2 class="text-white text-3xl font-extrabold leading-tight tracking-tight">
                    Satu Hub,<br>
                    <span class="text-yellow-400">Segala Akses.</span>
                </h2>
                <p class="text-blue-100 mt-3 text-sm font-light opacity-70 leading-relaxed max-w-xs">
                    Sistem Pengurusan Aset ICT yang telus dan sistematik untuk warga KPTM Batu Pahat.
                </p>
            </div>

            <div class="relative z-10 animate-float">
                <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20 inline-block">
                    <p class="text-white text-[10px] font-semibold tracking-widest uppercase opacity-80">Version 2026.1</p>
                </div>
            </div>
        </div>

        <div class="p-10 lg:p-12 flex flex-col justify-center bg-white/50">
            <div class="mb-6 lg:hidden text-center">
                <img src="images/logo.png" alt="Logo" class="h-10 mx-auto mb-2">
            </div>

            <div class="mb-6">
                <h3 class="text-2xl font-extrabold color-blue tracking-tight">Log Masuk</h3>
                <p class="text-gray-400 text-xs mt-1">Sila masukkan kredential akaun anda.</p>
            </div>

            <form id="login_form1" class="space-y-4" method="post" action="login.php">
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 ml-1">Username / ID Kolej</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-300 text-xs">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" name="txtUserName" id="txtUserName" required
                            class="w-full pl-10 pr-4 py-3.5 bg-white border border-gray-100 rounded-xl input-focus transition-all text-sm text-gray-700"
                            placeholder="staff_123 atau ID Kolej">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-400 ml-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-300 text-xs">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="txtPassword" id="txtPassword" required
                            class="w-full pl-10 pr-4 py-3.5 bg-white border border-gray-100 rounded-xl input-focus transition-all text-sm text-gray-700"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center text-xs text-gray-400 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2"> 
                        Ingat saya
                    </label>
                    <a href="#" class="text-xs font-bold color-blue hover:text-yellow-600 transition-colors">Lupa Password?</a>
                </div>

                <button type="submit" name="login" id="signin"
                    class="w-full bg-blue text-white py-3.5 rounded-xl font-bold shadow-lg shadow-blue-200 hover:scale-[1.01] active:scale-[0.98] transition-all flex items-center justify-center gap-3 mt-4 text-sm">
                    <span>Masuk Sekarang</span>
                    <i class="fa-solid fa-arrow-right-to-bracket text-xs opacity-70"></i>
                </button>
            </form>
            <div class="mt-8 text-center">
                <p class="text-gray-400 text-xs italic">
                    Belum ada akaun? <a href="signup.php" class="color-blue font-bold hover:underline underline-offset-4 ml-1">Daftar Sini</a>
                </p>
            </div>
        </div>
    </div>

    <footer class="fixed bottom-6 w-full text-center text-[8px] uppercase tracking-[0.4em] text-gray-400 font-bold z-0 opacity-50">
        ALL RIGHT RESERVED KPTMBP POWERED BY AHA 2024
    </footer>

</body>
</html>