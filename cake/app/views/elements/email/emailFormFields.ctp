<?php // views/elements/emails/emailFormFields.ctp : renders email form fields

?>

  <?php
    echo $form->input('event_id', array('type' => 'hidden'));
    echo $form->input('rsvp', array('type' => 'checkbox', 'label' => 'Include RSVP form'));
    echo $form->input('title', array('label' => 'Subject'));
    echo $form->input('content', array('type' => 'textarea', 'rows' => 5));
  ?>
  <fieldset>
    <legend>Send Email to Which Player Types?</legend>
    <?php echo $form->input('PlayerTypes'); ?>
  </fieldset>
  <fieldset>
    <legend>Send Email to RSVP Status</legend>
    <?php echo $form->input('ResponseTypes'); ?>
  </fieldset>
  <fieldset>
    <legend>When to Send Email</legend>
    <table>
      <tr>
        <td>
          <?php echo $form->radio('send', array('now' => 'Now', 'days_before' => 'Days Before', 'send_on' => ''), array('legend' => false, 'separator' => '</td><td>')); ?>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
        <?php
        $values = array();
        for ($i = 0; $i < 15; $i++)
        {
          array_push($values, $i);
        }
        //echo $form->select('days_before', $values, null, array('onchange' => 'myfunction(this.id)'));
        echo $form->select('days_before', $days_before, null, array('onchange' => 'myfunction(this.id)'), false);
        ?>
        </td>
        <td>
        <?php echo $form->input('send_on', array('onchange' => 'myfunction(this.id)')); ?>
        </td>
      </tr>
    </table>
  </fieldset>
<?php if (false): ?>
  <fieldset>
      <legend>Condition (optional)</legend>
      <div class="mar10">By specifying a condition, the email will only be sent if the condition is met.</div>
      <table>
        <tr style="text-align: left;">
          <td><?php echo $form->input('condition_type_regular', array('type' => 'checkbox', 'label' => 'Regulars')); ?></td>
          <td><?php echo $form->input('condition_type_sub', array('type' => 'checkbox', 'label' => 'Subs')); ?></td>
          <td><?php echo $form->input('condition_type_member', array('type' => 'checkbox', 'label' => 'Members')); ?></td>
        </tr>
      </table>
      <?php echo $form->input('condition_type', array('type' => 'select', 'label' => 'Equality', 'options' => array('less than' => 'less than', 'greater than' => 'greater than'))) ?>
      <?php echo $form->input('condition_count', array('label' => 'Count', 'length' => 11)); ?>
  </fieldset>
<?php endif; ?>
      <?php if (false): ?>
      <div id="conditions">
      <?php
        $conditionsOptions = array(
          'update' => 'conditions'
        );
        $emailId = '';
        if (isset($this->data['Email']) && isset($this->data['Email']['id']))
        {
          $emailId = $this->data['Email']['id'];
        }
        echo $ajax->link('Add Condition', '/conditions/add/' . $emailId, $conditionsOptions);
      ?>
      <?php endif; ?>