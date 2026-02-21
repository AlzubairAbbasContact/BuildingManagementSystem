<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-header">
        <h2>تسجيل الدخول</h2>
        <p>قم بإدخال بياناتك للدخول إلى لوحة التحكم</p>
    </div>
    
    <?php App\Core\Session::flash('register_success'); ?>

    <form action="<?php echo URL_ROOT; ?>/auth/login" method="post">
        <?php App\Core\Csrf::field(); ?>
        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
            <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
        </div>

        <div class="form-group">
            <label>كلمة المرور</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
            <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
        </div>

        <div class="form-group">
            <input type="submit" value="دخول" class="btn btn-block">
        </div>
    </form>
</div>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>
