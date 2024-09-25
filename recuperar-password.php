<?php
require_once "lib/base-dados.php";
require 'lib/classes/vendor/autoload.php'; // Carregar PHPMailer

session_start(); // Iniciar sessão

$mensagem = ""; // mensagem
$classeMensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["num_or_email_recuperar"])) {
        $num_or_email_recuperar = $_POST["num_or_email_recuperar"];

        // Verificar se número do aluno ou e-mail existe
        $utilizador = $bd->get("utilizadores", "*", [
            "OR" => [
                "num" => $num_or_email_recuperar,
                "email" => $num_or_email_recuperar
            ]
        ]);

        if ($utilizador) {
            $nome = $utilizador["nome"];
            // link para redefinir senha
            $resetLink = "http://localhost/PAP/reset-password.php?id={$utilizador['id']}";

            // Configurar PHPMailer
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'redefinirpalavrachave@gmail.com'; // Insira aqui o e-mail de onde será enviado o link de recuperação
            $mail->Password = 'iilw oecy ecej honk'; // Insira aqui a senha do e-mail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // destinatário 
            $mail->addAddress($utilizador["email"], $utilizador["nome"]);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperar Palavra-Passe';

            // e-mail
            $mensagem_personalizada = "Olá $nome,<br><br>";
            $mensagem_personalizada .= "Recebemos uma solicitação para redefinir a Palavra-Passe da sua conta no site de provas de refeições Agrupamento de Escolas Queluz-Belas.<br>";
            $mensagem_personalizada .= "Clique no link abaixo para criar a Palavra-Passe:<br>";
            $mensagem_personalizada .= "<a href='$resetLink'>$resetLink</a><br><br>";
            $mensagem_personalizada .= "Se você não solicitou essa alteração, ignore este e-mail.<br><br>";
            $mensagem_personalizada .= "Atenciosamente,<br>";
            $mensagem_personalizada .= "Provas de Refeições do Agrupamento de Escolas Queluz-Belas";

            $mail->Body = $mensagem_personalizada;

            $mail->SMTPDebug = 0;

            if($mail->send()) {
                // E-mail enviado com sucesso
                $mensagem = "E-mail enviado com sucesso para " . $utilizador["email"];
                $classeMensagem = 'alert-success';
            } else {
                // Erro no envio de e-mail
                $mensagem = 'Erro no envio do e-mail: ' . $mail->ErrorInfo;
                $classeMensagem = 'alert-danger';
            }
        } else {
            // Usuário não encontrado
            $mensagem = "Utilizador não encontrado.";
            $classeMensagem = 'alert-danger';
        }
    } else {
        // Campo não definido
        $mensagem = "Preencha o número do aluno ou e-mail corretamente.";
        $classeMensagem = 'alert-danger';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Recuperar Senha - Prova de Refeições</title>

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
                <img id="imagem" src="images/logo-espan.png" alt="Imagem Recuperação de Senha" />
                </div>
                <div id="formulario">
                <?php if (!empty($mensagem)) { ?>
                    <div class="alert <?php echo $classeMensagem; ?>"><?php echo $mensagem; ?></div>
                <?php } ?>
                <div class="campo">
                    <input type="text" name="num_or_email_recuperar" id="ilogin" placeholder="Email ou Nº Aluno" required/>
                </div>
                <div class="submit">
                    <input type="submit" value="Recuperar Password" /> <!-- Submeter formulário -->
                </div>
                <div class="links">
                    <a href="index.php">Voltar à página de login</a> <!-- login -->
                </div>
            </div>
        </section>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script> <!-- JavaScript -->
</body>
</html>
