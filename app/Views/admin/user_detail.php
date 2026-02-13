<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold mb-1" style="color:#0F1F3F;">
                <?= esc(lang('App.adminUserDetailTitle') ?? 'User Detail') ?>
            </h1>
            <p class="text-sm" style="color:#6B7280;">
                <?= esc(lang('App.adminUserDetailSubtitle') ?? 'Overview of the registered user profile.') ?>
            </p>
        </div>
        <a href="/admin/users"
           class="px-3 py-1.5 rounded-md text-sm font-semibold"
           style="background-color:#E5E7EB; color:#374151;">
            <?= esc(lang('App.adminBackToList') ?? 'Back to List') ?>
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h2 class="text-xs font-semibold uppercase mb-2" style="color:#6B7280;">Basic Info</h2>
                <div class="space-y-2 text-sm" style="color:#111827;">
                    <div>
                        <div class="text-xs uppercase tracking-wide" style="color:#9CA3AF;">ID</div>
                        <div><?= esc($user['id']) ?></div>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wide" style="color:#9CA3AF;">Full Name</div>
                        <div><?= esc($user['name'] ?? '') ?></div>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wide" style="color:#9CA3AF;">Mobile</div>
                        <div><?= esc($user['mobile'] ?? '') ?></div>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wide" style="color:#9CA3AF;">Email</div>
                        <div><?= esc($user['email'] ?? '') ?></div>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wide" style="color:#9CA3AF;">Category</div>
                        <div>
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                <?= esc($user['category'] ?? 'N/A') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xs font-semibold uppercase mb-2" style="color:#6B7280;">Preferences</h2>
                <div class="space-y-2 text-sm" style="color:#111827;">
                    <div>
                        <div class="text-xs uppercase tracking-wide" style="color:#9CA3AF;">Language</div>
                        <div>
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-800">
                                <?= esc(strtoupper($user['language'] ?? 'HI')) ?>
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wide" style="color:#9CA3AF;">Joined</div>
                        <div>
                            <?= isset($user['created_at']) ? date('d M Y, h:i A', strtotime($user['created_at'])) : '' ?>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wide" style="color:#9CA3AF;">Last Updated</div>
                        <div>
                            <?= isset($user['updated_at']) ? date('d M Y, h:i A', strtotime($user['updated_at'])) : '' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


