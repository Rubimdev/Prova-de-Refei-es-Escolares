<?php
header('Content-Type: text/html; charset=utf-8');
session_start(); // Inicia a sessão

require_once "lib/base-dados.php"; // base de dados

// Verifica se o utilizador está autenticado
if (!isset($_SESSION["autenticado"])) {
    echo "<h1>Acesso negado</h1>";
    exit;
}

$mensagem = '';
$classeMensagem = '';

// Cancelar reserva
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancelar_reserva'])) {
    $reserva_id = $_POST['reserva_id'];

    $resultado = $bd->delete("reservas", ["id" => $reserva_id]);

    if ($resultado->rowCount() > 0) {
        $mensagem = "Reserva cancelada com sucesso!";
        $classeMensagem = 'alert-success';
    } else {
        $mensagem = "Erro ao cancelar a reserva. Tente novamente.";
        $classeMensagem = 'alert-danger';
    }
}

// Atualizar status reserva
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['num_utilizador']) && isset($_POST['data']) && isset($_POST['status'])) {
    $num_utilizador = $_POST['num_utilizador'];
    $data = $_POST['data'];
    $status = $_POST['status'];

    $resultado = $bd->update("reservas", ["status" => $status], [
        "AND" => [
            "num_utilizador" => $num_utilizador,
            "data" => $data
        ]
    ]);

    if ($resultado->rowCount() > 0) {
        $mensagem = "Status atualizado com sucesso!";
        $classeMensagem = 'alert-success';
    }
}

// filtros vazios
$filtros = [
    "status" => "",
    "num_utilizador" => "",
    "data" => ""
];

// Verificar se há filtros de pesquisa 
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['status'])) {
        $filtros['status'] = $_GET['status'];
    }
    if (isset($_GET['num_utilizador'])) {
        $filtros['num_utilizador'] = $_GET['num_utilizador'];
    }
    if (isset($_GET['data'])) {
        $filtros['data'] = $_GET['data'];
    }
}

// pesquisar
$condicaoPesquisa = [];
if (!empty($filtros['status'])) {
    $condicaoPesquisa["reservas.status"] = $filtros['status'];
}
if (!empty($filtros['num_utilizador'])) {
    $condicaoPesquisa["reservas.num_utilizador"] = $filtros['num_utilizador'];
}
if (!empty($filtros['data'])) {
    $condicaoPesquisa["reservas.data"] = $filtros['data'];
}

// ordenar
$condicaoPesquisa["ORDER"] = ["reservas.data" => "ASC"];

// Obter reservas, aplicar os filtros e ordenando 
$reservas = $bd->select("reservas", [
    "[>]utilizadores" => ["id_utilizador" => "id"]
], [
    "reservas.id",
    "reservas.escola",
    "reservas.hora",
    "reservas.data",
    "reservas.status",
    "reservas.nome_utilizador",
    "reservas.num_utilizador"
], $condicaoPesquisa);
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas - Admin</title>

    <link rel="icon" href="images/favicon-espan.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/dashboard-style.css" />
    <link rel="stylesheet" href="css/media-query.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
</head>
<body>
    <form action="" method="get" class="d-flex flex-column min-vh-100">
        <header class="header">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md-auto">
                        <img src="images/logo-espan.png" alt="Logótipo" class="img-fluid">
                    </div>
                    <div class="col-md-auto header-links">
                        <a href="admin_dashboard.php">Home</a>
                        <div class="vr"></div>
                        <a href="#">Reservas</a>
                        <div class="vr"></div>
                        <a href="ementa-admin.php">Ementa</a>
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

        <div class="container">
            <form action="" method="get">
                <div class="row gx-2 align-items-end mt-3">
                    <div class="col-3 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="pendente" <?php echo ($filtros['status'] == "pendente") ? "selected" : ""; ?>>Pendente</option>
                            <option value="confirmada" <?php echo ($filtros['status'] == "confirmada") ? "selected" : ""; ?>>Confirmada</option>
                            <option value="negada" <?php echo ($filtros['status'] == "negada") ? "selected" : ""; ?>>Negada</option>
                        </select>
                    </div>
                    <div class="col-3 mb-3">
                        <label for="num_utilizador" class="form-label">Número</label>
                        <input type="text" name="num_utilizador" id="num_utilizador" class="form-control" value="<?php echo htmlspecialchars($filtros['num_utilizador']); ?>" autocomplete="off">
                    </div>
                    <div class="col-3 mb-3">
                        <label for="data" class="form-label">Data</label>
                        <input type="text" id="calendar" name="data" class="form-control" value="<?php echo htmlspecialchars($filtros['data']); ?>" autocomplete="off">
                    </div>
                    <div class="col-3 mb-3">
                        <button type="submit" class="btn btn-primary w-100">Pesquisar</button>
                    </div>
                </div>
            </form>

            <?php if (!empty($mensagem)) { ?>
                <div class="alert <?php echo $classeMensagem; ?>"><?php echo $mensagem; ?></div>
            <?php } ?>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><i class="material-icons">school</i> Escola</th>
                            <th><i class="material-icons">schedule</i> Hora</th>
                            <th><i class="material-icons">calendar_today</i> Data</th>
                            <th><i class="material-icons">person</i> Nome</th>
                            <th><i class="material-icons">school</i> Número</th>
                            <th><i class="material-icons">info</i> Status</th>
                            <th><i class="material-icons">edit</i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $reserva) { ?>
                            <tr>
                                <td class="align-middle"><?php echo htmlspecialchars($reserva["escola"]); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($reserva["hora"]); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($reserva["data"]); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($reserva["nome_utilizador"]); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($reserva["num_utilizador"]); ?></td>
                                <td class="align-middle"><?php echo ucfirst(htmlspecialchars($reserva["status"])); ?></td>
                                <td class="align-middle text-center">
                                    <form action="" method="post" class="d-inline">
                                        <input type="hidden" name="num_utilizador" value="<?php echo htmlspecialchars($reserva["num_utilizador"]); ?>">
                                        <input type="hidden" name="data" value="<?php echo htmlspecialchars($reserva["data"]); ?>">
                                        <select name="status" class="form-select form-select-sm mb-2">
                                            <option value="confirmada" <?php echo ($reserva["status"] == "confirmada") ? "selected" : ""; ?>>Confirmar</option>
                                            <option value="negada" <?php echo ($reserva["status"] == "negada") ? "selected" : ""; ?>>Negar</option>
                                        </select>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-sm btn-primary me-2 w-100">Atualizar</button>
                                            <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">
                                            <button type="submit" name="cancelar_reserva" class="btn btn-sm btn-danger w-100" onclick="return confirm('Tem a certeza que deseja cancelar a reserva?');">Cancelar</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="d-flex footer mt-auto">
            <p class="col-md-4 texto">Agrupamento de Escolas Queluz-Belas</p>
            <a href="https://www.espan.edu.pt" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <img src="images/logo-espan.png" alt="aeqb" class="logo" width="140">
            </a>
            <ul class="nav col-md-4 footer-links">
                <li class="nav-item"><a href="admin_dashboard.php" class="nav-link texto">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link texto">Reservas</a></li>
                <li class="nav-item"><a href="ementa-admin.php" class="nav-link texto">Ementa</a></li>
            </ul>
        </footer>
    </form>
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>


