<?php include("../template/cabecera.php"); ?>
<?php 


$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtMarca=(isset($_POST['txtMarca']))?$_POST['txtMarca']:"";
$txtUbicacion=(isset($_POST['txtUbicacion']))?$_POST['txtUbicacion']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";


include("../config/bd.php");

switch($accion){

    case "Agregar":

        
        $sentenciaSQL= $conexion->prepare("INSERT INTO catalogo (marca, ubicacion, imagen) VALUES (:marca,:ubicacion,:imagen);");
        $sentenciaSQL->bindParam(':marca',$txtMarca);
        $sentenciaSQL->bindParam(':ubicacion',$txtUbicacion);

        $fecha=new DateTime();
        $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

        $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

        if($tmpImagen!=""){

            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

        }

        $sentenciaSQL->bindParam(':imagen',$nombreArchivo);

        $sentenciaSQL->execute();


        echo "Presionado botón agregar";
        break;

    case "Modificar":

        $sentenciaSQL= $conexion->prepare("UPDATE  catalogo SET marca=:marca WHERE id=:id");
        $sentenciaSQL->bindParam(':marca',$txtMarca);
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();

        $sentenciaSQL= $conexion->prepare("UPDATE  catalogo SET ubicacion=:ubicacion WHERE id=:id");
        $sentenciaSQL->bindParam(':ubicacion',$txtUbicacion);
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();

        if($txtImagen!=""){

            $fecha=new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

            $sentenciaSQL= $conexion->prepare("SELECT imagen FROM catalogo WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $punto=$sentenciaSQL->fetch(PDO::FETCH_LAZY);


            if(isset($punto["imagen"]) && ($punto["imagen"]!="imagen.jpg") ){

                if(file_exists("../../img/".$punto["imagen"])){

                    unlink("../../img/".$punto["imagen"]);
                }

            }


            $sentenciaSQL= $conexion->prepare("UPDATE  catalogo SET imagen=:imagen WHERE id=:id");
            $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
        }



        echo "Presionado botón Modificar";
        break;

    case "Cancelar":
        echo "Presionado botón Cancelar";
        break;

    case "Seleccionar":

        $sentenciaSQL= $conexion->prepare("SELECT * FROM catalogo WHERE id=:id");
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();
        $punto=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtMarca=$punto['marca'];
        $txtUbicacion=$punto['ubicacion'];
        $txtImagen=$punto['imagen'];
        //echo "Presionado botón Seleccionar";
        break;

    case "Borrar":

        $sentenciaSQL= $conexion->prepare("SELECT imagen FROM catalogo WHERE id=:id");
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();
        $punto=$sentenciaSQL->fetch(PDO::FETCH_LAZY);


        if(isset($punto["imagen"]) && ($punto["imagen"]!="imagen.jpg") ){

            if(file_exists("../../img/".$punto["imagen"])){

                unlink("../../img/".$punto["imagen"]);
            }

        }

        $sentenciaSQL= $conexion->prepare("DELETE FROM catalogo WHERE id=:id");
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();
        //echo "Presionado botón Borrar";
        break;
}

$sentenciaSQL= $conexion->prepare("SELECT * FROM catalogo");
$sentenciaSQL->execute();
$listaCatalogo=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="col-md-5">
   
    <div class="card">
        <div class="card-header">
            Datos de puntos de experiencia
        </div>
            <div class="card-body">

                <form method="POST" enctype="multipart/form-data" >

                    <div class = "form-group">
                    <label for="txtID">ID</label>
                    <input type="text" class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID">
                    </div>

                    <div class = "form-group">
                    <label for="txtMarca">Marca</label>
                    <input type="text" class="form-control" value="<?php echo $txtMarca; ?>" name="txtMarca" id="txtMarca" placeholder="Nombre de la marca">
                    </div>

                    <div class = "form-group">
                    <label for="txtUbicacion">Ubicación</label>
                    <input type="text" class="form-control" value="<?php echo $txtUbicacion; ?>" name="txtUbicacion" id="txtUbicacion" placeholder="Ubicacion de la marca">
                    </div>

                    <div class = "form-group">
                    <label for="txtImagen">Imagen</label>

                    <br/>

                    <?php 
                        if($txtImagen!=""){
                        
                    ?>

                        <img src="../../img/<?php echo $txtImagen; ?>"width="50" alt="">          

                    <?php

                        }

                    ?>




                    <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Foto del punto de experiencia">
                    </div>

                        
                            <button type="submit" name="accion" value="Agregar" class="btn btn-success">Agregar</button>
                            <button type="submit" name="accion" value="Modificar" class="btn btn-warning">Modificar</button>
                            <button type="submit" name="accion" value="Cancelar" class="btn btn-info">Cancelar</button>
                        

                </form>
            
            </div>
        
    </div>

    
    
    
</div>
<div class="col-md-7">
    
</div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Marca</th>
                <th>Ubicacion</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($listaCatalogo as $punto) { ?>
            <tr>
                <td><?php echo $punto['id'] ?></td>
                <td><?php echo $punto['marca'] ?></td>
                <td><?php echo $punto['ubicacion'] ?></td>


                <td>
                    
                <img src="../../img/<?php echo $punto['imagen']; ?>"width="50" alt="">

                
            
                </td>

                <td>
                    
            

                <form method="post">

                    <input type="hidden" name="txtID" id="txtID" value= "<?php echo $punto['id']; ?>"/>

                    <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>
                    
                    <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>

                </form>
            
                </td>

            </tr>
            <?php } ?>
        </tbody>
    </table>

<?php include("../template/pie.php"); ?>