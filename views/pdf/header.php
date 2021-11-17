<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link type="text/css" href="bootstrap.min.css" rel="stylesheet" />
    <link type="text/css" href="stylePdfReport.css" rel="stylesheet" />
    <!-- <link type="text/css" href="<?php //$this->asset('css/bootstrap.min.css') ?>" rel="stylesheet" />
    <link type="text/css" href="<?php //$this->asset('css/stylePdfReport.css') ?>" rel="stylesheet" /> -->
    <style>
            @page {  margin: 24px 24px;  }
            footer { position: fixed; bottom: -15px; left: 0px; right: 0px;height: 50px; border-top: 1px solid #c3c3c3;
             color: rgba(0, 0, 0, 0.6);}
            
    </style>
</head>
<body>
<footer>
    <p style="font-size: 8px;color: rgba(0, 0, 0, 0.6); align-items: center; text-align: center;">
        <!-- <small>Escola de Saúde Pública do Ceará Paulo Marcelo Martins Rodrigues</small> -->
    </p>
    
</footer>
<table width="100%" style="height: 100px;">
    <thead>
        <tr class="">
            <td>                   
                <img src="<?php echo PLUGINS_PATH.'PDFReport/assets/img/logo-saude.png'; ?>" style="float:left;"/>
                <!-- <img src="<?php $this->asset('img/logo-saude.png') ?>"  class="pull-left" > -->

            </td>
            <td>
            <!-- <img src="<?php $this->asset('img/ESP-CE-ORGAO-SEC-INVERTIDA-WEB2_3.png') ?>" class="pull-right" alt=""> -->
                <img src="<?php echo PLUGINS_PATH.'PDFReport/assets/img/ESP-CE-ORGAO-SEC-INVERTIDA-WEB2_3.png'; ?>"  style="float:right;"/>
            </td>
        </tr>
    </thead>
</table>
