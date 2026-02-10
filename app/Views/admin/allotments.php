<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6" style="color: #0F1F3F;">
        Allotments
    </h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <table class="w-full text-left text-sm">
            <thead>
            <tr class="border-b">
                <th class="py-2">Allotment ID</th>
                <th class="py-2">Application ID</th>
                <th class="py-2">Name</th>
                <th class="py-2">Plot</th>
            </tr>
            </thead>
            <tbody id="admin-allotments-body">
            <tr>
                <td colspan="4" class="py-4 text-center" style="color: #4B5563;">
                    Loading allotments...
                </td>
            </tr>
            </tbody>
        </table>
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

        var body = document.getElementById("admin-allotments-body");
        if (!body) return;

        var list = getAllotments();
        if (!list.length) {
            body.innerHTML =
                '<tr><td colspan="4" class="py-4 text-center" style="color:#4B5563;">No allotments found.</td></tr>';
            return;
        }

        var html = "";
        for (var i = 0; i < list.length; i++) {
            var a = list[i];
            html += '<tr class="border-b">' +
                '<td class="py-2">' + a.id + '</td>' +
                '<td class="py-2">' + (a.applicationId || "") + '</td>' +
                '<td class="py-2">' + (a.name || "") + '</td>' +
                '<td class="py-2">' + (a.plot || "") + '</td>' +
                '</tr>';
        }
        body.innerHTML = html;
    })();
</script>


