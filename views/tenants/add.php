<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require APP_ROOT . '/views/layouts/sidebar.php'; ?>
    <div class="main-content" id="main-content">
        <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>

        <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px;">
            <h2 class="mb-3" style="margin-bottom: 20px;">تسجيل مستأجر جديد</h2>
            
            <form action="<?php echo URL_ROOT; ?>/tenants/add" method="post">
                <?php App\Core\Csrf::field(); ?>
                <div class="form-group">
                    <label>الشقة</label>
                    <select name="apartment_id" class="form-control" required>
                        <?php foreach($data['vacant_apartments'] as $apt): ?>
                            <option value="<?php echo $apt->id; ?>">شقة <?php echo $apt->apartment_number; ?> - الدور <?php echo $apt->floor; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>اسم المستأجر</label>
                    <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>" required pattern="^[\p{L}\s]+$">
                    <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                </div>

                <div class="form-group">
                    <label>رقم الهوية</label>
                    <input type="text" name="nid" class="form-control" value="<?php echo $data['nid']; ?>">
                </div>

                <div class="form-group">
                    <label>رقم الهاتف</label>
                    <input type="tel" name="phone" class="form-control <?php echo (!empty($data['phone_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['phone']; ?>" pattern="^(70|71|73|77|78)[0-9]{7}$" placeholder=" يبدأ بـ 7 ويتكون من 9 أرقام" required>
                    <span class="invalid-feedback"><?php echo $data['phone_err']; ?></span>
                </div>

                <div class="form-group">
                    <label>قيمة الإيجار الشهري</label>
                    <input type="text" name="rent_amount" class="form-control <?php echo (!empty($data['rent_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['rent_amount']; ?>" required>
                     <span class="invalid-feedback"><?php echo $data['rent_err']; ?></span>
                </div>

                <div class="form-group">
                    <label>تاريخ البدء</label>
                    <input type="date" name="start_date" class="form-control" value="<?php echo $data['start_date']; ?>">
                </div>

                <div class="form-group">
                    <label>تاريخ الانتهاء</label>
                    <input type="date" name="end_date" class="form-control" value="<?php echo $data['end_date']; ?>">
                </div>

                <div class="form-group">
                    <input type="submit" value="حفظ" class="btn btn-primary btn-block">
                </div>
                <a href="<?php echo URL_ROOT; ?>/tenants" class="btn btn-block" style="background:#7f8c8d;">إلغاء</a>
            </form>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>
