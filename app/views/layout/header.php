<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title><?php echo $data['title'] ?? SITENAME; ?></title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#0a1d37', // Navy Blue from Logo
                        'brand-dark': '#061324',
                        brand_orange: '#ff781f', // Orange from Logo
                        dark: '#0f172a',
                        'dark-light': '#f1f5f9',
                        accent: '#ff781f'
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Fraunces', 'serif']
                    },
                    borderRadius: {
                        'xl': '6px',
                        '2xl': '10px',
                        '3xl': '12px'
                    }
                }
            }
        }
    </script>
    
    <style>
        html, body { height: 100%; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .no-scroll::-webkit-scrollbar { display: none; }
        .no-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        
        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(15, 23, 42, 0.05);
        }
        
        .bottom-nav-active { color: #ff781f; }
        
        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
        .animate-bounce-subtle { animation: bounce-subtle 2s infinite; }
        
        #pullIndicator {
            height: 0;
            overflow: hidden;
            transition: height 0.2s, opacity 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            opacity: 0;
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow { animation: spin-slow 3s linear infinite; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 flex justify-center min-h-[100dvh] overflow-hidden">

    <!-- App Container (Mobile Size on Desktop) -->
    <div class="w-full max-w-[480px] bg-white relative flex flex-col shadow-[0_0_100px_rgba(0,0,0,0.1)] border-x border-slate-200 h-[100dvh] overflow-hidden">

        <!-- HEADER -->
        <header class="glass absolute top-0 left-0 right-0 z-50 px-5 py-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center">
                <a href="<?php echo URLROOT; ?>" class="block">
                    <img src="<?php echo URLROOT; ?>/assets/img/logo.png" alt="PararBazar Logo" class="h-16 w-auto object-contain">
                </a>
            </div>
            <?php 
            $currentUrl = $_GET['url'] ?? 'home';
            $isStaffPage = in_array(explode('/', $currentUrl)[0], ['staff', 'collect', 'delivery']);
            if (!$isStaffPage): 
            ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo URLROOT; ?>/users/profile" class="bg-brand/10 text-brand text-[10px] font-bold px-3 py-1.5 rounded-full border border-brand/20 flex items-center gap-1.5 hover:bg-brand/20 transition-colors">
                        <i class="fa-solid fa-user"></i>
                        প্রোফাইল
                    </a>
                <?php else: ?>
                    <button onclick="openLoginModal()" class="bg-brand text-white text-[10px] font-bold px-4 py-1.5 rounded-full shadow-md shadow-brand/20 flex items-center gap-1.5 hover:bg-brand-dark transition-colors active:scale-95">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        লগইন
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        </header>

        <!-- MAIN CONTENT AREA -->
        <main id="mainContent" class="flex-1 overflow-y-auto pt-32 pb-40 px-5 no-scroll">
            <div id="pullIndicator" class="text-brand flex flex-col items-center gap-1">
                <i class="fa-solid fa-arrows-rotate animate-spin-slow"></i>
                <span class="text-[8px] font-bold">রিফ্রেশ করা হচ্ছে...</span>
            </div>
