<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require APP_ROOT . '/views/layouts/sidebar.php'; ?>
    <div class="main-content" id="main-content">
        <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>

        <div class="d-flex" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
            <h2>سجل المدفوعات</h2>
            <a href="<?php echo URL_ROOT; ?>/payments/add" class="btn btn-primary"><i class="fas fa-plus"></i> تسجيل دفعة</a>
        </div>

        <?php App\Core\Session::flash('payment_msg'); ?>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>المستأجر</th>
                        <th>المبلغ المدفوع</th>
                        <th>المتبقي (ديناميك)</th>
                        <th>حالة الدفعة</th>
                        <th>تاريخ الدفع</th>
                        <th>ملاحظات</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['payments'])): ?>
                        <tr><td colspan="6" class="text-center">لا توجد مدفوعات مسجلة.</td></tr>
                    <?php else: ?>
                        <?php foreach($data['payments'] as $payment): ?>
                            <tr>
                                <td><?php echo $payment->tenant_name; ?></td>
                                <td><?php echo number_format($payment->amount_paid); ?></td>
                                <td style="<?php echo ($payment->remaining > 0) ? 'color: red; font-weight: bold;' : 'color: green;'; ?>">
                                    <?php echo number_format($payment->remaining); ?>
                                </td>
                                <td>
                                    <?php echo isset($payment->status) && $payment->status === 'canceled' ? 'ملغاة' : 'نشطة'; ?>
                                    <?php if(!empty($payment->cancellation_reason)): ?>
                                        <div class="text-muted small">سبب: <?php echo htmlspecialchars($payment->cancellation_reason); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $payment->payment_date; ?></td>
                                <td><?php echo $payment->notes; ?></td>
                                <td>
                                    <?php if(!isset($payment->status) || $payment->status !== 'canceled'): ?>
                                        <a href="<?php echo URL_ROOT; ?>/payments/cancel/<?php echo $payment->id; ?>" class="btn btn-sm btn-danger">إلغاء</a>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/views/layouts/footer.php'; ?>
