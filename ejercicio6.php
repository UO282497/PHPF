<!DOCTYPE HTML>

<html lang="es">

<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
    <title>Ejercicio6</title>
    <!--Metadatos de los documentos HTML5-->
    <meta name="author" content="Sergio" />
    <meta name="description" content="Ejercicio6" />

    <!--Definición de la ventana gráfica-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


    <!-- añadir el elemento link de enlace a la hoja de estilo dentro del <head> del documento html -->
    <link rel="stylesheet" type="text/css" href="ejercicio6.css" />
    <?php
    session_start();
    class BaseDatos
    {

        protected $string = "";
        public function __construct()
        {

        }
        public function crearbd()
        {
            $servername = "localhost";
            $username = "DBUSER2022";
            $password = "DBPSWD2022";


            $db = new mysqli($servername, $username, $password);


            if ($db->connect_error) {
                exit("<p>ERROR de conexión:" . $db->connect_error . "</p>");
            } else {
                $this->string = "<p>Conexión establecida con " . $db->host_info . "</p>";
            }


            $cadenaSQL = "CREATE DATABASE IF NOT EXISTS SEWPHP COLLATE utf8_spanish_ci";
            if ($db->query($cadenaSQL) === TRUE) {
                $this->string = "<p>Base de datos 'SEWPHP' creada con éxito</p>";
            } else {
                $this->string = "<p>ERROR en la creación de la Base de Datos 'SEWPHP'. Error: " . $db->error . "</p>";
                exit();
            }

            $db->close();
        }
        public function create()
        {
            $servername = "localhost";
            $username = "DBUSER2022";
            $password = "DBPSWD2022";
            $database = "SEWPHP";


            $db = new mysqli($servername, $username, $password);

            //selecciono la base de datos AGENDA para utilizarla
            $db->select_db($database);

            // se puede abrir y seleccionar a la vez
            //$db = new mysqli($servername,$username,$password,$database);
    
            //Crear la tabla persona DNI, Nombre, Apellido
            $crearTabla = "CREATE TABLE IF NOT EXISTS PruebasUsabilidad (dni INT NOT NULL AUTO_INCREMENT, 
                        nombre VARCHAR(255) NOT NULL, 
                        apellidos VARCHAR(255) NOT NULL, 
                        email VARCHAR(255) NOT NULL, 
                        telefono VARCHAR(255) NOT NULL,  
                        edad INT NOT NULL, 
                        sexo VARCHAR(255) NOT NULL, 
                        pericia INT NOT NULL, 
                        tiempo INT NOT NULL, 
                        exito BIT NOT NULL, 
                        comentarios VARCHAR(255) NOT NULL, 
                        propuestas VARCHAR(255) NOT NULL, 
                        valoracion INT NOT NULL, 
                        PRIMARY KEY (dni))";

            if ($db->query($crearTabla) === TRUE) {
                $this->string = "<p>Tabla 'PruebasUsabilidad' creada con éxito </p>";
            } else {
                $this->string = "<p>ERROR en la creación de la tabla PruebasUsabilidad. Error : " . $db->error . "</p>";
                exit();
            }
            //cerrar la conexión
            $db->close();

        }

        public function insert()
        {

            $servername = "localhost";
            $username = "DBUSER2022";
            $password = "DBPSWD2022";
            $database = "SEWPHP";

            // Conexión al SGBD local con XAMPP con el usuario creado 
            $db = new mysqli($servername, $username, $password, $database);


            // comprueba la conexion
            if ($db->connect_error) {
                exit("<h2>ERROR de conexión:" . $db->connect_error . "</h2>");
            } else {
                $this->string = "<h2>Conexión establecida</h2>";
            }

            //prepara la sentencia de inserción
            $consultaPre = $db->prepare("INSERT INTO PruebasUsabilidad (dni, nombre, apellidos, email, telefono, edad, 
            sexo, pericia, tiempo, exito, comentarios, propuestas, valoracion) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");

            $consultaPre->bind_param(
                'ssssiisiiisss',
                $_POST["id"]
                , $_POST["nombre"]
                , $_POST["apellidos"]
                , $_POST["email"]
                , $_POST["telefono"]
                , $_POST["edad"]
                , $_POST["sexo"]
                , $_POST["pericia"]
                , $_POST["tiempo"]
                , $_POST["exito"]
                , $_POST["comentarios"]
                , $_POST["propuestas"]
                , $_POST["valoracion"]

            );

            //ejecuta la sentencia
            $consultaPre->execute();

            //muestra los resultados
            $this->string = "<p>Filas agregadas: " . $consultaPre->affected_rows . "</p>";

            $consultaPre->close();

            //cierra la base de datos
            $db->close();

        }

        public function select()
        {

            $servername = "localhost";
            $username = "DBUSER2022";
            $password = "DBPSWD2022";
            $database = "SEWPHP";

            // Conexión al SGBD local. En XAMPP el usuario debe estar creado previamente 
            $db = new mysqli($servername, $username, $password, $database);

            // compruebo la conexion
            if ($db->connect_error) {
                exit("<p>ERROR de conexión:" . $db->connect_error . "</p>");
            } else {
                $this->string = "<p>Conexión establecida con " . $db->host_info . "</p>";
            }

            //consultar la tabla persona
            $resultado = $db->prepare('SELECT * FROM PruebasUsabilidad WHERE dni = ?');

            $resultado->bind_param(
                's',
                $_POST["idcon"]


            );
            $resultado->execute();
            $res = $resultado->get_result();



            // compruebo los datos recibidos     
            if ($res->num_rows > 0) {
                // Mostrar los datos en un lista
                $this->string = "<p>Los datos en la tabla 'PruebasUsabilidad' son: </p>";
                $this->string .= "<p>Número de filas = " . $res->num_rows . "</p>";
                $this->string .= "<ul>";
                $this->string .= "<li>" . 'nombre' . " - " . 'apellidos' . " - " . 'email' .
                    " - " . 'telefono' . " - " . 'edad' . " - " . 'sexo' . " - " . 'pericia' .
                    " - " . 'tiempo' . " - " . 'exito' . " - " . 'comentarios' . " - " . 'propuestas' .
                    " - " . 'valoracion' . "</li>";
                while ($row = $res->fetch_assoc()) {
                    $this->string .= "<li>" . $row['nombre'] . " - " . $row['apellidos'] . " - " . $row['email'] .
                        " - " . $row['telefono'] . " - " . $row['edad'] . " - " . $row['sexo'] . " - " . $row['pericia'] .
                        " - " . $row['tiempo'] . " - " . $row['exito'] . " - " . $row['comentarios'] . " - " . $row['propuestas'] .
                        " - " . $row['valoracion'] .
                        "</li>";
                }
                $this->string .= "</ul>";
            } else {
                $this->string = "<p>Tabla vacía. Número de filas = " . $res->num_rows . "</p>";
            }
            //cerrar la conexión
            $db->close();
        }
        public function update()
        {
            $servername = "localhost";
            $username = "DBUSER2022";
            $password = "DBPSWD2022";
            $database = "SEWPHP";

            // Conexión al SGBD local con XAMPP con el usuario creado 
            $db = new mysqli($servername, $username, $password, $database);


            // comprueba la conexion
            if ($db->connect_error) {
                exit("<h2>ERROR de conexión:" . $db->connect_error . "</h2>");
            } else {
                $this->string = "<h2>Conexión establecida</h2>";
            }

            //prepara la sentencia de inserción
            $consultaPre = $db->prepare("UPDATE PruebasUsabilidad SET  nombre=?, apellidos=?, email=?, telefono=?, edad=?, 
            sexo=?, pericia=?, tiempo=?, exito=?, comentarios=?, propuestas=?, valoracion=? WHERE dni=? ");

            $consultaPre->bind_param(
                'ssssiisiiisss',

                $_POST["nombre"]
                , $_POST["apellidos"]
                , $_POST["email"]
                , $_POST["telefono"]
                , $_POST["edad"]
                , $_POST["sexo"]
                , $_POST["pericia"]
                , $_POST["tiempo"]
                , $_POST["exito"]
                , $_POST["comentarios"]
                , $_POST["propuestas"]
                , $_POST["valoracion"]
                , $_POST["id"]

            );

            //ejecuta la sentencia
            $consultaPre->execute();

            //muestra los resultados
            $this->string = "<p>Filas modificadas: " . $consultaPre->affected_rows . "</p>";

            $consultaPre->close();

            //cierra la base de datos
            $db->close();

        }
        public function delete()
        {
            //Versión 1.1 22/Noviembre/2020 Juan Manuel Cueva Lovelle. Universidad de Oviedo
            //datos de la base de datos
            $servername = "localhost";
            $username = "DBUSER2022";
            $password = "DBPSWD2022";
            $database = "SEWPHP";


            // Conexión al SGBD local con el usuario creado previamente en XAMPP
            $db = new mysqli($servername, $username, $password, $database);

            // compruebo la conexion
            if ($db->connect_error) {
                exit("<h2>ERROR de conexión:" . $db->connect_error . "</h2>");
            } else {
                $this->string = "<h2>Conexión establecida</h2>";
            }

            //prepara la consulta
            $consultaPre = $db->prepare("DELETE FROM PruebasUsabilidad WHERE dni = ?");

            //obtiene los parámetros de la variable predefinida $_POST
            // s indica que dni es un string
            $consultaPre->bind_param('s', $_POST["idcon"]);


            //ejecuta la consulta
            $consultaPre->execute();
            if ($consultaPre->affected_rows > 0) {
                $this->string = "<p>Elemento/s borrado/s</p>";
            } else {
                $this->string = "<p>No existen elementos</p>";
            }


            //cerrar la conexión
            $db->close();
        }
        public function informe()
        {
            $servername = "localhost";
            $username = "DBUSER2022";
            $password = "DBPSWD2022";
            $database = "SEWPHP";

            // Conexión al SGBD local. En XAMPP el usuario debe estar creado previamente 
            $db = new mysqli($servername, $username, $password, $database);

            // compruebo la conexion
            if ($db->connect_error) {
                exit("<p>ERROR de conexión:" . $db->connect_error . "</p>");
            } else {
                $this->string = "<p>Conexión establecida con " . $db->host_info . "</p>";
            }

            //consultar la tabla persona
            $resultado = $db->prepare('SELECT * FROM PruebasUsabilidad');

            $resultado->execute();
            $res = $resultado->get_result();
            $c = 0;
            $d1 = 0; //media edad
            $d2 = 0; //Porcentaje sexo
            $d3 = 0; //media pericia
            $d4 = 0; //media tiempo
            $d5 = 0; //Porcentaje exito
            $d6 = 0; //media valoracion
    



            // compruebo los datos recibidos     
            if ($res->num_rows > 0) {
                // Mostrar los datos en un lista
                $this->string = "<p>Informe de la tabla 'PruebasUsabilidad': </p>";
                while ($row = $res->fetch_assoc()) {
                    $c++;
                    $d1 += $row['edad'];
                    if ($row['sexo'] == "Hombre") {
                        $d2 += 1;
                    }
                    $d3 += $row['pericia'];

                    $d4 += $row['tiempo'];

                    $d5 += $row['exito'];

                    $d6 += $row['valoracion'];


                }
            } else {
                $this->string = "<p>Tabla vacía. Número de filas = " . $res->num_rows . "</p>";
            }
            $this->string .= "<ul>
            <li>Edad media de los usuarios = " . $d1 / $c . "</li>
            <li>Frecuencia del sexo de los usuarios = " . $d2 / $c * 100 . "% de hombres, y " . (100 - $d2 / $c * 100) . "% de mujeres</li>
            <li>Pericia media de los usuarios = " . $d3 / $c . "</li>
            <li>Tiempo medio de los usuarios = " . $d4 / $c . " segundos</li>
            <li>Tasa de éxito de los usuarios = " . ($d5 / $c * 100) . "% de éxito</li>
            <li>Valoración media de los usuarios = " . $d6 / $c . "</li>
            
            </ul>";


            //cerrar la conexión
            $db->close();

        }
        public function importar()
        {

            $servername = "localhost";
            $username = "DBUSER2022";
            $password = "DBPSWD2022";
            $database = "SEWPHP";

            // Conexión al SGBD local. En XAMPP el usuario debe estar creado previamente 
            $db = new mysqli($servername, $username, $password, $database);

            // compruebo la conexion
            if ($db->connect_error) {
                exit("<p>ERROR de conexión:" . $db->connect_error . "</p>");
            } else {
                $this->string = "<p>Conexión establecida con " . $db->host_info . "</p>";
            }

            $fileName = $_FILES["subir"]["tmp_name"];



            if ($_FILES["subir"]["type"] != "text/csv") {
                $this->string = "<p>Archivo con formato incorrecto o no subido.</p>";
                return;
            }



            // $fileName = basename('pruebasUsabilidad.csv');
            $filePath = '' . $fileName;
            if (!empty($fileName) && file_exists($filePath)) {
                $file = fopen($fileName, "r");

                while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
                    $consultaPre = $db->prepare("INSERT INTO PruebasUsabilidad (dni, nombre, apellidos, email, telefono, edad, 
                    sexo, pericia, tiempo, exito, comentarios, propuestas, valoracion) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");

                    $consultaPre->bind_param(
                        'ssssiisiiisss'
                        , $getData[0]
                        , $getData[1]
                        , $getData[2]
                        , $getData[3]
                        , $getData[4]
                        , $getData[5]
                        , $getData[6]
                        , $getData[7]
                        , $getData[8]
                        , $getData[9]
                        , $getData[10]
                        , $getData[11]
                        , $getData[12]

                    );


                    $result = $consultaPre->execute();
                    if (isset($result)) {
                        $this->string = "<p>CSV importado con exito</p>";
                    } else {
                        $this->string = "<p>Error al importar CSV.</p>";
                    }
                }

                fclose($file);
            }
        }
        public function exportar()
        {
            $servername = "localhost";
            $username = "DBUSER2022";
            $password = "DBPSWD2022";
            $database = "SEWPHP";

            $db = new mysqli($servername, $username, $password, $database);

            if ($db->connect_error) {
                exit("<p>ERROR de conexión:" . $db->connect_error . "</p>");
            } else {
                $this->string = "<p>Conexión establecida con " . $db->host_info . "</p>";
            }


            $res = $db->prepare('SELECT * FROM PruebasUsabilidad');

            $res->execute();
            $resultado = $res->get_result();

            if ($resultado) {

                $file = fopen('pruebasUsabilidad.csv', 'w');


                while ($row = mysqli_fetch_assoc($resultado)) {

                    fputcsv($file, $row);


                }
                fclose($file);




            } else {
                $this->string = "<p>Error al exportar CSV.</p>";

                return;
            }


        }

        public function getString()
        {
            return $this->string;
        }





    }
    if (!isset($_SESSION['bd'])) {
        $_SESSION['bd'] = new BaseDatos();
    }
    $bd = $_SESSION['bd'];

    if (count($_POST) > 0) {
        if (isset($_POST['crearbd']))
            $bd->crearbd();
        if (isset($_POST['create']))
            $bd->create();
        if (isset($_POST['insert']))
            $bd->insert();
        if (isset($_POST['select']))
            $bd->select();
        if (isset($_POST['update']))
            $bd->update();
        if (isset($_POST['delete']))
            $bd->delete();
        if (isset($_POST['informe']))
            $bd->informe();
        if (isset($_POST['importar']))
            $bd->importar();
        if (isset($_POST['exportar']))
            $bd->exportar();
        if (isset($_POST['subir']))
            $bd->importar();
    }

    $_SESSION['bd'] = $bd;
    ?>
</head>

<body>
    <h1>Pruebas de usabilidad</h1>
    <nav>
        <h2>Menú de navegación</h2>
        <ul>
            <li><a href="#1" accesskey="A" tabindex="1">Creación de la base de datos</a> </li>
            <li><a href="#2" accesskey="B" tabindex="2">Creación de tabla</a> </li>
            <li><a href="#3" accesskey="C" tabindex="3">Inserción y actualización</a> </li>
            <li><a href="#4" accesskey="D" tabindex="4">Eliminación de elementos o búsqueda</a> </li>
            <li><a href="#5" accesskey="E" tabindex="5">Creación de informe de la tabla</a> </li>
            <li><a href="#6" accesskey="F" tabindex="6">Archivos</a> </li>
        </ul>
    </nav>
    <h2>Panel de opciones</h2>
    <form action='#' method='post' name='preciosoro' enctype='multipart/form-data'>
        <section id="1">
            <h2>Creación de la base de datos</h2>
            <label for='crearbd'>Crear base:</label>
            <input type="submit" id="crearbd" name="crearbd" value="Crear Base de Datos" title="Crear Base de Datos">
        </section>
        <section id="2">
            <h2>Creación de tabla</h2>
            <label for='create'>Crear tabla/s:</label>
            <input type="submit" id="create" name="create" value="Crear una tabla" title="Crear una tabla">
        </section>
        <section id="3">
            <h2>Inserción y actualización</h2>
            <label for='id'>Dni:</label>
            <p> <input type="text" id="id" name="id" /></p>
            <label for='nombre'>Nombre: </label>
            <p><input type="text" id="nombre" name="nombre" /></p>
            <label for='apellidos'>Apellidos: </label>
            <p><input type="text" id="apellidos" name="apellidos" /></p>
            <label for='email'>E-mail: </label>
            <p><input type="text" id="email" name="email" /></p>
            <label for='telefono'>Teléfono: </label>
            <p><input type="number" id="telefono" name="telefono" /></p>
            <label for='edad'>Edad: </label>
            <p><input type="number" id="edad" name="edad" /></p>
            <label for='sexo'>Sexo: </label>
            <p><input type="text" id="sexo" name="sexo" /></p>
            <label for='pericia'>Pericia: </label>
            <p><input type="number" id="pericia" name="pericia" /></p>
            <label for='tiempo'>Tiempo: </label>
            <p><input type="number" id="tiempo" name="tiempo" /></p>
            <label for='exito'>Éxito en completar la tarea (0 no completada, 1 completada):</label>
            <p><input type="number" id="exito" name="exito" max=1 min=0 />
            </p>
            <label for='comentarios'>Comentarios: </label>
            <p><input type="text" id="comentarios" name="comentarios" /></p>
            <label for='propuestas'>Propuestas: </label>
            <p><input type="text" id="propuestas" name="propuestas" /></p>
            <label for='valoracion'>Valoración: </label>
            <p><input type="number" id="valoracion" name="valoracion" max=10 min=0 /></p>
            <input type="submit" id="insert" name="insert" value="Insertar datos en una tabla"
                title="Insertar datos en una tabla">
            <input type="submit" id="update" name="update" value="Modificar datos en una tabla"
                title="Modificar datos en una tabla">
        </section>
        <section id="4">
            <h2>Eliminación de elementos o búsqueda</h2>
            <label for='idcon'>Dni para buscar o eliminar: </label>
            <p><input type="text" id="idcon" name="idcon" /></p>

            <input type="submit" id="delete" name="delete" value="Eliminar datos de una tabla"
                title="Eliminar datos de una tabla">
            <input type="submit" id="select" name="select" value="Buscar datos en una tabla"
                title="Buscar datos en una tabla">
        </section>



        <section id="5">
            <h2>Creación de informe de la tabla</h2>
            <input type="submit" id="informe" name="informe" value="Generar informe" title="Generar informe">
        </section>
        <section id="6">
            <h2>Archivos</h2>
            <input type="submit" id="exportar" name="exportar"
                value="Exportar datos a un archivo en formato CSV los datos desde una tabla de la Base de Datos"
                title="Exportar datos a un archivo en formato CSV los datos desde una tabla de la Base de Datos">

            <label for='subir'>Sube tu CSV para crear las entradas</label>
            <input type='file' id='subir' name='subir' />
            <input type='submit' value='Cargar datos desde un archivo CSV en una tabla de la Base de Datos'
                name='subir' />
        </section>



    </form>
    <main>
        <p>
            <?php echo $bd->getString() ?>
        </p>
    </main>
</body>