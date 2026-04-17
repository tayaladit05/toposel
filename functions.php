<?php
function toposel_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('woocommerce');
  add_theme_support('custom-logo', [
    'height'      => 48,
    'width'       => 200,
    'flex-height' => true,
    'flex-width'  => true,
  ]);

  register_nav_menus([
    'primary' => __('Primary Menu', 'toposel'),
  ]);
}
add_action('after_setup_theme', 'toposel_setup');

function toposel_assets() {
  wp_enqueue_style(
    'toposel-style',
    get_stylesheet_uri(),
    array(),
    filemtime(get_stylesheet_directory() . '/style.css') // cache bust
  );
}
add_action('wp_enqueue_scripts', 'toposel_assets');

function toposel_primary_menu_fallback($args) {
  $menu_class = !empty($args['menu_class']) ? $args['menu_class'] : 'menu';
  $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');

  $items = [
    ['label' => __('Shop', 'toposel'), 'url' => $shop_url],
    ['label' => __('On Sale', 'toposel'), 'url' => add_query_arg('on-sale', '1', $shop_url)],
    ['label' => __('New Arrivals', 'toposel'), 'url' => home_url('/#new-arrivals')],
    ['label' => __('Brands', 'toposel'), 'url' => home_url('/#brands')],
  ];

  echo '<ul class="' . esc_attr($menu_class) . '">';
  foreach ($items as $item) {
    echo '<li><a href="' . esc_url($item['url']) . '">' . esc_html($item['label']) . '</a></li>';
  }
  echo '</ul>';
}