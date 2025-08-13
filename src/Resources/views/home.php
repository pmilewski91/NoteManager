<div class="container py-5">
    <?php if ($isLoggedIn): 
        require __DIR__ . '/../components/log_in_index.php'; 
    else: 
        require __DIR__ . '/../components/log_out_index.php'; 
    endif; ?>
</div>
