
$(document).ready(function() {
    $('.email-auto').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "https://s3-4137.nuage-peda.fr/share/api/search-users",
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.nom + " " + item.prenom,
                            value: item.email,
                            id: item.id,
                        };
                    }));
                }
            });
        },
        select: function(event, ui) {
            $(".email-auto").val(ui.item.email);
        }
    });
});
