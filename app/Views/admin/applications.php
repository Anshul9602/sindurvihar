<!-- Title Section -->
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2" style="color: #0F1F3F;">
        <?= esc(lang('App.adminApplicationsListTitle')) ?>
    </h1>
    <p class="text-sm" style="color: #6B7280;">
        <?= esc(lang('App.adminApplicationsSubtitle')) ?>
    </p>
</div>

<!-- Filters Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="p-4 border-b border-gray-200 cursor-pointer" onclick="toggleFilters()">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-sm" style="color: #374151;">
                <?= esc(lang('App.adminFilters')) ?>
            </h3>
            <span id="filter-arrow" class="text-gray-500">▼</span>
        </div>
    </div>
    <div id="filters-content" class="p-4">
        <form id="filter-form" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #374151;">
                    <?= esc(lang('App.adminFilterStatus')) ?>
                </label>
                <select name="status" id="filter-status" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value=""><?= esc(lang('App.adminFilterAllStatus')) ?></option>
                    <option value="draft">Draft</option>
                    <option value="submitted">Submitted</option>
                    <option value="verified">Verified</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #374151;">
                    <?= esc(lang('App.adminFilterSearch')) ?>
                </label>
                <input type="text" name="search" id="filter-search" 
                       placeholder="<?= esc(lang('App.adminFilterSearchPlaceholder')) ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end gap-2">
                <button type="button" onclick="applyFilters()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition">
                    <?= esc(lang('App.adminFilterButton')) ?>
                </button>
                <button type="button" onclick="resetFilters()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition">
                    <?= esc(lang('App.adminFilterReset')) ?>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Users List Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="font-semibold text-sm flex items-center gap-2" style="color: #374151;">
            <span>👥</span>
            <?= esc(lang('App.adminApplicationsListTitle')) ?>
        </h3>
        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
            <?= esc(count($applications ?? [])) ?> <?= esc(lang('App.adminTotal')) ?>
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminApplicationId')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminApplicationName')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminApplicationMobile')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminApplicationCategory')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminApplicationStatus')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminPaymentStatus')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminJoined')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminActions')) ?></th>
                </tr>
            </thead>
            <tbody id="applications-table-body">
                <?php if (empty($applications)): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center" style="color: #9CA3AF;">
                            <?= esc(lang('App.adminNoApplicationsFound')) ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($applications as $app): ?>
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-3" style="color: #111827;"><?= esc($app['id']) ?></td>
                        <td class="px-4 py-3" style="color: #111827;"><?= esc($app['full_name']) ?></td>
                        <td class="px-4 py-3" style="color: #111827;"><?= esc($app['mobile'] ?? 'N/A') ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs uppercase font-medium bg-gray-100" style="color: #374151;">
                                <?= esc($app['income_category'] ?? 'General') ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <?php 
                            $status = $app['status'] ?? 'draft';
                            $statusClass = 'bg-gray-100 text-gray-800';
                            if ($status === 'submitted') $statusClass = 'bg-yellow-100 text-yellow-800';
                            elseif ($status === 'verified') $statusClass = 'bg-green-100 text-green-800';
                            elseif ($status === 'rejected') $statusClass = 'bg-red-100 text-red-800';
                            ?>
                            <span class="px-2 py-1 rounded text-xs font-semibold <?= $statusClass ?>">
                                <?= esc(ucfirst(str_replace('_', ' ', $status))) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <?php 
                            $paymentStatus = $app['payment_status'] ?? 'pending';
                            $hasPayment = $app['has_payment'] ?? false;
                            
                            // If no payment record exists, show as pending
                            if (!$hasPayment) {
                                $paymentStatus = 'pending';
                            }
                            
                            $paymentStatusClass = 'bg-gray-100 text-gray-800';
                            $paymentStatusText = lang('App.adminPaymentPending');
                            
                            if ($paymentStatus === 'completed' || $paymentStatus === 'success') {
                                $paymentStatusClass = 'bg-green-100 text-green-800';
                                $paymentStatusText = lang('App.adminPaymentCompleted');
                            } elseif ($paymentStatus === 'pending') {
                                $paymentStatusClass = 'bg-yellow-100 text-yellow-800';
                                $paymentStatusText = lang('App.adminPaymentPending');
                            } elseif ($paymentStatus === 'failed') {
                                $paymentStatusClass = 'bg-red-100 text-red-800';
                                $paymentStatusText = lang('App.adminPaymentFailed');
                            }
                            ?>
                            <span class="px-2 py-1 rounded text-xs font-semibold <?= $paymentStatusClass ?>">
                                <?= esc($paymentStatusText) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs" style="color: #6B7280;">
                            <?= date('d M Y, h:i A', strtotime($app['created_at'] ?? 'now')) ?>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="/admin/applications/<?= esc($app['id']) ?>" 
                                   class="p-1.5 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition" 
                                   title="<?= esc(lang('App.adminView')) ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="/admin/applications/<?= esc($app['id']) ?>/edit" 
                                   class="p-1.5 bg-cyan-100 text-cyan-600 rounded hover:bg-cyan-200 transition" 
                                   title="<?= esc(lang('App.adminEdit')) ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <button onclick="deleteApplication(<?= esc($app['id']) ?>)" 
                                        class="p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition" 
                                        title="<?= esc(lang('App.adminDelete')) ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
let filtersVisible = true;

function toggleFilters() {
    const content = document.getElementById('filters-content');
    const arrow = document.getElementById('filter-arrow');
    filtersVisible = !filtersVisible;
    if (filtersVisible) {
        content.style.display = 'block';
        arrow.textContent = '▼';
    } else {
        content.style.display = 'none';
        arrow.textContent = '▶';
    }
}

function applyFilters() {
    const status = document.getElementById('filter-status').value;
    const search = document.getElementById('filter-search').value.toLowerCase();
    const rows = document.querySelectorAll('#applications-table-body tr');
    let visibleCount = 0;

    rows.forEach(row => {
        if (row.querySelector('td[colspan]')) {
            // Skip "no data" row
            return;
        }

        const rowStatus = row.querySelector('td:nth-child(5) span')?.textContent.toLowerCase().trim();
        const rowName = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
        const rowMobile = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';

        const statusMatch = !status || rowStatus === status || rowStatus.includes(status);
        const searchMatch = !search || rowName.includes(search) || rowMobile.includes(search);

        if (statusMatch && searchMatch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Update total count
    const totalBadge = document.querySelector('.bg-blue-100');
    if (totalBadge) {
        totalBadge.textContent = visibleCount + ' <?= esc(lang('App.adminTotal')) ?>';
    }
}

function resetFilters() {
    document.getElementById('filter-status').value = '';
    document.getElementById('filter-search').value = '';
    const rows = document.querySelectorAll('#applications-table-body tr');
    rows.forEach(row => {
        row.style.display = '';
    });
    const totalBadge = document.querySelector('.bg-blue-100');
    if (totalBadge) {
        totalBadge.textContent = '<?= esc(count($applications ?? [])) ?> <?= esc(lang('App.adminTotal')) ?>';
    }
}

function deleteApplication(id) {
    if (confirm('Are you sure you want to delete this application?')) {
        // Add delete functionality here
        console.log('Delete application:', id);
    }
}
</script>
