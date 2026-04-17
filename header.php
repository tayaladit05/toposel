<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$announcement = '';
if (is_front_page() && function_exists('get_field')) {
  $announcement = get_field('announcement_text');
}
?>

<?php if (!empty($announcement)): ?>
<div class="announcement-bar">
  <?php echo esc_html($announcement); ?>
</div>
<?php endif; ?>

<header class="site-header" id="site-header">
  <div class="site-header__inner">
    <button class="site-header__toggle" type="button" aria-expanded="false" aria-controls="site-mobile-menu" aria-label="Open menu">
      <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M3 6h18M3 12h18M3 18h18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>

    <a class="site-header__brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Homepage">
      <?php if (has_custom_logo()): ?>
        <?php echo wp_get_attachment_image(get_theme_mod('custom_logo'), 'full', false, ['class' => 'site-header__logo-image']); ?>
      <?php else: ?>
        <span class="site-header__logo-text">SHOP.CO</span>
      <?php endif; ?>
    </a>

    <nav class="site-header__nav" aria-label="Primary navigation">
      <?php
      wp_nav_menu([
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'site-header__menu',
        'fallback_cb'    => 'toposel_primary_menu_fallback',
        'depth'          => 1,
      ]);
      ?>
    </nav>

    <form role="search" method="get" class="site-header__search" action="<?php echo esc_url(home_url('/')); ?>">
      <svg class="site-header__search-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"></circle>
        <path d="M20 20l-4-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
      </svg>
      <input type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search for products..." aria-label="Search products">
      <input type="hidden" name="post_type" value="product">
    </form>

    <div class="site-header__actions">
      <a class="site-header__action site-header__action--search" href="<?php echo esc_url(home_url('/shop/')); ?>" aria-label="Search products">
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
          <circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"></circle>
          <path d="M20 20l-4-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
        </svg>
      </a>

      <a class="site-header__action" href="<?php echo esc_url(function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart/')); ?>" aria-label="Open cart">
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
          <path d="M3 4h2l2.2 10.2a1 1 0 0 0 1 .8h8.5a1 1 0 0 0 1-.8L20 7H7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
          <circle cx="10" cy="19" r="1.5" fill="none" stroke="currentColor" stroke-width="2"></circle>
          <circle cx="17" cy="19" r="1.5" fill="none" stroke="currentColor" stroke-width="2"></circle>
        </svg>
      </a>

      <a class="site-header__action" href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account/')); ?>" aria-label="Open account">
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
          <circle cx="12" cy="8" r="4" fill="none" stroke="currentColor" stroke-width="2"></circle>
          <path d="M5 20a7 7 0 0 1 14 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
        </svg>
      </a>
    </div>
  </div>

  <nav class="site-header__mobile-nav" id="site-mobile-menu" aria-label="Mobile navigation">
    <?php
    wp_nav_menu([
      'theme_location' => 'primary',
      'container'      => false,
      'menu_class'     => 'site-header__mobile-menu',
      'fallback_cb'    => 'toposel_primary_menu_fallback',
      'depth'          => 1,
    ]);
    ?>
  </nav>
</header>