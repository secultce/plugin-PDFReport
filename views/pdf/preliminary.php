<?php 
    $this->layout = 'nolayout-pdf'; 
    $sub = $app->view->jsObject['subscribers'];
    $nameOpportunity = $sub[0]->opportunity->name;
    $opportunity = $app->view->jsObject['opp'];
    $claimDisabled = $app->view->jsObject['claimDisabled'];
    include_once('header-pdf.php'); 
?>

<main>
    <div class="container">
        <div class="pre-text">Resultado Preliminar</div>
        <div class="opportunity-info">
            <p>Oportunidade: </p>
            <h4><?php echo $nameOpportunity ?></h4>
        </div>
    </div>
    <?php    
        $type = $opportunity->evaluationMethodConfiguration->type->id;
        if($opportunity->registrationCategories == "" &&  $type == 'technical'){
            include_once('technical-no-category.php');
        }elseif($opportunity->registrationCategories == "" &&  $type == 'simple'|| $type == 'documentary'){
            include_once('simple-documentary-no-category.php');
        }elseif($opportunity->registrationCategories !== "" &&  $type == 'technical'){
            include_once('technical-category.php');
        }elseif($opportunity->registrationCategories !== "" &&  $type == 'simple'|| $type == 'documentary'){
            include_once('simple-documentary-category.php');
        }
    ?>
</main>
