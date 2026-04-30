</main>

    <!-- BOTTOM NAV -->
    <?php 
    $fullUrl = $_GET['url'] ?? 'home';
    if ($fullUrl !== 'staff/login'): 
    ?>
        <?php if (isset($_SESSION['staff_id'])): ?>
        <nav class="glass absolute bottom-0 left-0 right-0 z-50 px-10 pt-4 pb-7 flex items-center justify-around shadow-[0_-10px_40px_rgba(0,0,0,0.05)] border-t border-slate-100" style="padding-bottom: calc(1rem + env(safe-area-inset-bottom));">
            <?php 
            $currentUrl = explode('/', $fullUrl)[0];
            ?>
            <a href="<?php echo URLROOT; ?>/collect" class="flex flex-col items-center gap-1.5 <?php echo ($currentUrl == 'collect') ? 'text-brand_orange' : 'text-slate-500'; ?> transition-all hover:text-brand_orange">
                <span class="text-xl"><i class="fa-solid fa-box"></i></span>
                <span class="text-[9px] font-bold">সংগ্রহ</span>
            </a>
            <a href="<?php echo URLROOT; ?>/delivery" class="flex flex-col items-center gap-1.5 <?php echo ($currentUrl == 'delivery') ? 'text-brand_orange' : 'text-slate-500'; ?> transition-all hover:text-brand_orange">
                <span class="text-xl"><i class="fa-solid fa-truck-fast"></i></span>
                <span class="text-[9px] font-bold">ডেলিভারি</span>
            </a>
            <a href="<?php echo URLROOT; ?>/staff/logout" class="flex flex-col items-center gap-1.5 text-slate-500 transition-all hover:text-rose-500">
                <span class="text-xl"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
                <span class="text-[9px] font-bold">লগআউট</span>
            </a>
        </nav>
        <?php else: ?>
        <nav class="glass absolute bottom-0 left-0 right-0 z-50 px-10 pt-4 pb-7 flex items-center justify-between shadow-[0_-10px_40px_rgba(0,0,0,0.05)] border-t border-slate-100" style="padding-bottom: calc(1rem + env(safe-area-inset-bottom));">
            <?php 
            $currentUrl = explode('/', $fullUrl)[0];
            ?>
            
            <a href="<?php echo URLROOT; ?>/home" class="flex flex-col items-center gap-1.5 <?php echo ($currentUrl == 'home' || $currentUrl == '') ? 'text-brand_orange' : 'text-slate-500'; ?> transition-all hover:text-brand_orange">
                <span class="text-xl"><i class="fa-solid fa-house"></i></span>
                <span class="text-[9px] font-bold">হোম</span>
            </a>
            
            <a href="<?php echo URLROOT; ?>/users/profile" class="flex flex-col items-center gap-1.5 <?php echo ($currentUrl == 'users') ? 'text-brand_orange' : 'text-slate-500'; ?> transition-all hover:text-brand_orange">
                <span class="text-xl"><i class="fa-solid fa-user"></i></span>
                <span class="text-[9px] font-bold">প্রোফাইল</span>
            </a>

            <a href="<?php echo URLROOT; ?>/orders" class="flex flex-col items-center gap-1.5 <?php echo ($currentUrl == 'orders') ? 'text-brand_orange' : 'text-slate-500'; ?> transition-all hover:text-brand_orange">
                <span class="text-xl"><i class="fa-solid fa-clock-rotate-left"></i></span>
                <span class="text-[9px] font-bold">অর্ডার হিস্টোরি</span>
            </a>
        </nav>
        <?php endif; ?>
    <?php endif; ?>

    <!-- FLOATING CART BUTTON -->
    <?php if ($currentUrl == 'home' || $currentUrl == ''): ?>
    <div id="floatCart" class="absolute left-1/2 -translate-x-1/2 z-40 hidden transition-all duration-300 translate-y-10 opacity-0" style="bottom: calc(6rem + env(safe-area-inset-bottom));">
        <button onclick="openCart()" class="bg-brand hover:bg-brand-dark text-white px-5 py-3.5 rounded-md shadow-2xl shadow-brand/20 flex items-center gap-3 font-bold transform active:scale-95 transition-transform border border-white/10">
            <i class="fa-solid fa-cart-shopping"></i>
            <span id="cartTotalFloat" class="text-xs">৳0.00</span>
            <span id="cartBadge" class="bg-white text-brand rounded w-6 h-6 flex items-center justify-center text-[10px] font-bold">0</span>
        </button>
    </div>
    <?php endif; ?>

    <!-- CART PANEL -->
    <div id="cartPanel" class="absolute inset-x-0 bottom-0 z-[60] bg-white rounded-t-3xl shadow-2xl transform translate-y-full transition-transform duration-500 ease-out flex flex-col max-h-[92vh] border-t border-slate-100">
        <div class="flex justify-center p-3">
            <div class="w-12 h-1 bg-slate-200 rounded-full"></div>
        </div>
        
        <div class="px-6 py-2 flex items-center justify-between border-b border-slate-50">
            <h3 class="text-xl font-bold text-slate-900">ব্যাগ <span class="text-brand_orange">সারাংশ</span></h3>
            <button onclick="closeCart()" class="w-10 h-10 bg-slate-50 rounded-md flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <div id="cartItemsList" class="flex-1 overflow-y-auto px-6 py-4 no-scroll space-y-4">
            <!-- Items injected via JS -->
        </div>
        
        <div class="px-6 pt-8 border-t border-slate-50 space-y-5 bg-slate-50/50" style="padding-bottom: calc(2rem + env(safe-area-inset-bottom));">
            <div class="grid grid-cols-2 gap-4">
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-phone"></i></span>
                    <input id="phoneInput" type="tel" placeholder="ফোন নম্বর" class="w-full bg-white border border-slate-200 rounded-md px-10 py-3.5 text-xs focus:outline-none focus:ring-1 focus:ring-brand/40 focus:border-brand text-slate-900 placeholder:text-slate-400">
                </div>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-location-dot"></i></span>
                    <input id="addressInput" type="text" placeholder="ঠিকানা" class="w-full bg-white border border-slate-200 rounded-md px-10 py-3.5 text-xs focus:outline-none focus:ring-1 focus:ring-brand/40 focus:border-brand text-slate-900 placeholder:text-slate-400">
                </div>
            </div>
            
            <div class="bg-white p-5 rounded-md border border-slate-100 space-y-3 shadow-sm">
                <div class="flex justify-between text-[10px] text-slate-400 font-bold">
                    <span>উপ-মোট</span>
                    <span id="subtotalVal" class="text-slate-900">৳0.00</span>
                </div>
                <div class="flex justify-between text-[10px] text-brand_orange font-bold">
                    <span>ডেলিভারি ফি</span>
                    <span>৳2.00</span>
                </div>
                <div class="flex justify-between text-xl font-black text-slate-900 pt-3 border-t border-slate-50">
                    <span class="font-bold">সর্বমোট</span>
                    <span id="totalVal">৳2.00</span>
                </div>
            </div>
            
            <button onclick="checkout()" class="w-full bg-brand hover:bg-brand-dark text-white py-6 rounded-md font-bold shadow-lg shadow-brand/10 transition-all flex items-center justify-center gap-3 group">
                <span class="group-hover:translate-x-[-2px] transition-transform">অর্ডার করুন</span>
                <i class="fa-solid fa-arrow-right group-hover:translate-x-[2px] transition-transform"></i>
            </button>
        </div>
    </div>

    <!-- OVERLAY -->
    <div id="cartOverlay" onclick="closeCart()" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm z-[55] hidden opacity-0 transition-opacity duration-300"></div>

    <!-- TOAST -->
    <div id="toast" class="absolute top-24 left-1/2 -translate-x-1/2 z-[100] bg-slate-900 text-white pl-3 pr-6 py-3 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] opacity-0 transition-all duration-500 translate-y-4 pointer-events-none text-[10px] font-bold flex items-center gap-4 border border-white/10 backdrop-blur-md">
        <div id="toastImgContainer" class="w-10 h-10 rounded-xl overflow-hidden bg-white/10 flex-shrink-0 hidden">
            <img id="toastImg" src="" class="w-full h-full object-cover">
        </div>
        <div id="toastIcon" class="w-10 h-10 rounded-xl bg-brand/20 flex items-center justify-center text-brand text-lg flex-shrink-0">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <span id="toastMsg" class="whitespace-nowrap">অর্ডার সফল হয়েছে!</span>
    </div>

    <!-- CUSTOM CONFIRM POPUP -->
    <div id="confirmPopup" class="fixed inset-0 z-[110] flex items-center justify-center px-6 hidden opacity-0 transition-opacity duration-300">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeConfirm()"></div>
        <div class="relative bg-white w-full max-w-sm rounded-3xl p-8 shadow-2xl transform scale-90 transition-transform duration-300 ease-out flex flex-col items-center text-center">
            <div id="confirmIcon" class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-3xl text-brand_orange mb-6 border border-slate-100">
                <i class="fa-solid fa-question"></i>
            </div>
            <h3 id="confirmTitle" class="text-xl font-bold text-slate-900 mb-2">আপনি কি নিশ্চিত?</h3>
            <p id="confirmDesc" class="text-xs text-slate-400 font-bold leading-relaxed mb-8">এই পদক্ষেপটি অর্ডারের স্ট্যাটাস পরিবর্তন করবে।</p>
            
            <div class="grid grid-cols-2 gap-3 w-full">
                <button onclick="closeConfirm()" class="bg-slate-50 text-slate-400 py-4 rounded-md font-bold text-[10px] hover:bg-slate-100 transition-colors">বাতিল</button>
                <button id="confirmActionBtn" class="bg-brand text-white py-4 rounded-md font-bold text-xs transform active:scale-95 transition-all">নিশ্চিত করুন</button>
            </div>
        </div>
    </div>

    <!-- LOGIN MODAL -->
    <div id="loginModal" class="fixed inset-0 z-[120] flex items-end sm:items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeLoginModal()"></div>
        <div class="relative bg-white w-full max-w-sm rounded-t-3xl sm:rounded-3xl p-8 shadow-2xl transform translate-y-full sm:translate-y-0 sm:scale-90 transition-transform duration-500 ease-out flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-slate-900">লগইন</h3>
                <button onclick="closeLoginModal()" class="w-8 h-8 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-900 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form id="loginForm" onsubmit="handleLogin(event)" class="space-y-4">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider pl-1">ফোন নম্বর</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-phone"></i></span>
                        <input id="loginPhone" type="tel" required placeholder="01XXXXXXXXX" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-10 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand text-slate-900 transition-all">
                    </div>
                </div>
                
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider pl-1">পাসওয়ার্ড</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-lock"></i></span>
                        <input id="loginPassword" type="password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-10 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand text-slate-900 transition-all">
                    </div>
                </div>

                <div id="loginError" class="text-red-500 text-[10px] font-bold hidden text-center bg-red-50 py-2 rounded-lg border border-red-100"></div>
                
                <button type="submit" id="loginBtn" class="w-full bg-brand hover:bg-brand-dark text-white py-4 rounded-xl font-bold text-sm shadow-lg shadow-brand/20 transition-all flex items-center justify-center gap-2 mt-4 transform active:scale-95">
                    <span>লগইন করুন</span>
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>
        </div>
    </div>

    </div> <!-- Close App Container -->

    <!-- App Logic -->
    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
    </script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>

