<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold" style="color: #0F1F3F;">
                <?= esc(lang('App.adminLotteryTitle') ?? 'Lottery Management') ?>
            </h1>
            <p class="text-sm mt-2" style="color:#6B7280;">
                <?= esc(lang('App.adminLotteryManagementText') ?? 'Run the lottery for verified applications and automatically create allotments.') ?>
            </p>
        </div>

        <button id="open-lottery-modal"
                class="px-5 py-2 rounded-md font-semibold text-white text-sm shadow"
                style="background-color:#0747A6;">
            <?= esc(lang('App.adminRunLottery') ?? 'Run Lottery') ?>
        </button>
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

    <!-- Plot summary by category -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <?php if (!empty($plotsByCategory)): ?>
            <?php foreach ($plotsByCategory as $cat => $info): ?>
                <div class="bg-white border rounded-lg p-4 shadow-sm">
                    <h3 class="text-sm font-semibold mb-1" style="color:#0F1F3F;"><?= esc($cat) ?></h3>
                    <p class="text-xs mb-1" style="color:#6B7280;">
                        <?= esc($info['count']) ?> <?= esc(lang('App.adminPlotsTitle') ?? 'plots') ?>
                    </p>
                    <?php if (!empty($info['examples'])): ?>
                        <p class="text-xs" style="color:#9CA3AF;">
                            <?= esc(lang('App.adminExamples') ?? 'Examples') ?>:
                            <?= esc(implode(', ', array_filter($info['examples']))) ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white border rounded-lg p-4 shadow-sm md:col-span-3">
                <p class="text-sm" style="color:#6B7280;">
                    <?= esc(lang('App.adminNoPlotsForLottery') ?? 'No available plots found. Please add plots before running the lottery.') ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

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
                </h2>
                <button type="button" id="close-lottery-modal" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form id="lottery-form" class="px-6 py-4">
                <?= csrf_field() ?>
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
</div>

<script>
    (function () {
        const openBtn   = document.getElementById('open-lottery-modal');
        const modal     = document.getElementById('lottery-modal');
        const closeBtn  = document.getElementById('close-lottery-modal');
        const cancelBtn = document.getElementById('cancel-lottery');
        const form      = document.getElementById('lottery-form');
        const errorBox  = document.getElementById('lottery-error');
        const successBox = document.getElementById('lottery-success');

        function openModal() {
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeModal() {
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
            }
        }

        if (openBtn)   openBtn.addEventListener('click', openModal);
        if (closeBtn)  closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

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

                fetch('/admin/lottery/run', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                    },
                    body: formData,
                }).then(function (res) {
                    return res.json();
                }).then(function (data) {
                    if (!data.success) {
                        if (errorBox) {
                            errorBox.textContent = data.message || 'Failed to run lottery.';
                            errorBox.classList.remove('hidden');
                        }
                        return;
                    }

                    if (successBox) {
                        let msg = data.message || 'Lottery run successfully.';
                        if (data.winner && data.plot) {
                            msg += ' Winner Application #' + data.winner.application_id +
                                ' (' + (data.winner.name || 'Applicant') + '), Plot ' + (data.plot.plot_number || '') +
                                ' [' + (data.plot.category || '') + '].';
                        }
                        successBox.textContent = msg;
                        successBox.classList.remove('hidden');
                    }

                    // Optionally refresh page after short delay to show updated counts
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                }).catch(function () {
                    if (errorBox) {
                        errorBox.textContent = 'Unexpected error while running lottery.';
                        errorBox.classList.remove('hidden');
                    }
                });
            });
        }
    })();
</script>
