<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        Profile Settings
    </h1>

    <form id="profile-form" class="bg-white shadow-md rounded-lg p-6 space-y-4">
        <div>
            <label for="profile-name" class="block text-sm font-medium mb-1">Full Name</label>
            <input id="profile-name" type="text"
                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
        </div>
        <div>
            <label for="profile-email" class="block text-sm font-medium mb-1">Email</label>
            <input id="profile-email" type="email"
                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
        </div>
        <div>
            <label for="profile-language" class="block text-sm font-medium mb-1">Language</label>
            <select id="profile-language"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                <option value="en">English</option>
                <option value="hi">Hindi</option>
            </select>
        </div>

        <button type="submit"
                class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold text-white"
                style="background-color: #0747A6;">
            Save Profile
        </button>
    </form>
</div>

<script>
    (function () {
        function getProfile() {
            try {
                var raw = localStorage.getItem("user_profile");
                return raw ? JSON.parse(raw) : null;
            } catch (e) {
                return null;
            }
        }

        function saveProfile(profile) {
            localStorage.setItem("user_profile", JSON.stringify(profile));
        }

        var profile = getProfile() || {};
        var nameEl = document.getElementById("profile-name");
        var emailEl = document.getElementById("profile-email");
        var langEl = document.getElementById("profile-language");

        if (nameEl) nameEl.value = profile.name || "";
        if (emailEl) emailEl.value = profile.email || "";
        if (langEl && profile.language) langEl.value = profile.language;

        var form = document.getElementById("profile-form");
        if (form) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
                var updated = {
                    name: nameEl ? nameEl.value : "",
                    email: emailEl ? emailEl.value : "",
                    language: langEl ? langEl.value : "en"
                };
                saveProfile(updated);
                alert("Profile saved (stored in localStorage).");
            });
        }
    })();
</script>


