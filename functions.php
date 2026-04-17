<?php

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Theme setup.
 */
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

/**
 * Enqueue theme assets.
 */
function toposel_assets() {
  $theme_dir = get_stylesheet_directory();
  $theme_uri = get_stylesheet_directory_uri();

  wp_enqueue_style(
    'toposel-style',
    get_stylesheet_uri(),
    [],
    filemtime($theme_dir . '/style.css')
  );

  wp_enqueue_script(
    'toposel-theme',
    $theme_uri . '/assets/js/theme.js',
    [],
    filemtime($theme_dir . '/assets/js/theme.js'),
    true
  );
}
add_action('wp_enqueue_scripts', 'toposel_assets');

/**
 * Resolve ACF image values (array, ID, or URL) into a consistent structure.
 */
function toposel_resolve_image($image) {
  $url = '';
  $alt = '';

  if (is_array($image)) {
    $url = !empty($image['url']) ? (string) $image['url'] : '';
    $alt = !empty($image['alt']) ? (string) $image['alt'] : '';
  } elseif (is_numeric($image)) {
    $image_id = (int) $image;
    $url = (string) wp_get_attachment_image_url($image_id, 'full');
    $alt = (string) get_post_meta($image_id, '_wp_attachment_image_alt', true);
  } elseif (is_string($image)) {
    $url = $image;
  }

  return [
    'url' => $url,
    'alt' => $alt,
  ];
}

/**
 * Normalize an ACF taxonomy field value into an array of unique term IDs.
 */
function toposel_normalize_term_ids($value) {
  $term_ids = [];

  if (is_numeric($value)) {
    $term_ids[] = (int) $value;
  } elseif (is_object($value) && isset($value->term_id)) {
    $term_ids[] = (int) $value->term_id;
  } elseif (is_array($value)) {
    if (isset($value['term_id'])) {
      $term_ids[] = (int) $value['term_id'];
    } else {
      foreach ($value as $item) {
        if (is_object($item) && isset($item->term_id)) {
          $term_ids[] = (int) $item->term_id;
        } elseif (is_numeric($item)) {
          $term_ids[] = (int) $item;
        }
      }
    }
  }

  $term_ids = array_filter($term_ids);
  $term_ids = array_unique($term_ids);

  return array_values($term_ids);
}

/**
 * Fallback menu shown when a WordPress menu is not assigned.
 */
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