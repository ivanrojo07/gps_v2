<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
    <title>Mapa</title>
</head>
<body>
    <!-- DIV LOADING -->
	<div class="loading d-none" id="loading"></div>	
    {{-- nav --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-3">
                <div class="card m-2">
                    <h5 class="card-header">Buscar Interaccion</h5>
                    <form onsubmit="sendForm()">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="usuario_id">Usuario ID</label>
                                <input class="form-control" type="number" name="usuario_id" id="usuario_id" min="0">
                            </div>
                            <div class="form-group">
                                <label for="fecha">Fecha</label>
                                <input class="form-control" type="date" name="fecha" id="fecha">
                            </div>
                            <div class="form-group">
                                <label for="dias">Dias</label>
                                <input type="number" class="form-control" name="dias" id="dias" min="1" max="15">
                            </div>
                            <div class="form-group">
                                <label for="distancia">Distancia maxima</label>
                                <input type="number" name="distancia" id="distancia" min="0" max="15" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="tiempo">Rango de tiempo de interacción</label>
                                <input type="time" name="tiempo" id="tiempo" class="form-control">
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button class="btn btn-primary" type="input">
                                Buscar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
            <div class="col-12 col-md-9">
                <div class="card m-2">
                    <div class="card-header">
                        Interacciones
                    </div>
                    <div  class="card-body overflow-auto" style="height: 10rem;" id="interacciones_card">
                    </div>
                </div>
                <div class="card m-2">
                    <div class="card-body">
                        <div id="map" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/mapa.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('MAP_KEY')}}&callback=initMap"
	></script>
</body>
</html>