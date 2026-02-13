<?= view('layout/header') ?>

<style>
    /* Fixed header is already handled in header.php */
    header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        width: 100%;
    }
    
    .admin-sidebar {
        background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
        max-height: calc(100vh - 80px);
        width: 260px;
        position: fixed;
        left: 0;
        top: 80px;
        overflow-y: scroll;
        z-index: 999;
    }
    .admin-main {
        margin-left: 260px;
        min-height: calc(100vh - 80px);
        background-color: #f3f4f6;
        padding: 24px;
        margin-top: 0;
    }
    .admin-nav-item {
        color: #e5e7eb;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s;
        border-radius: 8px;
        margin: 4px 12px;
        text-decoration: none;
    }
    .admin-nav-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    .admin-nav-item.active {
        background-color: #3b82f6;
        color: #ffffff;
    }
    .admin-nav-section {
        margin-top: 24px;
        padding: 0 12px;
    }
    .admin-nav-section-title {
        color: #9ca3af;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        padding: 0 16px;
    }
    .dashboard-banner {
        background: linear-gradient(135deg, #0747A6 0%, #0F1F3F 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
        color: white;
    }
    .metric-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    .welcome-banner {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="flex" style="margin-top: 0;">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
      

        <?php 
        $currentUri = uri_string();
        $isActive = function($path) use ($currentUri) {
            return strpos($currentUri, $path) !== false ? 'active' : '';
        };
        // Special handling for verification routes to avoid both being active
        $isVerificationQueue = ($currentUri === 'admin/verification' || $currentUri === 'admin/verification/');
        $isVerifiedApplications = ($currentUri === 'admin/verification/verified');
        ?>
        <div class="admin-nav-section">
            <div class="admin-nav-section-title"><?= esc(lang('App.adminSidebarMain')) ?></div>
            <a href="/admin/dashboard" class="admin-nav-item <?= $isActive('admin/dashboard') ?>">
                <span>📊</span>
                <span><?= esc(lang('App.adminDashboardTitle')) ?></span>
            </a>
        </div>

        <div class="admin-nav-section">
            <div class="admin-nav-section-title"><?= esc(lang('App.adminSidebarUsers')) ?></div>
            <a href="/admin/applications" class="admin-nav-item <?= $isActive('admin/applications') ?>">
                <span>👥</span>
                <span><?= esc(lang('App.adminApplicationsTitle')) ?></span>
            </a>
            <a href="/admin/users" class="admin-nav-item <?= $isActive('admin/users') ?>">
                <span>📱</span>
                <span><?= esc(lang('App.adminRegisteredUsersTitle') ?? 'Registered Users') ?></span>
            </a>
        </div>

        <div class="admin-nav-section">
            <div class="admin-nav-section-title"><?= esc(lang('App.adminSidebarVerification')) ?></div>
            <a href="/admin/verification" class="admin-nav-item <?= $isVerificationQueue ? 'active' : '' ?>">
                <span>✅</span>
                <span><?= esc(lang('App.adminVerificationQueue')) ?></span>
            </a>
            <a href="/admin/verification/verified" class="admin-nav-item <?= $isVerifiedApplications ? 'active' : '' ?>">
                <span>✓</span>
                <span><?= esc(lang('App.adminVerifiedApplications') ?? 'Verified Applications') ?></span>
            </a>
        </div>

        <div class="admin-nav-section">
            <div class="admin-nav-section-title"><?= esc(lang('App.adminSidebarPayments')) ?></div>
            <a href="/admin/payments" class="admin-nav-item <?= $isActive('admin/payments') ?>">
                <span>💳</span>
                <span><?= esc(lang('App.adminPaymentsTitle')) ?></span>
            </a>
        </div>

        <div class="admin-nav-section">
            <div class="admin-nav-section-title"><?= esc(lang('App.adminSidebarLottery')) ?></div>
            <a href="/admin/lottery" class="admin-nav-item <?= $isActive('admin/lottery') ?>">
                <span>🎲</span>
                <span><?= esc(lang('App.adminLotteryManagement')) ?></span>
            </a>
        </div>

        <div class="admin-nav-section">
            <div class="admin-nav-section-title"><?= esc(lang('App.adminSidebarAllotment')) ?></div>
            <a href="/admin/allotments" class="admin-nav-item <?= $isActive('admin/allotments') ?>">
                <span>🏠</span>
                <span><?= esc(lang('App.adminAllotmentsTitle')) ?></span>
            </a>
            <a href="/admin/plots" class="admin-nav-item <?= $isActive('admin/plots') ?>">
                <span>📍</span>
                <span><?= esc(lang('App.adminPlotsTitle')) ?></span>
            </a>
        </div>

        <div class="admin-nav-section">
            <div class="admin-nav-section-title"><?= esc(lang('App.adminSidebarReportsSystem')) ?></div>
            <a href="/admin/reports" class="admin-nav-item <?= $isActive('admin/reports') ?>">
                <span>📄</span>
                <span><?= esc(lang('App.adminReportsTitle')) ?></span>
            </a>
            <a href="/admin/schemes" class="admin-nav-item <?= $isActive('admin/schemes') ?>">
                <span>📋</span>
                <span><?= esc(lang('App.adminSchemesTitle')) ?></span>
            </a>
            <a href="/admin/settings" class="admin-nav-item <?= $isActive('admin/settings') ?>">
                <span>⚙️</span>
                <span><?= esc(lang('App.adminSidebarSettings')) ?></span>
            </a>
            <a href="/auth/logout" class="admin-nav-item">
                <span>🚪</span>
                <span><?= esc(lang('App.adminSidebarLogout')) ?></span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main flex-1">
