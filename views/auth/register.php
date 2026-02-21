<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-header">
        <h2>إنشاء حساب جديد</h2>
        <p>قم بتعبئة البيانات لإنشاء حساب مدير</p>
    </div>

    <form action="<?php echo URL_ROOT; ?>/auth/register" method="post" enctype="multipart/form-data">
        <?php App\Core\Csrf::field(); ?>
        <div class="form-group">
            <label>الاسم الكامل</label>
            <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>" required pattern="^[\p{L}\s]+$">
            <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
        </div>

        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>" required>
            <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
        </div>
        
        <div class="form-group">
            <label>رقم الهاتف</label>
            <input type="tel" name="phone" class="form-control <?php echo (!empty($data['phone_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['phone']; ?>" placeholder="مثال : 770000000"  pattern="^(70|71|73|77|78)[0-9]{7}$" required>
            <span class="invalid-feedback"><?php echo $data['phone_err']; ?></span>
        </div>

        <div class="form-group">
            <label>الدور</label>
            <select name="role" class="form-control">
                <option value="user">مستخدم عادي</option>
                <option value="admin">مدير</option>
            </select>
        </div>

        <div class="form-group">
            <label style="display:inline-flex; align-items:center;">
                <input type="checkbox" name="is_active" value="1" checked style="width:auto; margin-left:8px;">
                حساب نشط
            </label>
        </div>

        <div class="form-group">
            <label>الصورة الشخصية (اختياري)</label>
            <input type="file" name="image" class="form-control <?php echo (!empty($data['image_err'])) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
        </div>

        <div class="form-group">
            <label>كلمة المرور</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
            <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
        </div>

        <div class="form-group">
            <label>تأكيد كلمة المرور</label>
            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
            <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
        </div>

        <div class="form-group">
            <input type="submit" value="إضافة المستخدم" class="btn btn-block">
        </div>
    </form>
</div>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>
