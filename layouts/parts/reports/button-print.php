<div class="pdf-report-btn">
<?php $this->applyTemplateHook('pdf-report-btn','before') ?>
    <a href="<?php echo $app->createUrl('pdf', 'minha_inscricao/' . $id); ?>" class="btn btn-default" target="_blank" title="Imprima seu formulário em PDF">
        Imprimir em PDF
    </a>
    <a href="<?php echo $app->createUrl('pdf', 'inscricaoCompleta/' . $id); ?>" class="btn btn-default" target="_blank" title="Imprima seu formulário de todas as fases em PDF">
        Imprimir completo em PDF
    </a>
    <br><br>
    <?php $this->applyTemplateHook('pdf-report-btn','after') ?>
</div>
