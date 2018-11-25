﻿<html>
    <head>
        <title> List </title>
        <link rel="stylesheet" href="style.css"/>
        <link rel="icon" type="image/png" href="Postgresql.png"/>
    </head>
    <body>
        <center>
            <h1 id="sgbd">Sistema de Gestão de Incêndios Florestais</h1>
        </center>
<?php

    function printQuery($result,$name) {
        echo("<table border='5'>");

        if ($name == "Processo") {
            echo("<tr><th>Numero Processo Socorro</th></tr>\n");
        }
        else if ($name == "Local") {
            echo("<tr><th>Morada Local</th></tr>\n");
        }
        else if ($name == "Evento") {
            echo("<tr><th>Numero Telefone</th><th>Instante Chamada</th><th>Nome Pessoa</th><th>Morada Local</th><th>Numero Processo Socorro</th></tr>\n");
        }
        else if ($name == "Meio") {
            echo("<tr><th>Numero Meio</th><th>Nome Meio</th><th>Nome Entidade</th></tr>\n");
        }
        else if ($name == "MeioCombate") {
            echo("<tr><th>Numero Meio</th><th>Nome Meio</th><th>Nome Entidade</th></tr>\n");
        }
        else if ($name == "MeioApoio") {
            echo("<tr><th>Numero Meio</th><th>Nome Meio</th><th>Nome Entidade</th></tr>\n");
        }
        else if ($name == "MeioSocorro") {
            echo("<tr><th>Numero Meio</th><th>Nome Meio</th><th>Nome Entidade</th></tr>\n");
        }
        else if ($name == "Entidade") {
            echo("<tr><th>Nome Entidade</th></tr>\n");
        }
        else if ($name == "Meio_Acc_Proc") {
            echo("<tr><th>Numero Meio</th><th>Nome Entidade</th><th>Numero Processo Socorro</th></tr>\n");
        }
        else if ($name == "MeioSocorro_Proc_Local") {
            echo("<tr><th>Numero Telefone</th><th>Instante de chamada</th><th>Nome Pessoa</th><th>Morada Local</th><th>Numero Processo Socorro</th><th>Numero de Meio</th><th>Nome Entidade</th></tr>\n");
        }
        

        $result->setFetchMode(PDO::FETCH_ASSOC);
        while($row = $result->fetch()){ 
            echo("<tr>"); 
            foreach($row as $key=>$val) { 
                echo("<td> $val </td>\n"); 
            }
            echo("</tr>");
        }
        echo("</table>");
    }

    $table = $_REQUEST['table'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($table == "Meio_Acc_Proc"){
            $numProcesso = input($_POST["numprocessosocorro"]);
        }

        else if ($table == "MeioSocorro_Proc_Local"){
            $moradaLocal = input($_POST["moradalocal"]);
        }
    }

    function input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    if ($table == "Meio_Acc_Proc"){ ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); echo "?table=Meio_Acc_Proc";?>">
          Numero Processo Socorro: <input type="text" name="numprocessosocorro"/>
          <br>
          <input type="submit" name="submit" value="Submit">
        </form>
    <?php
    }

    else if ($table == "MeioSocorro_Proc_Local"){ ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); echo "?table=MeioSocorro_Proc_Local";?>">
          Morada: <input type="text" name="moradalocal"/>
          <br>
          <input type="submit" name="submit" value="Submit">
        </form>
    <?php
    }


    try 
    {
        $user="istxxxxxx";		// -> replace by the user name
        $host="db.ist.utl.pt";	        // -> server where postgres is running
        $port=5432;			// -> default port where Postgres is installed
        $password="pass";	        // -> replace with the password
        $dbname = $user;		// -> by default the name of the database is the name of the user
        
        $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($table == "MeioAccProc"){
        if ($numProcesso == ""){
            $sql = "select * from Acciona;";
        }
        else{
            $sql = "select * from Acciona where numProcessoSocorro='$numProcesso';";
        }
        $result = $db->prepare($sql);
        $result->execute();

        printQuery($result, $table);
    }

    if ($table == "MeioSocorroProcLocal"){
        if ($moradaLocal == ""){
            $sql = "select numTelefone,instanteChamada,nomePessoa,moradaLocal,numProcessoSocorro,numMeio,nomeEntidade from Acciona natural join EventoEmergencia natural join MeioSocorro;";
        }
        else{
            $sql = "select numTelefone,instanteChamada,nomePessoa,moradaLocal,numProcessoSocorro,numMeio,nomeEntidade from EventoEmergencia natural join Acciona natural join MeioSocorro where moradaLocal='$moradaLocal;";
        }
        $result = $db->prepare($sql);
        $result->execute();

        printQuery($result, $table);
    }

    if ($table == "Processo") {

        $sql = "select * from ProcessoSocorro;";
        $result = $db->prepare($sql);

        $result->execute();

        printQuery($result,$table);
    }
    else if ($table == "Local") {

        $sql = "select * from Local;";
        $result = $db->prepare($sql);

        $result->execute();

        printQuery($result,$table);
    }
    else if ($table == "Evento") {

        $sql = "select * from EventoEmergencia;";
        $result = $db->prepare($sql);

        $result->execute();

        printQuery($result,$table);
    }
    else if ($table == "Meio") {

        $sql = "select numMeio, nomeMeio, nomeEntidade from Meio order by nomeEntidade;";
        $result = $db->prepare($sql);

        $result->execute();

        printQuery($result,$table);
    }
    else if ($table == "MeioCombate") {

        $sql = "select numMeio, nomeMeio, nomeEntidade from MeioCombate natural join Meio order by nomeEntidade;";
        $result = $db->prepare($sql);

        $result->execute();

        printQuery($result);
    }

    else if ($table == "MeioApoio") {

        $sql = "select numMeio, nomeMeio, nomeEntidade from MeioApoio natural join Meio order by nomeEntidade;";
        $result = $db->prepare($sql);

        $result->execute();

        printQuery($result,$table);
    }

    else if ($table == "MeioSocorro") {

        $sql = "select numMeio, nomeMeio, nomeEntidade from MeioSocorro natural join Meio order by nomeEntidade;";
        $result = $db->prepare($sql);

        $result->execute();

        printQuery($result,$table);
    }

    else if ($table == "Entidade") {

        $sql = "select * from EntidadeMeio;";
        $result = $db->prepare($sql);

        $result->execute();

        printQuery($result,$table);
    }
    
    else {
        #error message
    }

    $db = null;
} 
catch (PDOException $e)
{
    echo("<p>ERROR: {$e->getMessage()}</p>");
}


?>
    </body>
</html>