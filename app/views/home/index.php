<?php require APPROOT . '/views/layout/header.php'; ?>



<!-- Search -->
<div class="relative mb-8">
    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
        <i class="fa-solid fa-magnifying-glass"></i>
    </span>
    <input 
        id="searchInput" 
        type="text" 
        placeholder="পণ্য খুঁজুন..." 
        class="w-full bg-slate-50 border border-slate-100 shadow-sm rounded-md px-12 py-4 text-sm text-slate-900 focus:ring-1 focus:ring-brand/40 focus:outline-none transition-all placeholder:text-slate-400"
        oninput="filterProducts()"
    >
</div>

<!-- Categories -->
<div class="flex gap-3 overflow-x-auto pb-4 no-scroll -mx-1 px-1 mb-2">
    <?php 
    $cats = ['সব', 'ফল', 'সবজি', 'ডেইরি', 'বেকারি', 'পানীয়'];
    $icons = ['fa-border-all', 'fa-apple-whole', 'fa-carrot', 'fa-cheese', 'fa-bread-slice', 'fa-mug-hot'];
    foreach($cats as $i => $cat): 
    ?>
    <button 
        class="cat-chip whitespace-nowrap px-6 py-2.5 rounded-md text-[10px] font-bold transition-all flex items-center gap-2 <?php echo ($cat == 'সব' || $cat == 'All') ? 'bg-brand_orange text-white shadow-lg shadow-brand_orange/20' : 'bg-slate-50 text-slate-400 border border-slate-100 hover:border-brand_orange/30'; ?>"
        onclick="setCategory('<?php echo $cat; ?>', this)"
    >
        <i class="fa-solid <?php echo $icons[$i]; ?>"></i>
        <?php echo $cat; ?>
    </button>
    <?php endforeach; ?>
</div>

<!-- Product Grid -->
<div id="productGrid" class="grid grid-cols-2 gap-4 mt-2">
    <?php foreach($data['products'] as $product): ?>
    <div class="product-card bg-white rounded-md p-4 shadow-sm border border-slate-100 flex flex-col gap-4 group transition-all hover:border-brand/40" data-category="<?php echo $product->category; ?>" data-name="<?php echo strtolower($product->name); ?>">
        <div class="aspect-square bg-slate-50 rounded overflow-hidden flex items-center justify-center text-4xl text-brand transition-transform group-hover:scale-105 border border-slate-100">
            <?php if($product->image): ?>
                <img src="<?php echo URLROOT; ?>/uploads/<?php echo $product->image; ?>" alt="<?php echo $product->name; ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <i class="fa-solid <?php echo $product->icon; ?>"></i>
            <?php endif; ?>
        </div>
        <div class="flex-1 space-y-1">
            <div class="flex justify-between items-start gap-2">
                <h3 class="text-xs font-bold text-slate-900 line-clamp-2 flex-1"><?php echo $product->name; ?></h3>
                <span class="text-sm font-bold text-brand_orange flex-shrink-0">৳<?php echo number_format($product->price, 2); ?></span>
            </div>
            <p class="text-[8px] font-bold text-slate-400"><?php echo $product->category; ?></p>
        </div>
        
        <div id="ctrl-<?php echo $product->id; ?>" class="mt-auto">
            <button 
                onclick="addToCart({id: <?php echo $product->id; ?>, name: '<?php echo $product->name; ?>', price: <?php echo $product->price; ?>, icon: '<?php echo $product->icon; ?>', image: '<?php echo $product->image; ?>'})"
                class="w-full h-11 bg-brand hover:bg-brand-dark text-white rounded flex items-center justify-center gap-2 transition-all active:scale-95 shadow-lg shadow-brand/20 font-bold text-[10px]"
            >
                <i class="fa-solid fa-plus"></i>
                ব্যাগে যোগ করুন
            </button>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
    // Embedded data for JS logic
    const ALL_PRODUCTS = <?php echo json_encode($data['products']); ?>;
</script>

<?php require APPROOT . '/views/layout/footer.php'; ?>
