<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6" style="color: #0F1F3F;">
        Payments Console
    </h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <table class="w-full text-left text-sm">
            <thead>
            <tr class="border-b">
                <th class="py-2">Payment ID</th>
                <th class="py-2">Application ID</th>
                <th class="py-2">Amount</th>
                <th class="py-2">Status</th>
            </tr>
            </thead>
            <tbody id="admin-payments-body">
            <tr>
                <td colspan="4" class="py-4 text-center" style="color: #4B5563;">
                    Loading payments...
                </td>
            </tr>
            </tbody>
        </table>
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

        var body = document.getElementById("admin-payments-body");
        if (!body) return;

        var list = getPayments();
        if (!list.length) {
            body.innerHTML =
                '<tr><td colspan="4" class="py-4 text-center" style="color:#4B5563;">No payments found.</td></tr>';
            return;
        }

        var html = "";
        for (var i = 0; i < list.length; i++) {
            var p = list[i];
            html += '<tr class="border-b">' +
                '<td class="py-2">' + p.id + '</td>' +
                '<td class="py-2">' + (p.applicationId || "") + '</td>' +
                '<td class="py-2">₹' + (p.amount || 0) + '</td>' +
                '<td class="py-2">' + (p.status || "") + '</td>' +
                '</tr>';
        }
        body.innerHTML = html;
    })();
</script>


