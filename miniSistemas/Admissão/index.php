<?php
include_once "conexao.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="https://static.wixstatic.com/media/157734_1e57fd2b60d2447d9dcd9d5613ac2f38.png/v1/fill/w_208,h_95,al_c,q_85,usm_0.66_1.00_0.01/157734_1e57fd2b60d2447d9dcd9d5613ac2f38.webp" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../newstyle.css">
    <title>Sistemas</title>
</head>
<body>

<!--Menu Principal-->
<nav class="navbar navbar-light" style="background-color: #3c4e71;">
<div class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        
      <li class="nav-item">
          <a class="nav-link" href="../Admissão">Admissão</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../Rescisao">Rescisão</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../planoProprio">Plano Proprio</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../FaturaMensalidade">Fatura Mensalidade</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../FaturaServico">Fatura Seviço Unimed</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../dadossocios">Dados Socios</a>
        </li>
      </ul>
    </div>
  </div>
</div>
</nav>

<section>
<form action="?act=save" method="POST" name="form1" >
                <h1>Admissão</h1>
                <hr>
                <input type="hidden" name="id" <?php
                 
                // Preenche o id no campo id com um valor "value"
                if (isset($id) && $id != null || $id != "") {
                    echo "value=\"{$id}\"";
                }
                ?> />
                CPF:
               <input type="text" name="CPF" <?php
 
               // Preenche o nome no campo nome com um valor "value"
               if (isset($CPF) && $CPF != null || $CPF != "") {
                   echo "value=\"{$CPF}\"";
               }
               ?> />
                Nome:
               <input type="text" name="NomeTitular" <?php
 
               // Preenche o nome no campo nome com um valor "value"
               if (isset($NomeTitular) && $NomeTitular != null || $NomeTitular != "") {
                   echo "value=\"{$NomeTitular}\"";
               }
               ?> />
                Observação:
               <input type="text" name="Observacao" <?php
 

               if (isset($Observacao) && $Observacao != null || $Observacao != "") {
                   echo "value=\"{$Observacao}\"";
               }
               ?> />
                Fatura:
               <input type="text" name="Fatura" <?php
 
    
               if (isset($Fatura) && $Fatura != null || $Fatura != "") {
                   echo "value=\"{$Fatura}\"";
               }
               ?> />
               <input type="submit" value="salvar" />
               <hr>
            </form>
            <table border="1" width="100%">
                <tr>
                    <th>CPF</th>
                    <th>NomeTitular</th>
                    <th>Observação</th>
                    <th>Fatura</th>
                    <th>Ações</th>
                </tr>
                <?php
 
                // Bloco que realiza o papel do Read - recupera os dados e apresenta na tela
                try {
                    $stmt = $connection->prepare("SELECT * FROM dbo.DadosAdmissao");
                    if ($stmt->execute()) {
                        while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                            echo "<tr>";
                            echo "<td>".$rs->CPF."</td><td>".$rs->NomeTitular."</td><td>".$rs->Observacao
                                       ."</td><td>".$rs->Fatura."</td><td><center><a href=\"?act=upd&id=".$rs->id."\">[Alterar]</a>"
                                       ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                       ."<a href=\"?act=del&id=".$rs->id."\">[Excluir]</a></center></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "CPF  ";
                    }
                } catch (PDOException $erro) {
                    echo "Erro: ".$erro->getMessage();
                }
                ?>
            </table>
</section>

<!-- Script bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>