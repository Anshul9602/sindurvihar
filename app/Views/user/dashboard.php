<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6" style="color: #0F1F3F;">
        Welcome, <span id="user-name">User</span>
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">
                Application Status
            </h3>
            <div id="application-status-container">
                <p style="color: #4B5563;">No application found. Start a new application.</p>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">
                Quick Actions
            </h3>
            <div class="space-y-2">
                <a href="/user/eligibility">
                    <button class="w-full px-4 py-2 rounded-md font-semibold text-white"
                            style="background-color: #0747A6;">
                        Check Eligibility
                    </button>
                </a>
                <a href="/user/application">
                    <button class="w-full px-4 py-2 rounded-md font-semibold text-white"
                            style="background-color: #2563EB;">
                        Start Application
                    </button>
                </a>
                <a href="/user/application/status">
                    <button class="w-full px-4 py-2 rounded-md font-semibold border"
                            style="border-color: #0747A6; color: #0747A6;">
                        View Status
                    </button>
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2" style="color: #0F1F3F;">
                Profile
            </h3>
            <a href="/user/profile">
                <button class="w-full px-4 py-2 rounded-md font-semibold border"
                        style="border-color: #0747A6; color: #0747A6;">
                    Edit Profile
                </button>
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4" style="color: #0F1F3F;">
            Application Flow
        </h2>
        <div class="space-y-4">
            <a href="/user/eligibility">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                    <span style="color: #4B5563;">Step 1: Eligibility Check</span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        Go
                    </button>
                </div>
            </a>
            <a href="/user/application">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                    <span style="color: #4B5563;">Step 2: Application Form</span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        Go
                    </button>
                </div>
            </a>
            <a href="/user/documents">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                    <span style="color: #4B5563;">Step 3: Upload Documents</span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        Go
                    </button>
                </div>
            </a>
            <a href="/user/payment">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                    <span style="color: #4B5563;">Step 4: Payment</span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        Go
                    </button>
                </div>
            </a>
            <a href="/user/application/status">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                    <span style="color: #4B5563;">Step 5: Application Status</span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        Go
                    </button>
                </div>
            </a>
            <a href="/user/lottery-results">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                    <span style="color: #4B5563;">Step 6: Lottery Results</span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        Go
                    </button>
                </div>
            </a>
            <a href="/user/allotment">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                    <span style="color: #4B5563;">Step 7: Allotment</span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        Go
                    </button>
                </div>
            </a>
            <a href="/user/refund-status">
                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                    <span style="color: #4B5563;">Step 8: Refund Status</span>
                    <button class="px-3 py-1 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        Go
                    </button>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    (function () {
        function getUserAuth() {
            try {
                var raw = localStorage.getItem("user_auth");
                return raw ? JSON.parse(raw) : null;
            } catch (e) {
                return null;
            }
        }

        function getApplication() {
            try {
                var raw = localStorage.getItem("user_application");
                return raw ? JSON.parse(raw) : null;
            } catch (e) {
                return null;
            }
        }

        function statusLabel(status) {
            switch (status) {
                case "submitted":
                    return "Submitted";
                case "paid":
                    return "Paid";
                case "under_verification":
                    return "Under Verification";
                case "verified":
                    return "Verified";
                case "rejected":
                    return "Rejected";
                case "selected":
                    return "Selected";
                case "allotted":
                    return "Allotted";
                case "possession":
                    return "Possession Granted";
                default:
                    return "Draft";
            }
        }

        var auth = getUserAuth();
        if (!auth || !auth.user) {
            window.location.href = "/auth/login";
            return;
        }

        var nameEl = document.getElementById("user-name");
        if (nameEl) {
            nameEl.textContent = auth.user.name || "User";
        }

        var app = getApplication();
        var statusContainer = document.getElementById("application-status-container");
        if (statusContainer) {
            if (!app) {
                statusContainer.innerHTML = '<p style="color:#4B5563;">No application found. Start a new application.</p>';
            } else {
                var label = statusLabel(app.status);
                statusContainer.innerHTML =
                    '<span class="inline-block px-3 py-1 rounded-full text-white text-sm" ' +
                    'style="background-color:#2563EB;">' + label + '</span>';
            }
        }
    })();
</script>


