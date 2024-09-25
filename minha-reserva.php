<?php
session_start(); // Inicia a sessão

require_once "lib/base-dados.php";

// Verifica se o utilizador está autenticado
if (!isset($_SESSION["autenticado"])) {
    echo "<h1>Acesso negado</h1>";
    exit;
}

$mensagem = '';
$classeMensagem = '';

// cancelar a reserva
if (isset($_POST['cancelar_reserva'])) {
    $bd->delete("reservas", [
        "id" => $_POST['reserva_id']
    ]);
    $mensagem = "Reserva cancelada com sucesso.";
    $classeMensagem = "alert-success";
}

//reserva 
$reserva = $bd->get("reservas", "*", [
    "id_utilizador" => $_SESSION["id"]
]);

?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Reserva</title>

    <link rel="icon" href="images/favicon-espan.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/dashboard-style.css" />
    <link rel="stylesheet" href="css/media-query.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <form action="" method="post" class="d-flex flex-column min-vh-100">
        <header class="header">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md-auto">
                        <img src="images/logo-espan.png" alt="Logótipo" class="img-fluid">
                    </div>
                    <div class="col-md-auto header-links">
                        <a href="dashboard.php">Home</a>
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

        <div class="container mt-5">
            <?php if ($mensagem) { ?>
                <div class="alert <?php echo $classeMensagem; ?>"><?php echo $mensagem; ?></div>
            <?php } ?>
            
            <?php if ($reserva) { ?>
                <div class="form-group-container">
                    <div class="card">
                        <div class="card-body">
                            <div class="details">
                                <i class="material-icons">school</i>
                                <p><strong>Escola:</strong> <?php echo $reserva["escola"]; ?></p>
                            </div>
                            <div class="details">
                                <i class="material-icons">access_time</i>
                                <p><strong>Hora:</strong> <?php echo $reserva["hora"]; ?></p>
                            </div>
                            <div class="details">
                                <i class="material-icons">calendar_today</i>
                                <p><strong>Data:</strong> <?php echo $reserva["data"]; ?></p>
                            </div>
                            <div class="details">
                                <i class="material-icons">info</i>
                                <p><strong>Estado:</strong> <?php echo ucfirst($reserva["status"]); ?></p>
                            </div>
                            <div class="details">
                                <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">
                                <button type="submit" name="cancelar_reserva" class="btn btn-danger" onclick="return confirm('Tem a certeza que deseja cancelar a reserva?');">Cancelar Reserva</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <p class="alert alert-warning">Não há nenhuma reserva ativa.</p>
            <?php } ?>
        </div>

        <footer class="d-flex footer mt-auto">
            <p class="col-md-4 texto">Agrupamento de Escolas Queluz-Belas</p>
            <a href="https://www.espan.edu.pt" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <img src="images/logo-espan.png" alt="aeqb" class="logo" width="140">
            </a>
            <ul class="nav col-md-4 footer-links">
                <li class="nav-item"><a href="dashboard.php" class="nav-link texto">Home</a></li>
                <li class="nav-item"><a href="minha-reserva.php" class="nav-link texto">Minha Reserva</a></li>
                <li class="nav-item"><a href="ementa.php" class="nav-link texto">Ementa</a></li>
            </ul>
        </footer>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
