<?php

use MapasCulturais\App;
use MapasCulturais\Entities\RegistrationFile;

/**
 * RETORNO DE DOS METADADOS DO AGENTE COM OS INDICES SENDO O VALOR QUE ESTÁ
 * EM KEY NA TABELA E O RESULTADO SENDO O VALOR QUE ESTÁ EM VALUE NA TABELA
 *
 * @var \MapasCulturais\Entities\Registration $reg
 * @var array $fieldsOpportunities
 */
$result = $reg->getAgentsData();
unset($result['owner']['nomeCompleto ']);

$newAgentData = [];
$newAgentData['shortDescription'] = $reg->owner->shortDescription;
$newAgentData['longDescription'] = $reg->owner->longDescription;
$newAgentData['nomeCompleto'] = $reg->owner->nomeCompleto;

$agentMetaData = array_merge($result['owner'], $newAgentData);

$registrationMeta = $reg->getMetadata();

$iniSpan = '<span class="my-registration-value-span">';
$iniTitleSpan = '<span class="my-registration-value-span" style="font-weight: bold">';
$endSpan = '</span>';
?>

<?php foreach(array_reverse($fieldsOpportunities) as $fieldsOpportunity) { ?>
<div class="border-section">
    <h4 style="color: rgba(0, 0, 0, 0.87);font-family: Arial !important;">
        <?php
        echo $fieldsOpportunity['opportunityName'];
        ?>
    </h4>
    <?php
    foreach ($fieldsOpportunity['fields'] as $field) {
        $fieldName = 'field_' . $field['id'];

        if (isset($field['config']['require'])) {
            if (($field['config']['require']['hide'] ?? false) === '1') {
                continue;
            }

            $requiredFieldName = $field['config']['require']['field'];
            if ($field['config']['require']['value'] !== $reg->$requiredFieldName) {
                continue;
            }
        }

        switch ($field['fieldType']) {
            case 'agent-owner-field':
                $value = $field['config']['entityField'] === '@location'
                    ? $reg->$fieldName->endereco
                    : $reg->$fieldName;

                $value = $field['config']['entityField'] === 'dataDeNascimento'
                    ? date('d/m/Y', strtotime($value))
                    : $value;

                echo "<div>{$iniTitleSpan}{$field['title']}{$endSpan}: {$iniSpan}{$value}{$endSpan}</div>";
                break;
            case 'section': ?>
                <h5 style="padding-left: 20px;margin-bottom: 2px;text-decoration: underline"><?= $field['title'] ?></h5>
                <?php break;
            case 'checkbox': ?>
                <div>
                    <span class="my-registration-value-span" style="font-weight: bold;"><?= $field['title'] ?>: </span>
                    <span class="my-registration-value-span"><?= $reg->$fieldName === 'true' ? 'Sim' : 'Nao' ?></span>
                </div>
                <?php break;
            case 'file': ?>
                <div>
                    <?= $iniTitleSpan . $field['title'] . $endSpan ?>:
                    <?php foreach ($field['config'] as $file) {
                        /** @var RegistrationFile $file */
                        $file = App::i()->repo(RegistrationFile::class)
                            ->find($file['id']);

                        echo "<a href='{$file->getUrl()}' class='my-reg-font-10'>{$file->name}</a> <br />";
                    } ?>
                </div>
                <?php break;
            case 'select':
            case 'text': ?>
                <div>
                    <?= $iniTitleSpan . $field['title'] . $endSpan . ': '
                        . $iniSpan . $reg->$fieldName . $endSpan ?>
                </div>
                <?php break;
            case 'date': ?>
                <div>
                    <?= $iniTitleSpan . $field['title'] . $endSpan . ': '
                        . $iniSpan . date('d/m/Y', strtotime($reg->$fieldName)) . $endSpan ?>
                </div>
                <?php break;
            case 'links':
                echo "<div>{$iniTitleSpan}{$field['title']}{$endSpan}: ";
                $links = array_map(function ($link) {
                    return "<a href='{$link}' target='_blank' rel='noopener noreferer'>{$link}</a>";
                }, $reg->$fieldName);

                echo implode('<br>', $links) . '</div>';
                break;
            case 'textarea':
                echo "<div>{$iniTitleSpan}{$field['title']}{$endSpan}: ";
                echo "{$iniSpan}{$reg->$fieldName}{$endSpan}</div>";
                break;
            default:
                break;
        }
    }
echo '</div>';
}
