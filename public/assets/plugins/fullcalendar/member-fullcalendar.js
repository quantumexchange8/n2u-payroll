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
        // right: 'month,agendaWeek,agendaDay'
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
            url: '/user/viewSchedule', // Use the route URL
            data: {
                user_id: userId, // Pass the user's ID as a parameter
            },
            dataType: 'json',
            success: function(data) {
                var events = data.map(function(event) {
                    var title = event.shiftStart + ' - ' + event.shiftEnd;
                    return {
                        id: event.id,
                        title: title,
                        start: event.date,
                        end: event.date,
                        shiftStart: event.shiftStart, // Include Shift Start in event properties
                        shiftEnd: event.shiftEnd,     // Include Shift End in event properties
                    };
                });
                callback(events);
            }
        });
    },
    eventClick: function(event, jsEvent, view) {
        console.log(event); // Log the event object to the console
    
        // Extract and display data
        var scheduleId = event.id; // Get the schedule ID

        var fullName = event.title; // Assuming "title" contains the full name
        var date = event.start.format('YYYY-MM-DD'); // Format the date
    
        var shiftStart = event.shiftStart;
        var shiftEnd = event.shiftEnd;

        
    
        // Display data in the modal
        $('#modalScheduleId').html(scheduleId);
        $('#modalFullName').html(fullName);
        $('#modalDate').html(date);
        $('#modalShiftStart').html(shiftStart);
        $('#modalShiftEnd').html(shiftEnd);

    
        $('#fullCalModal').modal();
    }
    ,
    dayClick: function(date, jsEvent, view) {
        $("#createEventModal").modal("show");
    },
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
      return false;
  };
});