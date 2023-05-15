<!DOCTYPE html>
<html ng-app="myAplicacion">

<head>
    <title>StarWars</title>
    <style>
        @import "css/bootstrap.min.css";
        @import "css/app.css";
        @import "css/icofont/icofont.min.css";
    </style>
    <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>
    <h2>
        Proyect Star Wars
        <span>Podrás consultar nuestras naves y pilotos</span>
    </h2>

    <div>
        <a class="inicio mainBtn"><i class="fa fa-empire"></i></a>

        <ul class="options">
            <li class="option option1" >
                <a class="inicio opt opt1" href="/naves"><i class="fa fa-plane"></i></a>
                <p class="popup">Nuestras naves</p>
            <li>

            <li class="option option3">
                <a class="inicio opt opt3" href="/pilots"><i class="fa fa-user"></i></a>
                <p class="popup">Nuestros Pilotos</p>
            <li>
        </ul>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>