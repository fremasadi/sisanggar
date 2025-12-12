<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h1>
    <p class="mb-4">Anda login sebagai <strong>{{ ucfirst(Auth::user()->role) }}</strong>.</p>
</x-app-layout>
