<div class="container mx-auto px-4 py-12 max-w-md">
    <div class="bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
            Applicant Registration
        </h1>

        <form id="register-form" class="space-y-4">
            <div>
                <label for="reg-mobile" class="block text-sm font-medium mb-1">Mobile Number</label>
                <input id="reg-mobile" name="mobile" type="text" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div>
                <label for="reg-password" class="block text-sm font-medium mb-1">Password</label>
                <input id="reg-password" name="password" type="password" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <button type="submit"
                    class="w-full px-4 py-2 rounded-md font-semibold text-white"
                    style="background-color: #0747A6;">
                Register
            </button>
            <button type="button" id="register-bypass"
                    class="w-full mt-2 px-4 py-2 rounded-md font-semibold border"
                    style="border-color: #0747A6; color: #0747A6;">
                Skip (Bypass Demo)
            </button>
        </form>
    </div>
</div>

<script>
    (function () {
        function generateMockUser() {
            return {
                id: Date.now().toString(),
                name: "Demo User",
                mobile: document.getElementById("reg-mobile").value || "9999999999"
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

        function handleRegister(e) {
            if (e) {
                e.preventDefault();
            }
            var mockUser = generateMockUser();
            var mockProfile = generateMockUserProfile(mockUser.id);
            setUserAuth({ user: mockUser, token: "mock_token" });
            setUserProfile(mockProfile);
            window.location.href = "/user/dashboard";
        }

        var form = document.getElementById("register-form");
        var bypass = document.getElementById("register-bypass");
        if (form) {
            form.addEventListener("submit", handleRegister);
        }
        if (bypass) {
            bypass.addEventListener("click", function () {
                handleRegister(null);
            });
        }
    })();
</script>


