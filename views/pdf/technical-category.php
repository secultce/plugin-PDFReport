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

    function getSectionNote($opp, $registration, $section_id){
        $total = 0.00;
        $app = App::i();
        $committee = $opp->getEvaluationCommittee();
        $users = [];
        foreach ($committee as $item) {
            $users[] = $item->agent->user->id;
        }
        $evaluations = $app->repo('RegistrationEvaluation')->findByRegistrationAndUsersAndStatus($registration, $users);
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
        return $total / count($users);
    }
?>
<div class="container">
    <?php 
    foreach ($opp->registrationCategories as $key_first => $nameCat) :?>
        <div class="table-info-cat">
            <span><?php echo $nameCat; ?></span>
        </div>
        <table id="table-preliminar" width="100%">
            <thead>
                <tr style="border: 1px solid #CFDCE5;">
                    <?php 
                        if(isset($preliminary)){
                            echo '<th class="text-left" width="25%">Classificação</th>';
                        }
                    ?>
                    <th class="text-left" style="margin-top: 5px;" width="25%">Inscrição</th>
                    <th class="text-left" width="40%">Candidatos</th>
                    <?php 
                        if(isset($preliminary)){
                            echo '<th class="text-center" width="10%">NF</th>' ;
                        }else{
                            foreach($sections as $key => $sec){
                                if(in_array($nameCat, $sec->categories)){ ?>
                                    <th class="text-center" width="10%"><?php echo 'N'.($key + 1).'E' ?></th>
                        <?php   }
                            }
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                $countArray = [];
                foreach($sub as $key => $nameSub){
                    if($nameCat == $nameSub->category){
                        $countArray[$nameCat][] = $key;
                        ?>
                        <tr>
                            <?php 
                                if(isset($preliminary)){ ?>
                                    <td class="text-left"><?php echo count($countArray[$nameCat]) ?> </td>
                                <?php }
                            ?>
                            <td class="text-left"><?php echo $nameSub->number; ?></td>
                            <td class="text-left"><?php echo $nameSub->owner->name; ?></td>
                            <?php 
                                if(isset($preliminary)){ ?>
                                    <td class="text-center"><?php echo $nameSub->consolidatedResult; ?></td>
                                <?php } else{
                                    foreach($sections as $key => $sec){ 
                                        if(in_array($nameSub->category, $sec->categories)){ ?>
                                            <td class="text-center"><?php echo getSectionNote($opp, $nameSub, $sec->id); ?></td>
                            <?php       } 
                                    }
                                }
                            ?>
                        </tr>
                    <?php
                    }
                }
                ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>
