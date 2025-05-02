<?php
/**
 * Full Width Page Template
 */
?>

<div class="page-banner">
    <div class="container">
        <h1 class="page-title"><?php echo $page['title']; ?></h1>
        <div class="breadcrumbs">
            <a href="<?php echo $appUrl . '/' . $currentLang; ?>"><?php _e('home'); ?></a>
            <span class="separator">/</span>
            <span class="current"><?php echo $page['title']; ?></span>
        </div>
    </div>
</div>

<div class="page-content full-width">
    <div class="container-fluid">
        <div class="content-wrapper">
            <?php echo $page['content']; ?>
        </div>
    </div>
</div>