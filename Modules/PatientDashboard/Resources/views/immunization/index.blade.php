@extends('patient.layouts.master')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.1/main.min.css" rel="stylesheet" type="text/css">
@section('content')
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                <div class="topspce">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="white_wrapper">
                                <div class="header_card">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Vaccination Schedule</h4>
                                    </div>
                                </div>
                                <div class="schedules">
                                    <div class="vaccination_lists">
                                        Diphtheria
                                        <div class="price_vaccine">
                                            Rs.3000
                                        </div>
                                    </div>
                                    <div class="vaccination_lists">
                                        Measles
                                        <div class="price_vaccine">
                                            Rs.3000
                                        </div>
                                    </div>
                                    <div class="vaccination_lists">
                                        Whooping Cough
                                        <div class="price_vaccine">
                                            Rs.3000
                                        </div>
                                    </div>
                                    <div class="vaccination_lists">
                                        Tetanus
                                        <div class="price_vaccine">
                                            Rs.3000
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div id='calendar'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.3.1/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            initialDate: '2020-09-07',
            headerToolbar: {
                left: 'prev,next today, addRoom, addReservation',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },

            events: [{
                    title: 'COVID - 19 Vaccination',
                    start: '2020-09-01'
                },
                {
                    title: 'Pneumonia Vaccination ',
                    start: '2020-09-07',
                    end: '2020-09-10'
                },
                {
                    groupId: '999',
                    title: '10:30 am',
                    start: '2020-09-09T16:00:00'
                },

                {
                    title: 'Conference',
                    start: '2020-09-11',
                    end: '2020-09-13'
                },

                {
                    title: '',
                    start: '12:00:00'
                },
                {
                    title: '',
                    start: '14:30:00'
                },

                {
                    title: 'Measles',
                    url: 'http://google.com/',
                    start: '2020-09-28'
                }
            ]
        });

        calendar.render();
    });
</script>
@endsection