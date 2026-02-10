<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6" style="color: #0F1F3F;">
        Application Detail
    </h1>

    <?php if (empty($application)): ?>
        <div class="bg-white shadow-md rounded-lg p-6">
            <p style="color: #4B5563;">Application not found.</p>
        </div>
    <?php else: ?>
        <div class="bg-white shadow-md rounded-lg p-6 space-y-4">
            <div>
                <h2 class="text-xl font-semibold mb-4" style="color: #0F1F3F;">Application Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Application ID</label>
                        <p class="text-base font-semibold" style="color: #0F1F3F;"><?= esc($application['id']) ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Status</label>
                        <p class="text-base">
                            <span class="px-3 py-1 rounded text-sm uppercase
                                <?php 
                                $status = $application['status'] ?? 'draft';
                                if ($status === 'submitted') echo 'bg-yellow-100 text-yellow-800';
                                elseif ($status === 'verified') echo 'bg-green-100 text-green-800';
                                elseif ($status === 'rejected') echo 'bg-red-100 text-red-800';
                                else echo 'bg-gray-100 text-gray-800';
                                ?>">
                                <?= esc(ucfirst(str_replace('_', ' ', $status))) ?>
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Full Name</label>
                        <p class="text-base" style="color: #0F1F3F;"><?= esc($application['full_name']) ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Aadhaar Number</label>
                        <p class="text-base" style="color: #0F1F3F;"><?= esc($application['aadhaar']) ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Address</label>
                        <p class="text-base" style="color: #0F1F3F;"><?= esc($application['address']) ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">City/State</label>
                        <p class="text-base" style="color: #0F1F3F;">
                            <?= esc($application['city'] ?? 'N/A') ?>, <?= esc($application['state'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Annual Income</label>
                        <p class="text-base" style="color: #0F1F3F;">₹<?= number_format($application['income'], 2) ?></p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Income Category</label>
                        <p class="text-base">
                            <span class="px-3 py-1 rounded text-sm uppercase bg-blue-100 text-blue-800">
                                <?= esc(strtoupper($application['income_category'])) ?>
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium" style="color: #4B5563;">Submitted Date</label>
                        <p class="text-base" style="color: #0F1F3F;">
                            <?= date('d M Y, h:i A', strtotime($application['created_at'])) ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="border-t pt-4 mt-4">
                <h3 class="text-lg font-semibold mb-3" style="color: #0F1F3F;">Actions</h3>
                <div class="flex flex-wrap gap-3">
                    <?php if ($application['status'] === 'submitted' || $application['status'] === 'under_verification'): ?>
                        <button id="btn-verify" 
                                class="px-4 py-2 rounded-md font-semibold text-white"
                                style="background-color: #16A34A;">
                            Mark as Verified
                        </button>
                    <?php endif; ?>
                    <?php if ($application['status'] !== 'rejected'): ?>
                        <button id="btn-reject" 
                                class="px-4 py-2 rounded-md font-semibold text-white"
                                style="background-color: #DC2626;">
                            Reject Application
                        </button>
                    <?php endif; ?>
                    <a href="/admin/applications" 
                       class="px-4 py-2 rounded-md font-semibold border"
                       style="border-color: #0747A6; color: #0747A6;">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    (function () {
        var appId = <?= isset($application) ? $application['id'] : 'null' ?>;
        
        function updateStatus(newStatus) {
            if (!appId) return;
            
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/admin/applications/update-status", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                alert("Application status updated successfully!");
                                window.location.reload();
                            } else {
                                alert("Error: " + (response.message || "Failed to update status"));
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
                application_id: appId,
                status: newStatus
            }));
        }
        
        var btnVerify = document.getElementById("btn-verify");
        if (btnVerify) {
            btnVerify.addEventListener("click", function() {
                if (confirm("Are you sure you want to mark this application as verified?")) {
                    updateStatus("verified");
                }
            });
        }
        
        var btnReject = document.getElementById("btn-reject");
        if (btnReject) {
            btnReject.addEventListener("click", function() {
                if (confirm("Are you sure you want to reject this application?")) {
                    updateStatus("rejected");
                }
            });
        }
    })();
</script>


