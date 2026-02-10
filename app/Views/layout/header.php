<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sindur Vihar - Government Housing Lottery & Plot Allocation System</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="/assets/css/housing-portal.css">
</head>
<body class="min-h-screen flex flex-col" style="background-color:#FFFFFF; font-family:'Poppins', Arial, Helvetica, sans-serif;">

<header class="border-b relative rounded-b-lg shadow-sm" style="background-color:#FFFFFF; color:#1F2937; border-bottom-color:#E5E7EB;">
    <!-- Tricolor accent -->
    <div class="absolute top-0 left-0 right-0 h-0.5 flex rounded-t-lg">
        <div class="flex-1" style="background-color:#FF9933;"></div>
        <div class="flex-1" style="background-color:#FFFFFF;"></div>
        <div class="flex-1" style="background-color:#138808;"></div>
    </div>

    <div class="container mx-auto px-4 py-2">
        <nav class="flex items-center justify-between">
            <a href="/" class="flex items-center group">
                <img
                    src="/assets/housing/raj-logo.png"
                    alt="Rajasthan Government Logo"
                    class="object-contain h-12 md:h-14 lg:h-16 w-auto zoom-in-zoom-out"
                >
            </a>

            <!-- Desktop navigation -->
            <div class="hidden md:flex items-center space-x-4">
                <ul class="flex space-x-4">
                    <li>
                        <a href="/"
                           class="gov-hover font-medium text-xs md:text-sm px-3 py-1.5 rounded-lg"
                           style="color:#0747A6;"
                        >
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="/user/dashboard"
                           class="gov-hover font-medium text-xs md:text-sm px-3 py-1.5 rounded-lg hover:bg-gray-100"
                           style="color:#0747A6;"
                        >
                            User Portal
                        </a>
                    </li>
                    <li>
                        <a href="/admin/login"
                           class="gov-hover font-medium text-xs md:text-sm px-3 py-1.5 rounded-lg hover:bg-gray-100"
                           style="color:#0747A6;"
                        >
                            Admin Portal
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>

<!-- Banner Swiper (simple slider) -->
<section class="w-full relative mb-6 overflow-hidden">
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

<main class="flex-grow">

