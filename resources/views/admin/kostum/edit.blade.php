<x-app-layout>
    <h1 class="h3 mb-4 text-gray-800">Edit Kostum</h1>

    <div class="card shadow">
        <div class="card-body">

           <form action="{{ route('admin.kostum.update', $kostum->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.kostum.form')
                <button type="submit" class="btn btn-warning">Update</button>
            </form>


        </div>
    </div>
</x-app-layout>
