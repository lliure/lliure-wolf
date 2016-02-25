<?php

// Inclui o arquivo class.phpmailer.php localizado na pasta phpmailer
require("class.phpmailer.php");

// Inicia a classe PHPMailer
$mail = new PHPMailer();

// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsSMTP(); // Define que a mensagem será SMTP
$mail->Host = "smtp.lorem.com.br"; // Endereço do servidor SMTP
//$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
$mail->Username = 'web@lorem.com.br'; // Usuário do servidor SMTP
$mail->Password = 'senha'; // Senha do servidor SMTP

// Define o remetente
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->From = "web@dlorem.com.br"; // Seu e-mail
$mail->FromName = "Jeison"; // Seu nome


// Define os destinatário(s)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->AddAddress('web@lorem.com.br', 'Fulano da Silva');
//$mail->AddAddress('ciclano@site.net');
//$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
//$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // Cópia Oculta

// Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
//$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)

// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
$mail->Subject  = "Mensagem Teste"; // Assunto da mensagem
$mail->Body = 'teste de envio';

$mail->AltBody = 'teste de alternativo';

// Define os anexos (opcional)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo

// Envia o e-mail
$enviado = $mail->Send();

// Limpa os destinatários e os anexos
$mail->ClearAllRecipients();
$mail->ClearAttachments();

echo $mail->Body;

// Exibe uma mensagem de resultado
if ($enviado) {
	echo "E-mail enviado com sucesso!";
} else {
	echo "Não foi possível enviar o e-mail.<br /><br />";
	echo "<b>Informações do erro:</b> <br />" . $mail->ErrorInfo;
}
?>
