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
            0 <?= esc(lang('App.adminTotal')) ?>
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
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center" style="color: #9CA3AF;">
                        <?= esc(lang('App.adminNoAllotmentsFound')) ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
let filtersVisible = true;
let allAllotments = [];

(function () {
    function getAllotments() {
        try {
            var raw = localStorage.getItem("admin_allotments");
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    }

    var body = document.getElementById("allotments-table-body");
    if (!body) return;

    allAllotments = getAllotments();
    renderAllotments(allAllotments);
    updateTotalCount(allAllotments.length);
})();

function renderAllotments(allotments) {
    const body = document.getElementById("allotments-table-body");
    if (!allotments.length) {
        body.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center" style="color:#9CA3AF;"><?= esc(lang('App.adminNoAllotmentsFound')) ?></td></tr>';
        return;
    }

    let html = "";
    for (var i = 0; i < allotments.length; i++) {
        var a = allotments[i];
        html += '<tr class="border-b hover:bg-gray-50 transition">' +
            '<td class="px-4 py-3" style="color: #111827;">' + (a.id || 'N/A') + '</td>' +
            '<td class="px-4 py-3" style="color: #111827;">' + (a.applicationId || 'N/A') + '</td>' +
            '<td class="px-4 py-3" style="color: #111827;">' + (a.name || 'N/A') + '</td>' +
            '<td class="px-4 py-3" style="color: #111827;">' + (a.plot || 'N/A') + '</td>' +
            '<td class="px-4 py-3 text-xs" style="color: #6B7280;">' + 
            (a.date || new Date().toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'})) + '</td>' +
            '<td class="px-4 py-3">' +
            '<div class="flex items-center gap-2">' +
            '<a href="/admin/allotments/' + a.id + '" class="p-1.5 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition" title="<?= esc(lang('App.adminView')) ?>">' +
            '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>' +
            '</a>' +
            '<a href="/admin/allotments/' + a.id + '/edit" class="p-1.5 bg-cyan-100 text-cyan-600 rounded hover:bg-cyan-200 transition" title="<?= esc(lang('App.adminEdit')) ?>">' +
            '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>' +
            '</a>' +
            '<button onclick="deleteAllotment(' + (a.id || i) + ')" class="p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition" title="<?= esc(lang('App.adminDelete')) ?>">' +
            '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>' +
            '</button>' +
            '</div></td></tr>';
    }
    body.innerHTML = html;
}

function updateTotalCount(count) {
    const badge = document.getElementById('allotments-total');
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
    
    let filtered = allAllotments.filter(a => {
        const searchMatch = !search || 
            String(a.id || '').includes(search) || 
            String(a.applicationId || '').includes(search) ||
            String(a.name || '').toLowerCase().includes(search) ||
            String(a.plot || '').toLowerCase().includes(search);
        return searchMatch;
    });
    
    renderAllotments(filtered);
    updateTotalCount(filtered.length);
}

function resetFilters() {
    document.getElementById('filter-search').value = '';
    renderAllotments(allAllotments);
    updateTotalCount(allAllotments.length);
}

function deleteAllotment(id) {
    if (confirm('Are you sure you want to delete this allotment?')) {
        allAllotments = allAllotments.filter(a => a.id != id);
        localStorage.setItem("admin_allotments", JSON.stringify(allAllotments));
        renderAllotments(allAllotments);
        updateTotalCount(allAllotments.length);
    }
}
</script>
