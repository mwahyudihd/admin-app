<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   Admin Login
  </title>
  <link rel="stylesheet" href="assets/css/style.css">
 </head>
 <body class="bg-blue-700 flex items-center justify-center min-h-screen">
  <div class="text-center">
   <img alt="ULBI APP logo" class="mx-auto mb-4" height="100" src="assets/images/ulbi-icons.png" width="100"/>
   <h2 class="text-white text-3xl font-bold mt-2">
    ADMIN
    <span class="font-normal">
     Login
    </span>
   </h2>
   <div class="bg-white p-8 mt-6 rounded shadow-md w-full max-w-sm mx-auto">
    <p class="mb-4">
     Silahkan Login Pada Form dibawah ini
    </p>
    <form method="post" action="controllers/c_login.php">
     <div class="mb-4">
      <input class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Email address" name="email" type="email" required />
      <i class="fas fa-user absolute right-3 top-3 text-gray-400">
      </i>
     </div>
     <div class="mb-4">
      <input class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Password" name="password" type="password" required />
      <i class="fas fa-lock absolute right-3 top-3 text-gray-400">
      </i>
     </div>
     <div class="flex items-center mb-4">
      <input class="mr-2" id="remember" type="checkbox"/>
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
 </body>
</html>
