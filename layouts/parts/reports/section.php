<?php
use PDFReport\Entities\Pdf;

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
                echo "<br><br>";
                echo '<u>'.$fields['title'].'</u>';
            }else{
                echo $fields['title'].' :';
            }
            ?>
    </span>
    <span style="width: 20px; text-align: justify-all;"><?php 
        $valueMeta = Pdf::getValueField($fields['id'], $reg->id); 
        foreach ($valueMeta as $keyMeta => $valueMeta) {
            
            if($fields['fieldType'] == 'checkbox') {  
                if($valueMeta->value) {
                    echo $fields['description'];
                }else{
                    echo "Não informado";
                }
            }else if($fields['fieldType'] == 'cnpj') {
                $cnpj = Pdf::mask($valueMeta->value,'##.###.###/####-##');
                echo $cnpj;

            }else if($fields['fieldType'] == 'persons') {

                $persons = json_decode($valueMeta->value, true);
                $namesPersons = [];
                foreach($persons as $person) {
                    $namesPersons[] = $person['name'];
                }
                echo implode(", ", $namesPersons);

            }else{

                echo $valueMeta->value;

            }

            // if($fields['fieldType'] == 'space-field') {
            //     echo 'space-field';
            // }
        }
                            
        /**
         * RETORNO DE TODOS OS METADATAS DO AGENTE COM OS INDICES SENDO O VALOR QUE ESTÁ 
         * EM KEY NA TABELA E O RESULTADO SENDO O VALOR QUE ESTÁ EM VALUE NA TABELA
         */
        $agentMetaData = $reg->getAgentsData();
        //VERIFICANDO SE É O CAMPO É agent-owner-field
        if($fields['fieldType'] == 'agent-owner-field') {
            //PASSANDO O VALOR QUE VEM EM CONFIG PARA SABER SE TEM O VALOR DENTRO DO ARRAY $agentMetaData
            if(isset($agentMetaData['owner'])) {
                if(array_key_exists($fields['config']['entityField'], $agentMetaData['owner'])) {
                    print_r($agentMetaData['owner'][$fields['config']['entityField']]);
                } 
            }
        }

        ?></span><br>
    <?php
        endforeach;
    ?>

</div>
    </main>
