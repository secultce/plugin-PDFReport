<?php 
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
        <table width="100%">
            <thead>
                <tr>
                    <th class="text-left" width="30%">Inscrição</th>
                    <th class="text-left" width="50%">Agentes candidatos</th>
                    <th class="text-left" width="20%">??</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $isExist = false;
                foreach($sub as $key => $nameSub){ 
                    if($nameCat == $nameSub->category){?>
                        <tr>
                            <td><?php echo $nameSub->number; ?></td>
                            <td><?php echo $nameSub->owner->name; ?></td>
                        </tr>
                    <?php
                    }
                }
                ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>