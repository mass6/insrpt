/**
 * Created by sam on 10/8/15.
 * Version 1.1.0
 */
$(document).ready(function(){
    var multi = $('#contracts_assigned_input').multiSelect({
        selectableHeader: "<div class='contracts-select-header'>Unassigned</div>",
        selectionHeader: "<div class='contracts-select-header'>Assigned</div>"
    });

    $('#select-all').click(function(e){
        e.preventDefault();
        multi.multiSelect('select_all');
    });
    $('#deselect-all').click(function(e){
        e.preventDefault();
        multi.multiSelect('deselect_all');
    });

    // Reason closed input
    var div = $("#reason_closed_div");
    var reason_closed = $("#reason_closed_input");

    if (hasErrors) {
        $('#reason_closed_input').attr('disabled', false);
        $('#reason_closed_submit').attr('name', 'transition[close]');
        $('#reason_closed_div').show();
        $('#close_submit').hide();
    }
    $('#close_submit').click(function(e) {
        e.preventDefault();
        $('#reason_closed_input').attr('disabled', false);
        $('#reason_closed_div').fadeIn();
        $(this).hide();
        $('#reason_closed_submit').attr('name', 'transition[close]');
    });
    $("#cancel_close").click(function(e) {
        e.preventDefault();
        $('#reason_closed_div').fadeOut();
        $('#reason_closed_input').attr('disabled', true);
        $('#close_submit').show();
        $('#reason_closed_submit').attr('name', 'reason_closed');
    });
    $( "#reason_closed_input" ).change(function() {
        if ($("#reason_closed_input option:selected").val() == 'AVL') {
            $('#cataloguing_item_code_input').attr('required', true);
            $(window).scrollTo('#cataloguing_item_code_input', 800, {offset:-150});
            $('#cataloguing_item_code_input').focus();
        }
    });

    var setPurchaseRecurrenceVisibility = function() {
        var volumeRequested = $('#volume_requested_div');
        if ( $('#purchase_recurrence_input').val() !== 'AHC') {
            volumeRequested.show();
        } else {
            volumeRequested.hide();
        }
    };
    var setInitialVisibility = function() {
        setPurchaseRecurrenceVisibility();
    }();
    $( "#purchase_recurrence_input" ).change(function() {
        setPurchaseRecurrenceVisibility();
    });
});


$(function() {
    $(".attachment-wrap button").click(function(e) {
        var attachmentDiv =  $(this).parent();
        var confirmDeleteImage = confirm("Permanently delete this " + type + "?");
        if (confirmDeleteImage) {
            var type = attachmentDiv.attr("data-type");
            var id = attachmentDiv.attr("data-id");
            $.ajax({
                url: "/product-requests/attachments/" + type + "/" + id,
                headers: {
                    'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                },
                type: "DELETE",
                success: function (response) {
                    attachmentDiv.slideUp();
                }
            });
        }


    });
});