<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        Eligibility Checker
    </h1>

    <form id="eligibility-form" class="bg-white shadow-md rounded-lg p-6 space-y-4">
        <div>
            <label for="age" class="block text-sm font-medium mb-1">Age</label>
            <input id="age" type="number" min="18" max="70" required
                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
        </div>
        <div>
            <label for="income" class="block text-sm font-medium mb-1">Annual Household Income (₹)</label>
            <input id="income" type="number" min="0" required
                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
        </div>
        <div>
            <label for="residency" class="block text-sm font-medium mb-1">Residency</label>
            <select id="residency"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                <option value="state">Resident of the State</option>
                <option value="outside">Outside State</option>
            </select>
        </div>
        <div>
            <label for="property" class="block text-sm font-medium mb-1">Existing Residential Property</label>
            <select id="property"
                    class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                <option value="none">No property in scheme area</option>
                <option value="has">Already own property</option>
            </select>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mt-4">
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold text-white"
                    style="background-color: #0747A6;">
                Check Eligibility
            </button>
            <button type="button" id="eligibility-bypass"
                    class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold border"
                    style="border-color: #0747A6; color: #0747A6;">
                Skip (Mark Eligible)
            </button>
        </div>

        <p id="eligibility-result" class="mt-4 text-sm" style="color: #4B5563;"></p>
    </form>
</div>

<script>
    (function () {
        function saveEligibility(data) {
            localStorage.setItem("user_eligibility", JSON.stringify(data));
        }

        function handleResult(isEligible) {
            var el = document.getElementById("eligibility-result");
            if (!el) return;
            if (isEligible) {
                el.textContent = "You are eligible. Proceed to the application form.";
                el.style.color = "#16A34A";
                saveEligibility({ eligible: true });
                setTimeout(function () {
                    window.location.href = "/user/application";
                }, 1000);
            } else {
                el.textContent = "You are not eligible based on the provided details.";
                el.style.color = "#DC2626";
                saveEligibility({ eligible: false });
            }
        }

        var form = document.getElementById("eligibility-form");
        if (form) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
                handleResult(true);
            });
        }

        var bypass = document.getElementById("eligibility-bypass");
        if (bypass) {
            bypass.addEventListener("click", function () {
                handleResult(true);
            });
        }
    })();
</script>


