<?php

use IMAP\Connection;

	function logMe(string $msg): void
	{
		$path = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."log.txt";
		$fp = fopen($path, "a+");
		
		$msg = $msg." Data: ".date("d/m/Y H:i:s")."\r\n";
		$lengthMsg = strlen($msg);
	
		fwrite($fp, $msg, $lengthMsg);	
		fclose($fp);
	}
	
	function removeCaracter(string $valor): string
	{
		$valor = trim($valor);
		$valor = str_replace([".", ",", "-", "/", " "], "", $valor);
		return $valor;
	}
	
	function SemAcentos(string $string): string
	{
		$dest = str_split("AEIOUAEIOUAEIOUAOAEIOUaeiouaeiouaeiouaoaeiouCc");
		$orig = str_split(utf8_decode("ÁÉÍÓÚÄËÏÖÜÀÈÌÒÙÃÕÂÊÎÔÛáéíóúäëïöüàèìòùãõâêîôûÇç")); 
		foreach($orig as $k => $v) { $orig[$k] = "/".$v."/"; }
	
		if (iconv("UTF-8", "UTF-8", $string) == $string) {
			$string = utf8_decode($string);
		}
	
		$string = preg_replace(array_values($orig), array_values($dest), $string);
		return $string;
	}
	
	function getValueString(string $message, string $before, string $after, int $posBefore = 0): string
	{
		$pos = stripos($message, $before, $posBefore) + strlen($before);
		$len = stripos($message, $after, $pos) - ($pos);
		
		return substr($message, $pos, $len);	
	}
	
	function getAttachments($conn, $id): string
	{
		$parts = imap_fetchstructure($conn, $id);
		$message = "";
		
		//we get foreach errors
		foreach ($parts->parts as $key => $value) {
			$encoding = $parts->parts[$key]->encoding;
			
				$message = imap_fetchbody($conn, $id, 1);
				switch ($encoding) {
					case 0:
						$message = imap_8bit($message);
					case 1:
						$message = imap_8bit($message);
					case 2:
						$message = imap_binary($message);
					case 3:
						$message = imap_base64($message);
					case 4:
						$message = quoted_printable_decode($message);
					case 5:
					default:
						$message = $message;
				}
		}
		return $message;
	}

	function connectionImap()
	{
		return	imap_open("{mail.myemail.com:993/imap/ssl/novalidate-cert}INBOX", "test@myemail.com", "pass123") ;
	}

	function pegaEmailRemetente($conn, $id):string
	{
		$header = imap_headerinfo($conn, $id);

		$array = get_object_vars($header->sender[0]);
				
		["personal" => $personal, "mailbox" => $mailbox, "host" => $host] = $array;

		return $mailbox."@".$host;
	}
