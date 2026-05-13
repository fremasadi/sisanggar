<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Tambah Kelompok</h1>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.kelompok.store') }}" method="POST">
                @csrf
                @include('admin.kelompok.form', ['kelompok' => new \App\Models\Kelompok])
            </form>
        </div>
    </div>
</x-app-layout>
