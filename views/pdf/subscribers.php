<?php 
    $this->layout = 'nolayout'; 
    $sub = $app->view->jsObject['subscribers'];
    $nameOpportunity = $sub[0]->opportunity->name;
    //Objeto Oportunidade
    $op = $app->view->jsObject['opp'];
    include_once('style.php');  
    // dump($sub);
    // die;
?>
</style>
<div class="container">
    <?php //include_once('header.php'); ?>
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

    <table width="100%" style="height: 100px; margin-bottom:40px;">
        <thead>
            <tr class="">
                <td style="width: 10%;">                   

                    <!-- <img src="<?php $this->asset('img/logo-saude.png') ?>"  class="pull-left" > -->
                    <?php if(!empty($op->files['avatar'])): ?>
                        <img src="<?php echo $op->files['avatar']->path; ?>"  style="width: 80px; height: 80px;">
                    <?php else: ?>
                        <img src="<?php echo THEMES_PATH.'BaseV1/assets/img/avatar--opportunity.png'; ?>" style="width: 80px; height: 80px;">
                    <!-- <label for=""><?php echo THEMES_PATH.'BaseV1/assets/img/avatar--opportunity.png'; ?></label> -->
                    <?php endif; ?>

                </td>
                <td style="width: 90%;">
                <!-- <img src="<?php $this->asset('img/ESP-CE-ORGAO-SEC-INVERTIDA-WEB2_3.png') ?>" class="pull-right" alt=""> -->
                    <label for="" class="title-edital">Edital</label><br>
                    <label class="sub-title-edital"><?php echo $op->ownerEntity->name; ?></label>
                    <br>
                    <label for="" class="title-edital">Oportunidade</label><br>
                    <label class="sub-title-edital"><?php echo $op->name; ?></label>
                </td>
            </tr>
        </thead>
    </table>

    <div class="row">
        <div class="container">
            <div class="col-md-12" class="div-categoria" style="background: #1F4E37; border-radius: 8px;color: #FFFFFF;
padding: 4px 8px;">
                <label for="">Categoiria 01</label>
            </div>
        </div>
        <div class="col-md-12">
            <br>
        </div>
    </div>

    <div class="row">
        <div class="container">
        <br>
        <table class="table table-striped table-bordered">
        <thead>
            <tr style="color: #2D3540;">
                <th>Inscrição</th>
                <th>Agente</th>
                <th>Aval. Preliminar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sub as $key => $value) {
                $agent = $app->repo('Agent')->find($value->owner->id); ?>
            <tr>
                <td><?php echo $value->number; ?></td>
                <td><?php echo $agent->name; ?></td>
                <td><?php echo $value->preliminaryResult; ?></td>
                <td><?php
                    $status = '';
                        switch ($value->status) {
                            case 0:
                                $status = 'Rascunho';
                                break;
                            case 1:
                                $status = 'Pendente';
                                break;
                            case 2:
                                $status = 'Inválido';
                                break;
                            case 3:
                                $status = 'Não aprovado';
                                break;
                            case 8:
                                $status = 'Suplente';
                                break;
                            case 10:
                                $status = 'Selecionado';
                                break;
                        }
                    echo $status;
                    ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
        </div>
    </div>


</div>
<?php //include_once('footer.php'); ?>