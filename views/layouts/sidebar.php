<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3><?php echo SITE_NAME; ?></h3>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="<?php echo URL_ROOT; ?>/dashboard" class="<?php echo (isset($data['active']) && $data['active'] == 'dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> <span>الرئيسية</span>
            </a>
        </li>
        <li>
            <a href="<?php echo URL_ROOT; ?>/apartments" class="<?php echo (isset($data['active']) && $data['active'] == 'apartments') ? 'active' : ''; ?>">
                <i class="fas fa-building"></i> <span>إدارة الشقق</span>
            </a>
        </li>
        <li>
            <a href="<?php echo URL_ROOT; ?>/tenants" class="<?php echo (isset($data['active']) && $data['active'] == 'tenants') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> <span>إدارة المستأجرين</span>
            </a>
        </li>
        <li>
            <a href="<?php echo URL_ROOT; ?>/payments" class="<?php echo (isset($data['active']) && $data['active'] == 'payments') ? 'active' : ''; ?>">
                <i class="fas fa-money-bill-wave"></i> <span>المدفوعات</span>
            </a>
        </li>
        <li>
            <a href="<?php echo URL_ROOT; ?>/profile" class="<?php echo (isset($data['active']) && $data['active'] == 'profile') ? 'active' : ''; ?>">
                <i class="fas fa-user-cog"></i> <span>الملف الشخصي</span>
            </a>
        </li>
        <?php if(App\Core\Session::isAdmin()): ?>
        <li>
            <a href="<?php echo URL_ROOT; ?>/users" class="<?php echo (isset($data['active']) && $data['active'] == 'users') ? 'active' : ''; ?>">
                <i class="fas fa-users-cog"></i> <span>إدارة المستخدمين</span>
            </a>
        </li>
        <?php endif; ?>
        <li>
            <a href="<?php echo URL_ROOT; ?>/auth/logout" style="color: #e74c3c;">
                <i class="fas fa-sign-out-alt"></i> <span>تسجيل الخروج</span>
            </a>
        </li>
    </ul>
</nav>
