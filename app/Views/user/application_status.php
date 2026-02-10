<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        Application Status
    </h1>

    <div id="status-container" class="bg-white shadow-md rounded-lg p-6">
        <p style="color: #4B5563;">Loading status...</p>
    </div>
</div>

<script>
    (function () {
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
                    return "Selected in Lottery";
                case "allotted":
                    return "Allotted";
                case "possession":
                    return "Possession Granted";
                default:
                    return "Draft";
            }
        }

        var container = document.getElementById("status-container");
        var app = getApplication();
        if (!container) return;

        if (!app) {
            container.innerHTML =
                '<p style="color:#4B5563;">No application found. Start a new application from your dashboard.</p>';
            return;
        }

        var label = statusLabel(app.status);
        container.innerHTML =
            '<div>' +
            '<p class="mb-2" style="color:#4B5563;">Application ID: <strong>' + app.id + '</strong></p>' +
            '<p class="mb-2" style="color:#4B5563;">Current Status: <strong>' + label + '</strong></p>' +
            '<p style="color:#4B5563;">You can track further updates from this page or the dashboard.</p>' +
            '</div>';
    })();
</script>


