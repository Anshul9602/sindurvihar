<!-- Title Section -->
<div class="mb-6">
    <h1 class="text-2xl font-bold mb-2" style="color: #0F1F3F;">
        <?= esc(lang('App.adminApplicationDetailTitle')) ?>
    </h1>
</div>

<?php 
// Check if payment exists
$hasPayment = !empty($payment);
$paymentStatus = $payment['status'] ?? null;
$canVerify = $hasPayment && ($paymentStatus === 'completed' || $paymentStatus === 'success');
?>

<?php if (!$hasPayment): ?>
<!-- Payment Pending Banner -->
<div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-md">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-semibold text-yellow-800">
                <?= esc(lang('App.adminPaymentPending')) ?>
            </p>
            <p class="text-sm text-yellow-700 mt-1">
                <?= esc(lang('App.adminPaymentPendingMessage')) ?>
            </p>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (empty($application)): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <p style="color: #4B5563;"><?= esc(lang('App.adminApplicationNotFound')) ?></p>
    </div>
<?php else: ?>
    <!-- Main Application Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <!-- Application Information Section -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminApplicationInformation')) ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationId')) ?>
                    </label>
                    <p class="text-base font-semibold" style="color: #111827;">
                        <?= esc($application['id']) ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationStatus')) ?>
                    </label>
                    <p class="text-base">
                        <?php 
                        $status = $application['status'] ?? 'draft';
                        $statusClass = 'bg-gray-100 text-gray-800';
                        if ($status === 'submitted') $statusClass = 'bg-yellow-100 text-yellow-800';
                        elseif ($status === 'verified') $statusClass = 'bg-green-100 text-green-800';
                        elseif ($status === 'rejected') $statusClass = 'bg-red-100 text-red-800';
                        ?>
                        <span class="px-3 py-1 rounded text-sm font-semibold uppercase <?= $statusClass ?>">
                            <?= esc(ucfirst(str_replace('_', ' ', $status))) ?>
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationFullName')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= esc($application['full_name']) ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationAadhaar')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= esc($application['aadhaar'] ?? 'N/A') ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationAddress')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= esc($application['address'] ?? 'N/A') ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationCityState')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= esc($application['city'] ?? 'N/A') ?>, <?= esc($application['state'] ?? 'N/A') ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationAnnualIncome')) ?>
                    </label>
                    <p class="text-base font-semibold" style="color: #111827;">
                        ₹<?= number_format($application['income'] ?? 0, 2) ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationIncomeCategory')) ?>
                    </label>
                    <p class="text-base">
                        <span class="px-3 py-1 rounded text-sm font-semibold uppercase bg-blue-100 text-blue-800">
                            <?= esc(strtoupper($application['income_category'] ?? 'General')) ?>
                        </span>
                    </p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminApplicationSubmittedDate')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= date('d M Y, h:i A', strtotime($application['created_at'] ?? 'now')) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        <?php if (!empty($documents)): ?>
        <div class="border-t pt-6 mt-6">
            <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminDocuments')) ?>
            </h2>
            <div class="space-y-4">
                <?php
                // Parse document files from JSON
                $identityFiles = !empty($documents['identity_files']) ? json_decode($documents['identity_files'], true) : [];
                $incomeFiles = !empty($documents['income_files']) ? json_decode($documents['income_files'], true) : [];
                $residenceFiles = !empty($documents['residence_files']) ? json_decode($documents['residence_files'], true) : [];
                $annexureFiles = !empty($documents['annexure_files']) ? json_decode($documents['annexure_files'], true) : [];
                ?>
                
                <?php if (!empty($identityFiles)): ?>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #6B7280;">
                        <?= esc(lang('App.adminIdentityProof')) ?>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($identityFiles as $file): ?>
                            <a href="/<?= esc($file) ?>" target="_blank" 
                               class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <?= esc(basename($file)) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($incomeFiles)): ?>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #6B7280;">
                        <?= esc(lang('App.adminIncomeProof')) ?>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($incomeFiles as $file): ?>
                            <a href="/<?= esc($file) ?>" target="_blank" 
                               class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <?= esc(basename($file)) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($residenceFiles)): ?>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #6B7280;">
                        <?= esc(lang('App.adminResidenceProof')) ?>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($residenceFiles as $file): ?>
                            <a href="/<?= esc($file) ?>" target="_blank" 
                               class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <?= esc(basename($file)) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($annexureFiles)): ?>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #6B7280;">
                        <?= esc(lang('App.adminAnnexureFiles')) ?>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($annexureFiles as $file): ?>
                            <a href="/<?= esc($file) ?>" target="_blank" 
                               class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <?= esc(basename($file)) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($documents['notes'])): ?>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: #6B7280;">
                        <?= esc(lang('App.adminNotes')) ?>
                    </label>
                    <p class="text-sm p-3 bg-gray-50 rounded-md" style="color: #374151;">
                        <?= esc($documents['notes']) ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Payment Details Section -->
        <?php if (!empty($payment)): ?>
        <div class="border-t pt-6 mt-6">
            <h2 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminPaymentDetails')) ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminPaymentId')) ?>
                    </label>
                    <p class="text-base font-semibold" style="color: #111827;">
                        <?= esc($payment['id']) ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminPaymentAmount')) ?>
                    </label>
                    <p class="text-base font-semibold" style="color: #111827;">
                        ₹<?= number_format($payment['amount'] ?? 0, 2) ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminPaymentStatus')) ?>
                    </label>
                    <p class="text-base">
                        <?php 
                        $paymentStatus = $payment['status'] ?? 'pending';
                        $paymentStatusClass = 'bg-gray-100 text-gray-800';
                        if ($paymentStatus === 'completed' || $paymentStatus === 'success') {
                            $paymentStatusClass = 'bg-green-100 text-green-800';
                            $paymentStatus = lang('App.adminPaymentCompleted');
                        } elseif ($paymentStatus === 'pending') {
                            $paymentStatusClass = 'bg-yellow-100 text-yellow-800';
                            $paymentStatus = lang('App.adminPaymentPending');
                        } elseif ($paymentStatus === 'failed') {
                            $paymentStatusClass = 'bg-red-100 text-red-800';
                            $paymentStatus = lang('App.adminPaymentFailed');
                        }
                        ?>
                        <span class="px-3 py-1 rounded text-sm font-semibold uppercase <?= $paymentStatusClass ?>">
                            <?= esc($paymentStatus) ?>
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminPaymentDate')) ?>
                    </label>
                    <p class="text-base" style="color: #111827;">
                        <?= date('d M Y, h:i A', strtotime($payment['created_at'] ?? 'now')) ?>
                    </p>
                </div>
                <?php if (!empty($payment['transaction_ref'])): ?>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1" style="color: #6B7280;">
                        <?= esc(lang('App.adminPaymentTransactionRef')) ?>
                    </label>
                    <p class="text-base font-mono" style="color: #111827;">
                        <?= esc($payment['transaction_ref']) ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Actions Section -->
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-semibold mb-4" style="color: #0F1F3F;">
                <?= esc(lang('App.adminApplicationActions')) ?>
            </h3>
            <div class="flex flex-wrap gap-3">
                <?php 
                // Show verify button if payment is completed and application is not already verified or rejected
                $canVerifyApplication = $canVerify && 
                    $application['status'] !== 'verified' && 
                    $application['status'] !== 'rejected';
                
                if ($canVerifyApplication): 
                ?>
                    <button id="btn-verify" 
                            class="px-6 py-2 rounded-md font-semibold text-white bg-green-600 hover:bg-green-700 transition">
                        <?= esc(lang('App.adminMarkAsVerified')) ?>
                    </button>
                <?php endif; ?>
                <?php if ($application['status'] !== 'rejected'): ?>
                    <button id="btn-reject" 
                            class="px-6 py-2 rounded-md font-semibold text-white bg-red-600 hover:bg-red-700 transition">
                        <?= esc(lang('App.adminRejectApplication')) ?>
                    </button>
                <?php endif; ?>
                <a href="/admin/applications" 
                   class="px-6 py-2 rounded-md font-semibold border-2 border-blue-600 text-blue-600 hover:bg-blue-50 transition">
                    <?= esc(lang('App.adminBackToList')) ?>
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Reject Application Modal -->
<div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-xl font-bold mb-4" style="color: #0F1F3F;">
            <?= esc(lang('App.adminRejectApplicationTitle')) ?>
        </h3>
        <form id="reject-form">
            <div class="mb-4">
                <label for="reject-reason" class="block text-sm font-medium mb-2" style="color: #374151;">
                    <?= esc(lang('App.adminRejectReasonLabel')) ?>
                </label>
                <textarea id="reject-reason" name="reason" rows="4" required
                          placeholder="<?= esc(lang('App.adminRejectReasonPlaceholder')) ?>"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 px-4 py-2 rounded-md font-semibold text-white bg-red-600 hover:bg-red-700 transition">
                    <?= esc(lang('App.adminRejectConfirm')) ?>
                </button>
                <button type="button" id="reject-cancel"
                        class="flex-1 px-4 py-2 rounded-md font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    <?= esc(lang('App.adminRejectCancel')) ?>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Verify Application Modal -->
<div id="verify-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-xl font-bold mb-4" style="color: #0F1F3F;">
            <?= esc(lang('App.adminVerifyApplicationTitle')) ?>
        </h3>
        <form id="verify-form">
            <div class="mb-4">
                <label class="inline-flex items-start gap-2">
                    <input type="checkbox" id="verify-confirm-check" name="confirmed" value="1" required
                           class="mt-1 w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <span class="text-sm" style="color: #374151;">
                        <?= esc(lang('App.adminVerifyConfirmLabel')) ?>
                    </span>
                </label>
            </div>
            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 px-4 py-2 rounded-md font-semibold text-white bg-green-600 hover:bg-green-700 transition">
                    <?= esc(lang('App.adminVerifyConfirm')) ?>
                </button>
                <button type="button" id="verify-cancel"
                        class="flex-1 px-4 py-2 rounded-md font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    <?= esc(lang('App.adminVerifyCancel')) ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    var appId = <?= isset($application) ? $application['id'] : 'null' ?>;
    var rejectModal = document.getElementById("reject-modal");
    var verifyModal = document.getElementById("verify-modal");
    
    // Reject button handler
    var btnReject = document.getElementById("btn-reject");
    if (btnReject) {
        btnReject.addEventListener("click", function() {
            rejectModal.classList.remove("hidden");
        });
    }
    
    // Verify button handler
    var btnVerify = document.getElementById("btn-verify");
    if (btnVerify) {
        btnVerify.addEventListener("click", function() {
            verifyModal.classList.remove("hidden");
        });
    }
    
    // Close reject modal
    document.getElementById("reject-cancel").addEventListener("click", function() {
        rejectModal.classList.add("hidden");
        document.getElementById("reject-form").reset();
    });
    
    // Close verify modal
    document.getElementById("verify-cancel").addEventListener("click", function() {
        verifyModal.classList.add("hidden");
        document.getElementById("verify-form").reset();
    });
    
    // Close modals on outside click
    rejectModal.addEventListener("click", function(e) {
        if (e.target === rejectModal) {
            rejectModal.classList.add("hidden");
            document.getElementById("reject-form").reset();
        }
    });
    
    verifyModal.addEventListener("click", function(e) {
        if (e.target === verifyModal) {
            verifyModal.classList.add("hidden");
            document.getElementById("verify-form").reset();
        }
    });
    
    // Handle reject form submission
    document.getElementById("reject-form").addEventListener("submit", function(e) {
        e.preventDefault();
        var reason = document.getElementById("reject-reason").value.trim();
        
        if (!reason) {
            alert("<?= esc(lang('App.adminRejectReasonRequired')) ?>");
            return;
        }
        
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/admin/applications/" + appId + "/reject", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("<?= esc(lang('App.adminActionSuccess')) ?>");
                            window.location.reload();
                        } else {
                            alert("<?= esc(lang('App.adminActionFailed')) ?>: " + (response.message || ""));
                        }
                    } catch (e) {
                        alert("Error parsing response");
                    }
                } else {
                    alert("Server error: " + xhr.status);
                }
            }
        };
        
        xhr.send(JSON.stringify({
            reason: reason
        }));
    });
    
    // Handle verify form submission
    document.getElementById("verify-form").addEventListener("submit", function(e) {
        e.preventDefault();
        var confirmed = document.getElementById("verify-confirm-check").checked;
        
        if (!confirmed) {
            alert("<?= esc(lang('App.adminVerifyConfirmRequired')) ?>");
            return;
        }
        
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/admin/applications/" + appId + "/verify", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("<?= esc(lang('App.adminActionSuccess')) ?>");
                            window.location.reload();
                        } else {
                            alert("<?= esc(lang('App.adminActionFailed')) ?>: " + (response.message || ""));
                        }
                    } catch (e) {
                        alert("Error parsing response");
                    }
                } else {
                    alert("Server error: " + xhr.status);
                }
            }
        };
        
        xhr.send(JSON.stringify({
            confirmed: true
        }));
    });
})();
</script>
