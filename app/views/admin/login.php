<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | <?php echo SITENAME; ?></title>
    <link rel="icon" type="image/png" href="<?php echo URLROOT; ?>/assets/img/favicon.png">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#0a1d37',
                        brand_orange: '#ff781f'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-900 flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-10">
            <div class="inline-block p-4 bg-white rounded-2xl shadow-2xl mb-6">
                <img src="<?php echo URLROOT; ?>/assets/img/logo.png" alt="Logo" class="h-16 w-auto">
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Admin <span class="text-brand_orange">Panel</span></h1>
            <p class="text-slate-400 mt-2">Sign in to manage your delivery system</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden p-8 sm:p-10">
            <?php if(isset($data['error'])): ?>
                <div class="mb-6 bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo $data['error']; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/admin/login" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                    <div class="relative">
                        <input type="text" name="username" required 
                            class="w-full pl-4 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-brand_orange/10 focus:border-brand_orange outline-none transition-all duration-300 placeholder:text-slate-400"
                            placeholder="Enter admin username">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" required 
                            class="w-full pl-4 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-brand_orange/10 focus:border-brand_orange outline-none transition-all duration-300 placeholder:text-slate-400"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-500 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-brand_orange focus:ring-brand_orange">
                        Remember me
                    </label>
                    <a href="#" class="text-brand_orange font-bold hover:underline">Forgot password?</a>
                </div>

                <button type="submit" 
                    class="w-full py-4 bg-brand text-white font-bold rounded-2xl shadow-lg hover:shadow-brand_orange/20 hover:bg-brand-dark transition-all duration-300 flex items-center justify-center gap-3">
                    Sign In
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 text-sm mt-10">
            &copy; <?php echo date('Y'); ?> <?php echo SITENAME; ?>. Developed by Rasel Boss.
        </p>
    </div>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>
