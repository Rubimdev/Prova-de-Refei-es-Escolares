<?php
session_start();
require_once "lib/base-dados.php";

// Verifica se o utilizador está autenticado
if (!isset($_SESSION["autenticado"])) {
    echo "<h1>Acesso negado</h1>";
    exit;
}

$mensagem = '';
$classeMensagem = '';

// Verifica se o formulário foi enviado para enviar uma nova ementa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se foi enviado um arquivo PDF
    if (isset($_FILES["pdfEmenta"]) && $_FILES["pdfEmenta"]["error"] == 0) {
        // Verifica o tipo do arquivo
        $fileType = mime_content_type($_FILES["pdfEmenta"]["tmp_name"]);
        $allowedTypes = ['application/pdf'];

        if (in_array($fileType, $allowedTypes)) {
            // Diretório para salvar os PDFs
            $uploadDir = 'uploads/';

            // Cria o diretório se não existir
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Sanitiza o nome do arquivo para evitar ataques
            $fileName = preg_replace("/[^A-Za-z0-9 .]/", '_', $_FILES['pdfEmenta']['name']);
            $filePath = $uploadDir . $fileName;

            // Move o arquivo para o diretório de uploads
            if (move_uploaded_file($_FILES["pdfEmenta"]["tmp_name"], $filePath)) {
                // Extrai o mês e o ano da ementa
                $mes = $_POST['mes'];
                $ano = $_POST['ano'];
                $mesAno = $mes . '-' . $ano;

                // Insere os detalhes do PDF na base de dados
                $insercao = $bd->insert("ementas", [
                    "mes" => $mesAno,
                    "file_path" => $filePath
                ]);

                // Verifica se a inserção foi bem-sucedida
                if ($insercao) {
                    $mensagem = "PDF da ementa enviado com sucesso.";
                    $classeMensagem = "alert-success";
                } else {
                    $mensagem = "Erro ao enviar o PDF da ementa para o banco de dados.";
                    $classeMensagem = "alert-danger";
                }
            } else {
                $mensagem = "Erro ao fazer upload do arquivo PDF.";
                $classeMensagem = "alert-danger";
            }
        } else {
            $mensagem = "Tipo de arquivo não permitido. Envie um arquivo PDF.";
            $classeMensagem = "alert-warning";
        }
    } else {
        $mensagem = "Nenhum PDF da ementa foi enviado.";
        $classeMensagem = "alert-warning";
    }

    // Verifica se foi solicitada a exclusão de uma ementa
    if (isset($_POST['eliminar_ementa'])) {
        // Remove a ementa do banco de dados
        $bd->delete("ementas", [
            "id" => $_POST['ementa_id']
        ]);
        $mensagem = "Ementa eliminada com sucesso.";
        $classeMensagem = "alert-success";
    }
}

// Recupera todas as ementas do banco de dados
$ementas = $bd->select("ementas", "*");

// Array de meses em português
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

// Obtém o mês e ano atuais
$mesAtual = date('m');
$anoAtual = date('Y');
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ementa - Admin</title>

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
<body class="d-flex-column min-vh-100">
    <div class="flex-grow-1">
        <form action="" method="post" enctype="multipart/form-data" class="d-flex flex-column">
            <header class="header">
                <div class="container">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-auto">
                            <img src="images/logo-espan.png" alt="Logótipo" class="img-fluid">
                        </div>
                        <div class="col-md-auto header-links">
                            <a href="admin_dashboard.php">Home</a>
                            <div class="vr"></div>
                            <a href="reservas-admin.php">Reservas</a>
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
                <!-- Mensagem de feedback -->
                <?php if (!empty($mensagem)) { ?>
                    <div class="alert <?php echo $classeMensagem; ?>"><?php echo $mensagem; ?></div>
                <?php } ?> 

                <!-- Formulário para enviar PDF da ementa -->
                <div class="mb-3">
                    <label for="pdfEmenta" class="form-label">Escolha o PDF da ementa:</label>
                    <input type="file" class="form-control" id="pdfEmenta" name="pdfEmenta" required>
                </div>

                <!-- Selecionar o mês -->
                <div class="mb-3">
                    <label for="mes" class="form-label">Selecione o mês:</label>
                    <select class="form-select" id="mes" name="mes" required>
                        <?php foreach ($mesesPortugues as $key => $value) { ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Selecionar o ano -->
                <div class="mb-3">
                    <label for="ano" class="form-label">Selecione o ano:</label>
                    <select class="form-select" id="ano" name="ano" required>
                        <?php 
                        $currentYear = date("Y");
                        for ($year = $currentYear; $year <= $currentYear + 1; $year++) {
                            echo "<option value='$year'>$year</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
        </form>

        <!-- Listar links para download dos PDFs -->
        <div class="container mt-3">
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
                                        <!-- Formulário para exclusão -->
                                        <form action="" method="post" class="d-inline">
                                            <input type="hidden" name="ementa_id" value="<?php echo $ementa['id']; ?>">
                                            <button type="submit" name="eliminar_ementa" class="btn btn-delete" onclick="return confirm('Tem a certeza que deseja eliminar esta ementa?');">
                                                <i class="material-icons">delete</i>
                                            </button>
                                        </form>
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
    </div>

    <footer class="d-flex footer mt-auto">
        <p class="col-md-4 texto">Agrupamento de Escolas Queluz-Belas</p>
        <a href="https://www.espan.edu.pt" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <img src="images/logo-espan.png" alt="aeqb" class="logo" width="140">
        </a>
        <ul class="nav col-md-4 footer-links">
            <li class="nav-item"><a href="admin_dashboard.php" class="nav-link texto">Home</a></li>
            <li class="nav-item"><a href="reservas-admin.php" class="nav-link texto">Reservas</a></li>
            <li class="nav-item"><a href="#" class="nav-link texto">Ementa</a></li>
        </ul>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>

