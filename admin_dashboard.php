<?php
header('Content-Type: text/html; charset=utf-8');
session_start(); // Inicia a sessão

require_once "lib/base-dados.php"; // Inclui a base de dados

// Verifica se o utilizador está autenticado
if (!isset($_SESSION["autenticado"])) {
    echo "<h1>Acesso negado</h1>";
    exit;
}

$mensagem = ''; // Variável para armazenar a mensagem de feedback
$classeMensagem = ''; // Variável para armazenar a classe da mensagem (sucesso ou erro)

// Verifica se o formulário de reserva foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os valores do formulário
    $escola = $_POST["escola"];
    $hora = $_POST["hora"];
    $data = $_POST["data"];
    $nome_utilizador = $_POST["nome_utilizador"];
    $num_utilizador = $_POST["num_utilizador"];

    // Verifica se o aluno já fez uma reserva
    $reservaAlunoExistente = $bd->has("reservas", [
        "num_utilizador" => $num_utilizador
    ]);

    if ($reservaAlunoExistente) {
        $mensagem = "O aluno já fez uma reserva. Não é possível fazer outra.";
        $classeMensagem = 'alert-danger';
    } else {
        // Verifica se já existe uma reserva para a data e hora selecionadas na mesma escola
        $reservaDataHoraEscolaExistente = $bd->has("reservas", [
            "data" => $data,
            "hora" => $hora,
            "escola" => $escola
        ]);

        // Verifica se já existem duas reservas para a data selecionada na mesma escola
        $totalReservasNaData = $bd->count("reservas", [
            "data" => $data,
            "escola" => $escola
        ]);

        if ($reservaDataHoraEscolaExistente) {
            $mensagem = "Já existe uma reserva para esta hora nesta escola.";
            $classeMensagem = 'alert-danger';
        } elseif ($totalReservasNaData >= 2) {
            $mensagem = "Já existem duas reservas para esta data nesta escola.";
            $classeMensagem = 'alert-danger';
        } else {
            // Insere os dados da reserva na base de dados
            $resultado = $bd->insert("reservas", [
                "escola" => $escola,
                "hora" => $hora,
                "data" => $data,
                "nome_utilizador" => $nome_utilizador,
                "num_utilizador" => $num_utilizador,
            ]);

            // Verifica se a inserção foi bem-sucedida
            if ($resultado->rowCount() > 0) {
                $mensagem = "Reserva feita com sucesso!";
                $classeMensagem = 'alert-success';
            } else {
                $mensagem = "Erro ao fazer a reserva. Tente novamente.";
                $classeMensagem = 'alert-danger';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controlo</title>

    <link rel="icon" href="images/favicon-espan.ico" type="image/x-icon" />

    <link rel="stylesheet" href="css/dashboard-style.css" />
    <link rel="stylesheet" href="css/media-query.css" />

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
    <!-- Formulário de reserva -->
    <form action="" method="post" class="d-flex flex-column min-vh-100">
        <!-- Cabeçalho -->
        <header class="header">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md-auto">
                        <img src="images/logo-espan.png" alt="Logótipo" class="img-fluid">
                    </div>
                    <div class="col-md-auto header-links">
                        <!-- Links -->
                        <a href="#">Home</a>
                        <div class="vr"></div>
                        <a href="reservas-admin.php">Reservas</a>
                        <div class="vr"></div>
                        <a href="ementa-admin.php">Ementa</a>
                        <div class="vr"></div>
                        <!-- Dropdown-->
                        <div class="dropdown d-inline">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownCredenciais" data-bs-toggle="dropdown" aria-expanded="false">
                                Conta
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownCredenciais">
                                <li><a class="dropdown-item" href="logoff.php">Sair</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Formulário -->
        <div class="form-group-container">
            <?php if (!empty($mensagem)) { ?>
                <!-- Mensagem-->
                <div class="alert <?php echo $classeMensagem; ?>"><?php echo $mensagem; ?></div> 
            <?php } ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group mb-3 w-100">
                        <span class="input-group-text"><i class="material-icons">school</i></span>
                        <!-- Escola -->
                        <select name="escola" id="escola" class="form-control" required>
                            <option value="EB/ES Padre Alberto Neto">EB/ES Padre Alberto Neto</option>
                            <option value="EB Professor Galopim De Carvalho">EB Professor Galopim De Carvalho</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-3 w-100">
                        <span class="input-group-text"><i class="material-icons">access_time</i></span>
                        <!-- Hora -->
                        <select name="hora" id="hora" class="form-control" required>
                            <option value="12:00h às 12:30h">12:00h às 12:30h</option>
                            <option value="12:30h às 13:00h">12:30h às 13:00h</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-3 w-100">
                        <span class="input-group-text"><i class="material-icons">calendar_today</i></span>
                        <!-- Data -->
                        <input type="text" id="calendar" name="data" placeholder="Escolher Data" class="form-control"pattern="\d{2}/\d{2}/\d{4}"required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group mb-3 w-100">
                        <span class="input-group-text"><i class="material-icons">person</i></span>
                        <!-- Nome do encarregado de educação -->
                        <input type="text" name="nome_utilizador" placeholder="Nome do Encarregado de Educação" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group mb-3 w-100">
                        <span class="input-group-text"><i class="material-icons">school</i></span>
                        <!-- Número do aluno -->
                        <input type="text" name="num_utilizador" placeholder="Número do Aluno" class="form-control" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="submit">
                        <!-- Botão de submissão -->
                        <input type="submit" id="btn-calendar" value="Reservar" class="btn btn-primary w-100"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rodapé -->
        <footer class="d-flex footer mt-auto">
            <p class="col-md-4 texto">Agrupamento de Escolas Queluz-Belas</p>
            <a href="https://www.espan.edu.pt" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <img src="images/logo-espan.png" alt="aeqb" class="logo" width="140">
            </a>
            <ul class="nav col-md-4 footer-links">
                <!-- Links -->
                <li class="nav-item"><a href="#" class="nav-link texto">Home</a></li>
                <li class="nav-item"><a href="reservas-admin.php" class="nav-link texto">Reservas</a></li>
                <li class="nav-item"><a href="ementa-admin.php" class="nav-link texto">Ementa</a></li>
            </ul>
        </footer>
    </form>

    <!--JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>

