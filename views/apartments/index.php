<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require APP_ROOT . '/views/layouts/sidebar.php'; ?>
    <div class="main-content" id="main-content">
        <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>

        <div class="d-flex" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
            <h2>إدارة الشقق</h2>
            <a href="<?php echo URL_ROOT; ?>/apartments/add" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة شقة</a>
        </div>

        <?php App\Core\Session::flash('apartment_msg'); ?>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>رقم الشقة</th>
                        <th>الدور</th>
                        <th>الحالة</th>
                        <th>ملاحظات</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['apartments'])): ?>
                        <tr><td colspan="5" class="text-center">لا توجد شقق مضافة.</td></tr>
                    <?php else: ?>
                        <?php foreach($data['apartments'] as $apartment): ?>
                            <tr>
                                <td><?php echo $apartment->apartment_number; ?></td>
                                <td><?php echo $apartment->floor; ?></td>
                                <td>
                                    <?php if($apartment->status == 'vacant'): ?>
                                        <span class="badge badge-success">شاغرة</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">مؤجرة</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $apartment->notes; ?></td>
                                <td>
                                    <!-- Soft action: mark apartment as vacant instead of hard delete -->
                                    <a href="<?php echo URL_ROOT; ?>/apartments/delete/<?php echo $apartment->id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من جعل هذه الشقة شاغرة؟ هذا لن يحذف السجل.');">تعيين شاغرة</a>
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
