<?php
/**
 * Expects, set by the including page before require:
 *   $pageTitle        string  (without the site suffix)
 *   $pageDescription  string  (~150-160 chars)
 *   $canonicalPath    string  e.g. '/about'
 *   $ogImage          string  optional, absolute URL
 *   $jsonLd           array|array[]  optional, one or more JSON-LD schema blocks
 *   $noindex          bool    optional
 */
$siteName = 'Aloe Credit';
$siteUrl = 'https://www.aloecredit.co.za';
$title = ($pageTitle ?? 'Aloe Credit') . ' | ' . $siteName;
$description = $pageDescription ?? 'Aloe Credit offers clear, responsible personal loans for South Africans — fast pre-qualification, transparent terms, no hidden fees.';
$canonical = $siteUrl . ($canonicalPath ?? '/');
$image = $ogImage ?? $siteUrl . '/assets/img/og-cover.svg';
?><meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($description) ?>">
<link rel="canonical" href="<?= e($canonical) ?>">
<?php if (!empty($noindex)): ?>
<meta name="robots" content="noindex, nofollow">
<?php else: ?>
<meta name="robots" content="index, follow, max-image-preview:large">
<?php endif; ?>

<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e($siteName) ?>">
<meta property="og:title" content="<?= e($pageTitle ?? $siteName) ?>">
<meta property="og:description" content="<?= e($description) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e($image) ?>">
<meta property="og:locale" content="en_ZA">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($pageTitle ?? $siteName) ?>">
<meta name="twitter:description" content="<?= e($description) ?>">
<meta name="twitter:image" content="<?= e($image) ?>">

<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
<?php if (!empty($jsonLd)):
    $blocks = isset($jsonLd[0]) && is_array($jsonLd[0]) ? $jsonLd : [$jsonLd];
    foreach ($blocks as $block): ?>
<script type="application/ld+json"><?= json_encode($block, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<?php endforeach; endif; ?>
