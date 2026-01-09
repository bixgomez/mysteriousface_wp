/**
 * Admin Meta Boxes JavaScript
 * Handles personnel repeater functionality
 *
 * @package Mysterious_Face
 */

jQuery(document).ready(function($) {
    var personnelWrapper = $('.mf-personnel-repeater');
    var addButton = $('.mf-add-personnel');

    // Add new personnel row
    addButton.on('click', function(e) {
        e.preventDefault();
        var row = '<div class="mf-personnel-row">' +
            '<input type="text" name="personnel_name[]" placeholder="Name" value="" style="width: 40%;" /> ' +
            '<input type="text" name="personnel_contribution[]" placeholder="Contribution (instrument/role)" value="" style="width: 40%;" /> ' +
            '<button type="button" class="button mf-remove-personnel">Remove</button>' +
            '</div>';
        personnelWrapper.append(row);
        updatePersonnelJSON();
    });

    // Remove personnel row
    personnelWrapper.on('click', '.mf-remove-personnel', function(e) {
        e.preventDefault();
        $(this).closest('.mf-personnel-row').remove();
        updatePersonnelJSON();
    });

    // Update JSON on input changes
    personnelWrapper.on('input', 'input', function() {
        updatePersonnelJSON();
    });

    // Update hidden JSON field with current personnel data
    function updatePersonnelJSON() {
        var personnel = [];
        personnelWrapper.find('.mf-personnel-row').each(function() {
            var name = $(this).find('input[name="personnel_name[]"]').val();
            var contribution = $(this).find('input[name="personnel_contribution[]"]').val();
            // Include row even if empty (will be filtered on save)
            personnel.push({
                name: name,
                contribution: contribution
            });
        });
        $('#mf_personnel_json').val(JSON.stringify(personnel));
    }

    // Initialize JSON on page load
    if (personnelWrapper.find('.mf-personnel-row').length > 0) {
        updatePersonnelJSON();
    }
});
