<?php
    $session = session();
    $errorMessage = $session->getFlashdata('error') ?? null;
?>

<!-- Title Section -->
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2" style="color:#0F1F3F;">
        <?= esc(lang('App.adminRegisteredUsersTitle') ?? 'Registered Users') ?>
    </h1>
    <p class="text-sm" style="color:#6B7280;">
        <?= esc(lang('App.adminRegisteredUsersSubtitle') ?? 'Manage all registered portal users.') ?>
    </p>
</div>

<!-- Filters Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="p-4 border-b border-gray-200 cursor-pointer" onclick="toggleUserFilters()">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-sm" style="color:#374151;">
                <?= esc(lang('App.adminFilters')) ?>
            </h3>
            <span id="user-filter-arrow" class="text-gray-500">▼</span>
        </div>
    </div>
    <div id="user-filters-content" class="p-4">
        <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2" style="color:#374151;">
                    Language
                </label>
                <select id="filter-language"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="hi">Hindi</option>
                    <option value="en">English</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2" style="color:#374151;">
                    <?= esc(lang('App.adminFilterSearch')) ?>
                </label>
                <input type="text" id="filter-user-search"
                       placeholder="<?= esc(lang('App.adminFilterSearchPlaceholder')) ?>"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end gap-2">
                <button type="button" onclick="applyUserFilters()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition">
                    <?= esc(lang('App.adminFilterButton')) ?>
                </button>
                <button type="button" onclick="resetUserFilters()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition">
                    <?= esc(lang('App.adminFilterReset')) ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php if ($errorMessage): ?>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            alert(<?= json_encode($errorMessage) ?>);
        });
    </script>
<?php endif; ?>

<!-- Users List Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="font-semibold text-sm flex items-center gap-2" style="color:#374151;">
            <span>👤</span>
            <?= esc(lang('App.adminRegisteredUsersTitle') ?? 'Registered Users') ?>
        </h3>
        <span id="users-total-badge" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
            <?= esc(count($users ?? [])) ?> <?= esc(lang('App.adminTotal')) ?>
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;"><?= esc(lang('App.adminApplicationId')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;"><?= esc(lang('App.adminApplicationName')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;"><?= esc(lang('App.adminApplicationMobile')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;"><?= esc(lang('App.adminUserEmailLabel') ?? 'Email') ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;"><?= esc(lang('App.adminUserLanguageLabel') ?? 'Language') ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;"><?= esc(lang('App.adminUserCategoryLabel') ?? 'Category') ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;"><?= esc(lang('App.adminJoined')) ?></th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;"><?= esc(lang('App.adminActions')) ?></th>
                </tr>
            </thead>
            <tbody id="users-table-body">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center" style="color:#9CA3AF;">
                            <?= esc(lang('App.adminNoUsersFound') ?? 'No users found.') ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3" style="color:#111827;"><?= esc($user['id']) ?></td>
                            <td class="px-4 py-3" style="color:#111827;"><?= esc($user['name'] ?? '') ?></td>
                            <td class="px-4 py-3" style="color:#111827;"><?= esc($user['mobile'] ?? '') ?></td>
                            <td class="px-4 py-3" style="color:#111827;"><?= esc($user['email'] ?? '') ?></td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-800">
                                    <?= esc(strtoupper($user['language'] ?? 'HI')) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                    <?= esc($user['category'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:#6B7280;">
                                <?= isset($user['created_at']) ? date('d M Y, h:i A', strtotime($user['created_at'])) : '' ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="/admin/users/<?= esc($user['id']) ?>"
                                       class="p-1.5 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition"
                                       title="<?= esc(lang('App.adminView')) ?>">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <form action="/admin/users/<?= esc($user['id']) ?>/delete" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        <button type="submit"
                                                class="p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200 transition"
                                                title="<?= esc(lang('App.adminDelete')) ?>">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </form>
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
    let userFiltersVisible = true;

    function toggleUserFilters() {
        const content = document.getElementById('user-filters-content');
        const arrow = document.getElementById('user-filter-arrow');
        userFiltersVisible = !userFiltersVisible;
        if (userFiltersVisible) {
            content.style.display = 'block';
            arrow.textContent = '▼';
        } else {
            content.style.display = 'none';
            arrow.textContent = '▶';
        }
    }

    function applyUserFilters() {
        const language = document.getElementById('filter-language').value;
        const search = document.getElementById('filter-user-search').value.toLowerCase();
        const rows = document.querySelectorAll('#users-table-body tr');
        let visibleCount = 0;

        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) {
                return;
            }

            const rowLang = row.querySelector('td:nth-child(5) span')?.textContent.toLowerCase().trim();
            const rowName = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
            const rowMobile = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
            const rowCategory = row.querySelector('td:nth-child(6) span')?.textContent.toLowerCase() || '';

            const langMatch = !language || rowLang === language.toLowerCase();
            const searchMatch = !search || rowName.includes(search) || rowMobile.includes(search) || rowCategory.includes(search);

            if (langMatch && searchMatch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const badge = document.getElementById('users-total-badge');
        if (badge) {
            badge.textContent = visibleCount + ' ' + <?= json_encode(lang('App.adminTotal')) ?>;
        }
    }

    function resetUserFilters() {
        document.getElementById('filter-language').value = '';
        document.getElementById('filter-user-search').value = '';
        const rows = document.querySelectorAll('#users-table-body tr');
        rows.forEach(row => {
            row.style.display = '';
        });
        const badge = document.getElementById('users-total-badge');
        if (badge) {
            badge.textContent = '<?= esc(count($users ?? [])) ?> <?= esc(lang('App.adminTotal')) ?>';
        }
    }
</script>


