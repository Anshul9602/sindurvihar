<!DOCTYPE html>
<html lang="<?= esc(service('request')->getLocale()) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc(lang('App.siteTitle')) ?></title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="/assets/css/housing-portal.css">
<style>
section{
    padding: 40px 0;
}

/* Fixed Header */
.fixed-header {
    position: fixed !important;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    width: 100%;
    background-color: #FFFFFF !important;
}

/* Add padding to main content to account for fixed header */
main.flex-grow {
    padding-top: 90px;
}

/* For admin pages, the header is already included, so we just need to ensure it's fixed */
body > header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

</style>

</head>
<body class="min-h-screen flex flex-col" style="background-color:#FFFFFF; font-family:'Poppins', Arial, Helvetica, sans-serif;">

<header class="border-b relative rounded-b-lg shadow-sm fixed-header" style="background-color:#FFFFFF; color:#1F2937; border-bottom-color:#E5E7EB;">
    <!-- Tricolor accent -->
    <div class="absolute top-0 left-0 right-0 h-0.5 flex rounded-t-lg">
        <div class="flex-1" style="background-color:#FF9933;"></div>
        <div class="flex-1" style="background-color:#FFFFFF;"></div>
        <div class="flex-1" style="background-color:#138808;"></div>
    </div>

    <div class="container mx-auto px-4 py-2">
        <nav class="flex items-center justify-between">
            <a href="/" class="flex items-center group">
                <img
                    src="/assets/housing/raj-logo.png"
                    alt="Rajasthan Government Logo"
                    class="object-contain h-12 md:h-14 lg:h-16 w-auto zoom-in-zoom-out"
                >
            </a>

            <!-- Mobile menu button -->
            <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition" aria-label="Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#0747A6;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Desktop navigation -->
            <div class="hidden md:flex items-center space-x-4">
                <ul class="flex space-x-4">
                    <li>
                        <a href="<?= site_url('/') ?>"
                           class="gov-hover font-medium text-xs md:text-sm px-3 py-1.5 rounded-lg"
                           style="color:#0747A6;"
                        >
                            <?= esc(lang('App.navHome')) ?>
                        </a>
                    </li>
                    <?php if (session()->has('user_id')): ?>
                        <li>
                            <a href="<?= site_url('user/dashboard') ?>"
                               class="gov-hover font-medium text-xs md:text-sm px-3 py-1.5 rounded-lg hover:bg-gray-100"
                               style="color:#0747A6;"
                            >
                                <?= esc(lang('App.navDashboard')) ?>
                            </a>
                        </li>
                        <li>
                            <span class="font-medium text-xs md:text-sm px-3 py-1.5" style="color:#4B5563;">
                                <?= esc(session()->get('user_name')) ?>
                            </span>
                        </li>
                        <li>
                            <a href="<?= site_url('auth/logout') ?>"
                               class="gov-hover font-medium text-xs md:text-sm px-3 py-1.5 rounded-lg hover:bg-gray-100"
                               style="color:#DC2626;"
                            >
                                <?= esc(lang('App.navLogout')) ?>
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="<?= site_url('auth/login') ?>"
                               class="gov-hover font-medium text-xs md:text-sm px-3 py-1.5 rounded-lg hover:bg-gray-100"
                               style="color:#0747A6;"
                            >
                                <?= esc(lang('App.navUserPortal')) ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?= site_url('admin/login') ?>"
                           class="gov-hover font-medium text-xs md:text-sm px-3 py-1.5 rounded-lg hover:bg-gray-100"
                           style="color:#0747A6;"
                        >
                                <?= esc(lang('App.navAdminPortal')) ?>
                        </a>
                    </li>
                </ul>
                <!-- Language switcher -->
                <form action="<?= site_url('lang/switch') ?>" method="POST" class="ml-4">
                    <label for="lang-select" class="sr-only"><?= esc(lang('App.languageLabel')) ?></label>
                    <select id="lang-select" name="language"
                            class="border rounded px-2 py-1 text-xs md:text-sm"
                            onchange="this.form.submit()">
                        <?php $currentLang = session()->get('language') ?: service('request')->getLocale(); ?>
                        <option value="en" <?= $currentLang === 'en' ? 'selected' : '' ?>>
                            <?= esc(lang('App.languageEn')) ?>
                        </option>
                        <option value="hi" <?= $currentLang === 'hi' ? 'selected' : '' ?>>
                            <?= esc(lang('App.languageHi')) ?>
                        </option>
                    </select>
                </form>
            </div>
        </nav>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden md:hidden">
        <div class="fixed inset-y-0 right-0 w-64 bg-white shadow-xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out" id="mobile-menu">
            <div class="flex flex-col h-full">
                <!-- Mobile menu header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <span class="font-semibold text-lg" style="color:#0747A6;"><?= esc(lang('App.navMenu')) ?></span>
                    <button id="mobile-menu-close" class="p-2 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#374151;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Mobile menu items -->
                <div class="flex-1 overflow-y-scroll py-4">
                    <ul class="space-y-1 px-4">
                        <li>
                            <a href="<?= site_url('/') ?>"
                               class="block px-4 py-3 rounded-lg font-medium hover:bg-gray-100 transition"
                               style="color:#0747A6;"
                               onclick="closeMobileMenu()">
                                <?= esc(lang('App.navHome')) ?>
                            </a>
                        </li>
                        <?php if (session()->has('user_id')): ?>
                            <li>
                                <a href="<?= site_url('user/dashboard') ?>"
                                   class="block px-4 py-3 rounded-lg font-medium hover:bg-gray-100 transition"
                                   style="color:#0747A6;"
                                   onclick="closeMobileMenu()">
                                    <?= esc(lang('App.navDashboard')) ?>
                                </a>
                            </li>
                            <li>
                                <div class="px-4 py-3 font-medium" style="color:#4B5563;">
                                    <?= esc(session()->get('user_name')) ?>
                                </div>
                            </li>
                            <li>
                                <a href="<?= site_url('auth/logout') ?>"
                                   class="block px-4 py-3 rounded-lg font-medium hover:bg-gray-100 transition"
                                   style="color:#DC2626;"
                                   onclick="closeMobileMenu()">
                                    <?= esc(lang('App.navLogout')) ?>
                                </a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?= site_url('auth/login') ?>"
                                   class="block px-4 py-3 rounded-lg font-medium hover:bg-gray-100 transition"
                                   style="color:#0747A6;"
                                   onclick="closeMobileMenu()">
                                    <?= esc(lang('App.navUserPortal')) ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a href="<?= site_url('admin/login') ?>"
                               class="block px-4 py-3 rounded-lg font-medium hover:bg-gray-100 transition"
                               style="color:#0747A6;"
                               onclick="closeMobileMenu()">
                                <?= esc(lang('App.navAdminPortal')) ?>
                            </a>
                        </li>
                        <li class="border-t pt-4 mt-4">
                            <form action="<?= site_url('lang/switch') ?>" method="POST" class="px-4">
                                <label for="lang-select-mobile" class="block text-sm font-medium mb-2" style="color:#374151;">
                                    <?= esc(lang('App.languageLabel')) ?>
                                </label>
                                <select id="lang-select-mobile" name="language"
                                        class="w-full border rounded px-3 py-2 text-sm"
                                        onchange="this.form.submit()">
                                    <?php $currentLang = session()->get('language') ?: service('request')->getLocale(); ?>
                                    <option value="en" <?= $currentLang === 'en' ? 'selected' : '' ?>>
                                        <?= esc(lang('App.languageEn')) ?>
                                    </option>
                                    <option value="hi" <?= $currentLang === 'hi' ? 'selected' : '' ?>>
                                        <?= esc(lang('App.languageHi')) ?>
                                    </option>
                                </select>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
(function() {
    const menuBtn = document.getElementById('mobile-menu-btn');
    const menuOverlay = document.getElementById('mobile-menu-overlay');
    const menuPanel = document.getElementById('mobile-menu');
    const closeBtn = document.getElementById('mobile-menu-close');

    function openMobileMenu() {
        menuOverlay.classList.remove('hidden');
        setTimeout(() => {
            menuPanel.classList.remove('translate-x-full');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        menuPanel.classList.add('translate-x-full');
        setTimeout(() => {
            menuOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    if (menuBtn) {
        menuBtn.addEventListener('click', openMobileMenu);
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeMobileMenu);
    }

    if (menuOverlay) {
        menuOverlay.addEventListener('click', function(e) {
            if (e.target === menuOverlay) {
                closeMobileMenu();
            }
        });
    }

    // Make closeMobileMenu available globally for onclick handlers
    window.closeMobileMenu = closeMobileMenu;
})();
</script>


<main class="flex-grow">

