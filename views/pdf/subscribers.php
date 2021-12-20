<?php 
    use Saude\Utils\RegistrationStatus;

    $this->layout = 'nolayout-pdf'; 
    $sub = $app->view->jsObject['subscribers'];
    $nameOpportunity = $sub[0]->opportunity->name;
    $op = $app->view->jsObject['opp'];

    $isCategory = false;
    if(is_array($op->registrationCategories) && count($op->registrationCategories) > 0) {
        $isCategory = true;
    }
?>
</style>
<div class="container">
    <?php include_once('header-pdf.php'); ?>
    <div class="container">
        <div class="pre-text">Relação de Inscritos</div>
        <div class="opportunity-info">
            <p>Oportunidade: </p>
            <h4><?php echo $nameOpportunity ?></h4>
        </div>
    </div>
    <div class="row" style="margin-top: 20px">
        <div class="container">
            <table id="table-preliminar" width="100%" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Inscrição</th>
                        <th class="text-center">Agente</th>
                        <?php if($isCategory) : ?>
                        <th class="text-center">Categoria</th>
                        <?php endif; ?>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sub as $key => $value) {
                        $agent = $app->repo('Agent')->find($value->owner->id); ?>
                        <tr>
                            <td class="text-center"><?php echo $value->number; ?></td>
                            <td class="text-left"><?php echo $agent->name; ?></td>
                            <?php if($isCategory) : ?>
                            <td class="text-left"><?php echo $value->category !== "" ? $value->category : "Não Informado"; ?></td>
                            <?php endif; ?>
                            <td class="text-center"><?php echo RegistrationStatus::getStatusNameById($value->status); ?> </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
<?php include_once('footer.php'); ?>