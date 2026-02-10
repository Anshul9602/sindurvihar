<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-2" style="color: #0F1F3F;">
        <?= esc(lang('App.dashboardWelcome')) ?> <?= esc($user['name'] ?? '') ?>
    </h1>
    <p class="mb-6 text-sm" style="color:#6B7280;">
        Complete these steps to finish your Sindoor Vihar housing application.
    </p>

    <?php
    $eligibilityDone = $steps['eligibility']['completed'] ?? false;
    $applicationDone = $steps['application']['completed'] ?? false;
    $documentsDone   = $steps['documents']['completed'] ?? false;
    $paymentDone     = $steps['payment']['completed'] ?? false;

    $hasApplication  = !empty($application);
    if (! $hasApplication) {
        $statusLabel = lang('App.dashboardStatusNoApplication') ?? 'No application found. Start a new application.';
        $statusColor = '#4B5563';
        $statusBg    = 'transparent';
        $statusPill  = false;
    } elseif ($eligibilityDone && $applicationDone && $documentsDone && $paymentDone) {
        $statusLabel = lang('App.dashboardStatusSubmitted') ?? 'Submitted';
        $statusColor = '#166534';
        $statusBg    = '#DCFCE7';
        $statusPill  = true;
    } else {
        $statusLabel = lang('App.dashboardStatusPending') ?? 'Pending';
        $statusColor = '#92400E';
        $statusBg    = '#FEF3C7';
        $statusPill  = true;
    }
    ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">
                <?= esc(lang('App.dashboardApplicationStatus')) ?>
            </h3>
            <div id="application-status-container">
                <?php if ($statusPill): ?>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold"
                          style="background-color: <?= esc($statusBg) ?>; color: <?= esc($statusColor) ?>;">
                        <?= esc($statusLabel) ?>
                    </span>
                <?php else: ?>
                    <p style="color: <?= esc($statusColor) ?>;"><?= esc($statusLabel) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">
                <?= esc(lang('App.dashboardQuickActions')) ?>
            </h3>
            <div class="space-y-2">
                <a href="/user/eligibility">
                    <button class="w-full px-4 py-2 rounded-md font-semibold text-white"
                            style="background-color: #0747A6;">
                        <?= esc(lang('App.dashboardCheckEligibility')) ?>
                    </button>
                </a>
                <a href="/user/application">
                    <button class="w-full px-4 py-2 rounded-md font-semibold text-white"
                            style="background-color: #2563EB;">
                        <?= esc(lang('App.dashboardStartApplication')) ?>
                    </button>
                </a>
                <a href="/user/application/status">
                    <button class="w-full px-4 py-2 rounded-md font-semibold border"
                            style="border-color: #0747A6; color: #0747A6;">
                        <?= esc(lang('App.dashboardViewStatus')) ?>
                    </button>
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">
                <?= esc(lang('App.dashboardProfile')) ?>
            </h3>
            <a href="/user/profile">
                <button class="w-full px-4 py-2 rounded-md font-semibold border"
                        style="border-color: #0747A6; color: #0747A6;">
                    <?= esc(lang('App.dashboardEditProfile')) ?>
                </button>
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4" style="color: #0F1F3F;">
            <?= esc(lang('App.dashboardFlowTitle')) ?>
        </h2>
        <?php
        // Flags already computed above for status card
        ?>
        <div class="space-y-4">
            <!-- Step 1: Eligibility -->
            <a href="/user/eligibility">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                    <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep1')) ?></span>
                    <?php if ($eligibilityDone): ?>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-white text-xs"
                                  style="background-color:#16A34A;">✓</span>
                            <span class="text-xs font-semibold" style="color:#16A34A;">Completed</span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                                    style="border-color:#16A34A; color:#16A34A;">
                                Edit
                    </button>
                        </div>
                    <?php else: ?>
                        <button class="px-3 py-1 border rounded-md text-sm"
                                style="border-color:#0747A6; color:#0747A6;">
                            <?= esc(lang('App.dashboardGo')) ?>
                        </button>
                    <?php endif; ?>
                </div>
            </a>

            <!-- Step 2: Application Form -->
            <div class="<?= $eligibilityDone ? '' : 'opacity-60 cursor-not-allowed' ?>">
                <?php if ($eligibilityDone): ?>
            <a href="/user/application">
                <?php endif; ?>
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                        <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep2')) ?></span>
                        <?php if ($applicationDone): ?>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-white text-xs"
                                      style="background-color:#16A34A;">✓</span>
                                <span class="text-xs font-semibold" style="color:#16A34A;">Completed</span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                                        style="border-color:#16A34A; color:#16A34A;">
                                    Edit
                    </button>
                </div>
                        <?php else: ?>
                            <button class="px-3 py-1 border rounded-md text-sm"
                                    style="border-color:#0747A6; color:#0747A6;"
                                    <?= $eligibilityDone ? '' : 'disabled' ?>>
                                <?= esc(lang('App.dashboardGo')) ?>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php if ($eligibilityDone): ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Step 3: Documents -->
            <div class="<?= $applicationDone ? '' : 'opacity-60 cursor-not-allowed' ?>">
                <?php if ($applicationDone): ?>
            <a href="/user/documents">
                <?php endif; ?>
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                        <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep3')) ?></span>
                        <?php if ($documentsDone): ?>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-white text-xs"
                                      style="background-color:#16A34A;">✓</span>
                                <span class="text-xs font-semibold" style="color:#16A34A;">Completed</span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                                        style="border-color:#16A34A; color:#16A34A;">
                                    Edit
                    </button>
                </div>
                        <?php else: ?>
                            <button class="px-3 py-1 border rounded-md text-sm"
                                    style="border-color:#0747A6; color:#0747A6;"
                                    <?= $applicationDone ? '' : 'disabled' ?>>
                                <?= esc(lang('App.dashboardGo')) ?>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php if ($applicationDone): ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Step 4: Payment -->
            <div class="<?= $documentsDone ? '' : 'opacity-60 cursor-not-allowed' ?>">
                <?php if ($documentsDone): ?>
            <a href="/user/payment">
                <?php endif; ?>
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                        <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep4')) ?></span>
                        <?php if ($paymentDone): ?>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-white text-xs"
                                      style="background-color:#16A34A;">✓</span>
                                <span class="text-xs font-semibold" style="color:#16A34A;">Completed</span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                                        style="border-color:#16A34A; color:#16A34A;">
                                    Edit
                    </button>
                </div>
                        <?php else: ?>
                            <button class="px-3 py-1 border rounded-md text-sm"
                                    style="border-color:#0747A6; color:#0747A6;"
                                    <?= $documentsDone ? '' : 'disabled' ?>>
                                <?= esc(lang('App.dashboardGo')) ?>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php if ($documentsDone): ?>
                    </a>
                <?php endif; ?>
            </div>
            <a href="/user/application/status">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                    <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep5')) ?></span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        <?= esc(lang('App.dashboardGo')) ?>
                    </button>
                </div>
            </a>
            <a href="/user/lottery-results">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                    <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep6')) ?></span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        <?= esc(lang('App.dashboardGo')) ?>
                    </button>
                </div>
            </a>
            <a href="/user/allotment">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                    <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep7')) ?></span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        <?= esc(lang('App.dashboardGo')) ?>
                    </button>
                </div>
            </a>
            <a href="/user/refund-status">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                    <span style="color: #4B5563;"><?= esc(lang('App.dashboardStep8')) ?></span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        <?= esc(lang('App.dashboardGo')) ?>
                    </button>
                </div>
            </a>
        </div>
    </div>
</div>
