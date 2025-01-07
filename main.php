<?php $this->extend('template'); ?>

<?php $this->section('navbar'); ?>
<!-- Put the menu code here -->
<ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('dashboard') ?>">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('user') ?>">User Management</a>
    </li>

    <?php if (session()->get('role') == 2): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('laporan') ?>">Laporan</a>
        </li>
    <?php endif; ?>
</ul>
<?php $this->endSection(); ?>
