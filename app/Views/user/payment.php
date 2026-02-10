<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        Application Payment
    </h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <p class="mb-4" style="color: #4B5563;">
            This is a mock payment page. Use the Pay Now button for a normal flow or Skip button to
            auto-complete payment in demo mode.
        </p>

        <div class="flex justify-between items-center mb-4">
            <span style="color: #4B5563;">Amount Payable</span>
            <strong style="color: #0F1F3F;">₹1,000</strong>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mt-4">
            <button id="payment-pay"
                    class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold text-white"
                    style="background-color: #16A34A;">
                Pay Now (Mock)
            </button>
            <button id="payment-bypass"
                    class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold border"
                    style="border-color: #0747A6; color: #0747A6;">
                Skip (Auto-complete Payment)
            </button>
        </div>

        <p id="payment-result" class="mt-4 text-sm" style="color: #4B5563;"></p>
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

        function saveApplication(app) {
            localStorage.setItem("user_application", JSON.stringify(app));
            try {
                var listRaw = localStorage.getItem("admin_applications");
                var list = listRaw ? JSON.parse(listRaw) : [];
                var updated = list.map(function (a) {
                    if (a.id === app.id) {
                        return app;
                    }
                    return a;
                });
                localStorage.setItem("admin_applications", JSON.stringify(updated));
            } catch (e) {
            }
        }

        function savePayment(appId) {
            var payments = [];
            try {
                var raw = localStorage.getItem("user_payments");
                payments = raw ? JSON.parse(raw) : [];
            } catch (e) {
                payments = [];
            }
            payments.push({
                id: Date.now().toString(),
                applicationId: appId,
                amount: 1000,
                status: "success"
            });
            localStorage.setItem("user_payments", JSON.stringify(payments));
        }

        function completePayment() {
            var app = getApplication();
            if (!app) {
                return;
            }
            app.status = "paid";
            saveApplication(app);
            savePayment(app.id);
        }

        var pay = document.getElementById("payment-pay");
        var bypass = document.getElementById("payment-bypass");
        var result = document.getElementById("payment-result");

        function handleDone() {
            completePayment();
            if (result) {
                result.textContent = "Payment completed successfully. Redirecting to status page...";
                result.style.color = "#16A34A";
            }
            setTimeout(function () {
                window.location.href = "/user/application/status";
            }, 1000);
        }

        if (pay) {
            pay.addEventListener("click", function () {
                handleDone();
            });
        }
        if (bypass) {
            bypass.addEventListener("click", function () {
                handleDone();
            });
        }
    })();
</script>


