/**
 * Created by sam on 10/14/15.
 */
$(document).ready(function()
{
    $('.stack-trace').hide();
    $('.toggle-stack').on('click', function(e)
    {
        var stack = $(this).siblings('.stack-trace');
        var icon = $(this).children('i');
        stack.slideToggle('fast', function()
        {
            icon.toggleClass('fa-plus-square-o fa-minus-square-o');
        });
    });
});
