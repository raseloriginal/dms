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
            <div id="delivery-card-<?php echo $order->id; ?>" class="bg-white rounded-md p-3 shadow-sm border border-slate-100 space-y-2.5 transition-all">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <h4 class="text-sm font-extrabold text-slate-900">#<?php echo $order->order_no; ?></h4>
                        <?php 
                        $statusMap = [
                            'confirmed' => ['bg-blue-500/10 text-blue-500', 'সংগ্রহ করা হচ্ছে'],
                            'ready' => ['bg-brand/10 text-brand', 'প্রস্তুত']
                        ];
                        $s = $statusMap[$order->status] ?? ['bg-slate-100 text-slate-600', $order->status];
                        ?>
                        <span class="px-1.5 py-0.5 rounded text-[8px] font-bold <?php echo $s[0]; ?>">
                            <?php echo $s[1]; ?>
                        </span>
                    </div>
                    <span class="text-sm font-bold text-brand">৳<?php echo number_format($order->total, 2); ?></span>
                </div>
                
                <div class="grid grid-cols-1 gap-1.5">
                    <div class="flex gap-2 items-start bg-slate-50/70 p-2 rounded border border-slate-50">
                        <i class="fa-solid fa-location-dot text-brand text-[10px] mt-0.5"></i>
                        <p class="text-[10px] font-medium text-slate-700 leading-tight truncate"><?php echo $order->address; ?></p>
                    </div>
                    <div class="flex items-center justify-between bg-slate-50/70 p-1.5 px-2 rounded border border-slate-50">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-indigo-500 text-[10px]"></i>
                            <span class="text-[10px] font-bold text-slate-700"><?php echo $order->phone; ?></span>
                        </div>
                        <a href="tel:<?php echo $order->phone; ?>" class="bg-indigo-500 text-white w-5 h-5 rounded-full flex items-center justify-center shadow-sm shadow-indigo-500/20 active:scale-90 transition-transform">
                            <i class="fa-solid fa-phone text-[8px]"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Product Items Section -->
                <div class="pt-1.5 border-t border-slate-100">
                    <div class="space-y-1">
                        <?php foreach($order->items as $item): ?>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <?php if(isset($item->image) && $item->image): ?>
                                        <img src="<?php echo URLROOT; ?>/uploads/<?php echo $item->image; ?>" class="w-4 h-4 rounded object-cover">
                                    <?php else: ?>
                                        <div class="w-4 h-4 bg-slate-50 rounded flex items-center justify-center text-slate-400 border border-slate-100">
                                            <i class="fa-solid <?php echo isset($item->icon) ? $item->icon : 'fa-box'; ?> text-[8px]"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="text-[10px] font-medium text-slate-700 truncate max-w-[180px]"><?php echo $item->name; ?></span>
                                </div>
                                <span class="text-[9px] font-bold text-slate-500 bg-slate-50 px-1 rounded border border-slate-100">x<?php echo $item->qty; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?php if ($order->status == 'ready'): ?>
                    <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-50">
                        <button 
                            onclick="updateDeliveryStatus(<?php echo $order->id; ?>, 'delivered')"
                            class="bg-brand hover:bg-brand-dark text-white py-2 rounded font-bold shadow-sm shadow-brand/20 flex items-center justify-center gap-1.5 transition-all active:scale-95 text-[10px]"
                        >
                            <i class="fa-solid fa-check-double"></i>
                            <span>সম্পন্ন</span>
                        </button>
                        <button 
                            onclick="updateDeliveryStatus(<?php echo $order->id; ?>, 'cancelled')"
                            class="bg-slate-50 text-rose-500 border border-rose-100 py-2 rounded font-bold transition-all active:scale-95 flex items-center justify-center gap-1.5 text-[10px]"
                        >
                            <i class="fa-solid fa-xmark"></i>
                            <span>বাতিল</span>
                        </button>
                    </div>
                <?php elseif ($order->status == 'confirmed'): ?>
                    <div class="pt-1 border-t border-slate-50">
                        <div class="w-full bg-slate-50 text-slate-400 py-2 text-[10px] rounded font-bold flex items-center justify-center gap-1.5 border border-slate-100">
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
