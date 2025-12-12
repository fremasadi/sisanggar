<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sanggar Tari Kembang Sore - Lestarikan Budaya Indonesia')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23dc2626' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    @include('front.partials.topbar')

    @yield('content')

   @include('front.partials.footer')

@stack('scripts')

<script>
// Mencegah scroll ke atas saat reload
if (performance.navigation.type === 1) {
    const savedScroll = sessionStorage.getItem("savedScroll");
    if (savedScroll) {
        window.scrollTo(0, parseInt(savedScroll));
    }
}

// Simpan posisi scroll sebelum pindah halaman
window.addEventListener("beforeunload", function () {
    sessionStorage.setItem("savedScroll", window.scrollY);
});
</script>

<!-- Toast Notification -->
@if (session('success') || session('error'))
<div id="toast"
     class="fixed top-6 right-6 px-6 py-4 rounded-lg shadow-lg text-white 
            {{ session('success') ? 'bg-green-600' : 'bg-red-600' }}
            opacity-0 transition-opacity duration-500 z-50">
    {{ session('success') ?? session('error') }}
</div>
@endif

<script>
document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".add-to-cart-form").forEach(form => {

        form.querySelector(".addToCartBtn").addEventListener("click", async function () {

            let kostumId = form.getAttribute("data-id");

            let response = await fetch("{{ route('cart.add') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    kostum_id: kostumId,
                    quantity: 1
                })
            });

            let data = await response.json();

            showToast(data.message, data.status);
        });

    });

});

// Fungsi Menampilkan Toast
function showToast(message, status = "success") {
    let toast = document.createElement("div");
    toast.className = `fixed top-6 right-6 px-6 py-4 rounded-lg shadow-lg text-white z-50 
        ${status === "success" ? "bg-green-600" : "bg-red-600"}`;
    toast.innerText = message;
    toast.style.opacity = "0";
    toast.style.transition = "opacity 0.4s";

    document.body.appendChild(toast);

    setTimeout(() => toast.style.opacity = "1", 50);
    setTimeout(() => toast.style.opacity = "0", 3500);
    setTimeout(() => toast.remove(), 4000);
}
</script>




</body>
</html>