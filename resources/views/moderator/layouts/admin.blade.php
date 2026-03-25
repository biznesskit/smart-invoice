<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .list-group a{
            color:inherit;
            text-decoration:none;
        }
        .list-group .current{
            color:#5cb85c ;
            border-right:2px solid #5cb85c ;
        }
        .text-right{
            text-align:right;
        }
        .content .edit_form{
            max-width:36rem;
            padding:15px;
            border-radius:5px;
        }
    </style>
</head>
    <body>
        
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-4 col-lg-2 bg-light">
                    <x-moderator.sidebar />
                </div>
                <div class="col-8 col-lg-10 content h-100">
                    <x-moderator.flash-msgs />

                    @yield('content')
                    <span class='py-4 text-center lead text-muted p'>
                        <hr/>
                         Bizkit &copy; {{date('Y')}} 
                    </span>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <script>
            function confirm_submit(event,formID)
            {
                event.preventDefault();
                if( confirm("Are you sure you want to submit details?") ) document.getElementById(formID).submit();
            }
        </script>
    </body>
</html>