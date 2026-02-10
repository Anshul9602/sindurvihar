<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6" style="color: #0F1F3F;">
        Admin Dashboard
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">Total Applications</h3>
            <p id="admin-total-apps" class="text-3xl font-bold" style="color: #0747A6;">0</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">Pending Verification</h3>
            <p id="admin-pending" class="text-3xl font-bold" style="color: #F59E0B;">0</p>
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">Verified</h3>
            <p id="admin-verified" class="text-3xl font-bold" style="color: #10B981;">0</p>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4" style="color: #0F1F3F;">Quick Links</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="/admin/applications" class="block p-4 border rounded-lg hover:bg-gray-50">
                <h3 class="font-semibold mb-1" style="color: #0747A6;">Applications List</h3>
                <p class="text-sm" style="color: #4B5563;">View and manage all applications.</p>
            </a>
            <a href="/admin/verification" class="block p-4 border rounded-lg hover:bg-gray-50">
                <h3 class="font-semibold mb-1" style="color: #0747A6;">Verification Queue</h3>
                <p class="text-sm" style="color: #4B5563;">Verify applicant documents.</p>
            </a>
            <a href="/admin/lottery" class="block p-4 border rounded-lg hover:bg-gray-50">
                <h3 class="font-semibold mb-1" style="color: #0747A6;">Lottery Management</h3>
                <p class="text-sm" style="color: #4B5563;">Configure and run lotteries.</p>
            </a>
        </div>
    </div>
</div>

<script>
    (function () {
        function getAdminApplications() {
            try {
                var raw = localStorage.getItem("admin_applications");
                return raw ? JSON.parse(raw) : [];
            } catch (e) {
                return [];
            }
        }

        var apps = getAdminApplications();
        var total = apps.length;
        var pending = apps.filter(function (a) { return a.status === "under_verification" || a.status === "submitted"; }).length;
        var verified = apps.filter(function (a) { return a.status === "verified"; }).length;

        var elTotal = document.getElementById("admin-total-apps");
        var elPending = document.getElementById("admin-pending");
        var elVerified = document.getElementById("admin-verified");

        if (elTotal) elTotal.textContent = String(total);
        if (elPending) elPending.textContent = String(pending);
        if (elVerified) elVerified.textContent = String(verified);
    })();
</script>


