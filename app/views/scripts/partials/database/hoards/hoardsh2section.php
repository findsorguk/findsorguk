<!-- This needs to be HOARD not objecttype -->
<h1 class="lead"><?php echo $this->escape(ucFirst($this->objecttype));?></h1>
<p><strong>Unique ID:</strong> <span class="fourfigure"><?php echo $this->escape($this->old_findID);?></span></p>
<?php if(!is_null($this->objecttypecert)):?>
    <p>
        <!-- We may not be including certainty for hoards -->
        Object type certainty: <?php echo $this->certainty()->setCertainty($this->objecttypecert);?><br />
        Workflow status: <?php echo $this->workflowStatus()->setWorkflow($this->secwfstage);?> <?php echo $this->workflow()->setWorkflow($this->secwfstage);?>
    </p>
<?php endif;?>