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
                                <div class="flex items-center gap-2 flex-wrap">
                                    <a href="/admin/allotments/<?= esc($allotment['id']) ?>" 
                                       class="p-1.5 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition" 
                                       title="<?= esc(lang('App.adminView')) ?>">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <?php
                                    $hasChalan = ! empty($allotment['chalan_id']);
                                    $canGenerate = $hasBankAccount ?? false;
                                    ?>
                                    <?php if ($hasChalan): ?>
                                    <a href="<?= site_url('admin/chalans/' . $allotment['chalan_id']) ?>" class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800 hover:bg-blue-200 font-medium">
                                        <?= esc(lang('App.adminViewChalan') ?? 'View Chalan') ?>
                                    </a>
                                    <?php else: ?>
                                    <button type="button" class="btn-generate-chalan inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium <?= $canGenerate ? 'text-white hover:opacity-90' : 'text-gray-500 border border-gray-300' ?> transition"
                                            style="<?= $canGenerate ? 'background-color: #10B981;' : '' ?>"
                                            data-id="<?= (int)$allotment['id'] ?>"
                                            data-name="<?= esc($allotment['full_name'] ?? $allotment['user_name'] ?? 'N/A') ?>"
                                            data-plot="<?= esc($allotment['plot_number'] ?? 'N/A') ?>"
                                            data-has-bank="<?= $canGenerate ? '1' : '0' ?>"
                                            title="<?= esc(lang('App.adminGenerateChalan') ?? 'Generate Chalan') ?>">
                                        <?= esc(lang('App.adminGenerateChalan') ?? 'Generate Chalan') ?>
                                    </button>
                                    <?php endif; ?>
                                    <?php
                                    $ast = strtolower((string)($allotment['status'] ?? 'provisional'));
                                    $astClass = ($ast === 'allotted' || $ast === 'alloted') ? 'bg-green-100 text-green-800' : (($ast === 'final') ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800');
                                    ?>
                                    <span class="text-xs px-2 py-1 rounded-full <?= $astClass ?>">
                                        <?= esc(ucfirst($ast === 'alloted' ? 'allotted' : $ast)) ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Generate Chalan Modal -->
<div id="chalanModalOverlay" class="fixed inset-0 bg-black/50 z-40 hidden" onclick="closeChalanModal()"></div>
<div id="chalanModal" class="fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white rounded-lg shadow-xl z-50 hidden">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="font-semibold" style="color: #0F1F3F;">
            <?= esc(lang('App.adminGenerateChalan') ?? 'Generate Chalan') ?>
        </h3>
        <button type="button" onclick="closeChalanModal()" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    <div class="p-4">
        <div id="chalanModalInfo" class="mb-4 text-sm" style="color: #374151;"></div>
        <div id="chalanModalNoBank" class="hidden mb-4 p-3 rounded-lg text-sm" style="background-color: #FEE2E2; color: #B91C1C;">
            <?= esc(lang('App.adminChalanNoBankAccount') ?? 'Cannot generate chalan. Please add a bank account first from Chalans page.') ?>
        </div>
        <form id="chalanModalForm" action="" method="post" class="hidden">
            <?= csrf_field() ?>
            <input type="hidden" name="return_to" value="list">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1" style="color: #374151;"><?= esc(lang('App.chalanAmount') ?? 'Chalan Amount (₹)') ?></label>
                <input type="number" name="amount" id="chalanModalAmount" min="1" required
                       class="w-full border border-gray-300 rounded-md px-3 py-2"
                       placeholder="e.g. 50000">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-md font-semibold text-white" style="background-color: #10B981;">
                    <?= esc(lang('App.adminGenerateChalan') ?? 'Generate Chalan') ?>
                </button>
                <button type="button" onclick="closeChalanModal()" class="px-4 py-2 rounded-md font-semibold border border-gray-300" style="color: #374151;">
                    <?= esc(lang('App.cancel') ?? 'Cancel') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openChalanModal(btnOrData) {
    var btn = typeof btnOrData === 'object' ? btnOrData : document.querySelector('[data-id="' + btnOrData + '"]');
    if (!btn) return;
    var id = btn.getAttribute('data-id');
    var name = btn.getAttribute('data-name') || 'N/A';
    var plot = btn.getAttribute('data-plot') || 'N/A';
    var hasBank = btn.getAttribute('data-has-bank') === '1';
    document.getElementById('chalanModalInfo').textContent = (name ? 'Applicant: ' + name + '. ' : '') + 'Plot: ' + plot;
    document.getElementById('chalanModalNoBank').classList.toggle('hidden', hasBank);
    document.getElementById('chalanModalForm').classList.toggle('hidden', !hasBank);
    document.getElementById('chalanModalForm').action = '<?= site_url('admin/allotments/') ?>' + id + '/generate-chalan';
    document.getElementById('chalanModal').classList.remove('hidden');
    document.getElementById('chalanModalOverlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeChalanModal() {
    document.getElementById('chalanModal').classList.add('hidden');
    document.getElementById('chalanModalOverlay').classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-generate-chalan').forEach(function(btn) {
        btn.addEventListener('click', function() { openChalanModal(this); });
    });
});

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
