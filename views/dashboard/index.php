<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<div class="wrapper">
    <!-- Sidebar -->
    <?php require APP_ROOT . '/views/layouts/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Navbar -->
        <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>

        <!-- Content -->
        <h2>لوحة التحكم</h2>
        <?php App\Core\Session::flash('login_success'); ?>

        <div class="card-grid mt-3">
            <div class="stats-card">
                <div class="stats-info">
                    <h3><?php echo $data['total_apartments']; ?></h3>
                    <p>عدد الشقق</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>

            <div class="stats-card" style="border-right-color: #2ecc71;">
                <div class="stats-info">
                    <h3><?php echo $data['vacant_apartments']; ?></h3>
                    <p>الشقق الشاغرة</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-door-open"></i>
                </div>
            </div>

            <div class="stats-card" style="border-right-color: #e74c3c;">
                <div class="stats-info">
                    <h3><?php echo $data['occupied_apartments']; ?></h3>
                    <p>الشقق المؤجرة</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-home"></i>
                </div>
            </div>

            <div class="stats-card" style="border-right-color: #f39c12;">
                <div class="stats-info">
                    <h3><?php echo $data['total_tenants']; ?></h3>
                    <p>عدد المستأجرين</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            
             <div class="stats-card" style="border-right-color: #27ae60;">
                <div class="stats-info">
                    <h3><?php echo number_format($data['total_revenue']); ?> ريال</h3>
                    <p>إجمالي المحصل</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>

             <div class="stats-card" style="border-right-color: #c0392b;">
                <div class="stats-info">
                     <h3><?php echo number_format($data['pending_payments']); ?> ريال</h3>
                    <p>المبالغ المتأخرة</p>
                </div>
                <div class="stats-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
    // Simple toggle script
    document.getElementById('toggle-sidebar').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('main-content').classList.toggle('active');
    });
</script>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>
