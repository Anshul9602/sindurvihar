

<!-- Banner Swiper (simple slider) -->
<section class="w-full relative mb-6 overflow-hidden" style="padding: 0;margin-top:-10px;">
    <div class="relative w-full" style="height:50vh;">
        <img id="banner-slide"
             src="/assets/housing/banner1.jpeg"
             alt="Rajasthan Government Banner"
             class="absolute inset-0 w-full h-full object-cover fade-in-up">
    </div>
    <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
        <button id="banner-dot-0" class="w-3 h-3 rounded-full" style="background-color:#FFFFFF;"></button>
        <button id="banner-dot-1" class="w-3 h-3 rounded-full" style="background-color:rgba(255,255,255,0.5);"></button>
    </div>
</section>

<script>
    (function () {
        var images = [
            "/assets/housing/banner1.jpeg",
            "/assets/housing/banner2.jpeg"
        ];
        var index = 0;
        var img = document.getElementById("banner-slide");
        var dot0 = document.getElementById("banner-dot-0");
        var dot1 = document.getElementById("banner-dot-1");

        function setActiveDot(i) {
            if (!dot0 || !dot1) return;
            dot0.style.backgroundColor = i === 0 ? "#FFFFFF" : "rgba(255,255,255,0.5)";
            dot1.style.backgroundColor = i === 1 ? "#FFFFFF" : "rgba(255,255,255,0.5)";
        }

        function showSlide(i) {
            index = i;
            if (img) {
                img.src = images[index];
            }
            setActiveDot(index);
        }

        if (dot0) {
            dot0.addEventListener("click", function () { showSlide(0); });
        }
        if (dot1) {
            dot1.addEventListener("click", function () { showSlide(1); });
        }

        setActiveDot(0);

        setInterval(function () {
            showSlide((index + 1) % images.length);
        }, 4000);
    })();
</script>

<div class="w-full">
    <div class="container mx-auto px-4 py-6 md:py-8">
        <section class="mb-6 text-center">
            <div class="rounded-xl p-8 md:p-12 mb-6 relative overflow-hidden"
                 style="background: linear-gradient(135deg, #0747A6 0%, #0F1F3F 100%); color: #FFFFFF;">
                <div class="absolute top-0 left-0 right-0 h-1 flex">
                    <div class="flex-1" style="background-color: #FF9933;"></div>
                    <div class="flex-1" style="background-color: #FFFFFF;"></div>
                    <div class="flex-1" style="background-color: #138808;"></div>
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">
                    <?= esc(lang('App.homeTitle')) ?>
                </h1>
                <p class="text-lg md:text-xl mb-6" style="color: #E5E7EB;">
                    <?= esc(lang('App.homeSubtitle')) ?>
                </p>
                <p class="text-base md:text-lg mb-8 max-w-2xl mx-auto" style="color: #D1D5DB;">
                    <?= esc(lang('App.homeDescription')) ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center flex-wrap">
                    <a href="/auth/register"
                       class="px-6 py-3 rounded-md font-semibold"
                       style="background-color: #FFFFFF; color: #1E3A5F;">
                        <?= esc(lang('App.homeApplyNow')) ?>
                    </a>
                    <a href="/auth/login"
                       class="px-6 py-3 rounded-md font-semibold border"
                       style="border-color: #FFFFFF; color: #FFFFFF;">
                        <?= esc(lang('App.homeLogin')) ?>
                    </a>
                    <a href="/user/eligibility"
                       class="px-6 py-3 rounded-md font-semibold border"
                       style="border-color: #FFFFFF; color: #FFFFFF;">
                        <?= esc(lang('App.homeCheckEligibility')) ?>
                    </a>
                </div>
            </div>
        </section>

        <section class="mb-6">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 md:mb-8 text-center" style="color: #0747A6;">
                <?= esc(lang('App.homeSchemeHighlights')) ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex flex-col md:flex-row">
                        <div class="w-full md:w-1/2 flex items-center justify-center bg-black">
                            <video
                                src="/assets/housing/B2ByPass.mp4"
                                class="w-full h-auto"
                                loop
                                muted
                                autoplay
                                playsinline
                            ></video>
                        </div>
                        <div class="w-full md:w-1/2 p-6 md:p-8 flex flex-col">
                            <div class="flex items-center gap-2 mb-4">
                                <img
                                    src="/assets/housing/NEW.gif"
                                    alt="New Scheme"
                                    style="width: 28px; height: auto;"
                                >
                                <h3 class="text-xl md:text-2xl font-semibold" style="color: #0747A6;">
                                    <?= esc(lang('App.homeAboutScheme')) ?>
                                </h3>
                            </div>
                            <p class="text-sm md:text-base leading-relaxed mb-2" style="color: #4B5563;">
                                <?= esc(lang('App.homeAboutSchemeText1')) ?>
                            </p>
                            <p class="text-sm md:text-base leading-relaxed mb-2" style="color: #4B5563;">
                                <?= esc(lang('App.homeAboutSchemeText2')) ?>
                            </p>
                            <p class="text-sm md:text-base leading-relaxed" style="color: #4B5563;">
                                <?= esc(lang('App.homeAboutSchemeText3')) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-xl p-6 md:p-8 border border-gray-200">
                    <h3 class="text-xl md:text-2xl font-semibold mb-4" style="color: #0747A6;">
                        <?= esc(lang('App.homeImportantDatesFees')) ?>
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span style="color: #4B5563;"><?= esc(lang('App.homeApplicationStartDate')) ?></span>
                            <strong style="color: #0F1F3F;">January 1, 2024</strong>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span style="color: #4B5563;"><?= esc(lang('App.homeApplicationEndDate')) ?></span>
                            <strong style="color: #0F1F3F;">March 31, 2024</strong>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span style="color: #4B5563;"><?= esc(lang('App.homeVerificationWindow')) ?></span>
                            <strong style="color: #0F1F3F;">April 1–30, 2024</strong>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span style="color: #4B5563;"><?= esc(lang('App.homeLotteryDate')) ?></span>
                            <strong style="color: #0F1F3F;">May 15, 2024</strong>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span style="color: #4B5563;"><?= esc(lang('App.homeApplicationFee')) ?></span>
                            <strong style="color: #0F1F3F;">₹1,000</strong>
                        </div>
                        <div class="mt-4 flex justify-center">
                            <img
                                src="/assets/housing/Rajasthan-Ki-Sarkari-Yojnaye.png"
                                alt="Rajasthan Ki Sarkari Yojnaye"
                                class="w-full h-auto max-w-md"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-6">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 md:mb-8 text-center" style="color: #0747A6;">
                <?= esc(lang('App.homeEligibilityCriteria')) ?>
            </h2>
            <div class="bg-white shadow-sm rounded-xl p-6 md:p-8 border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-lg md:text-xl font-semibold mb-3" style="color: #0747A6;"><?= esc(lang('App.homeAgeRequirement')) ?></h4>
                        <p class="text-sm md:text-base" style="color: #4B5563;">
                            <?= esc(lang('App.homeAgeRequirementText')) ?>
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg md:text-xl font-semibold mb-3" style="color: #0747A6;"><?= esc(lang('App.homeIncomeCategories')) ?></h4>
                        <ul class="text-sm md:text-base space-y-1" style="color: #4B5563;">
                            <li><?= esc(lang('App.homeIncomeEWS')) ?></li>
                            <li><?= esc(lang('App.homeIncomeLIG')) ?></li>
                            <li><?= esc(lang('App.homeIncomeMIG')) ?></li>
                            <li><?= esc(lang('App.homeIncomeHIG')) ?></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg md:text-xl font-semibold mb-3" style="color: #0747A6;"><?= esc(lang('App.homePropertyOwnership')) ?></h4>
                        <p class="text-sm md:text-base" style="color: #4B5563;">
                            <?= esc(lang('App.homePropertyOwnershipText')) ?>
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg md:text-xl font-semibold mb-3" style="color: #0747A6;"><?= esc(lang('App.homeResidency')) ?></h4>
                        <p class="text-sm md:text-base" style="color: #4B5563;">
                            <?= esc(lang('App.homeResidencyText')) ?>
                        </p>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <a href="/user/eligibility"
                       class="px-6 py-3 rounded-md font-semibold"
                       style="background-color: #0747A6; color: #FFFFFF;">
                        <?= esc(lang('App.homeCheckEligibilityOnline')) ?>
                    </a>
                </div>
            </div>
        </section>

        <section class="mb-6">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 md:mb-8 text-center" style="color: #0747A6;">
                <?= esc(lang('App.homeApplicationProcess')) ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200">
                    <div class="w-16 h-16 rounded-full text-white flex items-center justify-center text-2xl font-bold mx-auto mb-4"
                         style="background-color: #0747A6;">
                        1
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;"><?= esc(lang('App.homeStep1Register')) ?></h3>
                    <p class="text-sm md:text-base" style="color: #4B5563;">
                        <?= esc(lang('App.homeStep1RegisterText')) ?>
                    </p>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200">
                    <div class="w-16 h-16 rounded-full text-white flex items-center justify-center text-2xl font-bold mx-auto mb-4"
                         style="background-color: #0747A6;">
                        2
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;"><?= esc(lang('App.homeStep2FillApplication')) ?></h3>
                    <p class="text-sm md:text-base" style="color: #4B5563;">
                        <?= esc(lang('App.homeStep2FillApplicationText')) ?>
                    </p>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200">
                    <div class="w-16 h-16 rounded-full text-white flex items-center justify-center text-2xl font-bold mx-auto mb-4"
                         style="background-color: #0747A6;">
                        3
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;"><?= esc(lang('App.homeStep3UploadDocuments')) ?></h3>
                    <p class="text-sm md:text-base" style="color: #4B5563;">
                        <?= esc(lang('App.homeStep3UploadDocumentsText')) ?>
                    </p>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200">
                    <div class="w-16 h-16 rounded-full text-white flex items-center justify-center text-2xl font-bold mx-auto mb-4"
                         style="background-color: #0747A6;">
                        4
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;"><?= esc(lang('App.homeStep4PayFee')) ?></h3>
                    <p class="text-sm md:text-base" style="color: #4B5563;">
                        <?= esc(lang('App.homeStep4PayFeeText')) ?>
                    </p>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200">
                    <div class="w-16 h-16 rounded-full text-white flex items-center justify-center text-2xl font-bold mx-auto mb-4"
                         style="background-color: #10B981;">
                        5
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;"><?= esc(lang('App.homeStep5Verification')) ?></h3>
                    <p class="text-sm md:text-base" style="color: #4B5563;">
                        <?= esc(lang('App.homeStep5VerificationText')) ?>
                    </p>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200">
                    <div class="w-16 h-16 rounded-full text-white flex items-center justify-center text-2xl font-bold mx-auto mb-4"
                         style="background-color: #10B981;">
                        6
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;"><?= esc(lang('App.homeStep6Lottery')) ?></h3>
                    <p class="text-sm md:text-base" style="color: #4B5563;">
                        <?= esc(lang('App.homeStep6LotteryText')) ?>
                    </p>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200">
                    <div class="w-16 h-16 rounded-full text-white flex items-center justify-center text-2xl font-bold mx-auto mb-4"
                         style="background-color: #10B981;">
                        7
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;"><?= esc(lang('App.homeStep7Allotment')) ?></h3>
                    <p class="text-sm md:text-base" style="color: #4B5563;">
                        <?= esc(lang('App.homeStep7AllotmentText')) ?>
                    </p>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200">
                    <div class="w-16 h-16 rounded-full text-white flex items-center justify-center text-2xl font-bold mx-auto mb-4"
                         style="background-color: #10B981;">
                        8
                    </div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;"><?= esc(lang('App.homeStep8Possession')) ?></h3>
                    <p class="text-sm md:text-base" style="color: #4B5563;">
                        <?= esc(lang('App.homeStep8PossessionText')) ?>
                    </p>
                </div>
            </div>
        </section>

        <section class="mb-6">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 md:mb-8 text-center" style="color: #0747A6;">
                <?= esc(lang('App.homeImportantInformation')) ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                <div class="bg-white shadow-sm rounded-xl p-6 md:p-8 border border-gray-200">
                    <h3 class="text-xl md:text-2xl font-semibold mb-4" style="color: #0747A6;">
                        <?= esc(lang('App.homeImportantDates')) ?>
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span style="color: #4B5563;"><?= esc(lang('App.homeApplicationStartDate')) ?></span>
                            <strong style="color: #0F1F3F;">January 1, 2024</strong>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span style="color: #4B5563;"><?= esc(lang('App.homeApplicationEndDate')) ?></span>
                            <strong style="color: #0F1F3F;">March 31, 2024</strong>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span style="color: #4B5563;"><?= esc(lang('App.homeVerificationPeriod')) ?></span>
                            <strong style="color: #0F1F3F;">April 1–30, 2024</strong>
                        </div>
                        <div class="flex justify-between items-center">
                            <span style="color: #4B5563;"><?= esc(lang('App.homeLotteryDate')) ?></span>
                            <strong style="color: #0F1F3F;">May 15, 2024</strong>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-xl p-6 md:p-8 border border-gray-200">
                    <h3 class="text-xl md:text-2xl font-semibold mb-4" style="color: #0747A6;">
                        <?= esc(lang('App.homeApplicationFees')) ?>
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span style="color: #4B5563;"><?= esc(lang('App.homeApplicationFee')) ?></span>
                            <strong style="color: #0F1F3F;">₹1,000</strong>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span style="color: #4B5563;"><?= esc(lang('App.homeProcessingFee')) ?></span>
                            <strong style="color: #0F1F3F;"><?= esc(lang('App.homeProcessingFeeIncluded')) ?></strong>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span style="color: #4B5563;"><?= esc(lang('App.homeRefundPolicy')) ?></span>
                            <strong style="color: #0F1F3F;"><?= esc(lang('App.homeRefundPolicyText')) ?></strong>
                        </div>
                        <div class="flex justify-between items-center">
                            <span style="color: #4B5563;"><?= esc(lang('App.homePaymentMethods')) ?></span>
                            <strong style="color: #0F1F3F;"><?= esc(lang('App.homePaymentMethodsText')) ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-6">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 md:mb-8 text-center" style="color: #0747A6;">
                <?= esc(lang('App.homeDownloadsResources')) ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="text-4xl mb-4">📄</div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;">
                        <?= esc(lang('App.homeSchemeBrochure')) ?>
                    </h3>
                    <p class="mb-4 text-sm md:text-base" style="color: #4B5563;">
                        <?= esc(lang('App.homeSchemeBrochureText')) ?>
                    </p>
                    <button class="px-4 py-2 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        <?= esc(lang('App.homeDownloadPDF')) ?>
                    </button>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="text-4xl mb-4">📋</div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;">
                        <?= esc(lang('App.homeApplicationGuidelines')) ?>
                    </h3>
                    <p class="mb-4 text-sm md:text-base" style="color: #4B5563;">
                        <?= esc(lang('App.homeApplicationGuidelinesText')) ?>
                    </p>
                    <button class="px-4 py-2 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        <?= esc(lang('App.homeDownloadPDF')) ?>
                    </button>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="text-4xl mb-4">📑</div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;">
                        <?= esc(lang('App.homeDocumentChecklist')) ?>
                    </h3>
                    <p class="mb-4 text-sm md:text-base" style="color: #4B5563;">
                        <?= esc(lang('App.homeDocumentChecklistText')) ?>
                    </p>
                    <button class="px-4 py-2 border rounded-md text-sm"
                            style="border-color: #0747A6; color: #0747A6;">
                        <?= esc(lang('App.homeDownloadPDF')) ?>
                    </button>
                </div>
            </div>
        </section>

        <section class="mb-6">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 md:mb-8 text-center" style="color: #0747A6;">
                <?= esc(lang('App.homeFAQ')) ?>
            </h2>
            <div class="bg-white shadow-sm rounded-xl p-6 md:p-8 border border-gray-200">
                <div class="space-y-4">
                    <div>
                        <h4 class="text-base md:text-lg font-semibold mb-1" style="color: #0747A6;">
                            <?= esc(lang('App.homeFAQ1Question')) ?>
                        </h4>
                        <p class="text-sm md:text-base" style="color: #4B5563;">
                            <?= esc(lang('App.homeFAQ1Answer')) ?>
                        </p>
                    </div>
                    <div>
                        <h4 class="text-base md:text-lg font-semibold mb-1" style="color: #0747A6;">
                            <?= esc(lang('App.homeFAQ2Question')) ?>
                        </h4>
                        <p class="text-sm md:text-base" style="color: #4B5563;">
                            <?= esc(lang('App.homeFAQ2Answer')) ?>
                        </p>
                    </div>
                    <div>
                        <h4 class="text-base md:text-lg font-semibold mb-1" style="color: #0747A6;">
                            <?= esc(lang('App.homeFAQ3Question')) ?>
                        </h4>
                        <p class="text-sm md:text-base" style="color: #4B5563;">
                            <?= esc(lang('App.homeFAQ3Answer')) ?>
                        </p>
                    </div>
                    <div>
                        <h4 class="text-base md:text-lg font-semibold mb-1" style="color: #0747A6;">
                            <?= esc(lang('App.homeFAQ4Question')) ?>
                        </h4>
                        <p class="text-sm md:text-base" style="color: #4B5563;">
                            <?= esc(lang('App.homeFAQ4Answer')) ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-6">
            <h2 class="text-2xl md:text-3xl font-bold mb-6 md:mb-8 text-center" style="color: #0747A6;">
                <?= esc(lang('App.homeContactSupport')) ?>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200">
                    <div class="text-4xl mb-4">📞</div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;"><?= esc(lang('App.homeHelpline')) ?></h3>
                    <p class="text-sm md:text-base" style="color: #4B5563;">01429-243637</p>
                    <p class="text-xs md:text-sm" style="color: #4B5563;"><?= esc(lang('App.homeHelplineHours')) ?></p>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200">
                    <div class="text-4xl mb-4">✉️</div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;"><?= esc(lang('App.homeEmail')) ?></h3>
                    <p class="text-sm md:text-base" style="color: #4B5563;">chaksuulb.jaipur@gmail.com</p>
                    <p class="text-xs md:text-sm" style="color: #4B5563;"><?= esc(lang('App.homeEmailResponse')) ?></p>
                </div>
                <div class="bg-white shadow-sm rounded-xl p-6 text-center border border-gray-200">
                    <div class="text-4xl mb-4">📍</div>
                    <h3 class="text-base md:text-lg font-semibold mb-2" style="color: #0747A6;"><?= esc(lang('App.homeOffice')) ?></h3>
                    <p class="text-sm md:text-base" style="color: #4B5563;">
                        <?= esc(lang('App.homeOfficeAddress')) ?>
                    </p>
                    <p class="text-xs md:text-sm" style="color: #4B5563;"><?= esc(lang('App.homeOfficeHours')) ?></p>
                </div>
            </div>
        </section>

        <section class="mb-6">
            <div class="rounded-xl p-8 md:p-12 text-center"
                 style="background: linear-gradient(135deg, #0747A6 0%, #0F1F3F 100%); color: #FFFFFF;">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">
                    <?= esc(lang('App.homeReadyToBegin')) ?>
                </h2>
                <p class="text-base md:text-lg mb-8" style="color: #E5E7EB;">
                    <?= esc(lang('App.homeReadyToBeginText')) ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/auth/register"
                       class="px-6 py-3 rounded-md font-semibold"
                       style="background-color: #FFFFFF; color: #0747A6;">
                        <?= esc(lang('App.homeApplyNow')) ?>
                    </a>
                    <a href="/user/eligibility"
                       class="px-6 py-3 rounded-md font-semibold border"
                       style="border-color: #FFFFFF; color: #FFFFFF;">
                        <?= esc(lang('App.homeCheckEligibility')) ?>
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>


