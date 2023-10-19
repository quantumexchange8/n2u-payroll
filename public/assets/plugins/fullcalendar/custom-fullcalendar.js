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
          left: 'title,prev,next,today',
          right: 'month,agendaWeek,agendaDay'
      },
      firstDay: 1,
      editable: true,
      droppable: true,
      dragRevertDuration: 0,
      defaultView: 'month',
      eventLimit: true,
      events: function(start, end, timezone, callback) {
          // Fetch events from your data source using AJAX
          $.ajax({
              url: 'schedule', // Replace with your API endpoint
              dataType: 'json',
              success: function(data) {
                  var events = data.map(function(event) {
                      // Format the event title to include date and employee ID
                      var title = 'Employee ID: ' + event.employee_id + ' Date: ' + event.date;

                      return {
                          id: event.id,
                          title: title, // Use the formatted title
                          start: event.start,
                          end: event.end,
                          backgroundColor: event.backgroundColor,
                          borderColor: event.borderColor,
                          textColor: event.textColor,
                          date: event.date,
                          // location: event.location,
                          // user: event.user,
                          // event: event.event
                      };
                  });
                  callback(events);
              }
          });
      },
      eventClick: function(event, jsEvent, view) {
          $('#modalTitle1').html(event.title);
          $('#modalDate').html(event.date);
          $('#modalLocation').html(event.location);
          $('#modalVisibility').html(event.user);
          $('#modalEvent').html(event.event);
          $('#fullCalModal').modal();
      },
      dayClick: function(date, jsEvent, view) {
          $("#createEventModal").modal("show");
      },
      eventDragStop: function(event, jsEvent, ui, view) {
          if (isEventOverDiv(jsEvent.clientX, jsEvent.clientY)) {
              var el = $("<div class='fc-event'>").appendTo('#external-events-listing').text(event.title);
              el.draggable({
                  zIndex: 999,
                  revert: true,
                  revertDuration: 0
              });
              el.data('event', { title: event.title, id: event.id, stick: true });
          }
      }
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