<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        <?= esc(lang('App.refundStatusTitle')) ?>
    </h1>

    <div id="refund-container" class="bg-white shadow-md rounded-lg p-6">
        <p style="color: #4B5563;"><?= esc(lang('App.refundStatusNoRecords')) ?> <?= esc(lang('App.refundStatusDemoPlaceholder')) ?></p>
    </div>
</div>

<script>
    (function () {
        function getPayments() {
            try {
                var raw = localStorage.getItem("user_payments");
                return raw ? JSON.parse(raw) : [];
            } catch (e) {
                return [];
            }
        }

        var container = document.getElementById("refund-container");
        var payments = getPayments();
        if (!container) return;

        if (!payments.length) {
            container.innerHTML = '<p style="color:#4B5563;"><?= esc(lang('App.refundStatusNoRecords')) ?> <?= esc(lang('App.refundStatusDemoPlaceholder')) ?></p>';
            return;
        }

        var html = '<ul class="space-y-2">';
        for (var i = 0; i < payments.length; i++) {
            var p = payments[i];
            html += '<li class="flex justify-between border-b pb-2">' +
                '<span style="color:#4B5563;"><?= esc(lang('App.refundStatusPaymentId')) ?> ' + p.id + '</span>' +
                '<span style="color:#16A34A;"><?= esc(lang('App.refundStatusStatus')) ?> ' + (p.status || "success") + '</span>' +
                '</li>';
        }
        html += '</ul>';
        container.innerHTML = html;
    })();
</script>


