<?php require APPROOT . '/views/layout/header.php'; ?>

<div class="px-5">
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 text-center mb-6">
        <div class="w-20 h-20 bg-brand/10 text-brand rounded-full mx-auto flex items-center justify-center text-3xl mb-4 border-4 border-white shadow-md">
            <i class="fa-solid fa-user"></i>
        </div>
        <h2 class="text-xl font-bold text-slate-900"><?php echo $data['user']->phone ?? 'Customer'; ?></h2>
        <p class="text-sm text-slate-500 mt-1"><?php echo $data['user']->address ?? ''; ?></p>
    </div>

    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100">
        <a href="<?php echo URLROOT; ?>/orders" class="flex items-center justify-between p-5 border-b border-slate-50 hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-brand_orange/10 text-brand_orange rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <span class="font-bold text-slate-700">অর্ডার হিস্টোরি</span>
            </div>
            <i class="fa-solid fa-chevron-right text-slate-300"></i>
        </a>
        <a href="<?php echo URLROOT; ?>/users/logout" class="flex items-center justify-between p-5 hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-rose-500/10 text-rose-500 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </div>
                <span class="font-bold text-rose-500">লগআউট</span>
            </div>
            <i class="fa-solid fa-chevron-right text-slate-300"></i>
        </a>
    </div>
</div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
