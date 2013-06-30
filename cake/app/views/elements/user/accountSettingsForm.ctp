<?php // views/elements/user/accountSettings.ctp
//$userSetting
?>

<fieldset class="UserFieldset">
  <legend>Account Settings</legend>
  <?php foreach($settingTypes as $settingType): ?>
    <?php
    $label = '<span class="bold biggest">' . $settingType['SettingType']['name'] . '</span>';
    $label .= '<span class="mar10">' . $settingType['SettingType']['description'] . '</span>';
    echo $form->input('Settings.' . $settingType['SettingType']['id'], array('type' => 'checkbox', 'label' => $label));
    ?>
  <?php endforeach; ?>
</fieldset>
