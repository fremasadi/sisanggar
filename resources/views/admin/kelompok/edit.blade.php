<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Edit Kelompok</h1>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.kelompok.update', $kelompok) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.kelompok.form', ['kelompok' => $kelompok])
            </form>
        </div>
    </div>
</x-app-layout>
