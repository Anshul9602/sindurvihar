<!-- Title Section -->
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2" style="color: #0F1F3F;">
        <?= esc(lang('App.adminPlotsTitle')) ?>
    </h1>
    <p class="text-sm" style="color: #6B7280;">
        <?= esc(lang('App.adminPlotsSubtitle')) ?>
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
        <form id="filter-form" method="GET" action="/admin/plots" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #374151;">
                    <?= esc(lang('App.adminPlotCategory')) ?>
                </label>
                <select name="category" id="filter-category" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value=""><?= esc(lang('App.adminFilterAllStatus')) ?></option>
                    <option value="EWS" <?= (isset($filterCategory) && $filterCategory === 'EWS') ? 'selected' : '' ?>>EWS</option>
                    <option value="LIG" <?= (isset($filterCategory) && $filterCategory === 'LIG') ? 'selected' : '' ?>>LIG</option>
                    <option value="Residential" <?= (isset($filterCategory) && $filterCategory === 'Residential') ? 'selected' : '' ?>>Residential</option>
                    <option value="General" <?= (isset($filterCategory) && $filterCategory === 'General') ? 'selected' : '' ?>>General</option>
                    <option value="MIG-A" <?= (isset($filterCategory) && $filterCategory === 'MIG-A') ? 'selected' : '' ?>>MIG-A</option>
                    <option value="MIG-B" <?= (isset($filterCategory) && $filterCategory === 'MIG-B') ? 'selected' : '' ?>>MIG-B</option>
                    <option value="HIG" <?= (isset($filterCategory) && $filterCategory === 'HIG') ? 'selected' : '' ?>>HIG</option>
                    <option value="SC" <?= (isset($filterCategory) && $filterCategory === 'SC') ? 'selected' : '' ?>>SC</option>
                    <option value="ST" <?= (isset($filterCategory) && $filterCategory === 'ST') ? 'selected' : '' ?>>ST</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #374151;">
                    <?= esc(lang('App.adminPlotStatus')) ?>
                </label>
                <select name="status" id="filter-status" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value=""><?= esc(lang('App.adminFilterAllStatus')) ?></option>
                    <option value="available" <?= (isset($filterStatus) && $filterStatus === 'available') ? 'selected' : '' ?>><?= esc(lang('App.adminPlotAvailableStatus')) ?></option>
                    <option value="allocated" <?= (isset($filterStatus) && $filterStatus === 'allocated') ? 'selected' : '' ?>><?= esc(lang('App.adminPlotAllocatedStatus')) ?></option>
                    <option value="reserved" <?= (isset($filterStatus) && $filterStatus === 'reserved') ? 'selected' : '' ?>><?= esc(lang('App.adminPlotReservedStatus')) ?></option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #374151;">
                    <?= esc(lang('App.adminFilterSearch')) ?>
                </label>
                <input type="text" name="search" id="filter-search" 
                       value="<?= esc($filterSearch ?? '') ?>"
                       placeholder="<?= esc(lang('App.adminFilterSearchPlaceholder')) ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2" style="color: #374151;">
                    <?= esc(lang('App.adminPerPage')) ?>
                </label>
                <select name="per_page" id="filter-per-page" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="25" <?= (isset($filterPerPage) && $filterPerPage == 25) ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= (isset($filterPerPage) && $filterPerPage == 50) ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= (isset($filterPerPage) && $filterPerPage == 100) ? 'selected' : '' ?>>100</option>
                    <option value="200" <?= (isset($filterPerPage) && $filterPerPage == 200) ? 'selected' : '' ?>>200</option>
                    <option value="300" <?= (isset($filterPerPage) && $filterPerPage == 300) ? 'selected' : '' ?>>300</option>
                    <option value="400" <?= (isset($filterPerPage) && $filterPerPage == 400) ? 'selected' : '' ?>>400</option>
                    <option value="500" <?= (isset($filterPerPage) && $filterPerPage == 500) ? 'selected' : '' ?>>500</option>
                </select>
            </div>
            <div class="flex items-end gap-2 md:col-span-4">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition">
                    <?= esc(lang('App.adminFilterButton')) ?>
                </button>
                <a href="/admin/plots" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition">
                    <?= esc(lang('App.adminFilterReset')) ?>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Plots List Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="font-semibold text-sm flex items-center gap-2" style="color: #374151;">
            <span>📍</span>
            <?= esc(lang('App.adminPlotsTitle')) ?>
        </h3>
        <div class="flex items-center gap-3">
            <a href="/admin/plots/add" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <?= esc(lang('App.adminAddPlot')) ?>
            </a>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold" id="plots-total">
                <?= esc($pager->getTotal() ?? count($plots ?? [])) ?> <?= esc(lang('App.adminTotal')) ?>
            </span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminPlotImage')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminPlotName')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminPlotNumber')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminPlotCategory')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminPlotDimensions')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminPlotLocation')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminPlotQuantity')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminPlotStatus')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color: #6B7280;"><?= esc(lang('App.adminActions')) ?></th>
                </tr>
            </thead>
            <tbody id="plots-table-body">
                <?php if (empty($plots)): ?>
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center" style="color: #9CA3AF;">
                            <?= esc(lang('App.adminNoPlotsFound')) ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($plots as $plot): ?>
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <?php if (!empty($plot['plot_image'])): ?>
                                <img src="/<?= esc($plot['plot_image']) ?>" 
                                     alt="<?= esc($plot['plot_name']) ?>" 
                                     class="w-16 h-16 object-cover rounded-md">
                            <?php else: ?>
                                <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center">
                                    <span class="text-gray-400 text-xs">No Image</span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3" style="color: #111827;">
                            <div class="font-semibold"><?= esc($plot['plot_name']) ?></div>
                            <?php if (!empty($plot['description'])): ?>
                                <div class="text-xs text-gray-500 mt-1"><?= esc(substr($plot['description'], 0, 50)) ?>...</div>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3" style="color: #111827;"><?= esc($plot['plot_number'] ?? 'N/A') ?></td>
                        <td class="px-4 py-3">
                            <?php
                            // Category is now stored as plain string (e.g. "SC", "General").
                            // For backward compatibility, also handle old "EWS:10,MIG-A:5" format.
                            $categoryStr     = trim((string) ($plot['category'] ?? ''));
                            $categoryDisplay = 'N/A';

                            if ($categoryStr !== '') {
                                if (strpos($categoryStr, ':') === false && strpos($categoryStr, ',') === false) {
                                    // New format: simple category name
                                    $categoryDisplay = $categoryStr;
                                } else {
                                    // Old format: "EWS:10,MIG-A:5"
                                    $pairs = [];
                                    foreach (explode(',', $categoryStr) as $part) {
                                        [$cat, $qty] = array_pad(explode(':', $part), 2, '');
                                        $cat = trim($cat);
                                        $qty = (int) $qty;
                                        if ($cat !== '' && $qty > 0) {
                                            $pairs[] = $cat . ' = ' . $qty;
                                        }
                                    }
                                    if (! empty($pairs)) {
                                        $categoryDisplay = implode(', ', $pairs);
                                    } else {
                                        // Fallback to raw string if parsing fails
                                        $categoryDisplay = $categoryStr;
                                    }
                                }
                            }
                            ?>
                            <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                <?= esc($categoryDisplay) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3" style="color: #111827;">
                            <?php if (!empty($plot['dimensions'])): ?>
                                <?= esc($plot['dimensions']) ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                            <?php if (!empty($plot['area'])): ?>
                                <div class="text-xs text-gray-500"><?= esc($plot['area']) ?> sq ft</div>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3" style="color: #111827;"><?= esc($plot['location']) ?></td>
                        <td class="px-4 py-3" style="color: #111827;">
                            <div class="font-semibold"><?= esc($plot['quantity'] ?? 0) ?></div>
                            <div class="text-xs text-gray-500"><?= esc($plot['available_quantity'] ?? 0) ?> <?= esc(lang('App.adminPlotAvailable')) ?></div>
                        </td>
                        <td class="px-4 py-3">
                            <?php 
                            $status = $plot['status'] ?? 'available';
                            $statusClass = 'bg-gray-100 text-gray-800';
                            if ($status === 'available') {
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusText = lang('App.adminPlotAvailableStatus');
                            } elseif ($status === 'allocated') {
                                $statusClass = 'bg-blue-100 text-blue-800';
                                $statusText = lang('App.adminPlotAllocatedStatus');
                            } elseif ($status === 'reserved') {
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                $statusText = lang('App.adminPlotReservedStatus');
                            } else {
                                $statusText = ucfirst($status);
                            }
                            ?>
                            <span class="px-2 py-1 rounded text-xs font-semibold <?= $statusClass ?>">
                                <?= esc($statusText) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <!-- View goes to edit page for now -->
                                <a href="/admin/plots/<?= esc($plot['id']) ?>/edit" 
                                   class="p-1.5 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition" 
                                   title="<?= esc(lang('App.adminView')) ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="/admin/plots/<?= esc($plot['id']) ?>/edit" 
                                   class="p-1.5 bg-cyan-100 text-cyan-600 rounded hover:bg-cyan-200 transition" 
                                   title="<?= esc(lang('App.adminEdit')) ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <button onclick="deletePlot(<?= esc($plot['id']) ?>)" 
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
    
    <!-- Pagination -->
    <?php if (isset($pager) && $pager && $pager->getTotal() > 0): ?>
    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="text-sm" style="color: #6B7280;">
                <?php 
                $total = $pager->getTotal() ?? 0;
                $currentPage = $pager->getCurrentPage() ?? 1;
                $perPage = $pager->getPerPage() ?? 20;
                $start = ($currentPage - 1) * $perPage + 1;
                $end = min($currentPage * $perPage, $total);
                ?>
                Showing <?= $start ?> 
                to <?= $end ?> 
                of <?= $total ?> results
            </div>
            <div class="flex items-center gap-2">
                <?php if ($pager->getPageCount() > 1): ?>
                    <?= $pager->links('default', 'admin_plots') ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
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

// Filters are now handled server-side via form submission
// No client-side filtering needed

function deletePlot(id) {
    if (confirm('Are you sure you want to delete this plot?')) {
        // Create a form to submit POST request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/plots/' + id + '/delete';
        
        // Add CSRF token if available
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
        }
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

