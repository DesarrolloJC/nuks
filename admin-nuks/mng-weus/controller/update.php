<?php
	header('Content-Type: application/json');
	$LISTO = array('success'=>true,'title'=>'Exito!','msg'=>'Actualizado correctamente.','class'=>'success','final'=>'location.reload();');
	$ERROR = array('success'=>false,'title'=>'Error!','msg'=>'Ocurrió un error al subir ','class'=>'warning');
	$FILE = '../model/weus.html';

	$ESCRIBIR = $_POST['contenido'];

	$FO = fopen($FILE, "w");
	fwrite($FO, $ESCRIBIR);
	fclose($FO);

	echo json_encode($LISTO);
?>