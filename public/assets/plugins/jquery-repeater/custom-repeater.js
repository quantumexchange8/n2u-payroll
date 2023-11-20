/*---------------------------------------------
Template name :  Dashmin
Version       :  1.0
Author        :  ThemeLooks
Author url    :  http://themelooks.com


** Custom Repetar JS

----------------------------------------------*/

$(function () {
    'use strict';

    $(document).ready(function () {
        console.log('test');

        $(".remove-btn").on("click", function (e) {
            e.preventDefault(); // Prevent the default behavior (page refresh)

            // Reference to the current remove button
            var $removeBtn = $(this);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Continue with the removal logic
                    $removeBtn.closest("[data-repeater-item]").slideUp();
                }
            });
        });

        $(".file-repeater, .contact-repeater, .repeater-default").repeater({
            show: function () {
                $(this).slideDown();
            },
        });
    });
});