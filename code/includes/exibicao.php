<?php
const CATEGORIAS = [
    'generico' => 'Genérico',
    'referencia' => 'Referência',
    'controlado' => 'Controlado',
    'higiene' => 'Higiene',
];

const URGENCIAS = [
    'baixa' => 'Baixa',
    'media' => 'Média',
    'alta' => 'Alta',
];

const STATUS = [
    'solicitado' => 'Solicitado',
    'em_separacao' => 'Em separação',
    'recebido' => 'Recebido',
];

function escaparHtml($texto)
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}
