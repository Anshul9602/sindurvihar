<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        <?= esc(lang('App.allotmentDetailsTitle')) ?>
    </h1>

    <div id="allotment-container" class="bg-white shadow-md rounded-lg p-6">
        <p style="color: #4B5563;"><?= esc(lang('App.allotmentNoAllotmentFound')) ?> <?= esc(lang('App.refundStatusDemoPlaceholder')) ?></p>
    </div>
</div>

<script>
    (function () {
        function getAllotments() {
            try {
                var raw = localStorage.getItem("admin_allotments");
                return raw ? JSON.parse(raw) : [];
            } catch (e) {
                return [];
            }
        }

        var container = document.getElementById("allotment-container");
        var list = getAllotments();
        if (!container) return;

        if (!list.length) {
            container.innerHTML = '<p style="color:#4B5563;"><?= esc(lang('App.allotmentNoAllotmentFound')) ?> <?= esc(lang('App.refundStatusDemoPlaceholder')) ?></p>';
            return;
        }

        var first = list[0];
        container.innerHTML =
            '<p class="mb-2" style="color:#4B5563;"><?= esc(lang('App.allotmentNumber')) ?> <strong>' + first.id + '</strong></p>' +
            '<p class="mb-2" style="color:#4B5563;"><?= esc(lang('App.allotmentPlotDetails')) ?> <strong>' + (first.plot || "Demo Plot") + '</strong></p>' +
            '<p style="color:#4B5563;"><?= esc(lang('App.allotmentPrintMessage')) ?></p>';
    })();
</script>


