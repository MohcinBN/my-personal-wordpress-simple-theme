<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        (function() {
            var savedTheme = localStorage.getItem('mochin-theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1a1a2e',
                        secondary: '#16213e',
                        accent: '#0f3460',
                        light: '#e8e8e8',
                    },
                }
            }
        }
    </script>
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300'); ?>>
<?php wp_body_open(); ?>

<div id="page" class="min-h-screen flex flex-col">
    <header class="border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-3xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="site-branding">
                    <h1 class="text-2xl font-bold">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-gray-900 dark:text-white hover:text-gray-600 dark:hover:text-gray-300 no-underline">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                </div>

                <div class="hamburger">
                    <span class="slice"></span>
                    <span class="slice"></span>
                    <span class="slice"></span>
                </div>
                <nav class="flex items-center space-x-6" id="main-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'flex items-center space-x-6',
                        'fallback_cb'    => 'mochin_fallback_menu',
                        'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                        'walker'         => new Mochin_Nav_Walker(),
                    ));
                    ?>
                    
                    <button id="theme-toggle" class="p-2 text-xl hover:opacity-70 transition-opacity" aria-label="Toggle dark mode">
                        <span class="dark:hidden">🕶</span>
                        <span class="hidden dark:inline">☀</span>
                    </button>
                </nav>
            </div>
        </div>
    </header>

    <main id="main" class="flex-grow">
        <div class="max-w-3xl mx-auto px-4 py-8">

<?php
/**
 * Fallback menu if no menu is set
 */
function mochin_fallback_menu() {
    ?>
    <ul class="flex items-center space-x-6">
        <li><a href="<?php echo esc_url(home_url('/')); ?>" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white no-underline">Blog</a></li>
        <li><a href="<?php echo esc_url(home_url('/about')); ?>" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white no-underline">About</a></li>
        <li><a href="<?php echo esc_url(get_bloginfo('rss2_url')); ?>" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white no-underline">Feed</a></li>
    </ul>
    <?php
}

/**
 * Custom Nav Walker
 */
class Mochin_Nav_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $output .= '<li>';
        $output .= '<a href="' . esc_url($item->url) . '" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white no-underline">';
        $output .= esc_html($item->title);
        $output .= '</a>';
    }
    
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}
?>
