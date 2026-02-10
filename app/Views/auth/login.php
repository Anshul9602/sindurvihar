<div class="container mx-auto px-4 py-12 max-w-md">
    <div class="bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
            Applicant Login
        </h1>

        <form id="login-form" class="space-y-4">
            <div>
                <label for="login-mobile" class="block text-sm font-medium mb-1">Mobile / Email</label>
                <input id="login-mobile" name="mobile" type="text" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div>
                <label for="login-password" class="block text-sm font-medium mb-1">Password</label>
                <input id="login-password" name="password" type="password" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <button type="submit"
                    class="w-full px-4 py-2 rounded-md font-semibold text-white"
                    style="background-color: #0747A6;">
                Login
            </button>
            <button type="button" id="login-bypass"
                    class="w-full mt-2 px-4 py-2 rounded-md font-semibold border"
                    style="border-color: #0747A6; color: #0747A6;">
                Skip (Bypass Demo)
            </button>
            <p class="text-center text-sm mt-4" style="color: #4B5563;">
                Don't have an account?
                <a href="/auth/register" class="text-blue-600 hover:underline">Register</a>
            </p>
        </form>
    </div>
</div>

<script>
    (function () {
        function generateMockUser() {
            return {
                id: Date.now().toString(),
                name: "Demo User",
                mobile: "9999999999"
            };
        }

        function generateMockUserProfile(userId) {
            return {
                userId: userId,
                email: "demo@example.com",
                language: "en"
            };
        }

        function setUserAuth(data) {
            localStorage.setItem("user_auth", JSON.stringify(data));
        }

        function setUserProfile(profile) {
            localStorage.setItem("user_profile", JSON.stringify(profile));
        }

        function handleLogin(e) {
            if (e) {
                e.preventDefault();
            }
            var mockUser = generateMockUser();
            var mockProfile = generateMockUserProfile(mockUser.id);
            setUserAuth({ user: mockUser, token: "mock_token" });
            setUserProfile(mockProfile);
            window.location.href = "/user/dashboard";
        }

        var form = document.getElementById("login-form");
        var bypass = document.getElementById("login-bypass");
        if (form) {
            form.addEventListener("submit", handleLogin);
        }
        if (bypass) {
            bypass.addEventListener("click", function () {
                handleLogin(null);
            });
        }
    })();
</script>


