<?php
// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $CPF = (isset($_POST["CPF"]) && $_POST["CPF"] != null) ? $_POST["CPF"] : "";
    $NomeTitular = (isset($_POST["NomeTitular"]) && $_POST["NomeTitular"] != null) ? $_POST["NomeTitular"] : "";
    $Observacao = (isset($_POST["Observacao"]) && $_POST["Observacao"] != null) ? $_POST["Observacao"] : NULL;
    $Fatura = (isset($_POST["Fatura"]) && $_POST["Fatura"] != null) ? $_POST["Fatura"] : NULL;
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $CPF = NULL;
    $NomeTitular = NULL;
    $Observacao = NULL;
    $Fatura = NULL;
}
 
// Cria a conexão com o banco de dados
try {
    $connection = new PDO("sqlsrv:server=192.168.1.20,1433; Database=SisplusGestao", "jose", "123");
    $statement = $connection->prepare("SELECT * FROM dbo.DadosAdmissao;");
    $statement->execute();
    $SisplusGestao = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
    echo "Erro na conexão:".$erro->getMessage();
}
 
// Bloco If que Salva os dados no Banco - atua como Create e Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $NomeTitular != "") {
    try {
        if ($id != "") {
            $stmt = $connection->prepare("UPDATE dbo.DadosAdmissao SET CPF=?, NomeTitular=?, Observacao=?, Fatura=? WHERE id = ?");
            $stmt->bindParam(5, $id);
        } else {
            $stmt = $connection->prepare("INSERT INTO dbo.DadosAdmissao (CPF, NomeTitular, Observacao, Fatura) VALUES (?, ?, ?, ?)");
        }
        $stmt->bindParam(1, $CPF);
        $stmt->bindParam(2, $NomeTitular);
        $stmt->bindParam(3, $Observacao);
        $stmt->bindParam(4, $Fatura);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $CPF = null;
                $NomeTitular = null;
                $Observacao = null;
                $Fatura = null;
            } else {
                echo "Erro ao tentar efetivar cadastro";
            }
        } else {
               throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}
 
// Bloco if que recupera as informações no formulário, etapa utilizada pelo Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = $connection->prepare("SELECT * FROM dbo.DadosAdmissao WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $CPF = $rs->CPF;
            $NomeTitular = $rs->NomeTitular;
            $Observacao = $rs->Observacao;
            $Fatura = $rs->Fatura;
        } else {
            throw new PDOException("CPF não encontrado no sistema");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
 
// Bloco if utilizado pela etapa Delete
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        $stmt = $connection->prepare("DELETE FROM dbo.DadosAdmissao WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "Registo foi excluído com êxito";
            $id = null;
        } else {
            throw new PDOException("CPF não encontrado no sistema");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
?>