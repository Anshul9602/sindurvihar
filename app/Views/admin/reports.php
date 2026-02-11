<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6" style="color: #0F1F3F;">
        Reports &amp; Analytics
    </h1>

    <!-- Summary cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-sm font-semibold mb-2" style="color:#4B5563;">Applications Summary</h2>
            <ul class="text-sm space-y-1" style="color:#374151;">
                <li>Draft: <span class="font-semibold"><?= esc($appStatus['draft'] ?? 0) ?></span></li>
                <li>Submitted: <span class="font-semibold"><?= esc($appStatus['submitted'] ?? 0) ?></span></li>
                <li>Under Verification: <span class="font-semibold"><?= esc($appStatus['under_verification'] ?? 0) ?></span></li>
                <li>Verified: <span class="font-semibold"><?= esc($appStatus['verified'] ?? 0) ?></span></li>
                <li>Rejected: <span class="font-semibold"><?= esc($appStatus['rejected'] ?? 0) ?></span></li>
            </ul>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-sm font-semibold mb-2" style="color:#4B5563;">Payments Summary</h2>
            <ul class="text-sm space-y-1" style="color:#374151;">
                <li>
                    Completed: 
                    <span class="font-semibold"><?= esc($paymentStatus['completed']['count'] ?? 0) ?></span>
                    &mdash; ₹<?= number_format($paymentStatus['completed']['amount'] ?? 0, 2) ?>
                </li>
                <li>
                    Pending: 
                    <span class="font-semibold"><?= esc($paymentStatus['pending']['count'] ?? 0) ?></span>
                    &mdash; ₹<?= number_format($paymentStatus['pending']['amount'] ?? 0, 2) ?>
                </li>
                <li>
                    Failed: 
                    <span class="font-semibold"><?= esc($paymentStatus['failed']['count'] ?? 0) ?></span>
                    &mdash; ₹<?= number_format($paymentStatus['failed']['amount'] ?? 0, 2) ?>
                </li>
            </ul>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-sm font-semibold mb-2" style="color:#4B5563;">Plots Summary</h2>
            <ul class="text-sm space-y-1" style="color:#374151;">
                <li>Available: <span class="font-semibold"><?= esc($plotStatus['available'] ?? 0) ?></span></li>
                <li>Allocated: <span class="font-semibold"><?= esc($plotStatus['allocated'] ?? 0) ?></span></li>
                <li>Reserved: <span class="font-semibold"><?= esc($plotStatus['reserved'] ?? 0) ?></span></li>
            </ul>
        </div>
    </div>

    <!-- Recent activity tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Applications -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-semibold text-sm" style="color:#374151;">Recent Applications</h3>
                <span class="text-xs text-gray-500"><?= esc(count($recentApplications ?? [])) ?> rows</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentApplications)): ?>
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-400">No data</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentApplications as $app): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2"><?= esc($app['id']) ?></td>
                                    <td class="px-4 py-2"><?= esc($app['full_name'] ?? $app['user_name'] ?? 'N/A') ?></td>
                                    <td class="px-4 py-2 text-xs">
                                        <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 font-semibold">
                                            <?= esc(ucfirst(str_replace('_', ' ', $app['status'] ?? 'draft'))) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-semibold text-sm" style="color:#374151;">Recent Payments</h3>
                <span class="text-xs text-gray-500"><?= esc(count($recentPayments ?? [])) ?> rows</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Application</th>
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentPayments)): ?>
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-400">No data</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentPayments as $pay): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2"><?= esc($pay['id']) ?></td>
                                    <td class="px-4 py-2"><?= esc($pay['application_id'] ?? 'N/A') ?></td>
                                    <td class="px-4 py-2">₹<?= number_format($pay['amount'] ?? 0, 2) ?></td>
                                    <td class="px-4 py-2 text-xs">
                                        <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 font-semibold">
                                            <?= esc(ucfirst($pay['status'] ?? 'pending')) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Plots -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-semibold text-sm" style="color:#374151;">Recent Plots</h3>
                <span class="text-xs text-gray-500"><?= esc(count($recentPlots ?? [])) ?> rows</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentPlots)): ?>
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-400">No data</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentPlots as $plot): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2"><?= esc($plot['id']) ?></td>
                                    <td class="px-4 py-2"><?= esc($plot['plot_name'] ?? 'N/A') ?></td>
                                    <td class="px-4 py-2 text-xs">
                                        <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 font-semibold">
                                            <?= esc(ucfirst($plot['status'] ?? 'available')) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
