<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); //Para mostrar errores.

 if(file_exists("archivo.txt")){
            $strJson = file_get_contents("archivo.txt");
            $aClientes = json_decode($strJson, true); }
            else    {
                $aClientes = array();
                    }
 
                    if(isset($_GET["id"])){
                        $id = $_GET["id"];
                    } else {
                        $id="";
                    }
                    
                    if(isset($_GET["do"]) && $_GET["do"] == "eliminar"){
                        unset($aClientes[$id]);

                        $strJson = json_encode($aClientes);

                        file_put_contents("archivo.txt", $strJson);
                        header("Location: index.php"); //Para limpiar el URL
                    }
                    if ($_POST) {
                        $dni = $_POST["txtDni"];
                        $nombre = $_POST["txtNombre"];
                        $telefono = $_POST["txtTelefono"];
                        $correo = $_POST["txtCorreo"];
                        $nImagen ="";
                    
                        
                        if ($_FILES["archivo"]["error"] === UPLOAD_ERR_OK) {
                            $nombreAzar = date("Ymdhmsi") . rand(1000, 2000);
                            $archivo_tmp = $_FILES["archivo"]["tmp_name"];
                            $extension = pathinfo($_FILES["archivo"]["name"], PATHINFO_EXTENSION);
                            if($extension == "jpg" || $extension == "png" || $extension == "jpeg"){
                                $nImagen = "$nombreAzar.$extension";
                                move_uploaded_file($archivo_tmp, "imagenes/$nImagen");
                            }
                        }
                    
                        if($id >= 0){
                                
                            if ($_FILES["archivo"]["error"] !== UPLOAD_ERR_OK) {
                                $nImagen = $aClientes[$id]["imagen"];
                             } else { 
                                if(file_exists("imagenes/". $aClientes[$id]["imagen"])){ 
                                    unlink("imagenes/". $aClientes[$id]["imagen"]);
                                }
                             }
                                             
                            $aClientes[$id] = array("dni" => $dni,
                                "nombre" => $nombre,
                                "telefono" => $telefono,
                                "correo" => $correo,
                                "imagen" => $nImagen,
                            );
                        } else{                            
                            $aClientes[] = array("dni" => $dni,
                                "nombre" => $nombre,
                                "telefono" => $telefono,
                                "correo" => $correo,
                                "imagen" => $nImagen,
                            );
                    
                        }
                    
                        
                        $strJson = json_encode($aClientes);                       
                        file_put_contents("archivo.txt", $strJson);
                    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABM Clientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome/css/fontawesome.min.css">
</head>
<body>
    <main class="container">
        <div class="row">
            <div class="col-12 my-5 text-center">
                <h1>Registro de Clientes</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div>
                        <label for="txtDni">DNI: </label>
                        <input type="text" name="txtDni" id="txtDni" class="form-control" required value="<?php echo isset($aClientes[$id])? $aClientes[$id]["dni"] : ""; ?>">  
                    </div>
                    <div>
                        <label for="txNombre">Nombre: </label>
                        <input type="text" name="txtNombre" id="txtNombre" class="form-control" required value="<?php echo isset($aClientes[$id]) ? $aClientes[$id]["nombre"] : ""; ?>">  
                    </div>
                    <div>
                        <label for="txtTelefono">Telefono: </label>
                        <input type="text" name="txtTelefono" id="txtTelefono" class="form-control" required value="<?php echo isset($aClientes[$id]) ? $aClientes[$id]["telefono"] : "";?>">  
                    </div>
                    <div>
                        <label for="txtCorreo">Correo: </label>
                        <input type="email" name="txtCorreo" id="txtCorreo" class="form-control" required value="<?php echo isset($aClientes[$id]) ? $aClientes[$id]["correo"] : "";?>">  
                    </div>
                    <div class="py-3">
                        <label for="">Archivo adjunto: </label>
                        <input type="file" name="archivo" id="archivo" accept=".jpg .jpeg .png">  
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary m-1"> GUARDAR </button>
                        <button type="submit" class="btn btn-danger my-2"> NUEVO </button>
                    </div>
                </form>
            </div>
            <div class="col-6">
                <table class="table table-hover border">
                        <tr>
                            <th>Imagen</th>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Telefono</th>
                            <th>Correo</th>
                            <th>Acciones</th>
                        </tr> 
                        <?php foreach($aClientes as $pos => $cliente): ?>
                            <tr>
                                <td><img src="imagenes/<?php echo $cliente["imagen"]; ?>" class="img-thumbnail"></td>
                                <td><?php echo $cliente["dni"]; ?></td>
                                <td><?php echo $cliente["nombre"]; ?></td>
                                <td><?php echo $cliente["telefono"]; ?></td>
                                <td><?php echo $cliente["correo"]; ?></td>
                                <td>
                                <a href="?id=<?php echo $pos; ?>"><i class="fa-solid fa-user-pen"></a></i>
                                <a href="?id=<?php echo $pos; ?>&do=eliminar"><i class="fa-solid fa-trash-can"></i></a> 
                                </td>
                            </tr>
                        <?php endforeach; ?> 
                </table>
            </div>
        </div>
    </main>
</body>
</html>