/*---------------------------------------------
Template name :  Dashmin
Version       :  1.0
Author        :  ThemeLooks
Author url    :  http://themelooks.com


** Custom Repetar JS

----------------------------------------------*/

// $(function () {
//     'use strict';

//     $(document).ready(function () {
//         console.log('test');

//         $(".remove-btn").on("click", function (e) {
//             e.preventDefault(); // Prevent the default behavior (page refresh)

//             // Reference to the current remove button
//             var $removeBtn = $(this);

//             Swal.fire({
//                 title: 'Are you sure?',
//                 text: "You won't be able to revert this!",
//                 icon: 'warning',
//                 showCancelButton: true,
//                 confirmButtonColor: '#3085d6',
//                 cancelButtonColor: '#d33',
//                 confirmButtonText: 'Yes, delete it!'
//             }).then((result) => {
//                 if (result.isConfirmed) {
//                     // Continue with the removal logic
//                     $removeBtn.closest("[data-repeater-item]").slideUp();
//                 }
//             });
//         });

//         $(".file-repeater, .contact-repeater, .repeater-default").repeater({
//             show: function () {
//                 $(this).slideDown();
//             },
//         });
//     });
// });

// console.log('CSRF Token:', csrfToken);
$(function () {
    'use strict';

    $(document).ready(function () {
        // console.log('test');
        $(".remove-btn").on("click", function (e) {
            e.preventDefault(); // Prevent the default behavior (page refresh)

            // Reference to the current remove button
            var $removeBtn = $(this);

             // Log the task ID to the console
             var taskId = $removeBtn.data('task-id');
            //  console.log('Task ID:', taskId);

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
                    // Get the task ID from the data attribute
                    var taskId = $removeBtn.data('task-id');

                    // Get the task ID from the data attribute
                    // console.log('Task ID to be deleted:', taskId);

                    // Send an AJAX request to delete the task
                    $.ajax({
                        url: '/admin/deleteTask/' + taskId,
                        type: 'POST',
                        data: {
                            _method: 'DELETE', // Add the _method parameter to simulate DELETE
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function (data) {
                            console.log(data);

                            // Remove the repeater item on successful deletion
                            $removeBtn.closest("[data-repeater-item]").slideUp();
                            // You can add additional logic here based on the server response
                            Swal.fire('Deleted!', 'Your task has been deleted.', 'success');
                        },
                        error: function (xhr, status, error) {
                            console.error(error);
                            Swal.fire('Error!', 'Failed to delete task. Please try again.', 'error');
                        }
                    });
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