<x-app-layout>
    <h1 class="h4 mb-3">Tambah Kostum</h1>

    <form action="{{ route('admin.kostum.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.kostum.form')
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</x-app-layout>
