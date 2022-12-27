$(function () {
    $('#xml-validation').on('click', function () {
        $(this).addClass('disabled');
        $(this).closest('.card-body').addClass('placeholder');
        $(this).closest('form').submit();
    });
});
