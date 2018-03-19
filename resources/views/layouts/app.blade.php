<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/solid.js" integrity="sha384-4KkAk2UXMS9Xl3FoAAN43VJxRZ/emjElCz60xUTegPOZlbPLZGylvor2v7wQ0JNb" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/fontawesome.js" integrity="sha384-l7FyBM+wFIWpfmy8RYkWgEu/Me6Hrz98ijLu4nP3PkGbTtTCvtHB5ktI8hLEgEG3" crossorigin="anonymous"></script>

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" >
</head>
<body>
    <div id="app">
        @include('inc.navbar')
        <div class="container">
            @yield('content')
        </div>

        </div>
        <!-- /#page-content-wrapper -->

        </div>
    </div>



    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        $(document).ready(function () {
        var trigger = $('.hamburger'),
            overlay = $('.overlay'),
            isClosed = false;

            trigger.click(function () {
            hamburger_cross();      
            });

            function hamburger_cross() {

            if (isClosed == true) {          
                overlay.hide();
                trigger.removeClass('is-open');
                trigger.addClass('is-closed');
                isClosed = false;
            } else {   
                overlay.show();
                trigger.removeClass('is-closed');
                trigger.addClass('is-open');
                isClosed = true;
            }
        }
        
        $('[data-toggle="offcanvas"]').click(function () {
                $('#wrapper').toggleClass('toggled');
        });  
        });
    </script>
</body>
</html>