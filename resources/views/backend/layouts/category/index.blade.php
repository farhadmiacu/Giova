@extends('backend.master')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Categories</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Categories list</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Categories list</h4>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-success">Add Category</a>
                </div>

                {{-- <div class="card-body">
                    <div class="row gy-4">
                        <div class="col-xxl-12 col-md-12">
                            <div>
                                <label for="placeholderInput" class="form-label">Input with Placeholder</label>
                                <input type="password" class="form-control" id="placeholderInput" placeholder="Placeholder">
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="card-body">

                    <div class="row gy-4">

                    </div>
                </div> --}}

                {{-- <div class="card-body">
                    <div class="table-reponsive">
                        <table id="categoriesTable" class="table table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $key => $category)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            @if ($category->image)
                                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" style="width: 60px; height: auto; margin-top: 5px;">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check form-switch form-switch-right form-switch-md">
                                                <input class="form-check-input status-switch" type="checkbox" data-id="{{ $category->id }}" data-type="category" {{ $category->status ? 'checked' : '' }}
                                                    @cannot('category_edit') disabled @endcannot>
                                            </div>
                                        </td>
                                        <td>
                                            @can('category_edit')
                                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary"><i class="fa-regular fa-pen-to-square"></i></a>
                                            @endcan

                                            @can('category_delete')
                                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger delete-button"><i class="fa-regular fa-trash-can"></i></button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> --}}
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Category List</h4>
                    </div> --}}
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap" id="categoryTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    {{-- <th>Created At</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection

{{-- Push the script --}}
@push('scripts')
    <script>
        $(function() {
            $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                ajax: "{{ route('admin.categories.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'slug',
                        name: 'slug'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    // {
                    //     data: 'created_at',
                    //     name: 'created_at'
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endpush
