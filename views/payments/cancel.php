<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require APP_ROOT . '/views/layouts/sidebar.php'; ?>
    <div class="main-content" id="main-content">
        <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>

        <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px;">
            <h2 class="mb-3">إلغاء دفعة</h2>

            <form action="<?php echo URL_ROOT; ?>/payments/cancel/<?php echo $data['payment']->id; ?>" method="post">
                <?php App\Core\Csrf::field(); ?>

                <div class="form-group">
                    <label>المستأجر</label>
                    <input type="text" class="form-control" value="<?php echo $data['payment']->tenant_name; ?>" disabled>
                </div>

                <div class="form-group">
                    <label>المبلغ المدفوع</label>
                    <input type="text" class="form-control" value="<?php echo number_format($data['payment']->amount_paid,2); ?>" disabled>
                </div>

                <div class="form-group">
                    <label>سبب الإلغاء (اختياري)</label>
                    <textarea name="reason" class="form-control"><?php echo isset($data['reason']) ? htmlspecialchars($data['reason']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <input type="submit" value="إلغاء الدفعة" class="btn btn-danger btn-block">
                </div>
                <a href="<?php echo URL_ROOT; ?>/payments" class="btn btn-block" style="background:#7f8c8d; width: 90%">إلغاء</a>
            </form>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>
