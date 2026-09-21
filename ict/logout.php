<?php
session_start();

// Store user name for goodbye message (optional)
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User';

// Destroy all session data
session_unset();
session_destroy();

// Clear session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out | ePinjamICT</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --cream-bg: #FDFCF7;
            --primary-blue: #1E3A8A;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logout-container {
            background: white;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 60px;
            text-align: center;
            max-width: 500px;
            width: 90%;
            animation: fadeInScale 0.6s ease-out;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .logout-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            animation: bounceIcon 2s infinite;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        @keyframes bounceIcon {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .logout-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }

        .logout-message {
            font-size: 1rem;
            color: #6B7280;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .spinner {
            width: 50px;
            height: 50px;
            margin: 0 auto 20px;
            border: 4px solid #f3f4f6;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .redirect-text {
            font-size: 0.875rem;
            color: #9CA3AF;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Background Floating Icons */
        .float-icon {
            position: absolute;
            font-size: 2rem;
            color: rgba(255,255,255,0.1);
            animation: float 6s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }

        .float-icon:nth-child(1) { left: 10%; top: 20%; animation-delay: 0s; }
        .float-icon:nth-child(2) { right: 15%; top: 30%; animation-delay: 1s; }
        .float-icon:nth-child(3) { left: 20%; bottom: 25%; animation-delay: 2s; }
        .float-icon:nth-child(4) { right: 10%; bottom: 20%; animation-delay: 3s; }
    </style>
</head>
<body>
    
    <!-- Floating Background Icons -->
    <i class="fa-solid fa-right-from-bracket float-icon"></i>
    <i class="fa-solid fa-lock float-icon"></i>
    <i class="fa-solid fa-shield-halved float-icon"></i>
    <i class="fa-solid fa-power-off float-icon"></i>

    <div class="logout-container">
        <div class="logout-icon">
            <i class="fa-solid fa-right-from-bracket"></i>
        </div>
        
        <h1 class="logout-title">Goodbye!</h1>
        <p class="logout-message">You have been successfully logged out from ePinjamICT System.</p>
        
        <div class="spinner"></div>
        
        <p class="redirect-text">
            <i class="fa-solid fa-circle-notch fa-spin mr-2"></i>
            Redirecting to login page...
        </p>
    </div>

    <script>
        // Redirect to index/login page after 2 seconds
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 2000);
    </script>
</body>
</html>