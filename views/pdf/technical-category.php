<?php 
    use MapasCulturais\App;

    $this->layout = 'nolayout-pdf'; 
    $sub = $app->view->jsObject['subscribers'];
    $nameOpportunity = $sub[0]->opportunity->name;
    $opp = $app->view->jsObject['opp'];
    $sections = $opp->evaluationMethodConfiguration->sections;
    $criterios = $opp->evaluationMethodConfiguration->criteria;

    function getSectionNote($opp, $registration, $section_id, $qtdWeightTotal){
        $total = 0.00;
        $app = App::i();
        $committee = $opp->getEvaluationCommittee();
        $users = [];
        foreach ($committee as $item) {
            $users[] = $item->agent->user->id;
        }
        $evaluations = $app->repo('RegistrationEvaluation')->findByRegistrationAndUsersAndStatus($registration, $users);
        $result = 0;
        foreach ($evaluations as $eval){
            $cfg = $eval->getEvaluationMethodConfiguration();
            $category = $eval->registration->category;
            $totalSection = 0.00;
            foreach ($cfg->criteria as $cri) {
                if ($section_id == $cri->sid) {
                    $key = $cri->id;
                    if(!isset($eval->evaluationData->$key)){
                        return null;
                    } else {
                        $val = floatval($eval->evaluationData->$key);
                        $totalSection += is_numeric($val) ? floatval($cri->weight) * floatval($val) : 0;
                    }
                }
            }
            $total += floatval($totalSection);
        }
        $total = $total / $qtdWeightTotal;
        return $total;
    }
?>
<div class="container">
    <?php 
    foreach ($opp->registrationCategories as $key => $nameCat) :?>
        <div class="table-info-cat">
            <?php echo $nameCat; ?>
        </div>
        <table width="100%">
            <thead>
                <tr>
                    <th class="text-center" width="10%">Inscrição</th>
                    <th class="text-center" width="20%">Agentes candidatos</th>
                    <?php 
                        foreach ($sections as $section) {
                            if(in_array($nameCat, $section->categories)){?>
                                <th class="text-center" width="<?php echo (60 / count($sections)) ?>%"><?php echo $section->name; ?></th>
                    <?php   } 
                    }
                    ?>
                    <th class="text-center" width="10%">Resultado Preliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach($sub as $key => $nameSub){ 
                    if($nameCat == $nameSub->category){
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $nameSub->number; ?></td>
                            <td class="text-center"><?php echo $nameSub->owner->name; ?></td>
                            <?php 
                                foreach ($sections as $section) {
                                    if(in_array($nameCat, $section->categories)){?>
                                        <td class="text-center"><?php echo getSectionNote($opp, $nameSub, $section->id, $section->weight); ?></td>
                            <?php   }
                                }
                            ?>
                            <td class="text-center"><?php echo $nameSub->preliminaryResult; ?></td>
                        </tr>
                    <?php
                    }
                }
                ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>
