@extends('backend.master')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Brand</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Brand list</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">Brand list</h4>
                    <a href="{{ route('admin.brands.create') }}" class="btn btn-sm btn-success">Add Brand</a>
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

                <div class="card-body">
                    <!-- Wrap table in a form to disable autofill -->
                    <form autocomplete="off">
                        <!-- Hidden input to trick Chrome autofill -->
                        <input type="text" style="display:none">
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
                                    @foreach ($brands as $key => $brand)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $brand->name }}</td>
                                            <td>{{ $brand->slug }}</td>
                                            <td>
                                                @if ($brand->image)
                                                    <img src="{{ asset($brand->image) }}" alt="{{ $brand->name }}" style="width: 60px; height: auto; margin-top: 5px;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            {{-- <td>{{ $brand->status ? 'Active' : 'Inactive' }}</td> --}}
                                            <td>
                                                <div class="form-check form-switch form-switch-right form-switch-md">
                                                    <input class="form-check-input status-switch" type="checkbox" data-id="{{ $brand->id }}" data-type="brand" {{ $brand->status ? 'checked' : '' }}
                                                        @cannot('brand_edit') disabled @endcannot>
                                                </div>
                                            </td>

                                            <td>
                                                @can('brand_edit')
                                                    <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-sm btn-primary"><i class="fa-regular fa-pen-to-square"></i></a>
                                                @endcan

                                                @can('brand_delete')
                                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger delete-button secure-delete-button"><i class="fa-regular fa-trash-can"></i></button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
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
        $(document).ready(function() {
            $('#categoriesTable').DataTable({
                responsive: true,
            });

        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#categoriesTable').DataTable({
                responsive: true,
            });

            // Prevent Chrome autofill on search input
            setTimeout(function() {
                var searchInput = $('#categoriesTable_filter input[type="search"]');
                searchInput.attr({
                    autocomplete: 'off', // standard
                    name: 'dt_search_' + Date.now(), // unique name
                    id: 'dt_search_' + Date.now() // unique id
                });
            }, 100);
        });
    </script> --}}

    {{-- password delete  --}}
    <script>
        $(document).on('click', '.secure-delete-button', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            let row = $(this).closest('tr');

            Swal.fire({
                title: 'Confirm Deletion',
                html: `<div style="text-align: center;">
                        <p>Please enter your password to delete this record:</p>
                         <input type="password" id="swal-password" class="swal2-input" placeholder="Password" style="margin: 0 auto;">
                      </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Verify & Delete',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    let password = $('#swal-password').val();
                    if (!password) Swal.showValidationMessage('Password is required');
                    return password;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('admin.verify-password') }}',
                        type: 'POST',
                        data: {
                            password: result.value,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            if (res.success) {
                                // password is correct then submit the form
                                $.ajax({
                                    url: form.attr('action'),
                                    type: 'POST',
                                    data: form.serialize(),
                                    success: function(data) {
                                        if (data.success) {
                                            let table = $('#categoriesTable').DataTable();
                                            table.row(row).remove().draw();

                                            Swal.fire({
                                                icon: 'success',
                                                text: data.message,
                                                toast: true,
                                                position: 'top-end',
                                                timer: 3000,
                                                showConfirmButton: false
                                            });
                                        } else {
                                            Swal.fire('Error', data.message, 'error');
                                        }
                                    },
                                    error: function() {
                                        Swal.fire('Error', 'Something went wrong!', 'error');
                                    }
                                });
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Verification failed!', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endpush
