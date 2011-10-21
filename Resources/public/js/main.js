jQuery(document).ready(function() {
    jQuery(".bsky_jqueryautocomplete").autocomplete({
        source: function(request, response){
            jQuery.ajax({
                url: Routing.generate(jQuery(".bsky_jqueryautocomplete").data("route")),
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
        minLength: 2,
        select: function(event, ui) {
            jQuery(this).prev().val(ui.item.id); 
        }
    });
});