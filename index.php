<?php
// Conectar ao banco de dados (substitua com suas informações)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
    $acao = "";
    //Verificar se o parâmetro ACAO existe na URL
    if(isset($_REQUEST["acao"])) {
        $acao = $_REQUEST["acao"];
    }

if ($acao == "incluirpessoa") {
    // Receber os dados do formulário
    $rm = $_POST['pesRM'];
    $senha = base64_encode(sha1(md5($_POST['pesenha'])));

    // Inserir os dados no banco de dados
    $sql = "INSERT INTO usuarios (pesRM, pesenha) VALUES ('$rm', '$senha')";

    if ($conn->query($sql) === TRUE) {
        echo "Dados inseridos com sucesso";
    } else {
        echo "Erro ao inserir dados: " . $conn->error;
    }

} elseif ($acao == "logar") {
    // Receber os dados do formulário
    $rm = $_POST['rm'];
    $senha = base64_encode(sha1(md5($_POST['senha'])));

    // Buscar o usuário no banco de dados
    $sql = "SELECT * FROM usuarios WHERE pesRM = '$rm'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Usuário encontrado, verificar a senha
        $row = $result->fetch_assoc();
        $senhaCriptografada = $row['pesenha'];

        if (password_verify($senha, $senhaCriptografada)) {
            // Senha correta, login bem-sucedido
            echo "Login bem-sucedido";
        } else {
            // Senha incorreta
            echo "Senha incorreta";
        }
    } else {
        // Usuário não encontrado
        echo "Usuário não encontrado";
    }
} elseif ($acao == "alugarhoradaestrela") {
    $data_locacao = $_POST['data_locacao_horada'];
    $nome_livro = $_POST['nome_livro'];

    // Calcular a data de devolução (adicionando uma semana)
    $data_devolucao = date('Y/m/d', strtotime($data_locacao . ' + 7 days'));

    // Inserir os dados no banco de dados
    $sql = "INSERT INTO emprestimos (DataEmprestimo, Datadevolucao, Livro) VALUES ('$data_locacao', '$data_devolucao', '$nome_livro')";

    if ($conn->query($sql) === TRUE) {
        echo "Empréstimo registrado com sucesso";
    } else {
        echo "Erro ao registrar empréstimo: " . $conn->error;
    }
} elseif ($acao == "alugardomcas") {
    $data_locacao = $_POST['data_locacao_domcas'];
    $nome_livro = $_POST['nome_livro'];

    // Calcular a data de devolução (adicionando uma semana)
    $data_devolucao = date('Y/m/d', strtotime($data_locacao . ' + 7 days'));

    // Inserir os dados no banco de dados
    $sql = "INSERT INTO emprestimos (DataEmprestimo, Datadevolucao, Livro) VALUES ('$data_locacao', '$data_devolucao', '$nome_livro')";

    if ($conn->query($sql) === TRUE) {
        echo "Empréstimo registrado com sucesso";
    } else {
        echo "Erro ao registrar empréstimo: " . $conn->error;
    }
} 

  elseif ($acao == "alugarharry") {
    $data_locacao = $_POST['data_locacao_harry'];
    $nome_livro = "horadaestrela";

    // Calcular a data de devolução (adicionando uma semana)
    $data_devolucao = date('Y/m/d', strtotime($data_locacao . ' + 7 days'));

    // Verificar se já existe um empréstimo com as mesmas datas e mesmo livro
    $verificar_sql = "SELECT * FROM emprestimos WHERE data_locacao = '$data_locacao' AND data_devolucao = '$data_devolucao' AND nome_livro = '$nome_livro'";
    $resultado_verificacao = $conn->query($verificar_sql);

    if ($resultado_verificacao->num_rows > 0) {
        echo "Neste dia o livro já foi alugado, escolha outro dia!";
    } else {
        // Inserir os dados no banco de dados
        $sql = "INSERT INTO emprestimos (DataEmprestimo, Datadevolucao, Livro) VALUES ('$data_locacao', '$data_devolucao', '$nome_livro')";

        if ($conn->query($sql) === TRUE) {
            echo "Empréstimo registrado com sucesso";
        } else {
            echo "Erro ao registrar empréstimo: " . $conn->error;
        }
    }
}
  // Receber o ID do usuário do aplicativo
  $user_id = $_POST['user_id'];  // Certifique-se de passar o ID do usuário ao fazer a solicitação no Kodular

  // Buscar os dados do usuário no banco de dados
  $sql = "SELECT * FROM usuarios WHERE ID = '$user_id'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Usuário encontrado, enviar os dados como resposta JSON
    $row = $result->fetch_assoc();
    echo json_encode($row);
  } else {
    // Usuário não encontrado
    echo "Usuário não encontrado";
}

// Fechar a conexão
$conn->close();
?>
