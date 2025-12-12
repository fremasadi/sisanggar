<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Tambah Pengguna Baru</h1>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                @include('admin.users.form', ['user' => new \App\Models\User])
            </form>
        </div>
    </div>
</x-app-layout>
