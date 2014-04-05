<?php
/**
*
* API PHP Mailer - Plugin WAP
*
* @Versão 6.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once("class.phpmailer.php");

if(!defined('ll_dir')){
	$dir_c = dirname(__FILE__);
	
	if(strstr($dir_c , '/'))
		$dir = explode('/', $dir_c);
	else 
		$dir = explode('\\', $dir_c);

	array_pop($dir);
	array_pop($dir);

	if(strstr($dir_c , '/'))
		$dir = implode('/', $dir).'/';
	else 
		$dir = implode('\\', $dir).'\\';

	define("ll_dir", $dir);
	}

function limpaMail($in){
	if(strstr($in, '<')){
		$in = str_replace(array(' <', '>'), array('<', ''), $in);
		$in = explode('<', $in);
		
		$out['mail'] = $in[1];
		$out['nome'] = $in[0];
	} else{
		$out['mail'] = $in;
		$out['nome'] = '';
	}
	
	return $out;
}


function pm_mail($destinatario = null, $assunto = null, $menssagem = null, $header = null, $conf = null){	
	$to = limpaMail($destinatario);
	
	if(!empty($conf)){		
		$smtp = (object) $conf;
		
		/*
		$conf = array(
			'host' => 'mail.newsmade.com.br',
			'usuario' => 'syn+newsmade.com.br',
			'usuario' => '102030',
			'autenticacao' => true,
			'porta' => 25
			)
		*/
	} else {
		if(($llconf = simplexml_load_file(ll_dir . 'etc/llconf.ll')) == false)
			return 'Nao foi possível encontrar as configurações de smtp';
		else
			$smtp = $llconf->smtp;
	}
	
	
	$mail = new PHPMailer();

	$mail->IsSMTP(); 
	$mail->Host = $smtp->host;	
		
	if(isset($smtp->autenticacao))
		$mail->SMTPAuth = (boolean) $smtp->autenticacao;	
	
	
	if(isset($smtp->porta))
		$mail->Port = (int) $smtp->porta;	
		
	/*
	//if(isset($smtp->seguranca))
		//$mail->SMTPSecure = $smtp->seguranca;	
	*/
	$mail->Username = $smtp->usuario;
	$mail->Password = $smtp->senha;
	

	// remetente
	
	if(isset($header['from'])) {
		$from = limpaMail($header['from']);
	} else {
		$from['mail'] = $smtp->usuario;
		$from['nome'] = '';
	}
	
	$mail->From = $from['mail']; // Seu e-mail
	$mail->FromName = $from['nome']; // Seu nome
	
	if(isset($header['replyTo'])){
		$reply = limpaMail($header['replyTo']);
		$mail->AddReplyTo($reply['mail'], $reply['nome']); 
	}
	

	// Define os destinatário(s)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->AddAddress($to['mail'], $to['nome']);
	
	//$mail->AddAddress('ciclano@site.net');
	//$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
	//$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // Cópia Oculta

	// Define os dados técnicos da Mensagem
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsHTML(true);


	// Define a mensagem (Texto e Assunto)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->Subject  = $assunto; // Assunto da mensagem
	$mail->Body = $menssagem;

	$mail->AltBody = nl2br(strip_tags($menssagem));

	// Define os anexos (opcional)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo

	// Envia o e-mail
	$enviado = $mail->Send();

	// Limpa os destinatários e os anexos
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();

	// Exibe uma mensagem de resultado
	if ($enviado) {
		return true;
	} else {
		return $mail->ErrorInfo;
	}

}
?>