<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6" style="color:#0F1F3F;">
        <?= esc(lang('App.adminSidebarSettings') ?? 'Settings') ?>
    </h1>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 p-3 rounded-md text-sm" style="background-color:#FEE2E2; color:#DC2626;">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 p-3 rounded-md text-sm" style="background-color:#D1FAE5; color:#059669;">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <!-- Filters and Admin List (same style as applications table) -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4 border-b border-gray-200 cursor-pointer" onclick="toggleAdminFilters()">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-sm" style="color:#374151;">Filters</h3>
                <span id="admin-filter-arrow" class="text-gray-500">▼</span>
            </div>
        </div>
        <div id="admin-filters-content" class="p-4">
            <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color:#374151;">Role</label>
                    <select id="filter-role"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                        <option value="viewer">Viewer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color:#374151;">Search</label>
                    <input type="text" id="filter-admin-search"
                           placeholder="Search by name or mobile"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end gap-2">
                    <button type="button" onclick="applyAdminFilters()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition">
                        Apply
                    </button>
                    <button type="button" onclick="resetAdminFilters()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition">
                        Reset
                    </button>
                </div>
            </form>
        </div>

        <div class="p-4 border-t border-gray-200 flex items-center justify-between">
            <h2 class="font-semibold text-sm flex items-center gap-2" style="color:#374151;">
                <span>👤</span>
                Admin Users
            </h2>
            <div class="flex items-center gap-3">
                <span id="admin-total-badge" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                    <?= esc(count($admins ?? [])) ?> Total
                </span>
                <button type="button"
                        onclick="openAdminDrawer()"
                        class="px-3 py-1.5 bg-blue-600 text-white rounded-md text-xs font-semibold hover:bg-blue-700 transition">
                    + <?= esc(lang('App.adminRegisterButton') ?? 'Add Admin') ?>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;">ID</th>
                        <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;">Name</th>
                        <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;">Mobile</th>
                        <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;">Email</th>
                        <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;">Role</th>
                        <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;">Joined</th>
                        <th class="px-4 py-3 font-semibold text-xs uppercase" style="color:#6B7280;">Actions</th>
                    </tr>
                </thead>
                <tbody id="admins-table-body">
                <?php if (empty($admins)): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center" style="color:#9CA3AF;">
                            No admin users found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($admins as $admin): ?>
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3" style="color:#111827;"><?= esc($admin['id']) ?></td>
                            <td class="px-4 py-3" style="color:#111827;"><?= esc($admin['name']) ?></td>
                            <td class="px-4 py-3" style="color:#111827;"><?= esc($admin['mobile']) ?></td>
                            <td class="px-4 py-3" style="color:#111827;"><?= esc($admin['email']) ?></td>
                            <td class="px-4 py-3 text-xs">
                                <?php $role = $admin['role'] ?? 'admin';
                                $roleClass = 'bg-gray-100 text-gray-800';
                                if ($role === 'super_admin') $roleClass = 'bg-purple-100 text-purple-800';
                                elseif ($role === 'viewer') $roleClass = 'bg-green-100 text-green-800';
                                ?>
                                <span class="px-2 py-1 rounded text-xs font-semibold <?= $roleClass ?>">
                                    <?= esc(ucfirst(str_replace('_', ' ', $role))) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:#6B7280;">
                                <?= isset($admin['created_at']) ? date('d M Y, h:i A', strtotime($admin['created_at'])) : '' ?>
                            </td>
                            <td class="px-4 py-3">
                                <button type="button"
                                        onclick="openPasswordDrawer(<?= (int) $admin['id'] ?>, '<?= esc($admin['name'], 'js') ?>')"
                                        class="px-2 py-1 bg-cyan-100 text-cyan-700 rounded-md text-xs font-semibold hover:bg-cyan-200 transition">
                                    Change Password
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Slide-in Admin Registration Drawer -->
    <div id="admin-drawer-backdrop"
         class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden"
         onclick="closeAdminDrawer()"></div>

    <div id="admin-drawer"
         class="fixed inset-y-0 right-0 w-full max-w-md bg-white shadow-xl z-50 transform translate-x-full transition-transform duration-300">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
            <h2 class="text-sm font-semibold" style="color:#111827;">
                <?= esc(lang('App.adminRegisterTitle') ?? 'Admin Registration') ?>
            </h2>
            <button type="button" onclick="closeAdminDrawer()" class="text-gray-500 hover:text-gray-700 text-xl leading-none">
                ×
            </button>
        </div>
        <div class="p-4 pt-6" style="padding-top:80px;">
            <form id="admin-settings-add-form" action="<?= site_url('admin/settings') ?>" method="POST" class="space-y-4">
                <div>
                    <label for="admin-name" class="block text-sm font-medium mb-1">
                        <?= esc(lang('App.adminNameLabel') ?? 'Full Name') ?>
                    </label>
                    <input id="admin-name" name="name" type="text"
                           value="<?= esc(old('name', '')) ?>"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="admin-mobile" class="block text-sm font-medium mb-1">
                        <?= esc(lang('App.adminMobileLabel') ?? 'Mobile Number') ?>
                    </label>
                    <input id="admin-mobile" name="mobile" type="text" required maxlength="10" pattern="[0-9]{10}"
                           value="<?= esc(old('mobile', '')) ?>"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="admin-email" class="block text-sm font-medium mb-1">
                        <?= esc(lang('App.adminEmailLabel') ?? 'Email (optional)') ?>
                    </label>
                    <input id="admin-email" name="email" type="email"
                           value="<?= esc(old('email', '')) ?>"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="admin-password" class="block text-sm font-medium mb-1">
                        <?= esc(lang('App.adminPasswordLabel') ?? 'Password') ?>
                    </label>
                    <input id="admin-password" name="password" type="password" required minlength="6"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="admin-role" class="block text-sm font-medium mb-1">
                        Role
                    </label>
                    <select id="admin-role" name="role"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                        <option value="viewer">Viewer</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition">
                        <?= esc(lang('App.adminRegisterButton') ?? 'Register Admin') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Slide-in Admin Password Drawer -->
    <div id="admin-password-backdrop"
         class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden"
         onclick="closePasswordDrawer()"></div>

    <div id="admin-password-drawer" style="padding-top:80px;"
         class="fixed inset-y-0 right-0 w-full max-w-md bg-white shadow-xl z-50 transform translate-x-full transition-transform duration-300">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
            <h2 id="admin-password-title" class="text-sm font-semibold" style="color:#111827;">
                Change Admin Password
            </h2>
            <button type="button" onclick="closePasswordDrawer()" class="text-gray-500 hover:text-gray-700 text-xl leading-none">
                ×
            </button>
        </div>
        <div class="p-4 pt-6">
            <form id="admin-password-form" action="" method="POST" class="space-y-4">
                <div>
                    <label for="admin-new-password" class="block text-sm font-medium mb-1">
                        New Password
                    </label>
                    <input id="admin-new-password" name="password" type="password" required minlength="6"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="admin-confirm-password" class="block text-sm font-medium mb-1">
                        Confirm New Password
                    </label>
                    <input id="admin-confirm-password" name="password_confirm" type="password" required minlength="6"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="pt-2">
                    <button type="submit"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700 transition">
                        Save Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        var form = document.getElementById("admin-settings-add-form");
        if (!form) return;

        form.addEventListener("submit", function (e) {
            var mobile = document.getElementById("admin-mobile").value;
            var password = document.getElementById("admin-password").value;

            if (!/^[0-9]{10}$/.test(mobile)) {
                e.preventDefault();
                alert("Please enter a valid 10-digit mobile number");
                return false;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert("Password must be at least 6 characters long");
                return false;
            }
        });
    })();

    function openAdminDrawer() {
        var drawer = document.getElementById('admin-drawer');
        var backdrop = document.getElementById('admin-drawer-backdrop');
        if (!drawer || !backdrop) return;
        drawer.classList.remove('translate-x-full');
        backdrop.classList.remove('hidden');
    }

    function openPasswordDrawer(id, name) {
        var drawer = document.getElementById('admin-password-drawer');
        var backdrop = document.getElementById('admin-password-backdrop');
        var form = document.getElementById('admin-password-form');
        var title = document.getElementById('admin-password-title');

        if (!drawer || !backdrop || !form) return;

        form.action = '/admin/admins/' + id + '/password';
        if (title) {
            title.textContent = 'Change password for ' + name;
        }

        drawer.classList.remove('translate-x-full');
        backdrop.classList.remove('hidden');
    }

    function closePasswordDrawer() {
        var drawer = document.getElementById('admin-password-drawer');
        var backdrop = document.getElementById('admin-password-backdrop');
        if (!drawer || !backdrop) return;
        drawer.classList.add('translate-x-full');
        backdrop.classList.add('hidden');
    }

    function closeAdminDrawer() {
        var drawer = document.getElementById('admin-drawer');
        var backdrop = document.getElementById('admin-drawer-backdrop');
        if (!drawer || !backdrop) return;
        drawer.classList.add('translate-x-full');
        backdrop.classList.add('hidden');
    }

    let adminFiltersVisible = true;

    function toggleAdminFilters() {
        const content = document.getElementById('admin-filters-content');
        const arrow = document.getElementById('admin-filter-arrow');
        adminFiltersVisible = !adminFiltersVisible;
        if (adminFiltersVisible) {
            content.style.display = 'block';
            arrow.textContent = '▼';
        } else {
            content.style.display = 'none';
            arrow.textContent = '▶';
        }
    }

    function applyAdminFilters() {
        const role = document.getElementById('filter-role').value;
        const search = document.getElementById('filter-admin-search').value.toLowerCase();
        const rows = document.querySelectorAll('#admins-table-body tr');
        let visibleCount = 0;

        rows.forEach(row => {
            if (row.querySelector('td[colspan]')) {
                return;
            }

            const rowRole = row.querySelector('td:nth-child(5) span')?.textContent.toLowerCase().trim();
            const rowName = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
            const rowMobile = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';

            const roleMatch = !role || rowRole === role || rowRole.includes(role.replace('_', ' '));
            const searchMatch = !search || rowName.includes(search) || rowMobile.includes(search);

            if (roleMatch && searchMatch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const badge = document.getElementById('admin-total-badge');
        if (badge) {
            badge.textContent = visibleCount + ' Total';
        }
    }

    function resetAdminFilters() {
        document.getElementById('filter-role').value = '';
        document.getElementById('filter-admin-search').value = '';
        const rows = document.querySelectorAll('#admins-table-body tr');
        rows.forEach(row => {
            row.style.display = '';
        });
        const badge = document.getElementById('admin-total-badge');
        if (badge) {
            badge.textContent = '<?= esc(count($admins ?? [])) ?> Total';
        }
    }
</script>
