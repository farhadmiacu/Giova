{{-- SweetAlert2 Notifications --}}
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            // title: 'Success',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false,
            position: 'top-end',
            toast: true
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            // title: 'Error',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false,
            position: 'top-end',
            toast: true
        });
    @endif

    @if (session('warning'))
        Swal.fire({
            icon: 'warning',
            // title: 'Warning',
            text: '{{ session('warning') }}',
            timer: 3000,
            showConfirmButton: false,
            position: 'top-end',
            toast: true
        });
    @endif

    @if (session('info'))
        Swal.fire({
            icon: 'info',
            // title: 'Info',
            text: '{{ session('info') }}',
            timer: 3000,
            showConfirmButton: false,
            position: 'top-end',
            toast: true
        });
    @endif
</script>
{{-- SweetAlert2 Notifications --}}

{{-- SweetAlert2 Delete Confirmation (normal data table) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Listen to all delete buttons
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // prevent default form submit
                const form = this.closest('form'); // get the parent form

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // submit the form if confirmed
                    }
                });
            });
        });
    });
</script>
{{-- SweetAlert2 Delete Confirmation (normal data table) --}}

{{-- SweetAlert2 Delete Confirmation (Yajra data table) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delegate the event so it works with dynamically added buttons
        $(document).on('click', '.delete-button', function(e) {
            e.preventDefault(); // stop normal form submit
            const form = $(this).closest('form'); // grab parent form

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit form if confirmed
                }
            });
        });
    });
</script>
{{-- SweetAlert2 Delete Confirmation (Yajra data table) --}}
<!-- Toastr Notification -->
<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if (session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if (session('info'))
        toastr.info("{{ session('info') }}");
    @endif

    @if (session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif
</script>
<!-- Toastr Notification -->

<!-- IziToast Notification -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            iziToast.success({
                title: 'Success',
                message: '{{ session('success') }}',
                position: 'topRight',
                timeout: 5000,
                backgroundColor: '#405189',
                color: '#ffffff',
                iconColor: '#ffffff',
                theme: 'dark'
            });
        @endif

        @if (session('error'))
            iziToast.error({
                title: 'Error',
                message: '{{ session('error') }}',
                position: 'topRight',
                timeout: 5000
            });
        @endif

        @if (session('warning'))
            iziToast.warning({
                title: 'Warning',
                message: '{{ session('warning') }}',
                position: 'topRight',
                timeout: 5000
            });
        @endif

        @if (session('info'))
            iziToast.info({
                title: 'Info',
                message: '{{ session('info') }}',
                position: 'topRight',
                timeout: 5000
            });
        @endif
    });
</script>
<!-- IziToast Notification -->

{{-- Dropify Initialization --}}
<script>
    $(document).ready(function() {
        $('.dropify').dropify({
            messages: {
                'default': 'Drag and drop here or click',
                'replace': 'Drag and drop or click to replace',
                'remove': 'Remove file',
                'error': 'Ooops! something went wrong.'
            }
        });

    });
</script>
{{-- Dropify Initialization --}}
