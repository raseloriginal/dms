<?php require APPROOT . '/views/layout/header.php'; ?>



<?php if(empty($data['orders'])): ?>
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-20 h-20 bg-slate-50 rounded-md flex items-center justify-center text-3xl text-slate-200 mb-4 border border-slate-100">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900">অর্ডারের ইতিহাস নেই</h3>
        <p class="text-slate-400 text-xs mt-1">আজই আপনার কেনাকাটা শুরু করুন</p>
        <a href="<?php echo URLROOT; ?>/home" class="mt-8 bg-brand text-white px-10 py-4 rounded-md font-bold shadow-lg shadow-brand/20">দোকানে প্রবেশ করুন</a>
    </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach($data['orders'] as $order): ?>
            <div class="bg-white rounded-md p-6 shadow-sm border border-slate-100 transition-all hover:border-brand/30" onclick='openOrderPopup(<?php echo htmlspecialchars(json_encode($order), ENT_QUOTES); ?>)'>
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400">লেনদেন আইডি</span>
                        <h4 class="text-lg font-bold text-slate-900">#<?php echo $order->order_no; ?></h4>
                    </div>
                    <?php 
                    $statusClasses = [
                        'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                        'confirmed' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                        'ready' => 'bg-brand_orange/10 text-brand_orange border-brand_orange/20',
                        'delivered' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                        'cancelled' => 'bg-rose-500/10 text-rose-500 border-rose-500/20'
                    ];
                    $statusBangla = [
                        'pending' => 'অপেক্ষমান',
                        'confirmed' => 'সংগ্রহ করা হচ্ছে',
                        'ready' => 'প্রস্তুত',
                        'delivered' => 'ডেলিভারি হয়েছে',
                        'cancelled' => 'বাতিল'
                    ];
                    ?>
                    <span class="px-3 py-1.5 rounded border text-[9px] font-bold flex items-center gap-2 <?php echo $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-400 border-slate-200'; ?>">
                        <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                        <?php echo $statusBangla[$order->status] ?? $order->status; ?>
                    </span>
                </div>
                
                <div class="space-y-4 mb-6">
                    <div class="flex items-center gap-4 text-xs text-slate-500">
                        <div class="w-8 h-8 rounded bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <span class="truncate"><?php echo $order->address; ?></span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between pt-5 border-t border-slate-50">
                    <div class="flex items-center -space-x-3">
                        <?php foreach(array_slice($order->items, 0, 4) as $item): ?>
                            <div class="w-10 h-10 rounded bg-slate-50 border-2 border-white flex items-center justify-center text-sm text-brand shadow-sm">
                                <i class="fa-solid <?php echo $item->icon; ?>"></i>
                            </div>
                        <?php endforeach; ?>
                        <?php if(count($order->items) > 4): ?>
                            <div class="w-10 h-10 rounded bg-slate-50 border-2 border-white flex items-center justify-center text-[10px] font-bold text-slate-400 shadow-sm">
                                +<?php echo count($order->items) - 4; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <span class="text-2xl font-bold text-brand_orange">$<?php echo number_format($order->total, 2); ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Order Details Popup -->
<div id="orderPopup" class="fixed inset-x-0 bottom-0 z-[70] bg-white rounded-t-3xl shadow-2xl transform translate-y-full transition-transform duration-500 ease-out flex flex-col max-h-[85vh] border-t border-slate-100">
    <div class="flex justify-center p-3">
        <div class="w-12 h-1 bg-slate-200 rounded-full"></div>
    </div>
    
    <div class="px-6 py-2 flex items-center justify-between border-b border-slate-50">
        <div>
            <h3 id="popupTitle" class="text-xl font-bold text-slate-900">অর্ডার #0001</h3>
            <p id="popupDate" class="text-[9px] text-slate-400 font-bold mt-1">APR 29, 2026</p>
        </div>
        <button onclick="closeOrderPopup()" class="w-10 h-10 bg-slate-50 rounded-md flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors"><i class="fa-solid fa-xmark"></i></button>
    </div>
    
    <div class="flex-1 overflow-y-auto px-6 py-6 no-scroll space-y-6">
        <!-- Customer Info -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-slate-50 p-4 rounded-md border border-slate-100">
                <span class="text-[9px] font-bold text-slate-400 block mb-1">ফোন</span>
                <p id="popupPhone" class="text-xs font-bold text-slate-900 tracking-wider">0123456789</p>
            </div>
            <div class="bg-slate-50 p-4 rounded-md border border-slate-100">
                <span class="text-[9px] font-bold text-slate-400 block mb-1">অবস্থা</span>
                <p id="popupStatus" class="text-xs font-bold text-brand_orange">Pending</p>
            </div>
            <div class="bg-slate-50 p-4 rounded-md border border-slate-100 col-span-2">
                <span class="text-[9px] font-bold text-slate-400 block mb-1">গন্তব্য</span>
                <p id="popupAddress" class="text-xs font-bold text-slate-900">123 Street, City</p>
            </div>
        </div>

        <!-- Items -->
        <div>
            <h4 class="text-[10px] font-bold text-slate-500 mb-4">পণ্য সংগ্রহ করুন</h4>
            <div id="popupItems" class="space-y-3">
                <!-- Injected via JS -->
            </div>
        </div>
    </div>
    
    <div class="px-6 py-8 border-t border-slate-50 bg-slate-50/30 flex items-center justify-between">
        <div class="flex flex-col">
            <span class="text-[9px] font-bold text-slate-400">সর্বমোট</span>
            <span id="popupTotal" class="text-3xl font-bold text-brand_orange">$0.00</span>
        </div>
        <div class="flex gap-2">
            <button onclick="callCustomer()" class="w-12 h-12 bg-white border border-slate-100 rounded-md flex items-center justify-center text-brand shadow-sm"><i class="fa-solid fa-phone"></i></button>
            <button id="btnConfirm" class="bg-brand text-white px-8 py-3 rounded-md font-bold shadow-lg shadow-brand/20">অনুমোদন</button>
            <button id="btnCancel" class="bg-rose-500/10 text-rose-500 px-6 py-3 rounded-md font-bold text-xs">প্রত্যাখ্যান</button>
        </div>
    </div>
</div>

<div id="popupOverlay" onclick="closeOrderPopup()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[65] hidden opacity-0 transition-opacity duration-300"></div>

<?php require APPROOT . '/views/layout/footer.php'; ?>
