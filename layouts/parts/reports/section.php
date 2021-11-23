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
                echo "<hr><br>";
                echo '<u>'.$fields['title'].'</u>';
            }else{
                echo $fields['title'].': ';
            }
            ?>
    </span>
    <span style="width: 20px; text-align: justify-all;"><?php 
        $valueMetas = Pdf::getValueField($fields['id'], $reg->id); 
            dump($fields);
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

            } else if ($fields['fieldType'] ==  'space-field') {
                $endereco = json_decode($valueMeta->value, true);

                $additional     = ( isset($endereco['En_Complemento'] ) && $endereco['En_Complemento'] != '' ) ? ", " . $endereco['En_Complemento']: "" ;
                $neighborhood   = ( isset($endereco['En_Bairro'] ) && $endereco['En_Bairro'] != '' ) ? ", " . $endereco['En_Bairro']: "" ;
                $city           = ( isset($endereco['En_Municipio'] ) && $endereco['En_Municipio'] != '' ) ? ", " . $endereco['En_Municipio']: "" ;
                $state          = ( isset($endereco['En_Estado'] ) && $endereco['En_Estado'] != '' ) ? ", " . $endereco['En_Estado']: "" ;
                $cep            = ( isset($endereco['En_CEP'] ) && $endereco['En_CEP'] != '' ) ? ", " . $endereco['En_CEP']: "" ;
                $address_number = ( isset($endereco['En_Num'] ) && $endereco['En_Num'] != '' ) ? ", " . $endereco['En_Num']: "" ;
                $street         = ( isset($endereco['En_Nome_Logradouro'] ) && $endereco['En_Nome_Logradouro'] != '' ) ? $endereco['En_Nome_Logradouro']: "" ;
                //montando endereço caso o $endereco == null
                $address = $street .  $address_number . $additional . $neighborhood . $cep . $city . $state;

                echo $address;
            } else if ($fields['fieldType'] ==  'agent-owner-field') {
                if ($fields['config']['entityField'] == '@location') {
                    $endereco = json_decode($valueMeta->value, true);

                    $additional     = ( isset($endereco['En_Complemento'] ) && $endereco['En_Complemento'] != '' ) ? ", " . $endereco['En_Complemento']: "" ;
                    $neighborhood   = ( isset($endereco['En_Bairro'] ) && $endereco['En_Bairro'] != '' ) ? ", " . $endereco['En_Bairro']: "" ;
                    $city           = ( isset($endereco['En_Municipio'] ) && $endereco['En_Municipio'] != '' ) ? ", " . $endereco['En_Municipio']: "" ;
                    $state          = ( isset($endereco['En_Estado'] ) && $endereco['En_Estado'] != '' ) ? ", " . $endereco['En_Estado']: "" ;
                    $cep            = ( isset($endereco['En_CEP'] ) && $endereco['En_CEP'] != '' ) ? ", " . $endereco['En_CEP']: "" ;
                    $address_number = ( isset($endereco['En_Num'] ) && $endereco['En_Num'] != '' ) ? ", " . $endereco['En_Num']: "" ;
                    $street         = ( isset($endereco['En_Nome_Logradouro'] ) && $endereco['En_Nome_Logradouro'] != '' ) ? $endereco['En_Nome_Logradouro']: "" ;
                    //montando endereço caso o $endereco == null
                    $address = $street .  $address_number . $additional . $neighborhood . $cep . $city . $state;
                    echo $address;
                }
            }else if($fields['fieldType'] == 'date') {
                echo date("d/m/Y", strtotime($valueMeta->value));
            }else if($fields['fieldType'] == 'links') {
                Pdf::showDecode($valueMeta->value, 'title', 'value');
            }else {
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
        die;
    ?>

</div>
    </main>
