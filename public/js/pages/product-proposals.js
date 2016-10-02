/**
 * Created by sam on 05-JAN-2016.
 * Version 1.1.0
 */
$(document).ready(function() {

    // Confirm Reject Dialog
    $('#transition_reject').click(function(e){
        e.preventDefault();
        bootbox.prompt("Please state the reason you are rejecting the proposal.", function(reason) {
            if (reason === null) {
                return;
            } else {
                if (!reason) {
                    alert('You must provide a reason for rejecting the proposal.');
                } else {
                    var form = $('#transition_reject').closest("form");
                    form.append('<input type="hidden" name="transition[reject]" value="Reject" />');
                    form.append('<input type="hidden" name="reject_reason" value="' + reason +'" />');
                    form.submit();
                }
            }
        });
    });
});