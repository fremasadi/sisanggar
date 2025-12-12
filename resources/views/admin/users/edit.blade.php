<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Edit Pengguna</h1>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.users.form')
            </form>
        </div>
    </div>
</x-app-layout>
