<?php include("template/cabecera.php"); ?>

<?php include("administrador/config/bd.php");  
$sentenciaSQL= $conexion->prepare("SELECT * FROM catalogo");
$sentenciaSQL->execute();
$listaCatalogo=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>


<?php foreach($listaCatalogo as $punto) { ?>

<div class="col-md-3">
<div class="card">
<img class="card-img-top" src="./img/<?php echo $punto['imagen']; ?>" alt="">
<div class="card-body">
    <h4 class="card-title"><?php echo $punto['marca']; ?></h4>
    <a name="" id="" class="btn btn-primary" href="#" role="button"> Reservar</a>
</div>
</div>
</div>

<?php } ?>



<?php include("template/pie.php"); ?>