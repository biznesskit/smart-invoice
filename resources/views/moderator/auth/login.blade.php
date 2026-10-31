<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
    <body>
        <div class="container">

            <form class="border m-4 p-3 mx-auto bg-light" style="max-width:26rem"  action="{{route('moderator.login.store')}}" method="post">
                @csrf
                <h3 class="text-center">Moderator Portal</h3>
                <p class="text-danger text-center">
                    {{count($errors) ? 'Wrong credentials!' : ''}}
                </p>
                <div class="row mb-1">
                    <div class="col-sm-12">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Email</label>
                        <input name="username" type="email"  id="inputEmail3">
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-sm-12">
                        <label for="inputPassword3" class="col-sm-3 col-form-label">Password</label>
                        <input name="key" type="password"  id="inputPassword3">
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="">Sign in</button>
                </div>

            </form>
        </div>
    </body>
</html>