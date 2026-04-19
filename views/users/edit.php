<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require APP_ROOT . '/views/layouts/sidebar.php'; ?>
    <div class="main-content" id="main-content">
        <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>

        <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px;">
            <h2 class="mb-3" style="margin-bottom: 20px;">تعديل المستخدم</h2>
            
            <form action="<?php echo URL_ROOT; ?>/users/edit/<?php echo $data['user']->id; ?>" method="post">
                <?php App\Core\Csrf::field(); ?>

                <div class="form-group">
                    <label>الاسم</label>
                    <input type="text" class="form-control" value="<?php echo $data['user']->name; ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <input type="email" class="form-control" value="<?php echo $data['user']->email; ?>" disabled>
                </div>

                <div class="form-group">
                    <label>الدور</label>
                    <select name="role" class="form-control">
                        <option value="user" <?php echo ($data['user']->role == 'user') ? 'selected' : ''; ?>>مستخدم عادي</option>
                        <option value="admin" <?php echo ($data['user']->role == 'admin') ? 'selected' : ''; ?>>مدير</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>الحالة</label>
                    <div style="margin-top: 10px;">
                        <label style="display:inline-flex; align-items:center;">
                            <input type="checkbox" name="is_active" value="1" <?php echo ($data['user']->is_active) ? 'checked' : ''; ?> style="width:auto; margin-left:8px;">
                            حساب نشط (يمكنه تسجيل الدخول)
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <input type="submit" value="حفظ التعديلات" class="btn btn-primary btn-block">
                </div>
                <a href="<?php echo URL_ROOT; ?>/users" class="btn btn-block" style="background:#7f8c8d; width: 90%">إلغاء</a>
            </form>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>
