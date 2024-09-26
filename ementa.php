<?php
session_start();
require_once "lib/base-dados.php";

// ementas da base de dados
$ementas = $bd->select("ementas", "*");

// meses em português
$mesesPortugues = [
    '01' => 'Janeiro',
    '02' => 'Fevereiro',
    '03' => 'Março',
    '04' => 'Abril',
    '05' => 'Maio',
    '06' => 'Junho',
    '07' => 'Julho',
    '08' => 'Agosto',
    '09' => 'Setembro',
    '10' => 'Outubro',
    '11' => 'Novembro',
    '12' => 'Dezembro'
];

// mês e ano atuais
$mesAtual = date('m');
$anoAtual = date('Y');
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
                        <a href="#">Ementa</a>
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
            <h2>Ementas Disponíveis</h2>
            <div class="row">
                <?php if ($ementas) { ?>
                    <?php foreach ($ementas as $ementa) { ?>
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <?php 
                                        list($mes, $ano) = explode('-', $ementa['mes']);
                                        echo $mesesPortugues[$mes] . " $ano"; 
                                        if ($mes == $mesAtual && $ano == $anoAtual) {
                                            echo "<span class='indicator'>EM VIGOR</span>";
                                        }
                                        ?>
                                    </h5>
                                    <div>
                                        <a href="<?php echo $ementa['file_path']; ?>" target="_blank" class="btn btn-download">
                                            <i class="material-icons">file_download</i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="alert alert-warning">Nenhuma ementa disponível.</p>
                <?php } ?>
            </div>
        </div>

        <footer class="d-flex footer mt-auto">
            <p class="col-md-4 texto">Agrupamento de Escolas Queluz-Belas</p>
            <a href="https://www.espan.edu.pt" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <img src="images/logo-espan.png" alt="aeqb" class="logo" width="140">
            </a>
            <ul class="nav col-md-4 footer-links">
                <li class="nav-item"><a href="dashboard.php" class="nav-link texto">Home</a></li>
                <li class="nav-item"><a href="minha-reserva.php" class="nav-link texto">Minha Reserva</a></li>
                <li class="nav-item"><a href="#" class="nav-link texto">Ementa</a></li>
            </ul>
        </footer>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>
