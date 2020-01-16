<div id="child_select" class="crm-section editrow_child_select-section form-item">
    <div class="label">{$form.child_select.label}</div>
    <div class="content">{$form.child_select.html}</div>
</div>
{literal}
<script type="text/javascript">
CRM.$(function($) {

  $('#child_select').insertAfter($('[id^=editrow-email-]'));
  var slozoo = 0;
  {/literal}
  {if $slozoo}
    {literal}
      var slozoo = 1;
      var childunder = '{/literal}{$smarty.const.CHILDUNDER}{literal}';
      var childthree = '{/literal}{$smarty.const.CHILDTHREE}{literal}';
      var childplus = '{/literal}{$smarty.const.CHILDPLUS}{literal}';
      var freeparent = '{/literal}{$smarty.const.FREEPARENT}{literal}';
    {/literal}    
  {/if}
  {literal}
  var childprice = '{/literal}{$smarty.const.CHILDPRICE}{literal}';
  {/literal}
  {if $slovar}
    {literal}
      var slovar = 1;
      var childprice = '{/literal}price_{$childPrice}{literal}';
    {/literal}
  {/if}
  {if $isMultipleChild}
    {literal}
      var multichild = 1;
    var childprice = 'child_select';
    {/literal}
  {/if}
  {literal}
  /* var parentprice = '{/literal}{$smarty.const.PARENTPRICE}{literal}';

  var parent1fn = '{/literal}{$smarty.const.PARENT1FN}{literal}';
  var parent1ln = '{/literal}{$smarty.const.PARENT1LN}{literal}';

  var parent2fn = '{/literal}{$smarty.const.PARENT2FN}{literal}';
  var parent2ln = '{/literal}{$smarty.const.PARENT2LN}{literal}'; */

  var child1fn = '{/literal}{$smarty.const.CHILD1FN}{literal}';
  var child1ln = '{/literal}{$smarty.const.CHILD1LN}{literal}';
  var child1dob = '{/literal}{$smarty.const.CHILD1DOB}{literal}';
  var child1gen = '{/literal}{$smarty.const.CHILD1GEN}{literal}';

  var child2fn = '{/literal}{$smarty.const.CHILD2FN}{literal}';
  var child2ln = '{/literal}{$smarty.const.CHILD2LN}{literal}';
  var child2dob = '{/literal}{$smarty.const.CHILD2DOB}{literal}';
  var child2gen = '{/literal}{$smarty.const.CHILD2GEN}{literal}';

  var child3fn = '{/literal}{$smarty.const.CHILD3FN}{literal}';
  var child3ln = '{/literal}{$smarty.const.CHILD3LN}{literal}';
  var child3dob = '{/literal}{$smarty.const.CHILD3DOB}{literal}';
  var child3gen = '{/literal}{$smarty.const.CHILD3GEN}{literal}';

  var child4fn = '{/literal}{$smarty.const.CHILD4FN}{literal}';
  var child4ln = '{/literal}{$smarty.const.CHILD4LN}{literal}';
  var child4dob = '{/literal}{$smarty.const.CHILD4DOB}{literal}';
  var child4gen = '{/literal}{$smarty.const.CHILD4GEN}{literal}';

  //var parent1profile = 26;
  //var parent2profile = 27;
  var child1profile = 32;
  var child2profile = 28;
  var child3profile = 29;
  var child4profile = 33;

  /* $('.crm-profile-id-'+parent1profile).hide();
  $('.crm-profile-id-'+parent2profile).hide(); */
  $('.crm-profile-id-'+child1profile).hide();
  $('.crm-profile-id-'+child2profile).hide();
  $('.crm-profile-id-'+child3profile).hide();
  $('.crm-profile-id-'+child4profile).hide();

  // Children
  var selectedchildren = $('select#'+childprice).select2('data');

  // If SloZoo
  if (slozoo) {
    var childunderel = $('#'+childunder);
    var childthreeel = $('#'+childthree);
    var childplusel = $('#'+childplus);
    var freeparentel = $('#'+freeparent);

    var cunder = parseInt(childunderel.val()) || 0;
    var cthree = parseInt(childthreeel.val()) || 0;
    var cplus = parseInt(childplusel.val()) || 0;
    var fparent = parseInt(freeparentel.val()) || 0;
    var totalchildren = cunder + cthree + cplus;

    if (totalchildren) {
      if (totalchildren == 1) {
        $('.crm-profile-id-'+child1profile).show();
      }

      if (totalchildren == 2) {
        $('.crm-profile-id-'+child1profile).show();
        $('.crm-profile-id-'+child2profile).show();
      }

      if (totalchildren == 3) {
        $('.crm-profile-id-'+child1profile).show();
        $('.crm-profile-id-'+child2profile).show();
        $('.crm-profile-id-'+child3profile).show();
      }

      if (totalchildren >= 4) {
        $('.crm-profile-id-'+child1profile).show();
        $('.crm-profile-id-'+child2profile).show();
        $('.crm-profile-id-'+child3profile).show();
        $('.crm-profile-id-'+child4profile).show();
      }
    }

    $(freeparentel).keyup(function() {
      var cunder = parseInt(childunderel.val()) || 0;
      var cthree = parseInt(childthreeel.val()) || 0;
      var cplus = parseInt(childplusel.val()) || 0;
      var fparent = parseInt(freeparentel.val()) || 0;
      var totalchildren = cunder + cthree + cplus;
      if (fparent > totalchildren) {
        CRM.alert({/literal}'{ts escape="js"}Free Caregiver must be equal or less than # of tickets for children with ASD selected{/ts}'{literal});
      }
    });

    $(childunderel).add(childthreeel).add(childplusel).keyup(function() {
      var cunder = parseInt(childunderel.val()) || 0;
      var cthree = parseInt(childthreeel.val()) || 0;
      var cplus = parseInt(childplusel.val()) || 0;
      var totalchildren = cunder + cthree + cplus;
      if (totalchildren > 4) {
        CRM.alert({/literal}'{ts escape="js"}Please select only upto 4 children with ASD{/ts}'{literal});
      }
      //freeparentel.val(totalchildren);

      if (totalchildren) {
        if (totalchildren == 1) {
          $('.crm-profile-id-'+child1profile).show();
          $('#'+child2fn).val('');
          $('#'+child2ln).val('');
          $('#'+child2gen).select2('val', '');
          $('#'+child2dob).val('');
          $('#'+child2dob).next('input').datepicker('setDate', null);
          $('.crm-profile-id-'+child2profile).hide();
          $('#'+child3fn).val('');
          $('#'+child3ln).val('');
          $('#'+child3gen).select2('val', '');
          $('#'+child3dob).val('');
          $('#'+child3dob).next('input').datepicker('setDate', null);
          $('.crm-profile-id-'+child3profile).hide();
        }

        if (totalchildren == 2) {
          $('.crm-profile-id-'+child1profile).show();
          $('.crm-profile-id-'+child2profile).show();
          $('#'+child3fn).val('');
          $('#'+child3ln).val('');
          $('#'+child3gen).select2('val', '');
          $('#'+child3dob).val('');
          $('#'+child3dob).next('input').datepicker('setDate', null);
          $('.crm-profile-id-'+child3profile).hide();
          $('#'+child4fn).val('');
          $('#'+child4ln).val('');
          $('#'+child4gen).select2('val', '');
          $('#'+child4dob).val('');
          $('#'+child4dob).next('input').datepicker('setDate', null);
          $('.crm-profile-id-'+child4profile).hide();
        }

        if (totalchildren == 3) {
          $('.crm-profile-id-'+child1profile).show();
          $('.crm-profile-id-'+child2profile).show();
          $('.crm-profile-id-'+child3profile).show();
          $('#'+child4fn).val('');
          $('#'+child4ln).val('');
          $('#'+child4gen).select2('val', '');
          $('#'+child4dob).val('');
          $('#'+child4dob).next('input').datepicker('setDate', null);
          $('.crm-profile-id-'+child4profile).hide();
        }

        if (totalchildren >= 4) {
          $('.crm-profile-id-'+child1profile).show();
          $('.crm-profile-id-'+child2profile).show();
          $('.crm-profile-id-'+child3profile).show();
          $('.crm-profile-id-'+child4profile).show();          
        }

        if (totalchildren === 0) {
          $('.crm-profile-id-'+child1profile).hide();
          $('.crm-profile-id-'+child2profile).hide();
          $('.crm-profile-id-'+child3profile).hide();
          $('.crm-profile-id-'+child4profile).hide();
          $('#'+child2fn).val('');
          $('#'+child2ln).val('');
          $('#'+child2gen).select2('val', '');
          $('#'+child2dob).val('');
          $('#'+child2dob).next('input').datepicker('setDate', null);
          $('#'+child3fn).val('');
          $('#'+child3ln).val('');
          $('#'+child3gen).select2('val', '');
          $('#'+child3dob).val('');
          $('#'+child3dob).next('input').datepicker('setDate', null);
          $('#'+child4fn).val('');
          $('#'+child4ln).val('');
          $('#'+child4gen).select2('val', '');
          $('#'+child4dob).val('');
          $('#'+child4dob).next('input').datepicker('setDate', null);
        }
      }
    });
  }

  if (selectedchildren) {
    if (selectedchildren.text == 1) {
      $('.crm-profile-id-'+child1profile).show();
    }

    if (selectedchildren.text == 2) {
      $('.crm-profile-id-'+child1profile).show();
      $('.crm-profile-id-'+child2profile).show();
    }

    if (selectedchildren.text == 3) {
      $('.crm-profile-id-'+child1profile).show();
      $('.crm-profile-id-'+child2profile).show();
      $('.crm-profile-id-'+child3profile).show();
    }

    if (selectedchildren.text == 4) {
      $('.crm-profile-id-'+child1profile).show();
      $('.crm-profile-id-'+child2profile).show();
      $('.crm-profile-id-'+child3profile).show();
      $('.crm-profile-id-'+child4profile).show();
    }
  }


  $('select#'+childprice).select2().on("change", function(e) {
    var noofchildren = e.added.text;

    if (noofchildren == 1) {
      $('.crm-profile-id-'+child1profile).show();
      $('#'+child2fn).val('');
      $('#'+child2ln).val('');
      $('#'+child2gen).select2('val', '');
      $('#'+child2dob).val('');
      $('#'+child2dob).next('input').datepicker('setDate', null);
      $('.crm-profile-id-'+child2profile).hide();
      $('#'+child3fn).val('');
      $('#'+child3ln).val('');
      $('#'+child3gen).select2('val', '');
      $('#'+child3dob).val('');
      $('#'+child3dob).next('input').datepicker('setDate', null);
      $('.crm-profile-id-'+child3profile).hide();
    }

    if (noofchildren == 2) {
      $('.crm-profile-id-'+child1profile).show();
      $('.crm-profile-id-'+child2profile).show();
      $('#'+child3fn).val('');
      $('#'+child3ln).val('');
      $('#'+child3gen).select2('val', '');
      $('#'+child3dob).val('');
      $('#'+child3dob).next('input').datepicker('setDate', null);
      $('.crm-profile-id-'+child3profile).hide();
      $('#'+child4fn).val('');
      $('#'+child4ln).val('');
      $('#'+child4gen).select2('val', '');
      $('#'+child4dob).val('');
      $('#'+child4dob).next('input').datepicker('setDate', null);
      $('.crm-profile-id-'+child4profile).hide();
    }

    if (noofchildren == 3) {
      $('.crm-profile-id-'+child1profile).show();
      $('.crm-profile-id-'+child2profile).show();
      $('.crm-profile-id-'+child3profile).show();
      $('#'+child4fn).val('');
      $('#'+child4ln).val('');
      $('#'+child4gen).select2('val', '');
      $('#'+child4dob).val('');
      $('#'+child4dob).next('input').datepicker('setDate', null);
      $('.crm-profile-id-'+child4profile).hide();
    }

    if (noofchildren == 4) {
      $('.crm-profile-id-'+child1profile).show();
      $('.crm-profile-id-'+child2profile).show();
      $('.crm-profile-id-'+child3profile).show();
      $('.crm-profile-id-'+child4profile).show();
    }

    if (noofchildren == '- select -' || noofchildren == '- none -') {
      $('.crm-profile-id-'+child1profile).hide();
      $('.crm-profile-id-'+child2profile).hide();
      $('.crm-profile-id-'+child3profile).hide();
      $('.crm-profile-id-'+child4profile).hide();
      $('#'+child2fn).val('');
      $('#'+child2ln).val('');
      $('#'+child2gen).select2('val', '');
      $('#'+child2dob).val('');
      $('#'+child2dob).next('input').datepicker('setDate', null);
      $('#'+child3fn).val('');
      $('#'+child3ln).val('');
      $('#'+child3gen).select2('val', '');
      $('#'+child3dob).val('');
      $('#'+child3dob).next('input').datepicker('setDate', null);
      $('#'+child4fn).val('');
      $('#'+child4ln).val('');
      $('#'+child4gen).select2('val', '');
      $('#'+child4dob).val('');
      $('#'+child4dob).next('input').datepicker('setDate', null);
    }
  });

  // Parents
  /*
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
      $('#'+parent2fn).val('');
      $('#'+parent2ln).val('');
    }
  }); */
});
</script>
{/literal}

