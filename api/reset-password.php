<?php
require_once "lib/base-dados.php";

$mensagem = "";
$classeMensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["token"]) && isset($_POST["nova_senha"]) && isset($_POST["confirmar_senha"])) {
        $token = $_POST["token"];
        $nova_senha = md5($_POST["nova_senha"]); // criptografar a nova senha
        $confirmar_senha = md5($_POST["confirmar_senha"]); // criptografar a confirmação da senha

        // Verificar se as senhas coincidem
        if ($nova_senha === $confirmar_senha) {
            // Obter o ID do usuário 
            $id_usuario = isset($_GET['id']) ? $_GET['id'] : null;

            if ($id_usuario) {
                // Atualizar a senha na base
                $bd->update("utilizadores", ["senha" => $nova_senha], ["id" => $id_usuario]);

                // mensagem de sucesso
                $mensagem = "Senha redefinida com sucesso!";
                $classeMensagem = 'alert-success';
            } else {
                // mensagem de erro (ID do usuário não fornecido)
                $mensagem = "ID do usuário não fornecido. Por favor, tente novamente.";
                $classeMensagem = 'alert-danger';
            }
        } else {
            // mensagem de erro (senhas não coincidem)
            $mensagem = "As senhas não coincidem. Por favor, verifique e tente novamente.";
            $classeMensagem = 'alert-danger';
        }
    } else {
        // mensagem de erro (parâmetros incompletos)
        $mensagem = "Por favor, preencha todos os campos corretamente.";
        $classeMensagem = 'alert-danger';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Prova de Refeições - Redefinir Senha</title>

    <link rel="icon" href="images/favicon-espan.ico" type="image/x-icon" />

    <link rel="stylesheet" href="css/media-query.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <form action="" method="POST">
        <section id="formulario">
            <div>
                <img id="imagem" src="images/logo-espan.png" alt="Logo Espan" />

                <?php if (!empty($mensagem)) { ?>
                    <div class="alert <?php echo $classeMensagem; ?>"><?php echo $mensagem; ?></div>
                <?php } ?>
            </div>
            <div>
                <div class="campo">
                    <input type="password" name="nova_senha" id= "senha1" placeholder="Nova Password" autocomplete="new-password" required />
                    <span class="material-icons show-password" id="showPasswordIcon1" aria-label="Mostrar Password" style="user-select: none;">visibility_off</span>
                </div>
                <div class="campo">
                    <input type="password" name="confirmar_senha" id= "senha2" placeholder="Confirmar Nova Password" autocomplete="new-password" required />
                    <span class="material-icons show-password" id="showPasswordIcon1" aria-label="Mostrar Password" style="user-select: none;">visibility_off</span>
                </div>
                <div class="submit">
                    <input type="submit" value="Redefinir Password" />
                </div>
                <div class="links">
                    <a href="index.php">Voltar à página de login</a>
                </div>
            </div>
        </section>
        <!-- token -->
        <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? $_GET['token'] : ''; ?>">
    </form>  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script> 
</body>
</html>
