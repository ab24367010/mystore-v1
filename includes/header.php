<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>

    <!-- Meta Tags -->
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Мэргэжлийн website template-үүдийг худалдан авах. Таны бизнест тохирсон өндөр чанартай загвар.'; ?>">
    <meta name="keywords" content="website template, web design, монгол template, business website, e-commerce template">
    <meta name="author" content="<?php echo SITE_NAME; ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?>">
    <meta property="og:description" content="<?php echo isset($page_description) ? $page_description : 'Мэргэжлийн website template-үүдийг худалдан авах'; ?>">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/images/og-image.jpg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?>">
    <meta property="twitter:description" content="<?php echo isset($page_description) ? $page_description : 'Мэргэжлийн website template-үүдийг худалдан авах'; ?>">
    <meta property="twitter:image" content="<?php echo SITE_URL; ?>/images/og-image.jpg">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/responsive.css">
    <link rel="icon" type="image/x-icon" href="<?php echo SITE_URL; ?>/images/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
</head>
<body>