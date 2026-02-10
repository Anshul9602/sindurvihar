<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        Housing Application
    </h1>

    <form id="application-form" class="bg-white shadow-md rounded-lg p-6 space-y-4">
        <h2 class="text-xl font-semibold mb-2" style="color: #0F1F3F;">Identity Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="app-name" class="block text-sm font-medium mb-1">Full Name</label>
                <input id="app-name" type="text" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div>
                <label for="app-aadhaar" class="block text-sm font-medium mb-1">Aadhaar Number</label>
                <input id="app-aadhaar" type="text" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
        </div>

        <h2 class="text-xl font-semibold mt-4 mb-2" style="color: #0F1F3F;">Residence Details</h2>
        <div>
            <label for="app-address" class="block text-sm font-medium mb-1">Address</label>
            <input id="app-address" type="text" required
                   class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
        </div>

        <h2 class="text-xl font-semibold mt-4 mb-2" style="color: #0F1F3F;">Income Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="app-income" class="block text-sm font-medium mb-1">Annual Income (₹)</label>
                <input id="app-income" type="number" required
                       class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div>
                <label for="app-category" class="block text-sm font-medium mb-1">Category</label>
                <select id="app-category"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-500">
                    <option value="ews">EWS</option>
                    <option value="lig">LIG</option>
                    <option value="mig">MIG</option>
                    <option value="hig">HIG</option>
                </select>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mt-4">
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold text-white"
                    style="background-color: #0747A6;">
                Save & Submit
            </button>
            <button type="button" id="application-bypass"
                    class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold border"
                    style="border-color: #0747A6; color: #0747A6;">
                Skip (Auto-fill & Submit)
            </button>
        </div>
    </form>
</div>

<script>
    (function () {
        function submitToServer(data, callback) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "/user/application/submit", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                callback(true, response.message);
                            } else {
                                callback(false, response.message || "Failed to submit");
                            }
                        } catch (e) {
                            callback(false, "Error parsing response");
                        }
                    } else {
                        callback(false, "Server error: " + xhr.status);
                    }
                }
            };
            
            xhr.send(JSON.stringify(data));
        }

        function handleSubmit(e) {
            if (e) {
                e.preventDefault();
            }
            
            var form = document.getElementById("application-form");
            var submitBtn = form.querySelector("button[type='submit']");
            var originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = "Submitting...";
            
            var formData = {
                user_id: 1, // TODO: Get from session
                full_name: document.getElementById("app-name").value,
                aadhaar: document.getElementById("app-aadhaar").value,
                address: document.getElementById("app-address").value,
                city: "",
                state: "",
                income: document.getElementById("app-income").value,
                income_category: document.getElementById("app-category").value
            };
            
            submitToServer(formData, function(success, message) {
                if (success) {
                    alert("Application submitted successfully!");
                    window.location.href = "/user/documents";
                } else {
                    alert("Error: " + message);
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            });
        }

        function handleBypass() {
            // Auto-fill form and submit
            document.getElementById("app-name").value = "Demo Applicant";
            document.getElementById("app-aadhaar").value = "1234 5678 9012";
            document.getElementById("app-address").value = "Demo Address, City, State";
            document.getElementById("app-income").value = "300000";
            document.getElementById("app-category").value = "ews";
            
            // Submit after a short delay
            setTimeout(function() {
                handleSubmit(null);
            }, 500);
        }

        var form = document.getElementById("application-form");
        var bypass = document.getElementById("application-bypass");
        if (form) {
            form.addEventListener("submit", handleSubmit);
        }
        if (bypass) {
            bypass.addEventListener("click", handleBypass);
        }
    })();
</script>


