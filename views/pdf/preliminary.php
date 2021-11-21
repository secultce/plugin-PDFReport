<?php 
    $this->layout = 'nolayout-pdf'; 
    $sub = $app->view->jsObject['subscribers'];
    $nameOpportunity = $sub[0]->opportunity->name;
    $opportunity = $app->view->jsObject['opp'];
    $claimDisabled = $app->view->jsObject['claimDisabled'];
    include_once('header.php'); 
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
        //REDIRECIONA PARA OPORTUNIDADE CASO NÃO HAJA CATEGORIA        
        $type = $opportunity->evaluationMethodConfiguration->type->id;
        // dump($type);
        // dump($opportunity->registrationCategorie);
        // die;
        //QUANDO NÃO TIVER RECURSO OU ESTIVER DESABILITADO
        if($opportunity->registrationCategories == "" &&  $type == 'technical'){
            include_once('technical-no-category.php');
        }elseif($opportunity->registrationCategories == "" &&  $type == 'simple'|| $type == 'documentary'){
            include_once('simple-documentary-no-category.php');
        }

        if($opportunity->registrationCategories !== "" &&  $type == 'technical'){
            $preliminary = true;
            include_once('technical-category.php');
        }elseif($opportunity->registrationCategories !== "" &&  $type == 'simple'|| $type == 'documentary'){
            include_once('simple-documentary-category.php');
        }
    ?>
</main>
