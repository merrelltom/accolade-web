<?php if (!$kirby->user()) go('/') ?>
<!doctype html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <title><?= $site->title() ?> | <?= $page->title() ?></title>
  <?= css(['assets/css/style-1.0.css', '@auto']) ?>
  <script src="https://js.stripe.com/v3/"></script>
</head>

<body data-large="<?= $site->large_trophy_price();?>" data-medium="<?= $site->medium_trophy_price();?>" data-small="<?= $site->small_trophy_price();?>"
      data-l-mod="<?= $site->small_trophy_variance();?>" data-m-mod="<?= $site->medium_trophy_variance();?>" data-s-mod="<?= $site->large_trophy_variance();?>"
      data-s-grad1="<?= $site->small_gradient_1();?>" data-s-grad2="<?= $site->small_gradient_2();?>" data-s-grad3="<?= $site->small_gradient_3();?>" data-s-grad4="<?= $site->small_gradient_4();?>"
      data-m-grad1="<?= $site->medium_gradient_1();?>" data-m-grad2="<?= $site->medium_gradient_2();?>" data-m-grad3="<?= $site->medium_gradient_3();?>" data-m-grad4="<?= $site->medium_gradient_4();?>"
      data-l-grad1="<?= $site->large_gradient_1();?>" data-l-grad2="<?= $site->large_gradient_2();?>" data-l-grad3="<?= $site->large_gradient_3();?>" data-l-grad4="<?= $site->large_gradient_4();?>"
      data-range-high="<?= $site->likely_range_high();?>" data-range-low="<?= $site->likely_range_low();?>"
      >

