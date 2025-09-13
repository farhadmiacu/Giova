<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<!-- JAVASCRIPT -->
<script src="{{ asset('/') }}backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('/') }}backend/assets/libs/simplebar/simplebar.min.js"></script>
<script src="{{ asset('/') }}backend/assets/libs/node-waves/waves.min.js"></script>
<script src="{{ asset('/') }}backend/assets/libs/feather-icons/feather.min.js"></script>
<script src="{{ asset('/') }}backend/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script src="{{ asset('/') }}backend/assets/js/plugins.js"></script>

<!-- apexcharts -->
<script src="{{ asset('/') }}backend/assets/libs/apexcharts/apexcharts.min.js"></script>
<!-- Vector map-->
<script src="{{ asset('/') }}backend/assets/libs/jsvectormap/jsvectormap.min.js"></script>
<script src="{{ asset('/') }}backend/assets/libs/jsvectormap/maps/world-merc.js"></script>
<!--Swiper slider js-->
<script src="{{ asset('/') }}backend/assets/libs/swiper/swiper-bundle.min.js"></script>

<!-- Data Table (normal)-->
{{-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script> --}}
<!-- Yajra DataTable -->
<script src="https://cdn.datatables.net/v/bs5/dt-2.3.4/r-3.0.3/datatables.min.js"></script>
<!-- Dropify -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
<!-- ckeditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<!-- Toastr JS -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> --}}
<!-- sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- IziToast JS -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script> --}}
<!-- Dropzone JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<!-- FilePond JS -->
<script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
<!-- FilePond Plugins (optional, for image preview and validation) -->
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<!-- Dashboard init -->
<script src="{{ asset('/') }}backend/assets/js/pages/dashboard-ecommerce.init.js"></script>
<!-- App js -->
<script src="{{ asset('/') }}backend/assets/js/app.js"></script>

<!-- Set CSRF token for all AJAX requests -->
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
{{-- status update --}}
<script>
    $(document).on('change', '.status-switch', function() {
        let checkbox = $(this);
        let status = checkbox.is(':checked') ? 1 : 0;
        let id = checkbox.data('id');
        let type = checkbox.data('type');

        // Add a small spinner next to the switch
        let spinner = $('<div class="spinner-border spinner-border-sm text-primary ms-2" role="status"></div>');
        checkbox.closest('div').append(spinner);

        $.ajax({
            url: "{{ route('admin.status.update') }}",
            type: "POST",
            data: {
                id: id,
                type: type,
                status: status
            },
            success: function(response) {
                spinner.remove();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function() {
                spinner.remove();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    text: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });
</script>
{{-- status update end  --}}
