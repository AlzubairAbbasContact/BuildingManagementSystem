<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require APP_ROOT . '/views/layouts/sidebar.php'; ?>
    <div class="main-content" id="main-content">
        <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>

        <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px;">
            <h2 class="mb-3" style="margin-bottom: 20px;">الملف الشخصي</h2>
            
            <?php App\Core\Session::flash('profile_msg'); ?>

            <div class="text-center mb-3" style="margin-bottom: 20px;">
                <?php if(!empty($data['image'])): ?>
                    <img src="<?php echo URL_ROOT; ?>/uploads/<?php echo $data['image']; ?>" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                <?php else: ?>
                    <img src="<?php echo URL_ROOT; ?>/uploads/default.png" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                <?php endif; ?>
            </div>

            <form action="<?php echo URL_ROOT; ?>/profile" method="post" enctype="multipart/form-data">
                <?php App\Core\Csrf::field(); ?>
                <div class="form-group">
                    <label>الاسم</label>
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
                    <input type="tel" name="phone" class="form-control <?php echo (!empty($data['phone_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['phone']; ?>" pattern="^(70|71|73|77|78)[0-9]{7}$" required>
                    <span class="invalid-feedback"><?php echo $data['phone_err']; ?></span>
                </div>

                <div class="form-group">
                    <label>تغيير الصورة الشخصية</label>
                    <input type="file" name="image" class="form-control <?php echo (!empty($data['image_err'])) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
                </div>

                <div class="form-group">
                    <input type="submit" value="تحديث الملف" class="btn btn-primary btn-block">
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>
