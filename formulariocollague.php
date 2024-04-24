<?php require_once "menu.php";?>
<!DOCTYPE html>
<html>
<head>
    <title>Subir Archivos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #3498db, #8e44ad); /* Fondo degradado */
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        form {
            max-width: 500px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="file"] {
            margin-bottom: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            color: black;
        }

        input[type="submit"],
        button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover,
        button:hover {
            background-color: #45a049;
        }

        video {
            display: block;
            margin: 0 auto 10px;
            max-width: 100%;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .collage {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .thumbnail {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin: 5px;
            border-radius: 5px;
        }

        .hidden {
            display: none;
        }

        #verGaleriaBtn {
            width: auto;
            margin: 20px auto;
            display: block;
            background-color: #007bff;
        }

        #verGaleriaBtn:hover {
            background-color: #0056b3;
        }

        .alert {
            padding: 15px;
            background-color: #f44336;
            color: white;
            border-radius: 5px;
            margin-bottom: 10px;
            text-align: center;
            display: none;
        }
    </style>
</head>
<body>

<h2>Subir Imágenes</h2>

<form action="create_collage.php" method="post" enctype="multipart/form-data" onsubmit="return validarArchivos()">
    <div class="alert" id="alerta">Debes seleccionar al menos una imagen.</div>
    <label for="archivo">Selecciona imágenes para subir:</label>
    <input type="file" name="archivo[]" id="archivo" accept="image/*" multiple onchange="previewFiles(event)">
    <br>
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" id="nombre" value="">
    <label for="descripcion">Descripción:</label>
    <input type="text" name="descripcion" id="descripcion">
    <br>
    <input type="text" name="correo" id="correo" value="<?php echo $correoUsuario; ?>" style="display: none;">
    <div id="collage" class="hidden collage"></div>
    <br>
    <input type="submit" value="Guardar Collague" name="submit">  
</form>

<button id="verGaleriaBtn" onclick="verGaleria()">Ver mis collagues</button>

<script>
    function previewFiles(event) {
        var collage = document.getElementById('collage');
        collage.innerHTML = '';

        var files = event.target.files;

        for (var i = 0; i < files.length; i++) {
            (function(file) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var mediaType = file.type.split('/')[0];
                    var mediaElement;

                    if (mediaType === 'image') {
                        mediaElement = document.createElement('img');
                        mediaElement.classList.add('thumbnail');
                        mediaElement.src = e.target.result;
                        collage.appendChild(mediaElement);
                    }
                };

                reader.readAsDataURL(file);
            })(files[i]);
        }

        collage.classList.remove('hidden');
    }

    function verGaleria() {
        window.location.href = "verCollages.php"; // Cambiar "verImagenes.php" por la ruta correcta de tu galería
    }

    function validarArchivos() {
        var archivos = document.getElementById('archivo').files;
        var alerta = document.getElementById('alerta');

        if (archivos.length === 0) {
            alerta.style.display = 'block';
            return false;
        } else {
            alerta.style.display = 'none';
            return true;
        }
    }
</script>

</body>
</html>
