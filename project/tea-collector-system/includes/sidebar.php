<aside class="sidebar">
  <div class="sidebar-brand">
    <i class="fas fa-leaf"></i> <span><?= SITE_SHORT_NAME ?></span>
  </div>
  <nav class="sidebar-nav">
    <a href="<?= SITE_URL ?>dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

    <?php if (hasRole(['admin', 'manager'])): ?>
      <a href="<?= SITE_URL ?>modules/farmers/index.php"><i class="fas fa-users"></i> Farmers</a>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'manager', 'collector'])): ?>
      <a href="<?= SITE_URL ?>modules/collections/index.php"><i class="fas fa-boxes"></i> Collections</a>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'manager', 'accountant'])): ?>
      <a href="<?= SITE_URL ?>modules/payments/index.php"><i class="fas fa-money-bill-wave"></i> Payments</a>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'manager', 'transport_officer'])): ?>
      <a href="<?= SITE_URL ?>modules/transport/index.php"><i class="fas fa-truck"></i> Transport</a>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'manager', 'storekeeper'])): ?>
      <a href="<?= SITE_URL ?>modules/inventory/index.php"><i class="fas fa-warehouse"></i> Inventory</a>
    <?php endif; ?>

    <?php if (hasRole(['admin', 'manager', 'accountant'])): ?>
      <a href="<?= SITE_URL ?>modules/reports/daily.php"><i class="fas fa-chart-bar"></i> Reports</a>
    <?php endif; ?>

    <?php if (hasRole('admin')): ?>
      <a href="<?= SITE_URL ?>modules/users/index.php"><i class="fas fa-user-cog"></i> Users</a>
    <?php endif; ?>
  </nav>
</aside>
<main class="main-content">
  <nav class="topbar">
    <span class="page-title"><?= htmlspecialchars($pageTitle ?? '') ?></span>
    <div class="user-info">
      <i class="fas fa-user-circle"></i>
      <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
      <span class="badge bg-secondary"><?= htmlspecialchars($user['role']) ?></span>
      <a href="<?= SITE_URL ?>logout.php" class="btn btn-sm btn-outline-danger ms-3">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </nav>
  <div class="content-area">
    <?php displayFlash(); ?>