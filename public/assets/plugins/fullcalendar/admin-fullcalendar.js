/*---------------------------------------------
Template name :  Dashmin
Version       :  1.0
Author        :  ThemeLooks
Author url    :  http://themelooks.com


** Fullcalendar Config

----------------------------------------------*/


var rxhtmlTag = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([a-z][^\/\0>\x20\t\r\n\f]*)[^>]*)\/>/gi;
jQuery.htmlPrefilter = function( html ) {
    return html.replace( rxhtmlTag, "<$1></$2>" );
};

$(function() {
  'use strict';

  // Initialize the calendar
  var calendar = $('#fullcalendar').fullCalendar({
    header: {
        left: 'title',
        right: 'today,prev,next'
        // left: 'title,prev,next,today',
        // right: 'month,agendaWeek,agendaDay',

    },
    firstDay: 1,
    editable: true,
    droppable: true,
    dragRevertDuration: 0,
    defaultView: 'month',
    eventLimit: true,
    events: function(start, end, timezone, callback) {
        // Fetch events with joined data directly from your endpoint using AJAX
        $.ajax({
            url: '/admin/schedule', // Use the route URL
            dataType: 'json',
            success: function(data) {
                var events = data.map(function(event) {
                    var backgroundColor = event.off_day === 1 ? '#e861a3' : '#9086cc';
                    return {
                        id: event.id,
                        title: event.nickname,
                        start: event.date,
                        end: event.date, // Adjust the end date as needed
                        shiftStart: event.shift_start, // Include Shift Start in event properties
                        shiftEnd: event.shift_end,     // Include Shift End in event properties
                        dutyName: event.duty_name,
                        remarks: event.remarks,
                        backgroundColor: backgroundColor,
                    };
                });
                callback(events);
            }
        });
        
    },
    // eventClick: function(event, jsEvent, view) {
    //     console.log(event); // Log the event object to the console
    
    //     // Extract and display data
    //     var scheduleId = event.id; // Get the schedule ID

    //     var fullName = event.title; // Assuming "title" contains the full name
    //     var date = event.start.format('YYYY-MM-DD'); // Format the date
    
    //     var shiftStart = moment(event.shiftStart, 'HH:mm').format('hh:mm A');
    //     var shiftEnd = moment(event.shiftEnd, 'HH:mm').format('hh:mm A');

    //     var dutyName = event.dutyName;

    //     var remarks = event.remarks;

    //     // Set data attributes for the fields in fullCalModal
    //     $('#modalFullName').data('full-name', fullName);
    //     $('#modalDate').data('date', date);
    //     $('#modalShiftStart').data('shift-start', shiftStart);
    //     $('#modalShiftEnd').data('shift-end', shiftEnd);

    //     if (dutyName) {
    //         $('#modalDutyName').data('duty-name', dutyName);
    //     } else {
    //         $('#modalDutyName').data('duty-name', ''); // Set a blank value as a data attribute
    //     }

    //     $('#modalRemarks').html(remarks);
    
    //     // Display data in the modal
    //     $('#modalScheduleId').html(scheduleId);
    //     $('#modalFullName').html(fullName);
    //     $('#modalDate').html(date);
    //     $('#modalShiftStart').html(shiftStart);
    //     $('#modalShiftEnd').html(shiftEnd);

    //     // Set the duty name or a blank value if it's null or empty
    //     if (dutyName) {
    //         $('#modalDutyName').html(dutyName);
    //     } else {
    //         $('#modalDutyName').html(''); // Display a blank value
    //     }

    //     $('modalRemarks').html(remarks);

    //         // Set the event's ID as a data attribute in the edit button
    //     $('#editEventButton').data('event-id', event.id);

    //     // Set the ID as a data attribute to elements in fullCalModal
    //     $('#modalScheduleId').data('event-id', event.id);

    //     $('#fullCalModal').modal();
    // },
    dayClick: function(date, jsEvent, view) {
        var clickedDate = date.format();
        console.log('Clicked date:', clickedDate);
        // Set the modal title to match the selected date
        var formattedDate = moment(clickedDate).format('DD MMM YYYY'); // Format the date as desired
        $('#scheduleModalLabel').text('Schedules for ' + formattedDate);

        $.ajax({
            url: '/admin/getSchedule', // Adjust the URL to match your route
            type: 'GET',
            data: { date: clickedDate }, // Pass the clicked date as a parameter
            success: function(schedule) {
                var scheduleTableBody = $('#scheduleTableBody');
                scheduleTableBody.empty();
    
                schedule.forEach(function(item) {
                    var row = '<tr>' +
                        '<td>' + item.nickname + '</td>' +
                        '<td>' + (item.shift_start ? moment(item.shift_start, 'HH:mm').format('hh:mm A') : 'Off Day') + '</td>' +
                        '<td>' + (item.shift_end ? moment(item.shift_end, 'HH:mm').format('hh:mm A') : 'Off Day') + '</td>' +
                        '<td>' + (item.duty_name ? item.duty_name : 'Off Day') + '</td>' +
                        '<td>' + (item.remarks ? item.remarks : '') + '</td>' +
                        '<td>' +
                            '<button class="btn btn-primary btn-sm edit-schedule" data-schedule-id="' + item.id + '">Edit</button>' +
                            '<button class="btn btn-danger btn-sm delete-schedule" data-schedule-id="' + item.id + '">Delete</button>' +
                        '</td>' +
                        '</tr>';
                    scheduleTableBody.append(row);
                });
    
                $('#openModalButton').click();

                $('.edit-schedule').click(function() {
                    var scheduleId = $(this).data('schedule-id'); // Get the schedule ID from the data attribute
                    // Redirect to the edit page or perform the desired action
                    window.location.href = '/admin/editSchedule/' + scheduleId;
                });
            },
            error: function(error) {
                console.log('Error:', error);
            }
        });
    },
    // Add the event handling for the "Delete" button click
    eventRender: function(event, element) {
        $(document).on('click', '.delete-schedule', function(e) {
            e.preventDefault();
            var scheduleId = $(this).data('schedule-id'); // Corrected
            console.log('Delete button clicked for schedule ID:', scheduleId);
            // Close the modal first
            $('#scheduleModal').modal('hide');
            // Send an AJAX request to delete the schedule
            $.ajax({
                url: '/admin/deleteSchedule/' + scheduleId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Handle success
                    console.log(response.message);
                    // Handle success
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                    }).then(function() {
                        // Handle success, like removing the event from the calendar
                        $('#fullcalendar').fullCalendar('removeEvents', scheduleId);
                        // Once the schedule is successfully deleted, stop further execution
                        return false;
                    });
                },
                error: function(error) {
                    console.log('Error:', error);
                }
            });
        });
    }

    // eventDragStop: function(event, jsEvent, ui, view) {
    //     if (isEventOverDiv(jsEvent.clientX, jsEvent.clientY)) {
    //         var el = $("<div class='fc-event'>").appendTo('#external-events-listing').text(event.title);
    //         el.draggable({
    //             zIndex: 999,
    //             revert: true,
    //             revertDuration: 0
    //         });
    //         el.data('event', { title: event.title, id: event.id, stick: true });
    //     }
    // }
    
});

    var isEventOverDiv = function(x, y) {
        var external_events = $('#external-events');
        var offset = external_events.offset();
        offset.right = external_events.width() + offset.left;
        offset.bottom = external_events.height() + offset.top;

        if (x >= offset.left && y >= offset.top && x <= offset.right && y <= offset.bottom) {
            return true;
        }
        return false;
    };
});


