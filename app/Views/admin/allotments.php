<!-- Title Section -->
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2" style="color: #0F1F3F;">
        <?= esc(lang('App.adminAllotmentsTitle')) ?>
    </h1>
    <p class="text-sm" style="color: #6B7280;">
        <?= esc(lang('App.adminAllotmentsSubtitle')) ?>
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

<!-- Allotments List Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="font-semibold text-sm flex items-center gap-2" style="color: #374151;">
            <span>🏠</span>
            <?= esc(lang('App.adminAllotmentsTitle')) ?>
        </h3>
        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold" id="allotments-total">
            <?= isset($allotments) ? count($allotments) : 0 ?> <?= esc(lang('App.adminTotal')) ?>
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminAllotmentId')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminAllotmentApplicationId')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminAllotmentName')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminAllotmentPlot')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminDate')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminActions')) ?></th>
                </tr>
            </thead>
            <tbody id="allotments-table-body">
                <?php if (empty($allotments)): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center" style="color: #9CA3AF;">
                            <?= esc(lang('App.adminNoAllotmentsFound')) ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($allotments as $allotment): ?>
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3" style="color: #111827;">#<?= esc($allotment['id']) ?></td>
                            <td class="px-4 py-3" style="color: #111827;"><?= esc($allotment['application_id']) ?></td>
                            <td class="px-4 py-3" style="color: #111827;"><?= esc($allotment['full_name'] ?? $allotment['user_name'] ?? 'N/A') ?></td>
                            <td class="px-4 py-3" style="color: #111827;"><?= esc($allotment['plot_number'] ?? 'N/A') ?></td>
                            <td class="px-4 py-3 text-xs" style="color: #6B7280;">
                                <?= isset($allotment['created_at']) ? esc(date('d M Y', strtotime($allotment['created_at']))) : '—' ?>
                            </td>
                            <td class="px-4 py-3">
                                <!-- For now just a placeholder; detailed view/edit not implemented -->
                                <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                                    <?= esc(ucfirst($allotment['status'] ?? 'provisional')) ?>
                                </span>
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
    const search = document.getElementById('filter-search').value.toLowerCase();
    const rows   = document.querySelectorAll('#allotments-table-body tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const match = !search || text.includes(search);
        row.style.display = match ? '' : 'none';
        if (match) visibleCount++;
    });

    const badge = document.getElementById('allotments-total');
    if (badge) {
        badge.textContent = visibleCount + ' <?= esc(lang('App.adminTotal')) ?>';
    }
}

function resetFilters() {
    document.getElementById('filter-search').value = '';
    const rows   = document.querySelectorAll('#allotments-table-body tr');
    rows.forEach(row => row.style.display = '');
    const badge = document.getElementById('allotments-total');
    if (badge) {
        badge.textContent = rows.length + ' <?= esc(lang('App.adminTotal')) ?>';
    }
}
</script>
