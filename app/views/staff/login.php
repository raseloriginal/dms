<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="flex items-center justify-center min-h-[60vh] px-5">
    <div class="bg-white w-full max-w-sm rounded-3xl p-8 shadow-sm border border-slate-100">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-brand_orange/10 text-brand_orange rounded-2xl mx-auto flex items-center justify-center text-3xl mb-4">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-900">স্টাফ প্যানেল</h2>
            <p class="text-xs text-slate-500 mt-2 font-bold">লগইন করুন</p>
        </div>

        <?php if(isset($data['error'])): ?>
            <div class="bg-rose-50 text-rose-500 p-3 rounded-xl text-xs font-bold text-center mb-6 border border-rose-100">
                <?php echo $data['error']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo URLROOT; ?>/staff/login" method="POST" class="space-y-4">
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider pl-1">ইউজারনেম</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="username" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-10 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand text-slate-900 transition-all">
                </div>
            </div>
            
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider pl-1">পাসওয়ার্ড</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-10 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/20 focus:border-brand text-slate-900 transition-all">
                </div>
            </div>

            <button type="submit" class="w-full bg-brand hover:bg-brand-dark text-white py-4 rounded-xl font-bold text-sm shadow-lg shadow-brand/20 transition-all flex items-center justify-center gap-2 mt-4 transform active:scale-95">
                <span>লগইন করুন</span>
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
