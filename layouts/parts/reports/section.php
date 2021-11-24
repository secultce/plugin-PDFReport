<?php
use PDFReport\Entities\Pdf;

/**
 * RETORNO DE DOS METADATAS DO AGENTE COM OS INDICES SENDO O VALOR QUE ESTÁ 
 * EM KEY NA TABELA E O RESULTADO SENDO O VALOR QUE ESTÁ EM VALUE NA TABELA
 */
$result = $reg->getAgentsData();
unset($result['owner']['nomeCompleto ']);

$newAgentData = [];
$newAgentData['shortDescription'] = $reg->owner->shortDescription;
$newAgentData['longDescription'] = $reg->owner->longDescription;
$newAgentData['nomeCompleto'] = $reg->owner->nomeCompleto;

$agentMetaData = array_merge($result['owner'], $newAgentData);

?>

<div class="border-section">
    <h4 style="color: rgba(0, 0, 0, 0.87); font-family: Arial !important;">
        <?php 
        echo $reg->opportunity->name;
        ?>
    </h4>

    <?php 
        $check = 'Não confirmado';
        foreach ($field as $fields) :
    ?>

    <span class="span-section">
        <?php
            if($fields['fieldType'] === 'section') {
                echo "<hr><br>";
                echo '<u>'.$fields['title'].'</u>';
            }else{
                echo $fields['title'].': ';
            }
            ?>
    </span>
    <span style="width: 20px; text-align: justify-all;"><?php 
        $valueMetas = Pdf::getValueField($fields['id'], $reg->id); 

        foreach ($valueMetas as $keyMeta => $valueMeta) {
           
            if($fields['fieldType'] == 'checkbox') {  
                if($valueMeta->value) {
                    echo $fields['description'];
                }else{
                    echo "Não informado";
                }
            }else if($fields['fieldType'] == 'cnpj') {

                echo Pdf::mask($valueMeta->value,'##.###.###/####-##');

            }else if($fields['fieldType'] == 'cpf') {
                
                echo Pdf::mask($valueMeta->value,'###.###.###-##');
            
            }else if($fields['fieldType'] == 'persons') {

                Pdf::showDecode($valueMeta->value, null, 'name');

            } else if ($fields['fieldType'] == 'space-field') {

                Pdf::showSpaceField($fields['config']['entityField'] , $valueMeta->value);

            } else 
            if($fields['fieldType'] == 'date') {

                echo date("d/m/Y", strtotime($valueMeta->value));

            }else if($fields['fieldType'] == 'links') {

                Pdf::showDecode($valueMeta->value, 'title', 'value');

            }else if($fields['fieldType'] == 'checkboxes') {

                Pdf::showItensCheckboxes($valueMeta->value);

            }else if($fields['fieldType'] == 'agent-collective-field') {

                Pdf::showAgentCollectiveField($fields['config']['entityField'], $valueMeta->value );
                
            }else if($fields['fieldType'] !== 'agent-owner-field')  {

                echo trim(preg_replace('/\PL/u', ' ', $valueMeta->value));

            }
        }
            
        if($fields['fieldType'] === 'brPhone' || $fields['fieldType'] == 'number') {
            echo $valueMeta->value;
            
        }else
        
        if ($fields['fieldType'] ==  'agent-owner-field') {    

            Pdf::showAgenteOwnerField($fields['config']['entityField'], $valueMeta->value, $agentMetaData);
        }
        ?></span><br>
    <?php
        endforeach;
    ?>

</div>
    </main>
