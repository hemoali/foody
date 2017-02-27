<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Foody</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

</head>
<body>
<div class="flex-center position-ref full-height">
    @if (!session('token'))
        <div class="top-right links">
            <a href="{{ url('/login') }}">Login</a>
            <a href="{{ url('/signup') }}">Register</a>
        </div>
    @else
        <div class="top-right links">
            <a href="{{ url('/logout') }}">Logout</a>
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            Foody
        </div>

        <div class="container">
            <ul class="nav nav-tabs nav-justified">
                <li class="active"><a data-toggle="tab" href="#restaurants">Restaurants</a></li>
                @if (session('token'))
                    <li><a data-toggle="tab" href="#appointments">Appointments</a></li>
                    <li><a data-toggle="tab" href="#invitations">Invitations</a></li>
                @endif
            </ul>

            <div class="tab-content">
                <div id="restaurants" class="tab-pane fade in active">
                    @if (count($restaurants) > 0)
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Description</th>
                                <th>Link</th>
                                <th>Phone No.</th>
                                @if (session('token'))
                                    <th>Actions</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($restaurants as $rest)
                                <tr>
                                    <td>{{$rest['name']}}</td>
                                    <td>{{$rest['location']}}</td>
                                    <td>{{$rest['desc']}}</td>
                                    <td>{{$rest['link']}}</td>
                                    <td>{{$rest['phone_number']}}</td>
                                    @if (session('token'))
                                        <td>
                                            <button class="btn btn-success">Reserve!</button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div>
                            <a {{ $currentPage > 1 ? 'href=' . ( $currentPage-1 ) : '' }}>
                                <button {{ $currentPage > 1 ?  : 'disabled' }} class="btn btn-primary">Back</button>
                            </a>
                            <a {{ ($lastPage - $currentPage) >= 1 ? 'href=' . ( $currentPage+1 ) : '' }}>
                                <button {{ ($lastPage - $currentPage) >= 1 ? '' : 'disabled' }} class="btn btn-primary">
                                    Next
                                </button>
                            </a>
                        </div>
                    @else
                        <p><br/>No restaurants!</p>
                    @endif
                </div>
                @if (session('token'))
                    <div id="appointments" class="tab-pane fade">
                        @if (count($appointments) > 0)
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th width="25%">Appointment #</th>
                                    <th>Restaurant</th>
                                    <th>Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($appointments as $appointment)
                                    <tr>
                                        <td style="text-align: center;"><b>{{$appointment['id']}}</b></td>
                                        <td>{{$appointment['restaurant']['name']}}
                                            <br/>{{$appointment['restaurant']['location']}}
                                            <br/>{{$appointment['restaurant']['phone_number']}}</td>
                                        <td>{{$appointment['time']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <p><br/>No Appointments!</p>
                        @endif
                    </div>
                    <div id="invitations" class="tab-pane fade">
                        @if (count($invitations) > 0)
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th width="25%">Appointment #</th>
                                    <th>Inviter</th>
                                    <th>Restaurant</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($invitations["appointments"] as $invitation)
                                    <tr>
                                        <td style="text-align: center"><b>{{$invitation['id']}}</b></td>
                                        <td>{{$invitation['user']['name']}} <br/>{{$invitation['user']['email']}}</td>
                                        <td>{{$invitation['restaurant']['name']}}
                                            <br/>{{$invitation['restaurant']['location']}}
                                            <br/>{{$invitation['restaurant']['phone_number']}}</td>
                                        <td>{{$invitation['time']}}</td>
                                        <td>
                                            @if ($invitation['pivot']['status'] == Config::get('constants.APPOINTMENT_STATUS.ACCEPTED'))
                                                <p class="btn btn-link" disabled>Accepted</p>
                                            @elseif($invitation['pivot']['status'] == Config::get('constants.APPOINTMENT_STATUS.REJECTED'))
                                                <p class="btn btn-link" disabled>Rejected</p>
                                            @else
                                                <form style="display: inline;" method="post"
                                                      action="/accept">{{csrf_field()}}<input type="hidden"
                                                                                              name="invitation"
                                                                                              value="{{$invitation['id']}}">
                                                    <button class="btn btn-success" type="submit">Accept</button>
                                                </form>
                                                <form style="display: inline;" method="post"
                                                      action="/reject">{{csrf_field()}}<input type="hidden"
                                                                                              name="invitation"
                                                                                              value="{{$invitation['id']}}">
                                                    <button class="btn btn-danger" type="submit">Reject</button>
                                                </form>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <p><br/>No Invitations!</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</body>
</html>
