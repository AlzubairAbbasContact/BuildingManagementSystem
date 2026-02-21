<?php require APP_ROOT . '/views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require APP_ROOT . '/views/layouts/sidebar.php'; ?>
    <div class="main-content" id="main-content">
        <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>

        <div class="d-flex" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
            <h2>إدارة المستخدمين</h2>
            <a href="<?php echo URL_ROOT; ?>/auth/register" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة مستخدم</a>
        </div>

        <?php App\Core\Session::flash('user_msg'); ?>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الدور</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['users'])): ?>
                        <tr><td colspan="5" class="text-center">لا يوجد مستخدمين.</td></tr>
                    <?php else: ?>
                        <?php foreach($data['users'] as $user): ?>
                            <tr>
                                <td><?php echo $user->name; ?></td>
                                <td><?php echo $user->email; ?></td>
                                <td>
                                    <?php if($user->role == 'admin'): ?>
                                        <span class="badge badge-warning">مدير</span>
                                    <?php else: ?>
                                        <span style="background-color: gray;" class="badge badge-info">مستخدم</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($user->is_active): ?>
                                        <span class="badge badge-success">نشط</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">غير نشط</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo URL_ROOT; ?>/users/edit/<?php echo $user->id; ?>" class="btn btn-sm btn-info">تعديل</a>
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
