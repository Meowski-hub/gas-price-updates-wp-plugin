jQuery(document).ready(function($) {
    // File input validation
    $('input[name="csv_file"]').on('change', function() {
        var file = this.files[0];
        if (file) {
            if (file.type !== 'text/csv' && file.type !== 'application/csv') {
                alert('Please select a valid CSV file');
                this.value = '';
            }
        }
    });

    // Add loading state to form submission
    $('.gpu-upload-section form').on('submit', function() {
        var $submitButton = $(this).find('input[type="submit"]');
        $submitButton.prop('disabled', true);
        $submitButton.val('Uploading...');
    });
});