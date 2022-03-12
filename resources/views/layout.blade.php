<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @yield('meta')

    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    @yield('link')

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    @yield('script')
    
    <style>
        label {
            display: block;
            font-weight: bold;
        }

        .form-group {
            text-align: center;
        }

        .form-group1 {
            float: right;
        }

        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu>.dropdown-menu {
            top: 0;
            left: 100%;
        }

        .table tbody tr.highlight td {
            background-color: #ddd;
        }
    </style>

    <script>
        $(function() {
            $("ul.dropdown-menu [data-toggle='dropdown']").on("click", function(event) {
                event.preventDefault();
                event.stopPropagation();

                //method 1: remove show from sibilings and their children under your first parent

                /* 		if (!$(this).next().hasClass('show')) {
                		      
                		        $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
                		     }  */


                //method 2: remove show from all siblings of all your parents
                $(this).parents('.dropdown-submenu').siblings().find('.show').removeClass("show");

                $(this).siblings().toggleClass("show");


                //collapse all after nav is closed
                $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
                    $('.dropdown-submenu .show').removeClass("show");
                });

            });
        });
    </script>

</head>

<body>

    <div class="jumbotron text-center" style="margin-bottom:0">
        <h1> <a style="text-decoration : none; color : #000" href="{{ route('menu') }}">WEB HOTEL</a></h1>
        <p>@yield('header')</p>
    </div>

    @yield('content')

    <div class="jumbotron text-center" style="margin-bottom:0">
        <p>Web Hotel</p>
    </div>



</body>

</html>