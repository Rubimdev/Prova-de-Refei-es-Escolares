<?php
header('Content-Type: text/html; charset=utf-8');
require_once "lib/base-dados.php";

$mensagem = ""; // mensagem
$classeMensagem = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar os valores do formulário
    $nome = $_POST["nome"];
    $num = $_POST["num"];
    $email = $_POST["email"];
    $senha = md5($_POST["pass"]); // MD5

    // Verificar o número de aluno 
    $existeNumAluno = $bd->has("utilizadores", ["num" => $num]);

    if (!$existeNumAluno) {
        // Verificar o e-mail 
        $existeEmail = $bd->has("utilizadores", ["email" => $email]);

        if (!$existeEmail) {
            // Inserir os dados do utilizador na base de dados
            $resultado = $bd->insert("utilizadores", [
                "nome" => $nome,
                "num" => $num,
                "email" => $email,
                "senha" => $senha,
                "tipo" => "utilizador"
            ]);

            if ($resultado->rowCount() > 0) {
                $mensagem = "Registo bem-sucedido!"; // Mensagem de sucesso
                $classeMensagem = 'alert-success';
            } else {
                $mensagem = "Erro ao registar. Tente novamente."; // Mensagem de erro 
                $classeMensagem = 'alert-danger';
            }
        } else {
            $mensagem = "Este e-mail já está registado."; // Mensagem de erro
            $classeMensagem = 'alert-danger';
        }
    } else {
        $mensagem = "Este número de aluno já está em uso."; // Mensagem de erro
        $classeMensagem = 'alert-danger';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Prova de Refeições - Registro</title>

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
                <img id="imagem" src="images/logo-espan.png" alt="Logo Espan" />
                <?php if (!empty($mensagem)) { ?>
                    <div class="alert <?php echo $classeMensagem; ?>"><?php echo $mensagem; ?></div>
                <?php } ?>
            </div>
            <div id="formulario">
                <div class="campo">
                    <input type="text" name="nome" id="inome" placeholder="Nome do E.E" autocomplete="off" required/>
                </div>
                <div class="campo">
                    <input type="text" name="num" id="inumAluno" placeholder="Número do Aluno" autocomplete="off" required/>
                </div>
                <div class="campo">
                    <input type="email" name="email" id="iemail" placeholder="Email" autocomplete="email" required/>
                </div>
                <div class="campo">
                    <input type="password" name="pass" id="senha" placeholder="Password" autocomplete="new-password" required/>
                    <span class="material-icons show-password" aria-label="Mostrar Senha" style="user-select: none;">visibility_off</span>
                </div>
                <div class="submit">
                    <input type="submit" value="Registar" />
                </div>
                <div class="links">
                    <p>Já tem uma conta?<a href="index.php">Faça login</a></p>
                </div>
            </div>
        </section>
    </form>     
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script> <!-- JavaScript -->
</body>
</html>
