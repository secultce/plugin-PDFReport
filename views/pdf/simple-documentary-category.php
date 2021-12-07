<?php 
    use Saude\Utils\RegistrationStatus;

    $this->layout = 'nolayout-pdf'; 
    $sub = $app->view->jsObject['subscribers'];
    $nameOpportunity = $sub[0]->opportunity->name;
    $opp = $app->view->jsObject['opp'];

?>
<div class="container">
    <?php 
    foreach ($opp->registrationCategories as $keyCat => $nameCat) :?>
    
        <div class="table-info-cat">
            <?php echo $nameCat; ?>
        </div>
        <table id="table-preliminar" width="100%" >
            <thead>
                <tr>
                    <th class="text-center" width="30%">Inscrição</th>
                    <th class="text-center" width="50%">Candidatos</th>
                    <th class="text-center" width="20%">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $isExist = false;
                foreach($sub as $key => $nameSub){ 
                    if($nameCat == $nameSub->category){?>
                        <tr>
                            <td class="text-center"><?php echo $nameSub->number; ?></td>
                            <td class="text-center"><?php echo $nameSub->owner->name; ?></td>
                            <td class="text-center"><?php echo RegistrationStatus::getStatusNameById($nameSub->status); ?> </td>
                        </tr>
                    <?php
                    }
                }
                ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>