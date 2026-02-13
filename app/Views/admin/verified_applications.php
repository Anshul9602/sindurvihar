<!-- Title Section -->
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2" style="color: #0F1F3F;">
        <?= esc(lang('App.adminVerifiedApplications') ?? 'Verified Applications') ?>
    </h1>
    <p class="text-sm" style="color: #6B7280;">
        <?= esc(lang('App.adminVerifiedApplicationsSubtitle') ?? 'View all verified applications.') ?>
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

<!-- Verified Applications List Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="font-semibold text-sm flex items-center gap-2" style="color: #374151;">
            <span>✓</span>
            <?= esc(lang('App.adminVerifiedApplications') ?? 'Verified Applications') ?>
        </h3>
        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold" id="verified-total">
            0 <?= esc(lang('App.adminTotal')) ?>
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminApplicationId')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminApplicationName')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminApplicationMobile')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminApplicationStatus')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminVerifiedDate') ?? 'Verified Date') ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminActions')) ?></th>
                </tr>
            </thead>
            <tbody id="verified-table-body">
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center" style="color: #9CA3AF;">
                        <?= esc(lang('App.adminNoVerifiedFound') ?? 'No verified applications found.') ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
let filtersVisible = true;
let allApplications = <?= json_encode($applications ?? []) ?>;

(function () {
    renderApplications(allApplications);
    updateTotalCount(allApplications.length);
})();

function renderApplications(applications) {
    const body = document.getElementById("verified-table-body");
    if (!applications.length) {
        body.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center" style="color:#9CA3AF;"><?= esc(lang('App.adminNoVerifiedFound') ?? 'No verified applications found.') ?></td></tr>';
        return;
    }

    let html = "";
    applications.forEach(app => {
        html += '<tr class="border-b hover:bg-gray-50 transition">' +
            '<td class="px-4 py-3" style="color: #111827;">' + (app.id || 'N/A') + '</td>' +
            '<td class="px-4 py-3" style="color: #111827;">' + (app.full_name || app.name || 'N/A') + '</td>' +
            '<td class="px-4 py-3" style="color: #111827;">' + (app.mobile || 'N/A') + '</td>' +
            '<td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">Verified</span></td>' +
            '<td class="px-4 py-3 text-xs" style="color: #6B7280;">' + 
            (app.updated_at ? new Date(app.updated_at).toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'}) : 'N/A') + '</td>' +
            '<td class="px-4 py-3">' +
            '<div class="flex items-center gap-2">' +
            '<a href="/admin/applications/' + app.id + '" class="p-1.5 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition" title="<?= esc(lang('App.adminView')) ?>">' +
            '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>' +
            '</a>' +
            '</div></td></tr>';
    });
    body.innerHTML = html;
}

function updateTotalCount(count) {
    const badge = document.getElementById('verified-total');
    if (badge) {
        badge.textContent = count + ' <?= esc(lang('App.adminTotal')) ?>';
    }
}

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
    
    let filtered = allApplications.filter(app => {
        const searchMatch = !search || 
            String(app.full_name || app.name || '').toLowerCase().includes(search) || 
            String(app.mobile || '').includes(search);
        return searchMatch;
    });
    
    renderApplications(filtered);
    updateTotalCount(filtered.length);
}

function resetFilters() {
    document.getElementById('filter-search').value = '';
    renderApplications(allApplications);
    updateTotalCount(allApplications.length);
}
</script>

