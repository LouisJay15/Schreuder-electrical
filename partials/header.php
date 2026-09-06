<?php $navUser = current_user(); ?>
<a class="skip-link" href="#main">Skip to content</a>
<header class="site-header">
  <div class="container nav">
    <a class="brand" href="/">
      <svg class="brand-mark" viewBox="0 0 32 32" fill="none" aria-hidden="true">
        <rect width="32" height="32" rx="9" fill="#1E8F5E"/>
        <path d="M16 25C16 25 9 21.5 9 14.8C9 10.9 12 8 15.6 8C15.6 8 16 12 16 15C16 12 16.4 8 16.4 8C20 8 23 10.9 23 14.8C23 21.5 16 25 16 25Z" fill="#fff"/>
      </svg>
      Aloe Credit
    </a>
    <button class="nav-toggle" aria-label="Open menu" aria-expanded="false">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
    <ul class="nav-links">
      <li><a href="/about">About</a></li>
      <li><a href="/services">Loans</a></li>
      <li><a href="/loan-calculator">Calculator</a></li>
      <li><a href="/blog">Blog</a></li>
      <li><a href="/faq">FAQ</a></li>
      <li><a href="/contact">Contact</a></li>
      <?php if ($navUser): ?>
        <li><a href="<?= $navUser['role'] === 'admin' ? '/admin/' : '/dashboard' ?>">My Account</a></li>
      <?php else: ?>
        <li><a href="/login">Log in</a></li>
      <?php endif; ?>
    </ul>
    <div class="nav-actions">
      <a href="/apply" class="btn btn-primary">Apply Now</a>
    </div>
  </div>
</header>
