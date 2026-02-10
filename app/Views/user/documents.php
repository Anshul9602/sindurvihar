<div class="container mx-auto px-4 py-8 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #0F1F3F;">
        Document Upload
    </h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <p class="mb-4" style="color: #4B5563;">
            Upload your identity, income, and residence proof documents. For demo purposes, you can use
            the Skip button to auto-upload mock files.
        </p>

        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center mb-4">
            <p class="mb-2" style="color: #4B5563;">Drag & drop files here or click to select</p>
            <input type="file" multiple class="hidden">
        </div>

        <button id="documents-bypass"
                class="w-full sm:w-auto px-6 py-2 rounded-md font-semibold border"
                style="border-color: #0747A6; color: #0747A6;">
            Skip (Auto-upload Mock Documents)
        </button>

        <p id="documents-result" class="mt-4 text-sm" style="color: #4B5563;"></p>
    </div>
</div>

<script>
    (function () {
        function saveDocuments() {
            var docs = [
                { type: "Identity Proof", name: "aadhaar_demo.pdf" },
                { type: "Income Proof", name: "income_demo.pdf" },
                { type: "Residence Proof", name: "residence_demo.pdf" }
            ];
            localStorage.setItem("user_documents", JSON.stringify(docs));
            return docs;
        }

        var bypass = document.getElementById("documents-bypass");
        var result = document.getElementById("documents-result");

        if (bypass) {
            bypass.addEventListener("click", function () {
                saveDocuments();
                if (result) {
                    result.textContent = "Mock documents uploaded successfully. Proceeding to payment...";
                    result.style.color = "#16A34A";
                }
                setTimeout(function () {
                    window.location.href = "/user/payment";
                }, 1000);
            });
        }
    })();
</script>


