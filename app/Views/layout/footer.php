    </main>

    <footer class="mt-12 border-t relative overflow-hidden">

<style>
    .debug-bar-dinlineBlock{
display: none !important;
    }
</style>

        <!-- Background image -->
        <div class="absolute inset-0 w-full h-full z-0">
            <img
                src="/assets/housing/footer.png"
                alt="Rajasthan Government Footer"
                class="w-full h-full object-cover object-center"
            >
            <div class="absolute inset-0" style="background-color:rgba(0,0,0,0.5);"></div>
        </div>

        <!-- Content -->
        <div class="container mx-auto px-4 py-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4" style="color:#FFFFFF;">
                        <?= esc(lang('App.footerQuickLinks')) ?>
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="/" class="hover:underline" style="color:#E5E7EB;">
                                <?= esc(lang('App.footerHome')) ?>
                            </a>
                        </li>
                        <li>
                            <a href="/user/eligibility" class="hover:underline" style="color:#E5E7EB;">
                                <?= esc(lang('App.footerCheckEligibility')) ?>
                            </a>
                        </li>
                        <li>
                            <a href="/user/application" class="hover:underline" style="color:#E5E7EB;">
                                <?= esc(lang('App.footerApplyOnline')) ?>
                            </a>
                        </li>
                        <li>
                            <a href="/user/lottery-results" class="hover:underline" style="color:#E5E7EB;">
                                <?= esc(lang('App.footerLotteryResults')) ?>
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4" style="color:#FFFFFF;">
                        <?= esc(lang('App.footerContact')) ?>
                    </h3>
                    <ul class="space-y-2 text-sm" style="color:#E5E7EB;">
                        <li><?= esc(lang('App.footerHelpline')) ?></li>
                        <li><?= esc(lang('App.footerEmail')) ?></li>
                        <li><?= esc(lang('App.footerWorkingHours')) ?></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4" style="color:#FFFFFF;">
                        <?= esc(lang('App.footerGovernmentPortal')) ?>
                    </h3>
                    <p class="text-sm" style="color:#E5E7EB;">
                        <?= esc(lang('App.footerGovernmentPortalText')) ?>
                    </p>
                </div>
            </div>
            <div class="border-t pt-6 text-center" style="border-top-color:rgba(255,255,255,0.3);">
                <p class="text-sm" style="color:#E5E7EB;">
                    <?= str_replace('{year}', date('Y'), lang('App.footerCopyright')) ?>
                    <a href="#" class="hover:underline ml-2" style="color:#E5E7EB;"><?= esc(lang('App.footerPrivacyPolicy')) ?></a>
                    |
                    <a href="#" class="hover:underline ml-2" style="color:#E5E7EB;"><?= esc(lang('App.footerTermsOfUse')) ?></a>
                </p>
            </div>
        </div>
    </footer>
</body>
</html>


