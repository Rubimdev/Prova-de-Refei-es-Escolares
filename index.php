<?php
session_start(); // Inicia a sessão

require_once "lib/base-dados.php";

$mensagem = ""; // mensagem de erro

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // validação 
    $num_or_email = filter_input(INPUT_POST, 'num_or_email', FILTER_SANITIZE_STRING);
    $pass = $_POST["pass"]; 

    // Buscar utilizador na base de dados 
    $utilizador = $bd->get("utilizadores", "*", [
        "AND" => [
            "OR" => [
                "num" => $num_or_email,
                "email" => $num_or_email
            ],
            "senha" => md5($pass) // Encriptar pass
        ]
    ]);

    if ($utilizador) {
        $_SESSION["autenticado"] = true; // Definir sessão como autenticada
        $_SESSION["id"] = $utilizador["id"];
        $_SESSION["nome"] = $utilizador["nome"]; 
        $_SESSION["email"] = $utilizador["email"];
        $_SESSION["num"] = $utilizador["num"];
        $_SESSION["senha"] = $utilizador["senha"];
        
        if ($utilizador['tipo'] == 'admin') {
            header("Location: admin_dashboard.php"); // administrador
        } else {
            header("Location: dashboard.php"); // utilizador normal
        }
        exit; // Encerrar o script 
    } else {
        $mensagem = "Email ou Password incorretos."; 
    }
}

// Para login com utilizador demo
if (isset($_POST['demo_user'])) {
    $_SESSION["autenticado"] = true;
    $_SESSION["id"] = 1; // ID do utilizador demo
    $_SESSION["nome"] = "Demo User";
    $_SESSION["email"] = "demo@exemplo.com";
    $_SESSION["num"] = "123456";
    
    header("Location: dashboard.php");
    exit;
}

// Para login com admin demo
if (isset($_POST['demo_admin'])) {
    $_SESSION["autenticado"] = true;

    
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Prova de Refeições</title>
    
    <link rel="icon" href="images/favicon-espan.ico" type="image/x-icon" />
    <link rel="stylesheet" href="css/media-query.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <form action="" method="post" autocomplete="on">
        <section id="formulario">
            <div>
                <img id="imagem" src="images/logo-espan.png" alt="Logótipo Espan" />
            </div>

            <div id="formulario">
                <?php if (!empty($mensagem)) { ?>
                    <p class="alert alert-danger"><?php echo $mensagem; ?></p> <!-- Exibir mensagem -->
                <?php } ?>
                <div class="campo">
                    <input type="text" name="num_or_email" id="ilogin" placeholder="Email ou Nº Aluno"/>
                </div>
                <div class="campo">
                    <input type="password" name="pass" id="isenha" placeholder="Password" autocomplete="current-password"/>
                    <span class="material-icons show-password" aria-label="Mostrar Senha" style="user-select: none;">visibility_off</span> <!-- Ícone para mostrar/esconder senha -->
                </div>
                <div class="links">
                    <a href="recuperar-password.php">Esqueceu-se da palavra-passe?</a> <!-- recuperar senha -->
                </div>
                <div class="submit">
                    <input type="submit" value="Entrar" /> <!-- submeter formulário -->
                </div>
                <div class="registo">
                    <p>Ainda não tem conta? <a href="registo.php">Registar</a></p> <!-- página de registo -->
                </div>
                <div style="display:flex;align-items:center;">
                    <div style="flex-grow:1;height:2px;background:#ccc;"></div>
                    <span style="margin:0 10px; font-size: 20px;">ou</span>
                    <div style="flex-grow:1;height:2px;background:#ccc;"></div>
                </div>
                <!-- Adicionar a opção de login demo -->
                <div class="text-center my-3">
                    <button type="submit" name="demo_user" class="btn btn-secondary">Demo User</button>
                    <button type="submit" name="demo_admin" class="btn btn-secondary">Demo Admin</button>
                </div>
            </div>
        </section>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script> 
</body>
</html>
