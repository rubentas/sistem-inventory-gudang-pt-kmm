<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Inventory KMM</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>

<body class="min-h-screen bg-linear-to-br from-blue-700 to-blue-900 flex items-center justify-center p-4">
  {{ $slot }}
  @livewireScripts
</body>

</html>
