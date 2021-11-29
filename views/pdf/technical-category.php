<?php 
    use MapasCulturais\App;
    use Saude\Utils\RegistrationStatus;

    $this->layout = 'nolayout-pdf'; 
    $sub = $app->view->jsObject['subscribers'];
    $nameOpportunity = $sub[0]->opportunity->name;
    $opp = $app->view->jsObject['opp'];
    $sections = $opp->evaluationMethodConfiguration->sections;
    $criterios = $opp->evaluationMethodConfiguration->criteria;

    function invenDescSort($item1,$item2){
        if ($item1->consolidatedResult == $item2->consolidatedResult) return 0;
        return ($item1->consolidatedResult < $item2->consolidatedResult) ? 1 : -1;
    }
    usort($sub,'invenDescSort');
?>
<div class="container">
    <?php 
    foreach ($opp->registrationCategories as $key => $nameCat) :?>
        <div class="table-info-cat">
            <?php echo $nameCat; ?>
        </div>
        <table id="table-preliminar" width="100%">
            <thead>
                <tr>
                    <th class="text-center" width="25%">Classificação</th>
                    <th class="text-left" width="25%">Inscrição</th>
                    <th class="text-left" width="40%">Agentes candidatos</th>
                    <th class="text-center" width="10%"><?php echo !isset($preliminary) ? "RP" : "NF" ?></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach($sub as $key => $nameSub){ 
                    if($nameCat == $nameSub->category){
                        ?>
                        <tr>
                            <td class="text-center"><?php echo RegistrationStatus::getStatusNameById($nameSub->status); ?> </td>
                            <td class="text-left"><?php echo $nameSub->number; ?></td>
                            <td class="text-left"><?php echo $nameSub->owner->name; ?></td>
                            <td class="text-center"><?php echo !isset($preliminary) ? $nameSub->preliminaryResult : $nameSub->consolidatedResult; ?></td>
                        </tr>
                    <?php
                    }
                }
                ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>
