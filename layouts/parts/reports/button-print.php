<div class="pdf-report-btn">
<?php $this->applyTemplateHook('pdf-report-btn','before') ?>
    <a href="<?php echo $app->createUrl('pdf', 'minha_inscricao/' . $id); ?>" class="btn btn-default" target="_blank" title="Imprima seu formulário em PDF">
        Imprimir em PDF
    </a>
    <?php $this->applyTemplateHook('pdf-report-btn','after') ?>
</div>
