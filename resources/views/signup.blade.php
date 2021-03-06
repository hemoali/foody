<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

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
    <div class="content">
        <div class="modal-dialog">
            <div class="loginmodal-container">
                <div class="title m-b-md">
                    Foody
                </div>
                <h1>Join Us :D</h1><br>
                <form method="post" action="signup">
                    {{ csrf_field() }}
                    <input type="text" name="name" placeholder="Name" value="{{old('name')}}" required>
                    <div class="error">{{ $errors->first('name') }}</div>

                    <input type="text" name="email" placeholder="Email" value="{{old('email')}}" required>
                    <div class="error">{{ $errors->first('email') }}</div>
                    <input type="password" name="password1" placeholder="********" required>
                    <input type="password" name="password2" placeholder="********" required>
                    <div class="error">{{ $errors->first('password1') }}</div>
                    <div class="error">{{ $errors->first('password2') }}</div>

                    <div class="error">{!! Session::has('error-msg') ? Session::get("error-msg") : '' !!}</div>
                    <input type="submit" name="signup" class="login loginmodal-submit" value="Sign up">
                </form>
                <div class="login-help">
                    <a href="{{url('/login')}}">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
