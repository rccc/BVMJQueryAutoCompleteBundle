jQuery(document).ready(function() {
    jQuery('body').on('rccc_jqueryautocomplete_init', function(){
        jQuery('.rccc_jqueryautocomplete').autocomplete({
            source: function(request, response) {
                jQuery.ajax({
                    url: Routing.generate(jQuery(this.element).data('route')),
                    dataType: "json",
                    data: {
                        limit: 10,
                        query: request.term
                    },
                    success: function( data ) {
                        response(jQuery.map(data, function(label, id) {
                            return {
                                label: label,
                                id: id
                            }
                        }));
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                jQuery(this).prev().val(ui.item.id); 
            }
        });
    });
    jQuery('body').trigger('rccc_jqueryautocomplete_init');
});