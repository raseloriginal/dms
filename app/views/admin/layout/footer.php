        </main>
        
        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 py-4 px-8 flex justify-between items-center text-xs text-slate-400">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITENAME; ?> Delivery Management System. All rights reserved.</p>
            <div class="flex gap-4">
                <a href="#" class="hover:text-brand">Privacy Policy</a>
                <a href="#" class="hover:text-brand">Terms of Service</a>
            </div>
        </footer>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');
    const adminSidebar = document.getElementById('adminSidebar');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');

    function toggleSidebar() {
        if (!adminSidebar) return;
        
        const isClosed = adminSidebar.classList.contains('-translate-x-full');
        
        if (isClosed) {
            // Open sidebar
            adminSidebar.classList.remove('-translate-x-full');
            
            // Show backdrop
            if (sidebarBackdrop) {
                sidebarBackdrop.classList.remove('hidden');
                // Small delay for transition
                setTimeout(() => {
                    sidebarBackdrop.classList.remove('opacity-0');
                }, 10);
            }
        } else {
            // Close sidebar
            adminSidebar.classList.add('-translate-x-full');
            
            // Hide backdrop
            if (sidebarBackdrop) {
                sidebarBackdrop.classList.add('opacity-0');
                setTimeout(() => {
                    sidebarBackdrop.classList.add('hidden');
                }, 300); // Matches transition duration
            }
        }
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleSidebar);
    }

    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', toggleSidebar);
    }

    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', toggleSidebar);
    }
});
</script>

</body>
</html>
