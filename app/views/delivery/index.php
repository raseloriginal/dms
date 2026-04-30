<?php require APPROOT . '/views/layout/header.php'; ?>



<?php if(empty($data['orders'])): ?>
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-20 h-20 bg-slate-50 rounded-md flex items-center justify-center text-3xl text-slate-200 mb-4 border border-slate-100">
            <i class="fa-solid fa-truck"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900">ডেলিভারি লিস্ট খালি</h3>
        <p class="text-slate-400 text-xs mt-1">কোনো সক্রিয় ডেলিভারি নেই</p>
    </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach($data['orders'] as $order): ?>
            <div id="delivery-card-<?php echo $order->id; ?>" class="bg-white rounded-md p-6 shadow-sm border border-slate-100 space-y-6 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400">ডেলিভারি আইডি</span>
                        <div class="flex items-center gap-3">
                            <h4 class="text-lg font-bold text-slate-900">#<?php echo $order->order_no; ?></h4>
                            <?php 
                            $statusMap = [
                                'confirmed' => ['bg-blue-500/10 text-blue-500', 'সংগ্রহ করা হচ্ছে'],
                                'ready' => ['bg-brand/10 text-brand', 'প্রস্তুত']
                            ];
                            $s = $statusMap[$order->status] ?? ['bg-slate-100', $order->status];
                            ?>
                            <span class="px-2 py-0.5 rounded text-[8px] font-bold <?php echo $s[0]; ?>">
                                <?php echo $s[1]; ?>
                            </span>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-brand">৳<?php echo number_format($order->total, 2); ?></span>
                </div>
                
                <div class="space-y-4">
                    <div class="flex gap-4 p-4 bg-slate-50 rounded border border-slate-100">
                        <div class="w-10 h-10 bg-white rounded flex items-center justify-center text-brand shadow-sm flex-shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[9px] font-bold text-slate-400 block mb-1">গন্তব্য</span>
                            <p class="text-xs font-bold text-slate-900 leading-relaxed truncate"><?php echo $order->address; ?></p>
                        </div>
                    </div>
                    
                    <div class="flex gap-4 p-4 bg-slate-50 rounded border border-slate-100">
                        <div class="w-10 h-10 bg-white rounded flex items-center justify-center text-indigo-500 shadow-sm flex-shrink-0">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 block mb-1">যোগাযোগের নম্বর</span>
                            <a href="tel:<?php echo $order->phone; ?>" class="text-xs font-bold text-slate-900"><?php echo $order->phone; ?></a>
                        </div>
                    </div>
                </div>
                
                <?php if ($order->status == 'ready'): ?>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <button 
                            onclick="updateDeliveryStatus(<?php echo $order->id; ?>, 'delivered')"
                            class="bg-brand hover:bg-brand-dark text-white py-4 rounded-md font-bold shadow-lg shadow-brand/20 flex items-center justify-center gap-2 transition-all active:scale-95"
                        >
                            <i class="fa-solid fa-check-double"></i>
                            <span>সম্পন্ন</span>
                        </button>
                        <button 
                            onclick="updateDeliveryStatus(<?php echo $order->id; ?>, 'cancelled')"
                            class="bg-slate-50 text-rose-500 border border-rose-100 py-4 rounded-md font-bold transition-all active:scale-95 flex items-center justify-center gap-2 text-xs"
                        >
                            <i class="fa-solid fa-xmark"></i>
                            <span>বাতিল</span>
                        </button>
                    </div>
                <?php elseif ($order->status == 'confirmed'): ?>
                    <div class="pt-2">
                        <div class="w-full bg-slate-50 text-slate-400 py-4 rounded-md font-bold flex items-center justify-center gap-2 border border-slate-100">
                            <i class="fa-solid fa-hourglass-half animate-pulse"></i>
                            <span>সংগ্রহের জন্য অপেক্ষা করছে</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require APPROOT . '/views/layout/footer.php'; ?>
