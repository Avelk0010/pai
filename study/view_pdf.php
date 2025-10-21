<?php
// Archivo temporal para visualizar el HTML en el navegador
$htmlFile = 'rubrica_evaluacion.html';
if (file_exists($htmlFile)) {
    echo file_get_contents($htmlFile);
} else {
    echo "Archivo no encontrado";
}
?>