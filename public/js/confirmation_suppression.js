$(function(){
    $('#confirm-delete').on('show.bs.modal',
        function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        $(this).find('.test_titre').append($(e.relatedTarget).data('contenu'));
    });
});