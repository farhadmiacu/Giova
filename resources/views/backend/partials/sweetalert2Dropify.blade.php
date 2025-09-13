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

{{-- SweetAlert2 Delete Confirmation --}}
{{-- <script>
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
</script> --}}
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

{{-- SweetAlert2 Delete Confirmation --}}

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
