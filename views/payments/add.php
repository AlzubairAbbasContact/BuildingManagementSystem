<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require APP_ROOT . '/views/layouts/sidebar.php'; ?>
    <div class="main-content" id="main-content">
        <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>

        <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px;">
            <h2 class="mb-3" style="margin-bottom: 20px;">تسجيل دفعة جديدة</h2>
            
            <form action="<?php echo URL_ROOT; ?>/payments/add" method="post">
                <?php App\Core\Csrf::field(); ?>
                <div class="form-group">
                    <label>المستأجر</label>
                    <select name="tenant_id" class="form-control">
                        <?php foreach($data['tenants'] as $tenant): ?>
                            <option value="<?php echo $tenant->id; ?>"><?php echo $tenant->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>المبلغ المدفوع</label>
                    <input type="text" name="amount_paid" class="form-control <?php echo (!empty($data['amount_paid_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['amount_paid']; ?>"  required>
                    <span class="invalid-feedback"><?php echo $data['amount_paid_err']; ?></span>
                    <small class="text-muted">سيتم حساب المتبقي تلقائياً بناءً على إيجار المستأجر.</small>
                </div>

                <!-- Removed Manual Remaining Amount Input -->

                <div class="form-group">
                    <label>تاريخ الدفع</label>
                    <input type="date" name="payment_date" class="form-control" value="<?php echo $data['payment_date']; ?>">
                </div>

                <div class="form-group">
                    <label>ملاحظات</label>
                    <textarea name="notes" class="form-control"><?php echo $data['notes']; ?></textarea>
                </div>

                <div class="form-group">
                    <input type="submit" value="حفظ" class="btn btn-primary btn-block">
                </div>
                <a href="<?php echo URL_ROOT; ?>/payments" class="btn btn-block" style="background:#7f8c8d;">إلغاء</a>
            </form>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>
