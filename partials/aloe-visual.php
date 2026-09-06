<?php
/**
 * Self-contained animated brand visual — no external image request, so it
 * can never show up broken. Draws in on scroll, then breathes gently.
 * Optional $aloeVisualClass extra class, $aloeVisualLabel for alt/aria text.
 */
$label = $aloeVisualLabel ?? 'Abstract illustration of an aloe plant, representing steady growth';
$extraClass = $aloeVisualClass ?? '';
?>
<svg class="aloe-visual <?= e($extraClass) ?>" viewBox="0 0 600 600" role="img" aria-label="<?= e($label) ?>" data-reveal>
  <title><?= e($label) ?></title>
  <defs>
    <radialGradient id="aloeGlow" cx="50%" cy="62%" r="55%">
      <stop offset="0%" stop-color="#1e8f5e" stop-opacity="0.35"/>
      <stop offset="100%" stop-color="#1e8f5e" stop-opacity="0"/>
    </radialGradient>
    <linearGradient id="aloeLeafA" x1="0" y1="1" x2="0" y2="0">
      <stop offset="0%" stop-color="#146b46"/>
      <stop offset="100%" stop-color="#1e8f5e"/>
    </linearGradient>
    <linearGradient id="aloeLeafB" x1="0" y1="1" x2="0" y2="0">
      <stop offset="0%" stop-color="#1e8f5e"/>
      <stop offset="100%" stop-color="#7cd6ac"/>
    </linearGradient>
  </defs>

  <ellipse cx="300" cy="380" rx="220" ry="220" fill="url(#aloeGlow)"/>

  <g class="aloe-rings">
    <circle class="aloe-ring" cx="130" cy="180" r="5" fill="#7cd6ac"/>
    <circle class="aloe-ring" cx="470" cy="150" r="4" fill="#e6f3ec"/>
    <circle class="aloe-ring" cx="500" cy="330" r="6" fill="#7cd6ac"/>
    <circle class="aloe-ring" cx="95" cy="330" r="4" fill="#e6f3ec"/>
  </g>

  <g class="aloe-leaves" fill="none" stroke-linecap="round" stroke-linejoin="round">
    <path class="aloe-leaf" style="--d:0" d="M300 470 C 260 380 250 260 296 130 C 320 250 322 380 300 470 Z" fill="url(#aloeLeafA)"/>
    <path class="aloe-leaf" style="--d:1" d="M300 470 C 220 400 150 320 108 190 C 210 240 272 330 300 470 Z" fill="url(#aloeLeafB)"/>
    <path class="aloe-leaf" style="--d:2" d="M300 470 C 380 400 450 320 492 190 C 390 240 328 330 300 470 Z" fill="url(#aloeLeafB)"/>
    <path class="aloe-leaf" style="--d:3" d="M300 480 C 200 440 120 400 70 320 C 180 340 262 390 300 480 Z" fill="url(#aloeLeafA)"/>
    <path class="aloe-leaf" style="--d:4" d="M300 480 C 400 440 480 400 530 320 C 420 340 338 390 300 480 Z" fill="url(#aloeLeafA)"/>
  </g>

  <ellipse cx="300" cy="486" rx="70" ry="14" fill="#146b46" opacity="0.18"/>
</svg>
