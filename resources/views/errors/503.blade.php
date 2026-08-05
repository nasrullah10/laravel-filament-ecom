<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance</title>

    <style>
        body{
            margin:0;
            font-family:Arial, sans-serif;
            background:#f5f5f5;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .card{
            background:#fff;
            padding:40px;
            border-radius:10px;
            text-align:center;
            box-shadow:0 5px 15px rgba(0,0,0,.1);
            width:450px;
        }

        h1{
            color:#333;
        }

        #countdown{
            font-size:32px;
            font-weight:bold;
            color:#e63946;
            margin:20px 0;
        }
    </style>
</head>
<body>

<div class="card">
    <h1>🚧 Website Under Maintenance</h1>

    <p>We're upgrading our website.</p>

    <div id="countdown">30:00</div>

    <p>Please check back soon.</p>
</div>

<script>
    let duration = 30 * 60; // 30 minutes

    const countdown = document.getElementById('countdown');

    const timer = setInterval(function () {

        let minutes = Math.floor(duration / 60);
        let seconds = duration % 60;

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        countdown.innerHTML = minutes + ":" + seconds;

        if (duration <= 0) {
            clearInterval(timer);
            countdown.innerHTML = "Website will be back shortly";
        }

        duration--;

    }, 1000);
</script>

</body>
</html>