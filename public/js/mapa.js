var interacciones;
// Div para lanzar el loading user
var loading = document.getElementById('loading')
function sendForm(){
    // Se muestra el div loading
	loading.classList.remove("d-none")
    event.preventDefault();
    usuario_id = document.getElementById('usuario_id').value
    fecha = document.getElementById('fecha').value
    dias = document.getElementById('dias').value
    distancia = document.getElementById('distancia').value
    tiempo = document.getElementById('tiempo').value
    var param = {
        usuario_id,
        fecha,
        dias,
        distancia,
        tiempo
    }
    fetch("http://localhost:81/api/interacciones",{
        method:"POST",
        body: JSON.stringify(param),
        headers:{
            'Content-Type': 'application/json',
            'Accept':'application/json'
        }
    }).then(res=>res.json())
    .catch(error=>{
        console.log('Error: ',error)
        // Ocultamos el loading spinner
		loading.classList.add("d-none")
        $("#interacciones_card").empty()
        $("#interacciones_card").append(
            `<div class="alert alert-danger" role="alert">
                    No se encontraron interacciones con otros usuarios
                </div>`
        )
    })
    .then(response=>{
        interacciones = response
        $("#interacciones_card").empty()
        // Ocultamos el loading spinner
		loading.classList.add("d-none")
        if(interacciones.length > 0){
            interacciones.forEach(element => {
                var split_duracion = element.duracion.split(":")
                var duracion = (split_duracion[0] != "00" ? (split_duracion[0] == "01" ? split_duracion[0]+" hora " : split_duracion[0]+" horas ") : " ")
                                +(split_duracion[1] != "00" ? (split_duracion[1] == "01" ? split_duracion[1]+" minuto " : split_duracion[1]+" minutos "): "")
                                +(split_duracion[2] != "00" ? (split_duracion[2] == "01" ? split_duracion[2]+" segundo" : split_duracion[2]+" segundos") : "")
                card_interaccion = `
                <div class="card" onclick='drawRoute(${JSON.stringify(element)})'>
                    <div class="row m-2">
                        <div class="col-2 text-center">
                            <img src="${element.info_usuario360.image}" alt="avatar" class="rounded-circle" height="75">
                        </div>
                        <div class="col-4">
                            <p><span class="badge badge-secondary">Nombre:</span> ${element.info_usuario360.nombre+" "+element.info_usuario360.apellido_paterno+" "+element.info_usuario360.apellido_paterno }</p>
                            <p><span class="badge badge-secondary">fecha</span>${element.fecha}</p>
                        </div>
                        <div class="col-2 text-center">
                            <img src="${element.info_interaccion360.image}" alt="avatar" class="rounded-circle" height="75">
                        </div>
                        <div class="col-4">
                            <p><span class="badge badge-secondary">Interactuo:</span> ${element.info_interaccion360.nombre+" "+element.info_interaccion360.apellido_paterno+" "+element.info_interaccion360.apellido_paterno }</p>
                            <p><span class="badge badge-secondary">Tiempo</span>${duracion}</p>
                        </div>
                    </div>
                </div>
                `;
                $("#interacciones_card").append(card_interaccion)
                console.log(element)
                // Ocultamos el loading spinner
                loading.classList.add("d-none")
            });
        }
        else{
            $("#interacciones_card").append(
                `<div class="alert alert-danger" role="alert">
                        No se encontraron interacciones con otros usuarios
                    </div>`
            )
        }
    })
}
var map;
var routePolyline;
var usuarioMarkers;
// Creamos infowindows para información del usuario
var usuario_infowindow;
var interaccion_marker;
var distancia_line;
function initMap() {
    map = new google.maps.Map(document.getElementById('map'),{
        center: {
            lat: 19.458413,
            lng:-99.115337
        },
        zoom: 17,
        mapTypeId:'terrain',
        heading:90,
        tilt:45
    });
    routePolyline = new google.maps.Polyline({
        strokeColor: "#FF0000",
        strokeOpacity: 0.75,
        strokeWeight: 0.5,
    });
    routePolyline.setMap(map)
    usuarioMarkers = [];
    usuario_infowindow = new google.maps.InfoWindow();
    interaccion_marker = new google.maps.Marker();
    distancia_line = new google.maps.Polyline();

    
}
function drawRoute(params) {
    
    var punto_interaccions = params.punto_interaccions
    
    clearMarkers();
    routePolyline.setMap(null);
    routePolyline = new google.maps.Polyline({
        strokeColor: "#FF0000",
        strokeOpacity: 0.75,
        strokeWeight: 0.5,
    });
    routePolyline.setMap(map)
    punto_interaccions.forEach(point => {
        setPoint(point,params.info_usuario360,params.info_interaccion360)
        setLine(point.punto_usuario.lat,point.punto_usuario.lng,routePolyline)
    });
}


function setPoint(point,usuario,interaccion) {
    
    var google_mark = new google.maps.Marker({
        position: new google.maps.LatLng(point.punto_usuario.lat,point.punto_usuario.lng),
        map:map,
        // Insertamos el icono y el tamaño
        icon : {
            // url de la imagen del icono a utilizar
            // Si esta en el objeto del usuario 360 la utilizamos
            // De lo contrario agregamos una por defecto de maps
            url:usuario.icon,
            // Tamaño que llevara el icono
            scaledSize: new google.maps.Size(15, 15),
            // origin: new google.maps.Point(0,0), // origin
            // anchor: new google.maps.Point(0, 0) // anchor
        }
    });
    usuarioMarkers.push(google_mark)
    
    

    // handle para el evento click a las marcas del usuario
    google.maps.event.addListener(google_mark, 'click', (function(google_mark, i) {
        // Retornamos una función para insertar el body del innfowindo
        // con informacion del usuario y el punto
            return function() {
                usuario_infowindow.setContent(`<div class="card">
                    <div class="card-body">
                        <div><strong>Usuario:</strong> ${usuario.nombre+" "+usuario.apellido_paterno+" "+usuario.apellido_paterno }</div>
                        <div><strong>Fecha:</strong> ${point.punto_usuario.fecha}</div>
                        <div><strong>Hora:</strong> ${point.punto_usuario.hora}</div>
                        <div><strong>Distancia:</strong> ${point.distancia} metros</div>
                        <div><strong>Duración:</strong> ${point.punto_usuario.duracion}</div>
                    </div>
                </div>`);
                // Mostramos el infowindow
                usuario_infowindow.open(map, google_mark);
                distancia_line.setMap(null)
                distancia_line = new google.maps.Polyline({
                    strokeColor: "#3490DC",
                    strokeOpacity: 2.4,
                    strokeWeight: 2.4,
                });
                distancia_line.setMap(map)
                setLine(point.punto_usuario.lat,point.punto_usuario.lng,distancia_line)
                setLine(point.punto_interaccion.lat,point.punto_interaccion.lng,distancia_line)
                interaccion_marker.setMap(null);
                interaccion_marker = new google.maps.Marker({
                    position: new google.maps.LatLng(point.punto_interaccion.lat,point.punto_interaccion.lng),
                    map:map,
                    // Insertamos el icono y el tamaño
                    icon : {
                        // url de la imagen del icono a utilizar
                        // Si esta en el objeto del usuario 360 la utilizamos
                        // De lo contrario agregamos una por defecto de maps
                        url:interaccion.icon,
                        // Tamaño que llevara el icono
                        scaledSize: new google.maps.Size(15, 15),
                        // origin: new google.maps.Point(0,0), // origin
                        // anchor: new google.maps.Point(0, 0) // anchor
                    }
                });
                
                google.maps.event.addListener(interaccion_marker,'click',function(event){
                    usuario_infowindow.setContent(`<div class="card">
                        <div class="card-body">
                            <div><strong>Usuario:</strong> ${interaccion.nombre+" "+interaccion.apellido_paterno+" "+interaccion.apellido_paterno }</div>
                            <div><strong>Fecha:</strong> ${point.punto_interaccion.fecha}</div>
                            <div><strong>Hora:</strong> ${point.punto_interaccion.hora}</div>
                            <div><strong>Distancia:</strong> ${point.distancia} metros</div>
                            <div><strong>Duración:</strong> ${point.punto_interaccion.duracion}</div>
                        </div>
                    </div>`);
                    // Mostramos el infowindow
                    usuario_infowindow.setPosition(event.latLng);
                    usuario_infowindow.open(map);
                })
                
            }
        })(google_mark, point.id));

    google.maps.event.addListener(google_mark, 'mouseover', (function(google_mark, i) {
        // Retornamos una función para insertar el body del innfowindo
        // con informacion del usuario y el punto
            return function() {
                
                distancia_line.setMap(null)
                distancia_line = new google.maps.Polyline({
                    strokeColor: "#3490DC",
                    strokeOpacity: 2.4,
                    strokeWeight: 2.4,
                });
                distancia_line.setMap(map)
                setLine(point.punto_usuario.lat,point.punto_usuario.lng,distancia_line)
                setLine(point.punto_interaccion.lat,point.punto_interaccion.lng,distancia_line)
                interaccion_marker.setMap(null);
                interaccion_marker = new google.maps.Marker({
                    position: new google.maps.LatLng(point.punto_interaccion.lat,point.punto_interaccion.lng),
                    map:map,
                    // Insertamos el icono y el tamaño
                    icon : {
                        // url de la imagen del icono a utilizar
                        // Si esta en el objeto del usuario 360 la utilizamos
                        // De lo contrario agregamos una por defecto de maps
                        url:interaccion.icon,
                        // Tamaño que llevara el icono
                        scaledSize: new google.maps.Size(15, 15),
                        // origin: new google.maps.Point(0,0), // origin
                        // anchor: new google.maps.Point(0, 0) // anchor
                    }
                });
                
                google.maps.event.addListener(interaccion_marker,'click',function(event){
                    usuario_infowindow.setContent(`<div class="card">
                        <div class="card-body">
                            <div><strong>Usuario:</strong> ${interaccion.nombre+" "+interaccion.apellido_paterno+" "+interaccion.apellido_paterno }</div>
                            <div><strong>Fecha:</strong> ${point.punto_interaccion.fecha}</div>
                            <div><strong>Hora:</strong> ${point.punto_interaccion.hora}</div>
                            <div><strong>Distancia:</strong> ${point.distancia} metros</div>
                            <div><strong>Duración:</strong> ${point.punto_interaccion.duracion}</div>
                        </div>
                    </div>`);
                    // Mostramos el infowindow
                    usuario_infowindow.setPosition(event.latLng);
                    usuario_infowindow.open(map);
                })
                
            }
        })(google_mark, point.id));

}

function setLine(latitude, longitude,polyline) {
    const path_line = polyline.getPath()
    path_line.push(new google.maps.LatLng(latitude,longitude))
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {
    for (let i = 0; i < usuarioMarkers.length; i++) {
        usuarioMarkers[i].setMap(map);
    }
    distancia_line.setMap(map);
    interaccion_marker.setMap(map)
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
    setMapOnAll(null);
}