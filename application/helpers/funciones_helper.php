<?php
function formatearFecha($fecha)
{
	/* 2024-06-24 18:38:23 */
	$dia=substr($fecha,8,2);
	$mes=substr($fecha,5,2);
	$anio=substr($fecha,0,4);

	$fechaformateada=$dia."/".$mes."/".$anio;
	return $fechaformateada;
}


function estado($nota)
{
	if($nota>=61)
	{
		$estado="APROBADO";
	}
	else
	{
		$estado="APROBADO";
	}

	return $estado;
}
?>