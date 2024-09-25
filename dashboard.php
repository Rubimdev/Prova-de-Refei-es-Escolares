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

    // Verifica se o utilizador já fez uma reserva
    $reservaAlunoExistente = $bd->has("reservas", [
        "num_utilizador" => $_SESSION["num"]
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
                "id_utilizador" => $_SESSION["id"], // ID do utilizador autenticado
                "nome_utilizador" => $_SESSION["nome"],
                "num_utilizador" => $_SESSION["num"],
                "escola" => $escola,
                "hora" => $hora,
                "data" => $data
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

    <!-- ícones e css -->
    <link rel="icon" href="images/favicon-espan.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/dashboard-style.css" />
    <link rel="stylesheet" href="css/media-query.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">

    <!-- Inclui os arquivos JavaScript necessários -->
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
                        <a href="#">Home</a>
                        <div class="vr"></div>
                        <a href="minha-reserva.php">Minha Reserva</a>
                        <div class="vr"></div>
                        <a href="ementa.php">Ementa</a>
                        <div class="vr"></div>
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
        <h1 class="welcome-message">Bem-Vindo <span class="bold-name"><?php echo $_SESSION["nome"]; ?></span></h1>

        <!-- Carrossel de imagens -->
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/fundo-espan2.jpg" class="d-block w-100" alt="Imagem 1">
                </div>
                <div class="carousel-item">
                    <img src="images/fundo-espan3.jpg" class="d-block w-100" alt="Imagem 2">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next"> 
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Seguinte</span>
            </button>
        </div>

        <!-- Formulário de reserva -->
        <div class="form-group-container">
            <?php if (!empty($mensagem)) { ?>
                <div class="alert <?php echo $classeMensagem; ?>"><?php echo $mensagem; ?></div> <!-- Exibir mensagem de sucesso ou erro -->
            <?php } ?>
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group mb-3 w-100">
                        <span class="input-group-text"><i class="material-icons">school</i></span>
                        <select name="escola" id="escola" class="form-control" required>
                            <option value="EB/ES Padre Alberto Neto">EB/ES Padre Alberto Neto</option>
                            <option value="EB Professor Galopim De Carvalho">EB Professor Galopim De Carvalho</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-3 w-100">
                        <span class="input-group-text"><i class="material-icons">access_time</i></span>
                        <select name="hora" id="hora" class="form-control" required>
                            <option value="12:00h ás 12:30h">12:00h ás 12:30h</option>
                            <option value="12:30h ás 13:00h">12:30h ás 13:00h</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group mb-3 w-100">
                        <span class="input-group-text"><i class="material-icons">calendar_today</i></span>
                        <input type="text" id="calendar" name="data" placeholder="Escolher Dia" class="form-control" required pattern="\d{2}/\d{2}/\d{4}"required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="submit">
                        <input type="submit" value="Reservar" class="btn btn-primary w-100" /> <!-- Botão de submeter formulário -->
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
                <li class="nav-item"><a href="#" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="minha-reserva.php" class="nav-link">Minha Reserva</a></li>
                <li class="nav-item"><a href="ementa.php" class="nav-link">Ementa</a></li>
            </ul>
        </footer>
    </form>

    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script> 
</body>
</html>
