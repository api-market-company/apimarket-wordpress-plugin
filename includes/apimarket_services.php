<?php
$apimarket_services = array(
    'https://apimarket.mx/api/renapo/grupo/valida-curp' => [
        'label' => 'Validate CURP and get personal data (curp -> sexo, nombres, paisNacimiento, apellidoPaterno, apellidoMaterno, fechaNacimiento)',
        'schema' => array(
            'curp' => '[curp]'
        )
    ],
    'https://apimarket.mx/api/sat/grupo/obtener-rfc' => [
        'label' => 'Obtain RFC using CURP (curp -> rfc)',
        'schema' => array(
            'curp' => '[curp]'
        )
    ],
    'https://apimarket.mx/api/imss/grupo/localizar-umf' => [
        'label' => 'Locate UMF with postal code (cp -> unidad medico familiar)',
        'schema' => array(
            'cp' => '[cp]'
        )
    ],
    'https://apimarket.mx/api/imss/grupo/localizar-nss' => [
        'label' => 'Locate NSS with CURP (curp -> nss, sexo, nombre, amaterno, apaterno, fecNacimiento)',
        'schema' => array(
            'curp' => '[curp]'
        )
    ],
    'https://apimarket.mx/api/imss/grupo/consultar-vigencia' => [
        'label' => 'Check Social Security Validity (IMSS) with NSS and CURP (nss,curp -> nombre, vigencia, fechaVigencia)',
        'schema' => array(
            'nss' => '[nss]',
            'curp' => '[curp]'
        )
    ],
    'https://apimarket.mx/api/imss/grupo/con-clinica' => [
        'label' => 'Consult Clinic (UMF) with nss',
        'schema' => array(
            'nss' => '[nss]'
        )
    ],
    'https://apimarket.mx/api/infonavit/grupo/credit' => [
        'label' => 'Get the infonavit user credit (nss,curp --> score, and subscores)',
        'schema' => array(
            'curp' => '[curp]',
            'nss' => '[nss]'
        )
    ],
    'https://apimarket.mx/api/imss/grupo/historial-laboral' => [
        'label' => 'Consult the Labor History (IMSS) with NSS and CURP (nss, curp -> nombre, semanasCotizadas.semanasCotizadas, historialLaboral.movimientos[*].nombre)',
        'schema' => array(
            'curp' => '[curp]',
            'nss' => '[nss]'
        )
    ],
    'https://apimarket.mx/api/sat/grupo/validar-datos' => [
        'label' => 'Validate fiscal data (SAT) with CFDI4 (rfc -> nombreCompleto, tipoPersona, correoElectronico, puedeFacturar)',
        'schema' => array(
            'rfc' => '[rfc]'
        )
    ],
    'https://apimarket.mx/api/infonavit/grupo/obtener-cuenta' => [
        'label' => 'Get INFONAVIT SubAccount (nss -> subaccount)',
        'schema' => array(
            'nss' => '[nss]'
        )
    ],
    'https://apimarket.mx/api/infonavit/grupo/buscar-credito' => [
        'label' => 'Search INFONAVIT Credit with NSS (nss -> credit)',
        'schema' => array(
            'nss' => '[nss]'
        )
    ],
    'https://apimarket.mx/api/sat/grupo/calcular-rfc' => [
        'label' => 'Calculate RFC using personal data (personal data -> rfc)',
        'schema' => []
    ],
    'https://apimarket.mx/api/renapo/grupo/obtener-curp' => [
        'label' => 'Obtain CURP using personal data (personal data -> curp)',
        'schema' => []
    ],
    'https://apimarket.mx/api/sep/grupo/validar-cedula' => [
        'label' => 'Validate Professional License (SEP)',
        'schema' => []
    ],
    'https://apimarket.mx/api/sep/grupo/validar-certificado' => [
        'label' => 'Validate Certificate (SEP)',
        'schema' => []
    ],
    'https://apimarket.mx/api/sep/grupo/obtener-cedula' => [
        'label' => 'Obtain Professional License (SEP)',
        'schema' => []
    ],
    'https://apimarket.mx/api/sat/v2/lista69b' => [
        'label' => 'Lista 69B SAT',
        'schema' => array(
            'select' => 'rfc',
            'rfc' => '[rfc]'
        )
    ],
);