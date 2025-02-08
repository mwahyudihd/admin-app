<?php
require 'controllers/session_verify.php';
?>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>
        Admin Login
    </title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Memuat Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="assets/images/ulbi-icons.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/login.controller.js"></script>
    <script src="assets/js/alert.js"></script>
    <script src="assets/js/route.js"></script>
</head>
<body class="bg-blue-700 flex items-center justify-center min-h-screen" x-data="redirect()">
    <div class="text-center">
        <img alt="ULBI APP logo" class="mx-auto mb-4" height="100" src="assets/images/ulbi-icons.png" width="100"/>
        <h2 class="text-white text-3xl font-bold mt-2">
            ADMIN
            <span class="font-normal">
                Login
            </span>
        </h2>
        <div x-data="loginForm(base)" class="bg-white p-8 mt-6 rounded shadow-md w-full max-w-sm mx-auto">
            <p class="mb-4">
                Silahkan Login Pada Form dibawah ini
            </p>
            <form method="post" @submit.prevent="submitLogin">
                <div class="mb-4">
                    <input class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Email address" name="email" x-model="email" type="email" required />
                    <i class="fas fa-user absolute right-3 top-3 text-gray-400">
                    </i>
                </div>
                <div x-data="{ showPass: false }" class="mb-4 relative">
                    <input class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Password" name="password" x-model="password" :type="showPass ? 'text' : 'password'" required />
                    <a class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer" @click="showPass = !showPass">
                        <i class="fas" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </a>
                </div>
                <div class="flex items-center mb-4">
                    <input class="mr-2" id="remember" type="checkbox" x-model="rememberMe" />
                    <label class="text-sm" for="remember">
                        Remember Me
                    </label>
                </div>
                <button class="w-full bg-orange-500 text-white p-3 rounded hover:bg-orange-600" type="submit">
                    Sign In
                </button>
            </form>
            <a class="text-red-500 text-sm block mt-4" href="#">
                Anda Lupa Password?
            </a>
        </div>
    </div>
    <script>
        let sessionFlash = sessionStorage.getItem("status");
        let sessionFlashMsg = sessionStorage.getItem("msg");

        if (sessionFlash || sessionFlash == "error") {
            Swal.fire({
                title: "Ops!",
                text: `${sessionFlashMsg}`,
                icon: "error",
                showConfirmButton: true
            });
            setTimeout(() => {
                sessionStorage.removeItem("status");
                sessionStorage.removeItem("msg");
            }, 1000);
        }
    </script>
</body>
</html>
