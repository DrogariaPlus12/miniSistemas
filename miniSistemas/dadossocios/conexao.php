<?php
// Verificar se foi enviando dados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
    $CPF = (isset($_POST["CPF"]) && $_POST["CPF"] != null) ? $_POST["CPF"] : "";
    $CodigoSocio = (isset($_POST["CodigoSocio"]) && $_POST["CodigoSocio"] != null) ? $_POST["CodigoSocio"] : "";
    $NomeSocio = (isset($_POST["NomeSocio"]) && $_POST["NomeSocio"] != null) ? $_POST["NomeSocio"] :"";
} else if (!isset($id)) {
    // Se não se não foi setado nenhum valor para variável $id
    $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
    $CPF = NULL;
    $CodigoSocio = NULL;
    $NomeSocio = NULL;
}
// Cria a conexão com o banco de dados
try {
    $connection = new PDO("sqlsrv:server=192.168.1.20,1433; Database=SisplusGestao", "jose", "123");
    $statement = $connection->prepare("SELECT * FROM dbo.DadosSocios;");

    $statement->execute();

    $Tabelas = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
    echo "Erro na conexão:".$erro->getMessage();
}
// Bloco If que Salva os dados no Banco - atua como Create e Update
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $CodigoSocio != "") {
    try {
        if ($id != "") {
            $stmt = $connection->prepare("UPDATE dbo.DadosSocios SET CPF=?, CodigoSocio=?, NomeSocio=? WHERE id = ?");
            $stmt->bindParam(4, $id);
        } else {
            $stmt = $connection->prepare("INSERT INTO dbo.DadosSocios (CPF, CodigoSocio, NomeSocio) VALUES (?, ?, ?)");
        }
        $stmt->bindParam(1, $CPF);
        $stmt->bindParam(2, $CodigoSocio);
        $stmt->bindParam(3, $NomeSocio);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "Dados cadastrados com sucesso!";
                $id = null;
                $CPF = null;
                $CodigoSocio = null;
                $NomeSocio = null;
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
        $stmt = $connection->prepare("SELECT * FROM dbo.DadosSocios WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $CPF = $rs->CPF;
            $CodigoSocio = $rs->CodigoSocio;
            $NomeSocio = $rs->NomeSocio;
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
        $stmt = $connection->prepare("DELETE FROM dbo.DadosSocios WHERE id = ?");
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