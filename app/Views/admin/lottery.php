<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6" style="color: #0F1F3F;">
        Lottery Management
    </h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <p class="mb-4" style="color: #4B5563;">
            Run the lottery to randomly select one verified application and create an allotment record.
        </p>

        <button id="run-lottery"
                class="px-6 py-2 rounded-md font-semibold text-white"
                style="background-color: #0747A6;">
            Run Lottery
        </button>

        <p id="lottery-result" class="mt-4 text-sm" style="color: #4B5563;"></p>
    </div>
</div>

<script>
    (function () {
        function getApplications() {
            try {
                var raw = localStorage.getItem("admin_applications");
                return raw ? JSON.parse(raw) : [];
            } catch (e) {
                return [];
            }
        }

        function saveAllotment(app) {
            var list = [];
            try {
                var raw = localStorage.getItem("admin_allotments");
                list = raw ? JSON.parse(raw) : [];
            } catch (e) {
                list = [];
            }
            var allotment = {
                id: "ALLOT-" + Date.now().toString(),
                applicationId: app.id,
                name: app.name,
                plot: "Plot A-101"
            };
            list.push(allotment);
            localStorage.setItem("admin_allotments", JSON.stringify(list));
        }

        var btn = document.getElementById("run-lottery");
        var result = document.getElementById("lottery-result");
        if (!btn) return;

        btn.addEventListener("click", function () {
            var apps = getApplications().filter(function (a) { return a.status === "verified" || a.status === "paid"; });
            if (!apps.length) {
                if (result) {
                    result.textContent = "No eligible applications (paid/verified) found.";
                    result.style.color = "#DC2626";
                }
                return;
            }
            var selected = apps[Math.floor(Math.random() * apps.length)];
            selected.status = "selected";

            var all = getApplications();
            for (var i = 0; i < all.length; i++) {
                if (all[i].id === selected.id) {
                    all[i] = selected;
                    break;
                }
            }
            localStorage.setItem("admin_applications", JSON.stringify(all));
            localStorage.setItem("user_application", JSON.stringify(selected));
            saveAllotment(selected);

            if (result) {
                result.textContent = "Lottery run successfully. Selected application ID: " + selected.id;
                result.style.color = "#16A34A";
            }
        });
    })();
</script>


