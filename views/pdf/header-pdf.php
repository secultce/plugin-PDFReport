<?php 
    $this->layout = 'nolayout-pdf'; 
    $reg = $app->view->regObject['ins'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link type="text/css" href="bootstrap.min.css" rel="stylesheet" />
    <link type="text/css" href="stylePdfReport.css" rel="stylesheet" />
    <link type="text/css" href="<?php $this->asset('css/bootstrap.min.css') ?>" rel="stylesheet" />
    <link type="text/css" href="<?php $this->asset('css/stylePdfReport.css') ?>" rel="stylesheet" />
    <script src="<?php $this->asset('js/pdfreport/jquery.min.js') ?>"></script>

</head>
<body>
<?php if(isset($_GET['idopportunityReport']) && $_GET['idopportunityReport'] > 0) : ?>
<div class="container">
    <br>
    <a href="#" class="btn btn-primary" id="btn-print-report" >
        <i class="fa fa-print"></i>
        Imprimir Relatório
    </a>
</div>
<?php endif; ?>
<table width="100%" style="height: 100px;">
    <thead>
        <tr class="">
            <td>                   
                <img src="<?php echo PLUGINS_PATH.'PDFReport/assets/img/logo-saude.png'; ?>" style="float:left;"/>
            </td>
            <td>
            <img src="<?php echo PLUGINS_PATH.'PDFReport/assets/img/ESP-CE-ORGAO-SEC-INVERTIDA-WEB2_3.png'; ?>" style="margin-left: 300px;" alt="">
            </td>
        </tr>
    </thead>
</table>


