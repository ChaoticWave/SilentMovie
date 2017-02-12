/**
 * Search/ingest functionality
 */

jQuery(function($) {
        $('i.ingest').on('click', function(e) {
                e.preventDefault();

                if (confirm('Really ingest this item?')) {
                    $.post('/people/ingest');
                }
            }
        );
    }
);