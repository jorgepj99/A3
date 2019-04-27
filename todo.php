<html lang="en">
    <head>
        <title>title</title>
        <link rel="stylesheet" href="/static/css/bootstrap.min.css">
        <link rel="stylesheet" href="/static/css/boostrap-theme.css">
    </head>
    <style>
        .selector-for-some-widget {
            box-sizing: content-box;
          }
    </style>
    <body>
    <?php
        include 'conexion.php';
        session_start();
        if(isset($_SESSION["login"])){
            echo "Benvingut/a " . $_SESSION['login'];
        }else{
            header("Location:index.php");
        }
        if(isset($_POST["sessio"])){
            session_destroy();
            header("Location:index.php");
        }
        if(isset($_POST["descripcion"])){
            $user = mysqli_query($conn,"SELECT id_user FROM usuarios WHERE login ='".$_SESSION['login']."'") or die(error);
            while($row = mysqli_fetch_row($user)){
                $id_user=$row[0];
            }
            mysqli_query($conn,"insert into todo_tasks(descripcion,pendiente,fecha,usuarios_id_user) values('".$_POST["descripcion"]."',1,date(now()),".$id_user.")") or die("<br>error al crear registro");
        }
        if(isset($_POST["borrar"])){
            $sql2="DELETE FROM todo_tasks WHERE id_task=?";
            $stmt2 = mysqli_prepare($conn,$sql2);
            mysqli_stmt_bind_param($stmt2,"i",$_POST["borrar"]);
            mysqli_stmt_execute($stmt2); 
        }
    ?>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
        <input type="submit" name='sessio' value="Tanca sessio" class="btn btn-lg btn-primary btn-block"/>
        </form>
        <h1>Les meves tasques</h1><br><br>
        <h4>Afegir nova tasca</h4><br>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
            <input type="text" name="descripcion" class="form-control input-lg"/><br><br>
            <input type="submit" name='submit' value="Afegir tasca" class="btn btn-lg btn-primary btn-block"/> <br><br>
        </form>
            <h4>Tasques pendents</h4><br>
            
    <?php
        $user = mysqli_query($conn,"SELECT id_user FROM usuarios WHERE login ='".$_SESSION['login']."'") or die(error);
        while($row = mysqli_fetch_row($user)){
            $result = mysqli_query($conn,"SELECT id_task,descripcion,pendiente FROM todo_tasks WHERE usuarios_id_user =".$row[0]);
        }
        echo "<table border = '1'> \n";
        echo "<tr><td>Id de la tasca</td><td>Descripcio</td><td>Estat</td><td>Eliminar</td></tr>";
        while($row = mysqli_fetch_row($result)){
            echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>";
            if($row[2]==1){
                echo "Pendiente</td><td>";
            }else{
                echo "Realizada</td><td>";
            }
            echo "<form action='".$_SERVER['PHP_SELF']."' method='POST'><input name='borrar' value='".$row[0]."' hidden/><input type='submit' value='Eliminar tasca'/></form></tr>";
        }
        echo "</table> \n";
    ?>  
<script src="static/js/bootstrap.js"></script>
    </body>
</html>