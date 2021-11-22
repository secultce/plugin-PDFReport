<?php 
    $this->layout = 'nolayout-pdf'; 
    $sub = $app->view->jsObject['subscribers'];
    $nameOpportunity = $sub[0]->opportunity->name;
    //Objeto Oportunidade
    $op = $app->view->jsObject['opp'];
    include_once('style.php');
    //VERIFICANDO SE A OPORTUNIDADE TEM CATEGORIA  
    $isCategory = false;
    if(is_array($op->registrationCategories) && count($op->registrationCategories) > 0) {
        $isCategory = true;
    }

?>
</style>
<div class="container">
    <?php include_once('header.php'); 
    if($op->avatar) :
    ?>
    <table  style="height: 100px; margin-bottom:24px; margin-top:24px;">
        <thead>
            <tr>
                <td>
                    <img src="<?php echo $op->getFile('header')->url; ?>" alt="" srcset="">                
                </td>
            </tr>
        </thead>
    </table>
    <?php endif; ?>    

    <table width="100%" style="height: 100px; margin-bottom:40px; margin-top:40px;">
        <thead>
            <tr class="">
                <td style="width: 10%;">                   
                    <?php if(!empty($op->files['avatar'])): ?>
                        <img src="<?php echo $op->avatar->transform('avatarSmall')->url; ?>"  style="width: 80px; height: 80px;">
                    <?php else: ?>
                        <img src="<?php $this->asset('img/pdfreport/avatar--opportunity.png') ?>" style="width: 80px; height: 80px;">
                    <?php endif; ?>
                </td>
                <td style="width: 90%;">
                
                    <label class="title-edital">Edital</label><br>
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
        <br>
        <table class="table table-striped table-bordered">
        <thead>
            <tr style="color: #2D3540;">
                <th>Inscrição</th>
                <th>Agente</th>
                <?php if($isCategory) : ?>
                <th>Categoria</th>
                <?php endif; ?>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sub as $key => $value) {
                $agent = $app->repo('Agent')->find($value->owner->id); ?>
            <tr>
                <td><?php echo $value->number; ?></td>
                <td><?php echo $agent->name; ?></td>
                <?php if($isCategory) : ?>
                <td><?php echo $value->category !== "" ? $value->category : "Não Informado"; ?></td>
                <?php endif; ?>
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
<?php include_once('footer.php'); ?>