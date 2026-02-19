<div class="container mx-auto px-4 py-8 max-w-6xl">
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        <?= esc(lang('App.lotteryResultsTitle')) ?>
    </h1>

    <?php if ($userWon && $userAllotment): ?>
    <!-- Congratulations Card for Winner -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-400 rounded-lg p-6 mb-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                    <span class="text-2xl text-white">🎉</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold" style="color: #065F46;">
                        <?= esc(lang('App.lotteryCongratulations') ?? 'Congratulations!') ?>
                    </h2>
                    <p class="text-sm" style="color: #047857;">
                        <?= esc(lang('App.lotteryYouWon') ?? 'You are a winner in the lottery!') ?>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Winner Details Card -->
        <div class="bg-white rounded-lg p-5 border border-green-200">
            <h3 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.lotteryYourAllotment') ?? 'Your Allotment Details') ?>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm mb-1" style="color: #6B7280;"><?= esc(lang('App.lotteryAllotmentId') ?? 'Allotment ID') ?></p>
                    <p class="font-semibold" style="color: #111827;">#<?= esc($userAllotment['id']) ?></p>
                </div>
                <div>
                    <p class="text-sm mb-1" style="color: #6B7280;"><?= esc(lang('App.lotteryApplicationId') ?? 'Application ID') ?></p>
                    <p class="font-semibold" style="color: #111827;">#<?= esc($userApplication['id']) ?></p>
                </div>
                <div>
                    <p class="text-sm mb-1" style="color: #6B7280;"><?= esc(lang('App.lotteryPlotNumber') ?? 'Plot Number') ?></p>
                    <p class="font-semibold text-lg" style="color: #065F46;"><?= esc($userAllotment['plot_number']) ?></p>
                </div>
                <?php if (!empty($userAllotment['plot_area'])): ?>
                <div>
                    <p class="text-sm mb-1" style="color: #6B7280;"><?= esc(lang('App.lotteryPlotSize') ?? 'Plot Size') ?></p>
                    <p class="font-semibold" style="color: #111827;"><?= esc($userAllotment['plot_area']) ?> <?= esc(lang('App.lotterySqFt') ?? 'sq ft') ?></p>
                </div>
                <?php endif; ?>
                <div>
                    <p class="text-sm mb-1" style="color: #6B7280;"><?= esc(lang('App.lotteryBlockName') ?? 'Block Name') ?></p>
                    <p class="font-semibold" style="color: #111827;"><?= esc($userAllotment['block_name'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <p class="text-sm mb-1" style="color: #6B7280;"><?= esc(lang('App.lotteryStatus') ?? 'Status') ?></p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?= $userAllotment['status'] === 'final' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                        <?= esc(ucfirst($userAllotment['status'])) ?>
                    </span>
                </div>
                <div>
                    <p class="text-sm mb-1" style="color: #6B7280;"><?= esc(lang('App.lotteryAllottedDate') ?? 'Allotted Date') ?></p>
                    <p class="font-semibold" style="color: #111827;">
                        <?= esc(date('d M Y', strtotime($userAllotment['created_at']))) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- All Lottery Results Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold" style="color: #0F1F3F;">
                <?= esc(lang('App.lotteryAllResults') ?? 'All Lottery Results') ?>
            </h2>
            <p class="text-sm mt-1" style="color: #6B7280;">
                <?= esc(lang('App.lotteryResultsDescription') ?? 'List of all winners selected through the lottery process.') ?>
            </p>
        </div>

        <?php if (empty($allAllotments)): ?>
        <div class="p-8 text-center">
            <p class="text-gray-500 mb-4"><?= esc(lang('App.lotteryNoResults') ?? 'No lottery results available yet.') ?></p>
            <p class="text-sm text-gray-400"><?= esc(lang('App.lotteryResultsWillAppear') ?? 'Results will appear here once the lottery is conducted.') ?></p>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase" style="color: #6B7280;"><?= esc(lang('App.lotteryRank') ?? 'Rank') ?></th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase" style="color: #6B7280;"><?= esc(lang('App.lotteryWinnerName') ?? 'Winner Name') ?></th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase" style="color: #6B7280;"><?= esc(lang('App.lotteryPlotNumber') ?? 'Plot Number') ?></th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase" style="color: #6B7280;"><?= esc(lang('App.lotteryBlockName') ?? 'Block') ?></th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase" style="color: #6B7280;"><?= esc(lang('App.lotteryStatus') ?? 'Status') ?></th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase" style="color: #6B7280;"><?= esc(lang('App.lotteryDate') ?? 'Date') ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($allAllotments as $index => $allotment): ?>
                    <tr class="hover:bg-gray-50 <?= $userWon && $userAllotment && $userAllotment['id'] == $allotment['id'] ? 'bg-green-50' : '' ?>">
                        <td class="px-6 py-4">
                            <span class="font-semibold" style="color: #111827;">#<?= esc($index + 1) ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span style="color: #111827;"><?= esc($allotment['full_name'] ?? 'N/A') ?></span>
                            <?php if ($userWon && $userAllotment && $userAllotment['id'] == $allotment['id']): ?>
                            <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-800 rounded text-xs font-semibold">You</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold" style="color: #065F46;"><?= esc($allotment['plot_number']) ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span style="color: #111827;"><?= esc($allotment['block_name'] ?? 'N/A') ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold <?= $allotment['status'] === 'final' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                <?= esc(ucfirst($allotment['status'])) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm" style="color: #6B7280;">
                                <?= esc(date('d M Y', strtotime($allotment['created_at']))) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
