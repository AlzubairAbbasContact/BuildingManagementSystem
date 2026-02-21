<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require APP_ROOT . '/views/layouts/sidebar.php'; ?>
    <div class="main-content" id="main-content">
        <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>

        <div class="d-flex" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
            <h2>إدارة المستأجرين</h2>
            <a href="<?php echo URL_ROOT; ?>/tenants/add" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة مستأجر</a>
        </div>

        <?php App\Core\Session::flash('tenant_msg'); ?>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>الأسم</th>
                        <th>رقم الشقة</th>
                        <th>الدور</th>
                        <th>الهاتف</th>
                        <th>قيمة الإيجار</th>
                        <th>بداية العقد</th>
                        <th>نهاية العقد</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['tenants'])): ?>
                        <tr><td colspan="8" class="text-center">لا يوجد مستأجرين حالياً.</td></tr>
                    <?php else: ?>
                        <?php foreach($data['tenants'] as $tenant): ?>
                            <tr>
                                <td><?php echo $tenant->name; ?></td>
                                <td><?php echo $tenant->apartment_number; ?></td>
                                <td><?php echo $tenant->floor; ?></td>
                                <td><?php echo $tenant->phone; ?></td>
                                <td><?php echo number_format($tenant->rent_amount); ?></td>
                                <td><?php echo $tenant->start_date; ?></td>
                                <td><?php echo $tenant->end_date; ?></td>
                                <td>
                                    <a href="<?php echo URL_ROOT; ?>/tenants/delete/<?php echo $tenant->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟ سيتم حذف جميع المدفوعات المرتبطة.');">حذف</a>
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
