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
                        <th>المتبقي</th>
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
                                <td style="<?php echo ($payment->amount_remaining > 0) ? 'color: red; font-weight: bold;' : 'color: green;'; ?>">
                                    <?php echo number_format($payment->amount_remaining); ?>
                                </td>
                                <td><?php echo $payment->payment_date; ?></td>
                                <td><?php echo $payment->notes; ?></td>
                                <td>
                                    <a href="<?php echo URL_ROOT; ?>/payments/delete/<?php echo $payment->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟');">حذف</a>
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
