<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2" style="color: #0F1F3F;">
            <?= esc(lang('App.adminLotteryTitle') ?? 'Lottery Management') ?>
        </h1>
        <p class="text-sm" style="color:#6B7280;">
            <?= esc(lang('App.adminLotteryManagementText') ?? 'Run the lottery for verified applications and automatically create allotments.') ?>
        </p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 rounded-md border border-green-300 bg-green-50 px-4 py-3 text-sm" style="color:#166534;">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 rounded-md border border-red-300 bg-red-50 px-4 py-3 text-sm" style="color:#B91C1C;">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <!-- Category-specific Run Lottery Buttons -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">Run Lottery by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
            <button onclick="runCategoryLottery('General')"
                    class="category-lottery-btn px-4 py-2 rounded-full font-semibold text-white text-sm shadow transition"
                    style="background-color: #3B82F6;">
                General
            </button>
            <button onclick="runCategoryLottery('Govt')"
                    class="category-lottery-btn px-4 py-2 rounded-full font-semibold text-white text-sm shadow transition"
                    style="background-color: #10B981;">
                Govt
            </button>
            <button onclick="runCategoryLottery('ST')"
                    class="category-lottery-btn px-4 py-2 rounded-full font-semibold text-white text-sm shadow transition"
                    style="background-color: #F59E0B;">
                ST
            </button>
            <button onclick="runCategoryLottery('SC')"
                    class="category-lottery-btn px-4 py-2 rounded-full font-semibold text-white text-sm shadow transition"
                    style="background-color: #EF4444;">
                SC
            </button>
            <button onclick="runCategoryLottery('Media')"
                    class="category-lottery-btn px-4 py-2 rounded-full font-semibold text-white text-sm shadow transition"
                    style="background-color: #8B5CF6;">
                Media
            </button>
            <button onclick="runCategoryLottery('Transgender')"
                    class="category-lottery-btn px-4 py-2 rounded-full font-semibold text-white text-sm shadow transition"
                    style="background-color: #EC4899;">
                Transgender
            </button>
            <button onclick="runCategoryLottery('Army')"
                    class="category-lottery-btn px-4 py-2 rounded-full font-semibold text-white text-sm shadow transition"
                    style="background-color: #6366F1;">
                Army
            </button>
        </div>
    </div>

    <!-- All Plots Display in Cards -->
    <?php if (!empty($plots)): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">Available Plots</h2>
        <div id="plot-cards-container" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <?php 
            // Category color mapping
            $categoryColors = [
                'EWS' => '#EF4444',
                'LIG' => '#F59E0B',
                'Residential' => '#10B981',
                'General' => '#3B82F6',
                'Govt' => '#10B981',
                'ST' => '#F59E0B',
                'SC' => '#EF4444',
                'Media' => '#8B5CF6',
                'Transgender' => '#EC4899',
                'Army' => '#6366F1',
                'MIG-A' => '#06B6D4',
                'MIG-B' => '#14B8A6',
                'HIG' => '#8B5CF6',
            ];
            
            foreach ($plots as $plot): 
                $category = $plot['category'] ?? 'General';
                $color = $categoryColors[$category] ?? '#6B7280';
                $plotNumber = $plot['plot_number'] ?? 'N/A';
                $area = $plot['area'] ?? null;
            ?>
                <div class="plot-card border rounded-lg p-3 shadow-sm hover:shadow-md transition relative overflow-hidden" 
                     data-plot-number="<?= esc($plotNumber) ?>"
                     data-plot-id="<?= esc($plot['id']) ?>"
                     style="border-color: <?= $color ?>; border-width: 2px;">
                    <div class="text-center relative z-10">
                        <div class="text-xs font-semibold mb-1" style="color: #6B7280;"><?= esc($category) ?></div>
                        <div class="text-lg font-bold mb-1 plot-number" style="color: <?= $color ?>;"><?= esc($plotNumber) ?></div>
                        <?php if ($area): ?>
                            <div class="text-xs" style="color: #9CA3AF;"><?= esc(number_format($area, 2)) ?> sqm</div>
                        <?php else: ?>
                            <div class="text-xs" style="color: #9CA3AF;">Size: N/A</div>
                        <?php endif; ?>
                        <!-- Winner name will be displayed here -->
                        <div class="winner-name hidden mt-2 text-xs font-semibold" style="color: #10B981;"></div>
                    </div>
                    <!-- Animation overlay -->
                    <div class="lottery-animation-overlay absolute inset-0 opacity-0 pointer-events-none"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <p class="text-sm text-center" style="color:#6B7280;">
                <?= esc(lang('App.adminNoPlotsForLottery') ?? 'No available plots found. Please add plots before running the lottery.') ?>
            </p>
        </div>
    <?php endif; ?>

    <!-- Win Plots Section -->
    <?php if (!empty($allottedPlots)): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="font-semibold text-sm" style="color:#374151;">
                <?= esc(lang('App.adminWinPlotsTitle') ?? 'Win Plots (Allotted Plots)') ?>
            </h2>
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                <?= count($allottedPlots) ?> <?= esc(lang('App.adminTotal')) ?>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-xs font-semibold uppercase" style="color:#6B7280;"><?= esc(lang('App.adminPlotNumber') ?? 'Plot Number') ?></th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase" style="color:#6B7280;"><?= esc(lang('App.adminWinnerName') ?? 'Winner Name') ?></th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase" style="color:#6B7280;"><?= esc(lang('App.adminApplicationMobile')) ?></th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase" style="color:#6B7280;"><?= esc(lang('App.adminApplicationId') ?? 'Application ID') ?></th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase" style="color:#6B7280;"><?= esc(lang('App.adminPlotCategory') ?? 'Category') ?></th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase" style="color:#6B7280;"><?= esc(lang('App.adminAllottedDate') ?? 'Allotted Date') ?></th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase" style="color:#6B7280;"><?= esc(lang('App.adminStatus') ?? 'Status') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allottedPlots as $allotment): ?>
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <span class="font-semibold" style="color:#10B981;"><?= esc($allotment['plot_number'] ?? 'N/A') ?></span>
                            </td>
                            <td class="px-4 py-3" style="color:#111827;">
                                <?= esc($allotment['full_name'] ?? $allotment['user_name'] ?? 'N/A') ?>
                            </td>
                            <td class="px-4 py-3" style="color:#111827;">
                                <?= esc($allotment['application_mobile'] ?? $allotment['user_mobile'] ?? 'N/A') ?>
                            </td>
                            <td class="px-4 py-3" style="color:#111827;">
                                #<?= esc($allotment['application_id'] ?? 'N/A') ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php if (!empty($allotment['plot_category'])): ?>
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        <?= esc($allotment['plot_category']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs" style="color:#9CA3AF;"><?= esc(lang('App.notProvided') ?? 'Not set') ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:#6B7280;">
                                <?= isset($allotment['created_at']) ? esc(date('d M Y', strtotime($allotment['created_at']))) : '—' ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php 
                                $status = $allotment['status'] ?? 'provisional';
                                $statusColors = [
                                    'provisional' => ['bg-yellow-50', 'text-yellow-700'],
                                    'confirmed' => ['bg-green-50', 'text-green-700'],
                                    'cancelled' => ['bg-red-50', 'text-red-700'],
                                ];
                                $colors = $statusColors[$status] ?? ['bg-gray-50', 'text-gray-700'];
                                ?>
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold <?= $colors[0] ?> <?= $colors[1] ?>">
                                    <?= esc(ucfirst($status)) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Verified applications table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="font-semibold text-sm" style="color:#374151;">
                <?= esc(lang('App.adminVerifiedApplicationsLabel') ?? 'Verified Applications for Lottery') ?>
            </h2>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                <?= isset($applications) ? count($applications) : 0 ?> <?= esc(lang('App.adminTotal')) ?>
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-xs font-semibold uppercase" style="color:#6B7280;">ID</th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase" style="color:#6B7280;"><?= esc(lang('App.adminApplicationName')) ?></th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase" style="color:#6B7280;"><?= esc(lang('App.adminApplicationMobile')) ?></th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase" style="color:#6B7280;"><?= esc(lang('App.userCategory') ?? 'Category') ?></th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase" style="color:#6B7280;"><?= esc(lang('App.serviceCategory') ?? 'Service Category') ?></th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase" style="color:#6B7280;"><?= esc(lang('App.adminJoined')) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($applications)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center" style="color:#9CA3AF;">
                                <?= esc(lang('App.adminNoVerificationFound') ?? 'No verified applications found.') ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($applications as $app): ?>
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-4 py-3" style="color:#111827;">#<?= esc($app['id']) ?></td>
                                <td class="px-4 py-3" style="color:#111827;"><?= esc($app['full_name'] ?? $app['user_name'] ?? 'N/A') ?></td>
                                <td class="px-4 py-3" style="color:#111827;"><?= esc($app['mobile'] ?? 'N/A') ?></td>
                                <td class="px-4 py-3">
                                    <?php if (!empty($app['user_category'])): ?>
                                        <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                            <?= esc($app['user_category']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs" style="color:#9CA3AF;"><?= esc(lang('App.notProvided') ?? 'Not set') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if (!empty($app['income_category'])): ?>
                                        <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                            <?= esc($app['income_category']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs" style="color:#9CA3AF;"><?= esc(lang('App.notProvided') ?? 'Not set') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-xs" style="color:#6B7280;">
                                    <?= isset($app['created_at']) ? esc(date('d M Y', strtotime($app['created_at']))) : '—' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Lottery modal -->
<div id="lottery-modal" class="fixed inset-0 z-40 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-30"></div>
    <div class="relative z-50 flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold" style="color:#0F1F3F;">
                    <?= esc(lang('App.adminRunLottery') ?? 'Run Lottery') ?>
                    <span id="lottery-category-name" class="text-sm font-normal text-gray-600"></span>
                </h2>
                <button type="button" id="close-lottery-modal" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form id="lottery-form" class="px-6 py-4">
                <?= csrf_field() ?>
                <input type="hidden" name="category" id="lottery-category" value="">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1" style="color:#374151;">
                        <?= esc(lang('App.lotteryRoundNumber') ?? 'Lottery Round Number') ?>
                    </label>
                    <input type="text" name="round_number"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="e.g. 1, 2026-01" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1" style="color:#374151;">
                        <?= esc(lang('App.lotteryRoundName') ?? 'Lottery Round Name') ?>
                    </label>
                    <input type="text" name="round_name"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="<?= esc(lang('App.lotteryRoundNamePlaceholder') ?? 'First Lottery Round') ?>" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1" style="color:#374151;">
                        <?= esc(lang('App.serviceCategory') ?? 'Service Category') ?>
                    </label>
                    <select name="service_category" id="lottery-service-category"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Service Category</option>
                        <option value="EWS">EWS</option>
                        <option value="LIG">LIG</option>
                        <option value="MIG">MIG</option>
                        <option value="Govt">Govt</option>
                        <option value="Soldier">Soldier</option>
                    </select>
                </div>
                <div class="mb-4 flex items-start gap-2">
                    <input id="confirm-run" type="checkbox" name="confirmed" value="1"
                           class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded">
                    <label for="confirm-run" class="text-xs" style="color:#4B5563;">
                        <?= esc(lang('App.lotteryConfirmText') ?? 'I confirm that I want to run the lottery. One random verified applicant will be selected and matched with an available plot of the same category.') ?>
                    </label>
                </div>
                <div id="lottery-error" class="text-sm mb-2 hidden" style="color:#B91C1C;"></div>
                <div id="lottery-success" class="text-sm mb-2 hidden" style="color:#166534;"></div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" id="cancel-lottery"
                            class="px-4 py-2 text-sm rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50">
                        <?= esc(lang('App.cancel') ?? 'Cancel') ?>
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm rounded-md font-semibold text-white"
                            style="background-color:#0747A6;">
                        <?= esc(lang('App.adminRunLottery') ?? 'Run Lottery') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Winners popup modal -->
    <div id="lottery-winners-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative z-50 flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-xl w-full">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold" style="color:#0F1F3F;">
                        <?= esc(lang('App.lotteryResultsTitle') ?? 'Lottery Winners') ?>
                    </h2>
                    <button type="button" id="close-lottery-winners" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div class="px-6 py-4">
                    <div id="lottery-winners-body" class="space-y-3 text-sm" style="color:#374151;">
                        <!-- Winners will be injected here -->
                    </div>
                </div>
                <div class="px-6 py-3 border-t border-gray-200 flex justify-end">
                    <button type="button" id="ok-lottery-winners"
                            class="px-4 py-2 text-sm rounded-md font-semibold text-white"
                            style="background-color:#0747A6;">
                        <?= esc(lang('App.close') ?? 'Close') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Lottery Animation Styles */
    @keyframes lotterySpin {
        0% { transform: rotateY(0deg) scale(1); }
        50% { transform: rotateY(180deg) scale(1.1); }
        100% { transform: rotateY(360deg) scale(1); }
    }

    @keyframes lotteryPulse {
        0%, 100% { 
            opacity: 1;
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
        }
        50% { 
            opacity: 0.8;
            box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
        }
    }

    @keyframes lotteryShimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }

    .lottery-running .plot-card {
        animation: lotterySpin 0.5s infinite, lotteryPulse 1s infinite;
        border-width: 3px !important;
    }

    /* Phase 1: lock screen / preparation */
    .lottery-locked .plot-card {
        opacity: 0.35;
        filter: grayscale(100%);
    }

    /* Phase 2: scanning highlight */
    .plot-card.scan-highlight {
        opacity: 1 !important;
        filter: none !important;
        border-color: #FBBF24 !important; /* amber */
        box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.7);
        background: radial-gradient(circle at top, #FEF3C7 0%, #FFFFFF 45%, #FEF3C7 100%);
    }

    .lottery-running .lottery-animation-overlay {
        opacity: 0.3 !important;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.4),
            transparent
        );
        background-size: 200% 100%;
        animation: lotteryShimmer 1.5s infinite;
    }

    .plot-card.winner-card {
        animation: none !important;
        border-width: 4px !important;
        border-color: #10B981 !important;
        background: linear-gradient(135deg, #D1FAE5 0%, #FFFFFF 100%);
        transform: scale(1.05);
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
    }

    .plot-card.winner-card .winner-name {
        display: block !important;
        animation: fadeInUp 0.5s ease;
    }

    /* Phase 4: waiting / non-winner cards */
    .plot-card.waiting-card {
        opacity: 0.85;
        filter: grayscale(40%);
        border-color: #E5E7EB !important; /* gray-200 */
        background-color: #F9FAFB;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .lottery-countdown {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        font-size: 72px;
        font-weight: bold;
        color: #0747A6;
        text-shadow: 0 0 20px rgba(7, 71, 166, 0.5);
        pointer-events: none;
    }

    /* Animated category buttons */
    .category-lottery-btn {
        position: relative;
        overflow: hidden;
        border-radius: 9999px;
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.18);
        transform: translateY(0) scale(1);
    }

    .category-lottery-btn::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 0% 0%, rgba(255,255,255,0.45), transparent 55%);
        opacity: 0;
        transform: translateX(-40%);
        transition: opacity 0.3s ease, transform 0.5s ease;
        pointer-events: none;
    }

    .category-lottery-btn:hover:not(:disabled) {
        transform: translateY(-1px) scale(1.03);
        box-shadow: 0 18px 35px rgba(15, 23, 42, 0.25);
    }

    .category-lottery-btn:hover:not(:disabled)::before {
        opacity: 1;
        transform: translateX(40%);
    }

    .category-lottery-btn:active:not(:disabled) {
        transform: translateY(1px) scale(0.98);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.18);
    }

    .category-lottery-btn:disabled {
        opacity: 0.55;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .lottery-status-banner {
        position: fixed;
        top: calc(50% + 90px);
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        background-color: rgba(15, 23, 42, 0.9);
        color: #E5E7EB;
        font-size: 0.875rem;
        font-weight: 500;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.5);
        pointer-events: none;
    }
</style>

<script>
    (function () {
        const modal     = document.getElementById('lottery-modal');
        const closeBtn  = document.getElementById('close-lottery-modal');
        const cancelBtn = document.getElementById('cancel-lottery');
        const form      = document.getElementById('lottery-form');
        const errorBox  = document.getElementById('lottery-error');
        const successBox = document.getElementById('lottery-success');
        const categoryInput = document.getElementById('lottery-category');
        const categoryNameSpan = document.getElementById('lottery-category-name');
        let lotteryTimeout = null;
        let countdownInterval = null;
        let scanningTimeout = null;
        let lastScanCard = null;

        // Winners modal elements
        const winnersModal = document.getElementById('lottery-winners-modal');
        const winnersBody  = document.getElementById('lottery-winners-body');
        const winnersClose = document.getElementById('close-lottery-winners');
        const winnersOk    = document.getElementById('ok-lottery-winners');

        // Global function to open modal with category (used only for manual/advanced runs)
        window.openLotteryModal = function(category) {
            if (categoryInput) {
                categoryInput.value = category;
            }
            if (categoryNameSpan) {
                categoryNameSpan.textContent = category ? ' - ' + category : '';
            }
            if (modal) {
                modal.classList.remove('hidden');
            }
        };

        function closeModal() {
            // Stop any running lottery animations
            stopLotteryAnimation();
            
            if (lotteryTimeout) {
                clearTimeout(lotteryTimeout);
                lotteryTimeout = null;
            }
            
            if (modal) {
                modal.classList.add('hidden');
            }
            if (errorBox) {
                errorBox.classList.add('hidden');
                errorBox.textContent = '';
            }
            if (successBox) {
                successBox.classList.add('hidden');
                successBox.textContent = '';
            }
            if (form) {
                form.reset();
                if (categoryInput) {
                    categoryInput.value = '';
                }
                if (categoryNameSpan) {
                    categoryNameSpan.textContent = '';
                }
            }
        }

        // Direct run function for category buttons (no modal, fixed round, no service category)
        window.runCategoryLottery = function (category) {
            if (!form) {
                return;
            }

            // Build form data from existing form, then override fields
            const formData = new FormData(form);
            formData.set('round_number', '1');
            formData.set('round_name', category + '-1');
            formData.set('category', category);
            formData.delete('service_category'); // no service category for direct run
            formData.set('confirmed', '1');

            // Ensure modal is hidden and messages cleared
            if (modal) {
                modal.classList.add('hidden');
            }
            if (errorBox) {
                errorBox.classList.add('hidden');
                errorBox.textContent = '';
            }
            if (successBox) {
                successBox.classList.add('hidden');
                successBox.textContent = '';
            }

            // Start animation & countdown
            startLotteryAnimation();

            fetch('/admin/lottery/run', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                },
                body: formData,
            }).then(function (res) {
                return res.json();
            }).then(function (data) {
                const result = data || {};

                // Always let the full animation run, even if there is an error.
                lotteryTimeout = setTimeout(function () {
                    stopLotteryAnimation();

                    if (result.success) {
                        const allotments = (result.allotments) ? result.allotments : [];
                        displayWinnersOnPlots(allotments);
                        showWinnersPopup(allotments);
                    } else {
                        // Show error message in popup after animation
                        const msg = result.message || 'No eligible applications found for this category.';
                        showWinnersPopup([], msg);
                    }
                }, 30000); // 30 seconds
            }).catch(function () {
                // Network / server error: stop animation immediately and show error
                stopLotteryAnimation();
                if (errorBox) {
                    errorBox.textContent = 'Unexpected error while running lottery.';
                    errorBox.classList.remove('hidden');
                }
            });
        };

        // Lottery animation functions
        function startLotteryAnimation() {
            const plotCards = document.querySelectorAll('.plot-card');
            const plotsContainer = document.getElementById('plot-cards-container');
            const buttons = document.querySelectorAll('.category-lottery-btn');
            
            if (plotsContainer) {
                plotsContainer.classList.add('lottery-running', 'lottery-locked');
            }
            
            plotCards.forEach(function(card) {
                card.classList.add('lottery-running');
            });

            // Disable category buttons during run
            buttons.forEach(function (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-60', 'cursor-not-allowed');
            });

            // Show status + countdown and start scanning
            showCountdown();
            showStatusBanner();
            startScanningAnimation();
        }

        function stopLotteryAnimation() {
            const plotCards = document.querySelectorAll('.plot-card');
            const plotsContainer = document.getElementById('plot-cards-container');
            const buttons = document.querySelectorAll('.category-lottery-btn');
            
            if (plotsContainer) {
                plotsContainer.classList.remove('lottery-running', 'lottery-locked');
            }
            
            plotCards.forEach(function(card) {
                card.classList.remove('lottery-running', 'scan-highlight');
            });

            // Stop scanning
            if (scanningTimeout) {
                clearTimeout(scanningTimeout);
                scanningTimeout = null;
            }
            lastScanCard = null;

            // Hide countdown + status
            hideCountdown();
            hideStatusBanner();

            // Re-enable category buttons after run/error
            buttons.forEach(function (btn) {
                btn.disabled = false;
                btn.classList.remove('opacity-60', 'cursor-not-allowed');
            });
        }

        function showCountdown() {
            let countdown = 30;
            const countdownEl = document.createElement('div');
            countdownEl.className = 'lottery-countdown';
            countdownEl.id = 'lottery-countdown';
            countdownEl.textContent = countdown;
            document.body.appendChild(countdownEl);

            countdownInterval = setInterval(function() {
                countdown--;
                if (countdownEl) {
                    countdownEl.textContent = countdown;
                    if (countdown <= 5) {
                        countdownEl.style.color = '#EF4444';
                        countdownEl.style.transform = 'translate(-50%, -50%) scale(1.2)';
                    }
                }
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                }
            }, 1000);
        }

        function hideCountdown() {
            const countdownEl = document.getElementById('lottery-countdown');
            if (countdownEl) {
                countdownEl.remove();
            }
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
        }

        function showStatusBanner() {
            let banner = document.getElementById('lottery-status-banner');
            if (!banner) {
                banner = document.createElement('div');
                banner.id = 'lottery-status-banner';
                banner.className = 'lottery-status-banner';
                banner.textContent = 'Lottery running... please wait';
                document.body.appendChild(banner);
            }
        }

        function hideStatusBanner() {
            const banner = document.getElementById('lottery-status-banner');
            if (banner) {
                banner.remove();
            }
        }

        function displayWinnersOnPlots(allotments) {
            if (!allotments || allotments.length === 0) {
                return;
            }

            const winnerPlotNumbers = new Set();

            allotments.forEach(function(allotment) {
                const plotNumber = allotment.plot.plot_number;
                winnerPlotNumbers.add(String(plotNumber));
                const winnerName = allotment.winner.name || 'Winner';
                const applicationId = allotment.winner.application_id;

                // Find the plot card by plot_number
                const plotCard = document.querySelector('.plot-card[data-plot-number="' + plotNumber + '"]');
                
                if (plotCard) {
                    // Add winner class
                    plotCard.classList.add('winner-card');
                    
                    // Find and update winner name element
                    const winnerNameEl = plotCard.querySelector('.winner-name');
                    if (winnerNameEl) {
                        winnerNameEl.textContent = 'Winner: ' + winnerName;
                        winnerNameEl.classList.remove('hidden');
                    }

                    // Add confetti effect (optional)
                    createConfetti(plotCard);
                }
            });

            // Mark non-winner cards as waiting (soft gray)
            const plotCards = document.querySelectorAll('.plot-card');
            plotCards.forEach(function (card) {
                const num = card.getAttribute('data-plot-number');
                if (!winnerPlotNumbers.has(String(num))) {
                    card.classList.add('waiting-card');
                }
            });
        }

        function showWinnersPopup(allotments, message) {
            if (!winnersModal || !winnersBody) {
                return;
            }

            winnersBody.innerHTML = '';

            if (message) {
                const p = document.createElement('p');
                p.textContent = message;
                p.className = 'text-sm text-center text-gray-700';
                winnersBody.appendChild(p);
            } else if (!allotments || allotments.length === 0) {
                const p = document.createElement('p');
                p.textContent = 'No winners selected.';
                p.className = 'text-sm text-center text-gray-700';
                winnersBody.appendChild(p);
            } else {
                allotments.forEach(function (allotment, index) {
                    const row = document.createElement('div');
                    row.className = 'flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 border rounded-md px-3 py-2';

                    const left = document.createElement('div');
                    left.innerHTML = '<span class=\"font-semibold mr-2\">#' + (index + 1) + '</span>' +
                        (allotment.winner.name || 'Applicant');

                    const right = document.createElement('div');
                    right.className = 'text-xs text-gray-600';
                    right.textContent = 'App #' + allotment.winner.application_id +
                        ' → Plot ' + (allotment.plot.plot_number || '') +
                        ' [' + (allotment.plot.category || '') + ']';

                    row.appendChild(left);
                    row.appendChild(right);
                    winnersBody.appendChild(row);
                });
            }

            winnersModal.classList.remove('hidden');
        }

        function createConfetti(element) {
            const colors = ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6'];
            const rect = element.getBoundingClientRect();
            
            for (let i = 0; i < 20; i++) {
                const confetti = document.createElement('div');
                confetti.style.position = 'fixed';
                confetti.style.left = (rect.left + rect.width / 2) + 'px';
                confetti.style.top = (rect.top + rect.height / 2) + 'px';
                confetti.style.width = '8px';
                confetti.style.height = '8px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.borderRadius = '50%';
                confetti.style.pointerEvents = 'none';
                confetti.style.zIndex = '10000';
                confetti.style.opacity = '1';
                
                document.body.appendChild(confetti);
                
                const angle = (Math.PI * 2 * i) / 20;
                const velocity = 100 + Math.random() * 50;
                const vx = Math.cos(angle) * velocity;
                const vy = Math.sin(angle) * velocity;
                
                let x = rect.left + rect.width / 2;
                let y = rect.top + rect.height / 2;
                let opacity = 1;
                
                const animate = function() {
                    x += vx * 0.1;
                    y += vy * 0.1 + 2; // gravity
                    opacity -= 0.02;
                    
                    confetti.style.left = x + 'px';
                    confetti.style.top = y + 'px';
                    confetti.style.opacity = opacity;
                    
                    if (opacity > 0) {
                        requestAnimationFrame(animate);
                    } else {
                        confetti.remove();
                    }
                };
                
                requestAnimationFrame(animate);
            }
        }

        function startScanningAnimation() {
            const plotCards = document.querySelectorAll('.plot-card');
            if (!plotCards.length) return;

            const totalDuration = 30000; // 30 seconds - match countdown / lottery duration
            const startedAt = Date.now();

            function tick() {
                const elapsed = Date.now() - startedAt;
                const progress = elapsed / totalDuration;

                if (progress >= 1) {
                    if (lastScanCard) {
                        lastScanCard.classList.remove('scan-highlight');
                        lastScanCard = null;
                    }
                    scanningTimeout = null;
                    return;
                }

                // Remove previous highlight
                if (lastScanCard) {
                    lastScanCard.classList.remove('scan-highlight');
                }

                // Pick a random card to highlight
                const idx = Math.floor(Math.random() * plotCards.length);
                const card = plotCards[idx];
                card.classList.add('scan-highlight');
                lastScanCard = card;

                // Speed curve: slow → fast → slow
                let delay;
                if (progress < 0.3) {
                    delay = 180; // starting slow
                } else if (progress < 0.7) {
                    delay = 70;  // fastest in the middle
                } else {
                    delay = 220; // slow near the end
                }

                scanningTimeout = setTimeout(tick, delay);
            }

            tick();
        }

        if (closeBtn)  closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
        if (winnersClose) winnersClose.addEventListener('click', function () {
            winnersModal.classList.add('hidden');
            window.location.reload();
        });
        if (winnersOk) winnersOk.addEventListener('click', function () {
            winnersModal.classList.add('hidden');
            window.location.reload();
        });

        // Close on outside click
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal || e.target.classList.contains('bg-black')) {
                    closeModal();
                }
            });
        }

        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                if (errorBox) {
                    errorBox.classList.add('hidden');
                    errorBox.textContent = '';
                }
                if (successBox) {
                    successBox.classList.add('hidden');
                    successBox.textContent = '';
                }

                const formData = new FormData(form);

                // Close modal to show plot cards (without stopping animation)
                if (modal) {
                    modal.classList.add('hidden');
                }
                
                // Start lottery animation
                startLotteryAnimation();

                fetch('/admin/lottery/run', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                    },
                    body: formData,
                }).then(function (res) {
                    return res.json();
                }).then(function (data) {
                    const result = data || {};

                    // Always let the full animation run, even if there is an error.
                    lotteryTimeout = setTimeout(function() {
                        stopLotteryAnimation();

                        if (result.success) {
                            const allotments = (result.allotments) ? result.allotments : [];
                            displayWinnersOnPlots(allotments);
                            showWinnersPopup(allotments);
                        } else {
                            const msg = result.message || 'No eligible applications found for this category.';
                            showWinnersPopup([], msg);
                        }
                    }, 30000); // 30 seconds delay
                }).catch(function () {
                    stopLotteryAnimation();
                    if (errorBox) {
                        errorBox.textContent = 'Unexpected error while running lottery.';
                        errorBox.classList.remove('hidden');
                    }
                });
            });
        }
    })();
</script>
