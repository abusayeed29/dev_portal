@php
$slug = Auth::user()->role->slug;
$isViewReportRoster = getReadPermission(19,1);
@endphp
@extends('layouts.'.$slug)
@section('title','Roster')
@push('css')
<link rel="stylesheet" href="{{asset('backend/assets/vendors/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/toastr.js/toastr.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/assets/vendors/select2/select2.min.css')}}">
<style>
    .fc .fc-button-group>.fc-button {
        text-transform: capitalize !important;
    }
</style>
@endpush

@section('content')

<div class="page-content">
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0"></h4>
            <nav class="page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Calendar</li>
                </ol>
            </nav>
        </div>

        @if($isViewReportRoster)
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{route('roster.list')}}" class="btn btn-info btn-icon-text mb-2 mb-md-0">
                <i class="btn-icon-prepend" data-feather="eye"></i>
                View Report
            </a>
        </div>
        @endif

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div id='fullcalendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="fullCalModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modalTitle1" class="modal-title"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><span class="visually-hidden">close</span></button>
                </div>
            </div>
        </div>
    </div>

    <div id="createEventModal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modalTitle2" class="modal-title">Add Roster</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><span class="visually-hidden">close</span></button>
                </div>
                <div id="modalBody2" class="modal-body">
                    <form id="addRosterForm">
                        <div class="mb-3">
                            <label for="employeedId" class="form-label">Add Employees</label>
                            <select class="livesearch w-100 form-control mb-3" id="employeedId" name="user_id[]" data-width="100%" multiple="multiple">
                            </select>
                            <input type="hidden" name="start_date" id="startDate" value="" />
                            <input type="hidden" name="end_date" id="endDate" value="" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="button" id="rosterSubmit">Add</button>
                </div>
            </div>
        </div>
    </div>


</div>

@endsection

@push('js')

<script src="{{asset('backend/assets/vendors/fullcalendar/main.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/toastr.js/toastr.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('backend/assets/js/select2.js')}}"></script>

<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function printErrorMsg(msg) {
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "positionClass": "toast-top-right"
            };
            var error_html = '';
            for (var count = 0; count < msg.length; count++) {
                error_html += '<p>' + msg[count] + '</p>';
            }
            toastr.error(error_html);
        }

        function printSuccessMsg(msg) {
            toastr.options = {
                "closeButton": true,
                "newestOnTop": true,
                "positionClass": "toast-top-right"
            };
            toastr.success(msg);
        }

        $('#createEventModal').on('hidden.bs.modal', function(e) {
            location.reload();
        })

        $(function() {

            // sample calendar events data

            var Draggable = FullCalendar.Draggable;
            var calendarEl = document.getElementById('fullcalendar');
            var containerEl = document.getElementById('external-events');

            var curYear = moment().format('YYYY');
            var curMonth = moment().format('MM');

            // Calendar Event Source


            // new Draggable(containerEl, {
            //     itemSelector: '.fc-event',
            //     eventData: function(eventEl) {
            //         return {
            //             title: eventEl.innerText
            //         };
            //     }
            // });

            // initialize the calendar ==============/
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: "prev,today,next",
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },
                initialView: 'dayGridMonth',
                hiddenDays: [],
                navLinks: 'true',
                events: "{{url('get-event')}}",
                selectable: true,
                selectHelper: true,
                editable: true,
                select: function(info) {
                    $("#createEventModal").modal("show");
                    $('input#startDate').val(moment(info.start).format('YYYY-MM-DD'));
                    $('input#endDate').val(moment(info.end).format('YYYY-MM-DD'));

                    $('#rosterSubmit').click(function(e) {
                        e.preventDefault();
                        $.ajax({
                            url: "{{route('roster.store')}}",
                            method: 'POST',
                            data: $('#addRosterForm').serialize(),
                            success: function(response) {
                                console.log(response);
                                if (response.errors) {
                                    printErrorMsg(response.errors);
                                } else {
                                    printSuccessMsg(response.success);
                                    $('.alert-danger').hide();
                                    $('#addRosterForm').trigger("reset");
                                    $("#createEventModal").modal("hide");
                                    window.location.reload(true);
                                }
                            }
                        });
                    });


                },
                eventDrop: function(info) {
                    console.log(info.event.id);
                    var start_date = moment(info.event.start).format('YYYY-MM-DD');
                    var end_date = moment(info.event.end).format('YYYY-MM-DD');
                    $.ajax({
                        url: "{{route('roster.update')}}",
                        data: {
                            title: info.event.title,
                            start_date: start_date,
                            end_date: end_date,
                            id: info.event.id,
                        },
                        type: "POST",
                        success: function(response) {
                            console.log(response);
                            if (response.errors) {
                                printErrorMsg(response.errors);
                            } else {
                                printSuccessMsg(response.success);
                            }
                        }

                    });

                    // alert(start + ' ' + info.event.title + " was dropped on " + info.event.start.toISOString());

                    // if (!confirm("Are you sure about this change?")) {
                    //     info.revert();
                    // }

                },
                eventClick: function(info) {
                    var eventObj = info.event;
                    console.log(info);
                    $('#modalTitle1').html(eventObj.title);
                    $('#modalBody1').html(eventObj._def.extendedProps.description);
                    $('#eventUrl').attr('href', eventObj.url);
                    $('#fullCalModal').modal("show");
                },

                // dateClick: function(info) {
                //     $("#createEventModal").modal("show");
                //     console.log(info);

                //     // var event_start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                //     // var event_end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");

                // },
            });

            calendar.render();

            // end caledar  ==================================/

            // search employee
            $('.livesearch').select2({
                dropdownParent: $('#createEventModal'),
                placeholder: 'Select user',
                ajax: {
                    url: "{{ url('/employee/search') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data,
                        };
                    },
                    cache: true
                }
            });


        });

    });
</script>
@endpush