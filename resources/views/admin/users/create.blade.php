<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Tambah Pengguna Baru</h1>

    <div class="card shadow">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Data belum bisa disimpan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                @include('admin.users.form', ['user' => new \App\Models\User])
            </form>
        </div>
    </div>
</x-app-layout>
