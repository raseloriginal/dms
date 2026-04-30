<?php require APPROOT . '/views/layout/header.php'; ?>



<?php if(empty($data['items'])): ?>
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-20 h-20 bg-slate-50 rounded-md flex items-center justify-center text-3xl text-slate-200 mb-4 border border-slate-100">
            <i class="fa-solid fa-box"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900">সংগ্রহের জন্য কোনো পণ্য নেই</h3>
        <p class="text-slate-400 text-xs mt-1">নিশ্চিত করা অর্ডারের জন্য অপেক্ষা করা হচ্ছে</p>
    </div>
<?php else: ?>
    <!-- Progress Indicator -->
    <div class="bg-white rounded-md p-4 mb-6 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-bold text-slate-400">সংগ্রহের অগ্রগতি</span>
            <span id="progressText" class="text-[10px] font-bold text-brand">0/<?php echo count($data['items']); ?> সংগ্রহ হয়েছে</span>
        </div>
        <div class="w-full h-2 bg-slate-50 rounded-full overflow-hidden">
            <div id="manifestProgress" class="h-full bg-brand w-0 transition-all duration-500"></div>
        </div>
    </div>

    <div class="space-y-4 pb-24">
        <?php foreach($data['items'] as $item): ?>
            <div class="manifest-card bg-white rounded-md p-5 shadow-sm border border-slate-100 flex items-center gap-6 transition-all hover:border-brand/20" data-id="<?php echo $item->id; ?>">
                <div class="w-16 h-16 bg-slate-50 rounded flex items-center justify-center text-3xl text-brand flex-shrink-0">
                    <i class="fa-solid <?php echo $item->icon; ?>"></i>
                </div>
                
                <div class="flex-1 min-w-0">
                    <h4 class="text-lg font-bold text-slate-900 truncate"><?php echo $item->name; ?></h4>
                    <p class="text-[9px] font-bold text-slate-400 mt-1">Sourced from <?php echo $item->orderCount; ?> units</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-2xl font-bold text-brand"><?php echo $item->totalQty; ?></span>
                        <span class="text-[10px] font-bold text-slate-400">মোট পিস</span>
                    </div>
                </div>
                
                <button onclick="toggleManifestItem(<?php echo $item->id; ?>, this)" class="check-btn w-12 h-12 bg-slate-50 rounded flex items-center justify-center text-slate-300 transition-all active:scale-90 shadow-inner">
                    <i class="fa-solid fa-check"></i>
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Confirmed Orders Section -->
    <div class="mt-12 mb-8">
        <h4 class="text-[10px] font-bold text-slate-400 mb-4">অপেক্ষমান অর্ডারসমূহ</h4>
        <div class="space-y-3">
            <?php foreach($data['orders'] as $order): 
                $productIds = array_map(function($item) { return $item->product_id; }, $order->items);
            ?>
                <div class="order-queue-card bg-slate-50 rounded-md p-4 border border-slate-100 flex items-center justify-between" 
                     data-order-id="<?php echo $order->id; ?>" 
                     data-products='<?php echo json_encode($productIds); ?>'>
                    <div>
                        <h5 class="text-xs font-bold text-slate-900">Order #<?php echo $order->order_no; ?></h5>
                        <p class="text-[9px] text-slate-400 font-bold mt-0.5"><?php echo $order->address; ?></p>
                    </div>
                    <div class="order-status-badge flex items-center gap-2">
                        <div class="status-dot w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                        <span class="status-text text-[8px] font-bold text-blue-500">সংগ্রহ করা হচ্ছে</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Completion Action -->
    <div id="btnCompleteManifest" class="fixed bottom-24 left-6 right-6 z-50 transform translate-y-10 opacity-0 transition-all duration-500 hidden">
        <button onclick="completeManifest()" class="w-full bg-slate-900 text-white py-5 rounded-md font-bold shadow-2xl flex items-center justify-center gap-3">
            <i class="fa-solid fa-truck-ramp-box text-brand"></i>
            সংগ্রহ সম্পন্ন করুন
        </button>
    </div>
<?php endif; ?>

<?php require APPROOT . '/views/layout/footer.php'; ?>
