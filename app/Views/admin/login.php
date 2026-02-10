<div class="container mx-auto px-4 py-12 max-w-md">
    <div class="bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
            Admin Login
        </h1>

        <form id="admin-login-form" class="space-y-4">
            <div>
                <label for="admin-user" class="block text-sm font-medium mb-1">Username</label>
                <input id="admin-user" name="username" type="text" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div>
                <label for="admin-pass" class="block text-sm font-medium mb-1">Password</label>
                <input id="admin-pass" name="password" type="password" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <button type="submit"
                    class="w-full px-4 py-2 rounded-md font-semibold text-white"
                    style="background-color: #0747A6;">
                Login
            </button>
        </form>
    </div>
</div>

<script>
    (function () {
        function setAdminAuth(data) {
            localStorage.setItem("admin_auth", JSON.stringify(data));
        }

        function handleAdminLogin(e) {
            if (e) {
                e.preventDefault();
            }
            setAdminAuth({
                user: {
                    id: "admin-1",
                    name: "Admin User",
                    role: "admin"
                },
                token: "admin_mock_token"
            });
            window.location.href = "/admin/dashboard";
        }

        var form = document.getElementById("admin-login-form");
        if (form) {
            form.addEventListener("submit", handleAdminLogin);
        }
    })();
</script>


