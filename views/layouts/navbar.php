<div class="top-navbar">
    <div class="toggle-menu" id="toggle-sidebar">
        <i class="fas fa-bars pointer" style="cursor: pointer; font-size: 1.2rem;"></i>
    </div>
    <div class="user-dropdown">
        <span>مرحباً، <?php echo $_SESSION['user_name']; ?></span>
        <?php 
            $img = !empty($_SESSION['user_image']) ? $_SESSION['user_image'] : 'default.png';
        ?>
        <img src="<?php echo URL_ROOT; ?>/uploads/<?php echo $img; ?>" alt="User" class="user-img">
    </div>
</div>
