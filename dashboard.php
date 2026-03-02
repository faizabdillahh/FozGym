<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/partials/header.php';

require_login();

$pdo = get_pdo();

// Fetch counts for dashboard cards
$member_count = $pdo->query('SELECT COUNT(*) FROM members')->fetchColumn();
$class_count = $pdo->query('SELECT COUNT(*) FROM classes')->fetchColumn();
$enroll_count = $pdo->query('SELECT COUNT(*) FROM enrollments')->fetchColumn();

// Fetch 5 most recent enrollments for a new dashboard feature
$stmt_recent_enrollments = $pdo->query('SELECT e.id, u.name AS member_name, c.name AS class_title, e.enrollment_date AS enrolled_at
                                        FROM enrollments e
                                        JOIN users u ON e.user_id = u.id
                                        JOIN classes c ON e.class_id = c.id
                                        ORDER BY e.enrollment_date DESC
                                        LIMIT 5');
$recent_enrollments = $stmt_recent_enrollments->fetchAll();

// Fetch active/inactive member counts for Status Summary
$active_members_count = $pdo->query('SELECT COUNT(*) FROM members WHERE active = 1')->fetchColumn();
$inactive_members_count = $pdo->query('SELECT COUNT(*) FROM members WHERE active = 0')->fetchColumn();
?>
<h2 class="mb-4 fw-bold text-white">Dashboard</h2>

<div class="row g-4">

  <!-- Total Member -->
  <div class="col-md-4">
    <div class="card dashboard-card h-100">
      <div class="card-body text-center">
        <div class="card-icon mb-3">
          <img src="img/4.jpeg" class="card-icon-img">
        </div>
        <h6 class="card-title">Total Member</h6>
        <div class="stat-number stat-member"><?= $member_count; ?></div>
      </div>
    </div>
  </div>

  <!-- Total Kelas -->
  <div class="col-md-4">
    <div class="card dashboard-card h-100">
      <div class="card-body text-center">
        <div class="card-icon mb-3">
          <img src="img/6.jpeg" class="card-icon-img">
        </div>
        <h6 class="card-title">Total Kelas</h6>
        <div class="stat-number stat-class"><?= $class_count; ?></div>
      </div>
    </div>
  </div>

  <!-- Total Pendaftaran -->
  <div class="col-md-4">
    <div class="card dashboard-card h-100">
      <div class="card-body text-center">
        <div class="card-icon mb-3">
          <img src="img/5.jpeg" class="card-icon-img">
        </div>
        <h6 class="card-title">Total Pendaftaran</h6>
        <div class="stat-number stat-enroll"><?= $enroll_count; ?></div>
      </div>
    </div>
  </div>

</div>

<div class="row mt-4">
    <div class="col-md-6 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <h5 class="card-title text-red-important">Ringkasan Status Member</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center list-item-light-bg">
                        Member Aktif
                        <span class="badge bg-success rounded-pill"><?= $active_members_count; ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center list-item-light-bg">
                        Member Tidak Aktif
                        <span class="badge bg-danger rounded-pill"><?= $inactive_members_count; ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <h5 class="card-title text-black-important">Pendaftaran Terbaru</h5>
                <?php if (!empty($recent_enrollments)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($recent_enrollments as $enrollment): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-secondary">
                                <div>
                                    <strong><?= htmlspecialchars($enrollment['member_name']); ?></strong> terdaftar di <strong><?= htmlspecialchars($enrollment['class_title']); ?></strong>
                                </div>
                                <span class="badge bg-primary rounded-pill"><?= date('d M Y', strtotime($enrollment['enrolled_at'])); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="message-light-bg">Belum ada pendaftaran terbaru.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="mt-5 text-center">
  <div class="d-grid gap-3 d-md-block">
    <?php if (is_owner()): ?>
      <a class="btn btn-primary btn-lg px-4" href="<?= get_base_url(); ?>/members/index.php">
        Kelola Member
      </a>
      <a class="btn btn-info btn-lg px-4" href="<?= get_base_url(); ?>/enrollments/index.php">
        Kelola Pendaftaran
      </a>
    <?php endif; ?>

    <a class="btn btn-secondary btn-lg px-4" href="<?= get_base_url(); ?>/classes/index.php">
      Kelola Kelas
    </a>

    <a class="btn btn-warning btn-lg px-4" href="<?= get_base_url(); ?>/ai.php">
      AI Coach
    </a>
  </div>
</div>