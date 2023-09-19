// npm package: flatpickr
// github link: https://github.com/flatpickr/flatpickr

$(function() {
  'use strict';

  // date picker 
  if($('#flatpickr-date').length) {
    flatpickr("#flatpickr-date", {
      wrap: true,
      dateFormat: "Y-m-d H:i",
      minDate:"today"
    });
  }


  // time picker
  if($('#flatpickr-time-from').length) {
    flatpickr("#flatpickr-time-from", {
      wrap: true,
      enableTime: true,
      noCalendar: false,
      dateFormat: "Y-m-d H:i",
      minuteIncrement:30,
    });
  }

  if($('#flatpickr-time-to').length) {
    flatpickr("#flatpickr-time-to", {
      wrap: true,
      enableTime: true,
      noCalendar: false,
      dateFormat: "Y-m-d H:i",
      minuteIncrement:30,
    });
  }

  

});