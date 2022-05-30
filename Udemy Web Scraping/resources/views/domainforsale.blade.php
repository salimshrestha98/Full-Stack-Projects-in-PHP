<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="{{ url('/') }}/bootstrap-4/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/css/style.css">
    <link rel="stylesheet" href="{{ url('/') }}/fa/css/fontawesome-all.min.css">
    <script src="{{ url('/') }}/js/jquery-3.3.1.min.js"></script>
    <script src="{{ url('/') }}/js/popper.min.js"></script>
    <script src="{{ url('/') }}/bootstrap-4/js/bootstrap.min.js"></script>
    <title>Domain for sale - Fivescream.com</title>
    <style>
    #main {
        background: url('/img/domainsale.jpg');
    }

    .transparent {
        background: rgba(255,255,255,0.5);
    }

    h1 {
        font-size: 25px;
    }

    h2 {
        font-size: 25px;
    }
    </style>
</head>
<body>
    <div id="main">
        <div class="container-fluid transparent text-center p-3">
            <h1>This Domain <strong class="text-primary">Fivescream.com</strong> is for sale @ just <strong class="text-primary">$99</strong> !</h1>
        </div>
        <div class="container">
            <form action="/sale" method="post" class="form form-vertical border px-4 py-3 mt-5 offset-sm-3 col-sm-6 transparent">
            @csrf
                <h2 class="mb-4">Message Us</h2>
                <div>
                    <label for="cname">Name : </label>
                    <div class="form-group">
                        <input type="text" name="cname" id="" class="form-control" placeholder="James Bond" required>
                    </div>            
                </div>
                <div>
                    <label for="email">Email : </label>
                    <div class="form-group">
                        <input type="email" name="email" id="" class="form-control" placeholder="jbond@justmailme.com" required>
                    </div>            
                </div>

                <div>
                    <label for="email">Message : </label>
                    <div class="form-group">
                        <textarea name="message" id="" cols="30" rows="10" class="form-control" placeholder="Message Here..." required></textarea>
                    </div>            
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Send Now</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>