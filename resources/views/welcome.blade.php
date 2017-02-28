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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
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
            <a data-toggle="modal" class="pointer" data-target="#editRestaurantModal" onclick="$('#restaurant-model-submit-button').html('Add');$('#restaurant-model-title').html('Add Restaurant');document.restaurantForm.action='/restaurant';$('#edit-restaurant-id').val('');$('#edit-restaurant-name').val('');$('#edit-restaurant-location').val('');$('#edit-restaurant-description').val('');$('#edit-restaurant-phone').val('');$('#edit-restaurant-link').val('');">Add Restaurant
            </a>
            <a href="{{ url('/logout') }}">Logout</a>
        </div>


    @endif

    <div class="content">
        <div class="title m-b-md">
            Foody
        </div>

        <div class="container">
            <div class="error">{{ $errors->first()}}</div>

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
                                            <button class="btn btn-success"
                                                    style="display: block; margin: 2px 1px;width: 100%;min-width: 130px;"
                                                    onclick="$('#restaurant').val({{$rest['id']}});" data-toggle="modal"
                                                    data-target="#reservationModal">Reserve!
                                            </button>
                                            @if (session('role') == Config::get('constants.USER_ROLES.ADMIN') && !$rest['foursquare_id'])
                                                <button style="width: 48%;display: inline; margin: 1px 0;text-decoration: underline;font-size: 13px;"
                                                        class="btn btn-link btn-sm" data-toggle="modal" data-target="#editRestaurantModal" onclick="$('#restaurant-model-submit-button').html('Modify');$('#restaurant-model-title').html('Modify Restaurant');document.restaurantForm.action='/restaurant/edit';$('#edit-restaurant-id').val('{{$rest['id']}}');$('#edit-restaurant-name').val('{{$rest['name']}}');$('#edit-restaurant-location').val('{{$rest['location']}}');$('#edit-restaurant-description').val('{{$rest['desc']}}');$('#edit-restaurant-phone').val('{{$rest['phone_number']}}');$('#edit-restaurant-link').val('{{$rest['link']}}');">Modify
                                                </button>
                                                <form  style="width: 49%;display: inline; margin: 1px 0;font-size: 13px;padding: 0;" action="/restaurant/{{$rest['id']}}">{{method_field('DELETE')}} {{csrf_field()}}
                                                    <button style="text-decoration: underline;" type="submit"
                                                        class="btn btn-link btn-sm">Delete
                                                    </button>
                                                </form>
                                            @endif
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
                                    <th>Invitees</th>
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
                                        <td>
                                            @foreach($appointment['users'] as $user)
                                                <h4 style="display: inline;">{{$user['name']}}</h4>
                                                - {{($user['pivot']['status'] == Config::get('constants.APPOINTMENT_STATUS.ACCEPTED')) ? '(Accepted)':''}} {{($user['pivot']['status'] == Config::get('constants.APPOINTMENT_STATUS.REJECTED')) ? '(Rejected)':''}} {{($user['pivot']['status'] == Config::get('constants.APPOINTMENT_STATUS.PENDING')) ? '(Pending)':''}}
                                                <br/>
                                            @endforeach
                                        </td>
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

                    @if (session('role') == Config::get('constants.USER_ROLES.ADMIN'))
                        {{--Edit Restaurant Modal--}}
                        <div id="editRestaurantModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <form method="post" action="/restaurant/edit" name="restaurantForm">
                                    {{csrf_field()}}
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title" id="restaurant-model-title">Modify Restaurant</h4>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" id="edit-restaurant-id" name="restaurant_id"/>
                                            <div class="form-group">
                                                <div class="cols-sm-12">
                                                    <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-info"
                                                                                       aria-hidden="true"></i></span>
                                                        <input type="text" class="form-control"
                                                               name="name" id="edit-restaurant-name"
                                                               placeholder="Name..."/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="cols-sm-12">
                                                    <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-map-marker"
                                                                                       aria-hidden="true"></i></span>
                                                        <input type="text" class="form-control"
                                                               name="location" id="edit-restaurant-location"
                                                               placeholder="Location..."/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="cols-sm-12">
                                                    <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-align-justify"
                                                                                       aria-hidden="true"></i></span>
                                                        <input type="text" class="form-control"
                                                               name="desc" id="edit-restaurant-description"
                                                               placeholder="Description..."/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="cols-sm-12">
                                                    <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-link"
                                                                                       aria-hidden="true"></i></span>
                                                        <input type="text" class="form-control"
                                                               name="link" id="edit-restaurant-link"
                                                               placeholder="Link..."/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="cols-sm-12">
                                                    <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-phone"
                                                                                       aria-hidden="true"></i></span>
                                                        <input type="text" class="form-control"
                                                               name="phone_number" id="edit-restaurant-phone"
                                                               placeholder="Phone..."/>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success" id="restaurant-model-submit-button">Modify</button>
                                            <button type="button" class="btn btn-info" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    {{--Reservation Modal--}}
                    <div id="reservationModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <form method="post" action="/invite">
                                {{csrf_field()}}
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Restaurant Reservation</h4>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="restaurant" name="restaurant_id"/>
                                        <div class="form-group">
                                            <div class="cols-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-clock-o"
                                                                                       aria-hidden="true"></i></span>
                                                    <input type="text" class="form-control"
                                                           name="time"
                                                           placeholder="Time e.g. (2016-12-10 12:14:21) "/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="cols-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-users"
                                                                                       aria-hidden="true"></i></span>
                                                    <input type="hidden" value="" id="users_emails"
                                                           name="users"/>
                                                    <input type="text" id="emails_field" class="form-control"
                                                           name="emails" list="emails"
                                                           placeholder="Invite users (enter email -> press return!)...">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="invited-users">
                                        </div>
                                        {{--Get All Users--}}
                                        <datalist id="emails">
                                            @foreach($users as $user)
                                                <option value="{{$user['email']}}">
                                            @endforeach
                                        </datalist>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Invite</button>
                                        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    // Detect enter key press
    var invitedUsersContainer = $("#invited-users");
    var emailField = $("#emails_field");
    var usersEmails = $("#users_emails");

    $(function () {
        emailField.val("");
        usersEmails.val("");
    })
    $("#emails_field").on('keyup', function (e) {
        if (e.keyCode == 13 && !checkIfEmailExists(emailField.val())) {
            invitedUsersContainer.append($('<h3 style="margin: 2px 10px;" class="label label-success">' + emailField.val() + ' <span class="pointer" onclick="removeThisEmail(\'' + emailField.val() + '\');this.parentNode.parentNode.removeChild(this.parentNode);">&times;</span><br/></h3>'));
            usersEmails.val(usersEmails.val() + emailField.val() + ",");
            emailField.val("");
        }
    });
    function removeThisEmail(email) {
        if (checkIfEmailExists(email)) {
            usersEmails.val(usersEmails.val().replace(email + ",", ""));
        }
    }
    function checkIfEmailExists(email) {
        return ((usersEmails.val()).search("," + email + ",") > 0) || ((usersEmails.val()).search(email + ",") == 0);
    }
</script>
</body>
</html>

