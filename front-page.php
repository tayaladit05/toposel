<?php get_header(); ?>

<!-- Hero Section -->
<?php
$img     = get_field('hero_image');
$heading = get_field('hero_heading');
$subheading = get_field('hero_subheading');
$btn_text = get_field('hero_button_text');
$btn_link = get_field('hero_button_link');

$resolve_image = function ($image) {
  $url = '';
  $alt = '';

  if (is_array($image)) {
    $url = !empty($image['url']) ? $image['url'] : '';
    $alt = !empty($image['alt']) ? $image['alt'] : '';
  } elseif (is_numeric($image)) {
    $image_id = (int) $image;
    $url = wp_get_attachment_image_url($image_id, 'full');
    $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
  } elseif (is_string($image)) {
    $url = $image;
  }

  return [
    'url' => $url ?: '',
    'alt' => $alt ?: '',
  ];
};

$hero_image = $resolve_image($img);
?>

<main class="home-page">

<section class="hero">
  <div class="hero__content">
    <?php if($heading): ?>
      <h1 class="hero__heading"><?php echo esc_html($heading); ?></h1>
    <?php endif; ?>

    <?php if($subheading): ?>
      <p class="hero__subheading"><?php echo esc_html($subheading); ?></p>
    <?php endif; ?>

    <?php if($btn_text): ?>
      <a href="<?php echo esc_url($btn_link); ?>" class="hero__btn">
        <?php echo esc_html($btn_text); ?>
      </a>
    <?php endif; ?>

    <div class="hero__stats" aria-label="Store highlights">
      <div class="hero__stat-item">
        <strong>200+</strong>
        <span>International Brands</span>
      </div>
      <div class="hero__stat-item">
        <strong>2,000+</strong>
        <span>High-Quality Products</span>
      </div>
      <div class="hero__stat-item hero__stat-item--wide">
        <strong>30,000+</strong>
        <span>Happy Customers</span>
      </div>
    </div>
  </div>

  <?php if(!empty($hero_image['url'])): ?>
    <div class="hero__media">
      <img src="<?php echo esc_url($hero_image['url']); ?>" alt="<?php echo esc_attr($hero_image['alt'] ?: 'Hero Banner'); ?>" class="hero__image">
      <span class="hero__spark hero__spark--one" aria-hidden="true">✦</span>
      <span class="hero__spark hero__spark--two" aria-hidden="true">✦</span>
    </div>
  <?php endif; ?>
</section>

<!-- Brand Logos Bar -->
<?php
$logos = [];
for($i = 1; $i <= 5; $i++) {
  $logo = get_field('brand_logo_' . $i);
  if($logo) {
    $resolved_logo = $resolve_image($logo);
    if(!empty($resolved_logo['url'])) {
      $logos[] = $resolved_logo;
    }
  }
}
?>

<?php if(!empty($logos)): ?>
<section class="brands-bar" id="brands">
  <?php foreach($logos as $logo): ?>
    <img src="<?php echo esc_url($logo['url']); ?>" 
         alt="<?php echo esc_attr($logo['alt'] ?: 'Brand Logo'); ?>" 
         class="brands-bar__logo">
  <?php endforeach; ?>
</section>
<?php endif; ?>

<!-- New Arrivals -->
<section class="new-arrivals" id="new-arrivals">
  <h2 class="new-arrivals__title">NEW ARRIVALS</h2>

  <?php
  $cat = get_field('new_arrivals_category');
  $term_ids = [];

  if (is_numeric($cat)) {
    $term_ids[] = (int) $cat;
  } elseif (is_object($cat) && isset($cat->term_id)) {
    $term_ids[] = (int) $cat->term_id;
  } elseif (is_array($cat)) {
    if (isset($cat['term_id'])) {
      $term_ids[] = (int) $cat['term_id'];
    } else {
      // Supports ACF taxonomy field set to return multiple terms.
      foreach ($cat as $item) {
        if (is_object($item) && isset($item->term_id)) {
          $term_ids[] = (int) $item->term_id;
        } elseif (is_numeric($item)) {
          $term_ids[] = (int) $item;
        }
      }
    }
  }

  $term_ids = array_values(array_unique(array_filter($term_ids)));

  if(!empty($term_ids)):
    $query = new WP_Query([
      'post_type'      => 'product',
      'post_status'    => 'publish',
      'posts_per_page' => 2,
      'tax_query'      => [[
        'taxonomy' => 'product_cat',
        'field'    => 'term_id',
        'terms'    => $term_ids,
      ]]
    ]);

    if($query->have_posts()):
  ?>

  <div class="new-arrivals__grid">
    <?php while($query->have_posts()): $query->the_post();
      $price    = get_post_meta(get_the_ID(), '_price', true);
      $thumb    = get_the_post_thumbnail_url(get_the_ID(), 'medium');
    ?>
    <div class="product-card">
      <?php if($thumb): ?>
        <img src="<?php echo esc_url($thumb); ?>" 
              alt="<?php echo esc_attr(get_the_title()); ?>" 
             class="product-card__image">
      <?php endif; ?>
      <div class="product-card__info">
        <h3 class="product-card__title"><?php the_title(); ?></h3>
        <?php if($price): ?>
          <p class="product-card__price"><?php echo wc_price($price); ?></p>
        <?php endif; ?>
      </div>
    </div>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>

  <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="new-arrivals__view-all">
    View All
  </a>

  <?php endif; endif; ?>
</section>

</main>

<?php get_footer(); ?>