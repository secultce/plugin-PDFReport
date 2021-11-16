<?php
use PDFReport\Entities\Pdf;

?>
<table style="width: 100%;">
    <tbody>
        <tr>
            <td>
                <div  style="border: 1px solid #E8E8E8; border-radius: 8px; width:  100%;; height: 400px;">
                    <h4 style="margin-left: 10px; color: rgba(0, 0, 0, 0.87); font-family: Arial !important;">
                        <?php 
                        echo $reg->opportunity->name;

                        ?>
                    </h4>

                    <?php 
                        $check = 'Não confirmado';
                        foreach ($field as $fields) :
                           
                    ?>
                        <label class="my-registration-fields">
                        <?php
                        if($fields['fieldType'] === 'section') {
                            echo "<br><br>";
                            echo '<u>'.$fields['title'].'</u>';
                        }else{
                            echo $fields['title'].' :';
                        }
                        ?>
                        </label>
                        <span style="width: 20px; text-align: justify-all;"><?php 
                            $valueMeta = Pdf::getValueField($fields['id']); 
                            foreach ($valueMeta as $keyMeta => $valueMeta) {
                               
                                if($fields['fieldType'] == 'checkbox' && $valueMeta->value == true) {                                   
                                    echo $fields['description'];
                                }

                                if($fields['fieldType'] == 'cnpj') {
                                    $cnpj = Pdf::mask($valueMeta->value,'##.###.###/####-##');
                                    echo $cnpj;
                                }

                                if($fields['fieldType'] == 'select') {
                                    echo $valueMeta->value;
                                }
                            }
                            
                            /**
                             * RETORNO DE TODOS OS METADATAS DO AGENTE COM OS INDICES SENDO O VALOR QUE ESTÁ 
                             * EM KEY NA TABELA E O RESULTADO SENDO O VALOR QUE ESTÁ EM VALUE NA TABELA
                             */
                            $agentMetaData = $reg->getAgentsData();
                            //VERIFICANDO SE É O CAMPO É agent-owner-field
                            if($fields['fieldType'] == 'agent-owner-field') {
                                //PASSANDO O VALOR QUE VEM EM CONFIG PARA SABER SE TEM O VALOR DENTRO DO ARRAY $agentMetaData
                                if(array_key_exists($fields['config']['entityField'], $agentMetaData['owner'])) {
                                    print_r($agentMetaData['owner'][$fields['config']['entityField']]);
                                }
                            }
     
                            ?></span><br>
                    <?php
                       endforeach;
                    ?>
                    
                </div>
            </td>
        </tr>
    </tbody>
</table>

