{literal}
<script type="text/javascript">
CRM.$(function($) {
  var childprice = '{/literal}{$smarty.const.CHILDPRICE}{literal}';
  {/literal}
  {if $slovar}
    {literal}
      var slovar = 1;
      var childprice = '{/literal}price_{$childPrice}{literal}';
    {/literal}
  {/if}
  {literal}
  var parentprice = '{/literal}{$smarty.const.PARENTPRICE}{literal}';

  var parent1fn = '{/literal}{$smarty.const.PARENT1FN}{literal}';
  var parent1ln = '{/literal}{$smarty.const.PARENT1LN}{literal}';

  var parent2fn = '{/literal}{$smarty.const.PARENT2FN}{literal}';
  var parent2ln = '{/literal}{$smarty.const.PARENT2LN}{literal}';

  var parent1email = '{/literal}{$smarty.const.PARENT1EMAIL}{literal}';
  var parent2email = '{/literal}{$smarty.const.PARENT2EMAIL}{literal}';

  var child2fn = '{/literal}{$smarty.const.CHILD2FN}{literal}';
  var child2ln = '{/literal}{$smarty.const.CHILD2LN}{literal}';
  var child2dob = '{/literal}{$smarty.const.CHILD2DOB}{literal}';
  var child2email = '{/literal}{$smarty.const.CHILD2EMAIL}{literal}';

  var child3fn = '{/literal}{$smarty.const.CHILD3FN}{literal}';
  var child3ln = '{/literal}{$smarty.const.CHILD3LN}{literal}';
  var child3dob = '{/literal}{$smarty.const.CHILD3DOB}{literal}';
  var child3email = '{/literal}{$smarty.const.CHILD3EMAIL}{literal}';

  var child4fn = '{/literal}{$smarty.const.CHILD4FN}{literal}';
  var child4ln = '{/literal}{$smarty.const.CHILD4LN}{literal}';
  var child4dob = '{/literal}{$smarty.const.CHILD4DOB}{literal}';
  var child4email = '{/literal}{$smarty.const.CHILD4EMAIL}{literal}';

  var parent1profile = 21;
  var parent2profile = 22;
  var child2profile = 18;
  var child3profile = 19;
  var child4profile = 20;

  $('.crm-profile-id-'+parent1profile).hide();
  $('.crm-profile-id-'+parent2profile).hide();
  $('.crm-profile-id-'+child2profile).hide();
  $('.crm-profile-id-'+child3profile).hide();
  $('.crm-profile-id-'+child4profile).hide();

  // Children
  var selectedchildren = $('#'+childprice).select2('data');

  if (selectedchildren) {
    if (selectedchildren.text == 2) {
      $('.crm-profile-id-'+child2profile).show();
    }

    if (selectedchildren.text == 3) {
      $('.crm-profile-id-'+child2profile).show();
      $('.crm-profile-id-'+child3profile).show();
    }

    if (selectedchildren.text == 4) {
      $('.crm-profile-id-'+child2profile).show();
      $('.crm-profile-id-'+child3profile).show();
      $('.crm-profile-id-'+child4profile).show();
    }
  }


  $('#'+childprice).select2().on("change", function(e) {
    var noofchildren = e.added.text;

    if (noofchildren == 2) {
      $('.crm-profile-id-'+child2profile).show();
      $('#'+child3fn).val('');
      $('#'+child3ln).val('');
      $('#'+child3email).val('');
      $('#'+child3dob).val('');
      $('#'+child3dob).next('input').datepicker('setDate', null);
      $('.crm-profile-id-'+child3profile).hide();
      $('#'+child4fn).val('');
      $('#'+child4ln).val('');
      $('#'+child4email).val('');
      $('#'+child4dob).val('');
      $('#'+child4dob).next('input').datepicker('setDate', null);
      $('.crm-profile-id-'+child4profile).hide();
    }

    if (noofchildren == 3) {
      $('.crm-profile-id-'+child2profile).show();
      $('.crm-profile-id-'+child3profile).show();
      $('#'+child4fn).val('');
      $('#'+child4ln).val('');
      $('#'+child4email).val('');
      $('#'+child4dob).val('');
      $('#'+child4dob).next('input').datepicker('setDate', null);
      $('.crm-profile-id-'+child4profile).hide();
    }

    if (noofchildren == 4) {
      $('.crm-profile-id-'+child2profile).show();
      $('.crm-profile-id-'+child3profile).show();
      $('.crm-profile-id-'+child4profile).show();
    }

    if (noofchildren == '- select -') {
      $('.crm-profile-id-'+child2profile).hide();
      $('.crm-profile-id-'+child3profile).hide();
      $('.crm-profile-id-'+child4profile).hide();
      $('#'+child2fn).val('');
      $('#'+child2ln).val('');
      $('#'+child2email).select2('val', '');
      $('#'+child2dob).val('');
      $('#'+child2dob).next('input').datepicker('setDate', null);
      $('#'+child3fn).val('');
      $('#'+child3ln).val('');
      $('#'+child3email).val('');
      $('#'+child3dob).val('');
      $('#'+child3dob).next('input').datepicker('setDate', null);
      $('#'+child4fn).val('');
      $('#'+child4ln).val('');
      $('#'+child4email).val('');
      $('#'+child4dob).val('');
      $('#'+child4dob).next('input').datepicker('setDate', null);
    }
  });

  // Parents
  if ($('#'+parentprice).select2().text() == 1) {
    $('.crm-profile-id-'+parent1profile).show();
  }

  if ($('#'+parentprice).select2().text() == 2) {
    $('.crm-profile-id-'+parent1profile).show();
    $('.crm-profile-id-'+parent2profile).show();
  }

  $('#'+parentprice).select2().on("change", function(e) {
    var noofparents = e.added.text;

    if (noofparents == 1) {
      $('.crm-profile-id-'+parent1profile).show();
      $('#'+parent2fn).val('');
      $('#'+parent2ln).val('');
      $('#'+parent2email).val('');
      $('.crm-profile-id-'+parent2profile).hide();
    }

    if (noofparents == 2) {
      $('.crm-profile-id-'+parent1profile).show();
      $('.crm-profile-id-'+parent2profile).show();
    }

    if (noofparents == '- select -') {
      $('.crm-profile-id-'+parent1profile).hide();
      $('.crm-profile-id-'+parent2profile).hide();
      $('#'+parent1fn).val('');
      $('#'+parent1ln).val('');
      $('#'+parent1email).val('');
      $('#'+parent2fn).val('');
      $('#'+parent2ln).val('');
      $('#'+parent2email).val('');
    }
  });
});
</script>
{/literal}
