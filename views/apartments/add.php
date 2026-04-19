<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require APP_ROOT . '/views/layouts/sidebar.php'; ?>
    <div class="main-content" id="main-content">
        <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>

        <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px;">
            <h2 class="mb-3" style="margin-bottom: 20px;">إضافة شقة جديدة</h2>
            
            <form action="<?php echo URL_ROOT; ?>/apartments/add" method="post">
                <?php App\Core\Csrf::field(); ?>
                <div class="form-group">
                    <label>رقم الشقة</label>
                    <input type="text" name="apartment_number" class="form-control <?php echo (!empty($data['apartment_number_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['apartment_number']; ?>" required>
                    <span class="invalid-feedback"><?php echo $data['apartment_number_err']; ?></span>
                </div>

                <div class="form-group">
                    <label>الدور</label>
                    <input type="number" name="floor" class="form-control <?php echo (!empty($data['floor_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['floor']; ?>" min="0" required>
                    <span class="invalid-feedback"><?php echo $data['floor_err']; ?></span>
                </div>

                <div class="form-group">
                    <label>الحالة</label>
                    <select name="status" class="form-control">
                        <option value="vacant" <?php echo ($data['status'] == 'vacant') ? 'selected' : ''; ?>>شاغرة</option>
                        <option value="occupied" <?php echo ($data['status'] == 'occupied') ? 'selected' : ''; ?>>مؤجرة</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes" class="form-control"><?php echo $data['notes']; ?></textarea>
                </div>

                <div class="form-group">
                    <input type="submit" value="حفظ" class="btn btn-primary btn-block">
                </div>
                <a href="<?php echo URL_ROOT; ?>/apartments" class="btn btn-block" style="background:#7f8c8d; width: 90%">إلغاء</a>
            </form>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>
