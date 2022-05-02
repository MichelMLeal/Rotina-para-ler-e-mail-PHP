<?php	

	date_default_timezone_set('America/Sao_Paulo');
	ini_set('max_execution_time', 0);
	ini_set('memory_limit', '-1');
	set_time_limit(0);
	
	//pega as funções 
	require_once($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."functions.php");
	//validade se existe imap
	if (! function_exists("imap_open")) {	
		logMe("IMAP não está configurado. ");
		exit();
	} else {        
		//faz a conexão com o servidor de e-mail
		$connection = connectionImap();

		if($connection){			
			//cria um log
			logMe("-----------------------------------------------------------------------------------------------------------------------------------------");
			logMe("Conectado. ", "\r\n");
		
			//procura o texto do Subject do e-mail
			$emailData = imap_search($connection, 'SUBJECT "[subject] "');
			
			if (! empty($emailData)) {
				//função que faz o tratamento da string do corpo do e-mail
				$message		= getAttachments($connection, 2);
					
				$requerimento  	= trim(getValueString($message, " (", ")"));
			
				logMe("Corpo do E-mail---------------------------> ".$message);				
			}
				
			imap_close($connection);
			logMe("Conexão fechada. ");

		}else{
			logMe("Não foi possível conectar ao servidor de e-mail. ");
		}
	}
?>
