    </main>

    <footer class="mt-12 border-t relative overflow-hidden">
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
                        Quick Links
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="/" class="hover:underline" style="color:#E5E7EB;">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="/user/eligibility" class="hover:underline" style="color:#E5E7EB;">
                                Check Eligibility
                            </a>
                        </li>
                        <li>
                            <a href="/user/application" class="hover:underline" style="color:#E5E7EB;">
                                Apply Online
                            </a>
                        </li>
                        <li>
                            <a href="/user/lottery-results" class="hover:underline" style="color:#E5E7EB;">
                                Lottery Results
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4" style="color:#FFFFFF;">
                        Contact
                    </h3>
                    <ul class="space-y-2 text-sm" style="color:#E5E7EB;">
                        <li>Helpline: 1800-XXX-XXXX</li>
                        <li>Email: support@housingportal.gov.in</li>
                        <li>Mon–Sat, 8 AM – 6 PM</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4" style="color:#FFFFFF;">
                        Government Portal
                    </h3>
                    <p class="text-sm" style="color:#E5E7EB;">
                        Official website of the Housing Development Authority. Transparent, fair,
                        and secure housing allocation system.
                    </p>
                </div>
            </div>
            <div class="border-t pt-6 text-center" style="border-top-color:rgba(255,255,255,0.3);">
                <p class="text-sm" style="color:#E5E7EB;">
                    © <?= date('Y') ?> Housing Development Authority. All rights reserved.
                    <a href="#" class="hover:underline ml-2" style="color:#E5E7EB;">Privacy Policy</a>
                    |
                    <a href="#" class="hover:underline ml-2" style="color:#E5E7EB;">Terms of Use</a>
                </p>
            </div>
        </div>
    </footer>
</body>
</html>


