const SUCCESS_CODE = 2000;
const GENERIC_ERROR_CODE = 4000;
const NO_RECORDS_FOUND = 3000;
$(document).ready(function () {
    $(".sidenav-link").on('click',function (e){
        e.preventDefault();
        $(".sidenav-link").removeClass('active');
        $(this).addClass('active');
    });
    $('.show-categories').click(function (e) {
        e.preventDefault();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/admin/showCategories',

            data: {},

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $('body').on('click','#addCategory',function (e) {
        e.preventDefault();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/admin/showaddCategoryForm',

            data: {},

            success: function (data) {
                $("#main").html(data);
            }

        });
    });
    $('body').on('click','#submitAddCategory',function (e) {
        e.preventDefault();
        var categoryName = $("#categoryName").val();
        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });
        $.ajax({

            type: 'POST',

            url: '/admin/addCategory',

            data: {
                categoryName : categoryName
            },

            success: function (data) {
                var result = $.parseJSON(data);
                if (result.STATUS_CODE == SUCCESS_CODE) {
                    Swal.fire({
                        icon: 'success',
                        title: result.STATUS_MESSAGE,
                        showConfirmButton: false,
                        timer: 3000
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: result.STATUS_MESSAGE,
                        showConfirmButton: false,
                        timer: 3000
                    })
                }
            }

        });
    });
});
