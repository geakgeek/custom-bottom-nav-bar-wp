<?php
/*
Plugin Name: Custom Bottom Navigation Bar
Description: Adds a customizable bottom navigation bar for mobile and tablet devices.
Version: 1.4
Author: geargeek.in
*/

// Enqueue necessary styles and scripts
function cbnb_enqueue_scripts() {
    wp_enqueue_style('cbnb-styles', plugins_url('css/cbnb-styles.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'cbnb_enqueue_scripts');

// Add settings page
function cbnb_add_settings_page() {
    add_options_page('Bottom Nav Settings', 'Bottom Nav', 'manage_options', 'cbnb-settings', 'cbnb_render_settings_page');
}
add_action('admin_menu', 'cbnb_add_settings_page');

// Render settings page
function cbnb_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Bottom Navigation Bar Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('cbnb_settings');
            do_settings_sections('cbnb-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings
function cbnb_register_settings() {
    register_setting('cbnb_settings', 'cbnb_home_url');
    register_setting('cbnb_settings', 'cbnb_whatsapp_url');
    register_setting('cbnb_settings', 'cbnb_blog_url');

    add_settings_section('cbnb_main_section', 'Main Settings', null, 'cbnb-settings');

    add_settings_field('cbnb_home_url', 'Home URL', 'cbnb_home_url_callback', 'cbnb-settings', 'cbnb_main_section');
    add_settings_field('cbnb_whatsapp_url', 'WhatsApp URL', 'cbnb_whatsapp_url_callback', 'cbnb-settings', 'cbnb_main_section');
    add_settings_field('cbnb_blog_url', 'Blog URL', 'cbnb_blog_url_callback', 'cbnb-settings', 'cbnb_main_section');
}
add_action('admin_init', 'cbnb_register_settings');

// Callbacks for settings fields
function cbnb_home_url_callback() {
    $home_url = get_option('cbnb_home_url', home_url());
    echo "<input type='text' name='cbnb_home_url' value='$home_url' class='regular-text'>";
}

function cbnb_whatsapp_url_callback() {
    $whatsapp_url = get_option('cbnb_whatsapp_url', 'https://whatsapp.com/channel/0029VaiZR2kBadmdHWmPlW1v');
    echo "<input type='text' name='cbnb_whatsapp_url' value='$whatsapp_url' class='regular-text'>";
}

function cbnb_blog_url_callback() {
    $blog_url = get_option('cbnb_blog_url', home_url('/blog'));
    echo "<input type='text' name='cbnb_blog_url' value='$blog_url' class='regular-text'>";
}

// Add bottom navigation bar to footer
function cbnb_add_bottom_nav() {
    $home_url = get_option('cbnb_home_url', home_url());
    $whatsapp_url = get_option('cbnb_whatsapp_url', 'https://whatsapp.com/channel/0029VaiZR2kBadmdHWmPlW1v');
    $blog_url = get_option('cbnb_blog_url', home_url('/blog'));
    
    ?>
    <nav class="bottom-nav">
        <a href="<?php echo esc_url($home_url); ?>" class="nav-item <?php echo is_home() ? 'active' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!-- Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/></svg>
            <span>Home</span>
        </a>
        <a href="<?php echo esc_url($whatsapp_url); ?>" class="nav-item whatsapp-item" target="_blank" rel="nofollow noopener noreferrer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
            <span>JOIN</span>
        </a>
        <a href="<?php echo esc_url($blog_url); ?>" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!-- Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M160 96a96 96 0 1 1 192 0A96 96 0 1 1 160 96zm80 152V512l-48.4-24.2c-20.9-10.4-43.5-17-66.8-19.3l-96-9.6C12.5 457.2 0 443.5 0 427V224c0-17.7 14.3-32 32-32H62.3c63.6 0 125.6 19.6 177.7 56zm32 264V248c52.1-36.4 114.1-56 177.7-56H480c17.7 0 32 14.3 32 32V427c0 16.4-12.5 30.2-28.8 31.8l-96 9.6c-23.2 2.3-45.9 8.9-66.8 19.3L272 512z"/></svg>
            <span>Blog</span>
        </a>
    </nav>
    <?php
}
add_action('wp_footer', 'cbnb_add_bottom_nav');
