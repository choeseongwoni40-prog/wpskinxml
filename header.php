<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="site-container">
        <h1 class="site-title">
            <a href="<?php echo esc_url(get_option('revenue_pro_blog_link', home_url('/'))); ?>">
                <?php bloginfo('name'); ?>
            </a>
        </h1>
        
        <?php
        if (has_nav_menu('primary')) {
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => 'nav',
                'container_class' => 'main-navigation',
                'menu_class' => 'primary-menu'
            ));
        }
        ?>
    </div>
</header>

<main class="site-main">
    <div class="site-container">
