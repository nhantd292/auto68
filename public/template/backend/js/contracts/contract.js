load_action('#loadProducts', '/xadmin/api/loadKovProducts/', null);
load_action('#loadProductsReturn', '/xadmin/api/loadKovProductsReturn/', null);
// Xử lý load tỉnh thành
$('select[name="location_city_id"]').change(function () {
    var select = 'input[name="location_district_id"]';
    var parent = $(select).parent();
    $('.select2-container', parent).select2('val', '');
    $(select).attr('data-parent', $(this).val());
});

var contactPhone = $('#contactPhone').text().trim();
if (contactPhone) {
    load_action('#load_contract', '/xadmin/api/list-contract-by-phone/', {phone: contactPhone});
}

// Kiểm tra thông tin khách hàng
var contactId = $('#contactId').text().trim();
if (contactId) {
    checkContactToElement(contactId, 'element');
}
updateTotal()

function updateTotal() {
    var total_product_old = unFormatNumber($(".total_contract_product input").val());
    var total_contract = 0;
    var total_contract_product = 0;
    var total_contract_vat = $('.total_contract_vat input').val() ? $('.total_contract_vat input').val() : 0;
    var fee_other = $('.fee_other input').val() ? $('.fee_other input').val() : 0;
    var total_contract_discount = $('.total_contract_discount input').val() ? $('.total_contract_discount input').val() : 0;
    $.each($('.list-product-contract tr'), function (index, value) {
        var price = $(this).find('.price > input').val() ? $(this).find('.price > input').val() : 0;
        var number = $(this).find('.numbers input').val() ? $(this).find('.numbers input').val() : 0;
        total_contract_product += parseInt(unFormatNumber(price) * unFormatNumber(number));
        total_contract += parseInt(unFormatNumber(price) * unFormatNumber(number));
    });
    total_contract += parseInt(unFormatNumber(total_contract_vat));
    total_contract += parseInt(unFormatNumber(fee_other));
    total_contract -= parseInt(unFormatNumber(total_contract_discount));
    $(".total_contract_vat input").val(formatNumber(total_contract_vat));
    $(".total_contract span").text(formatNumber(total_contract));
    $(".total_contract input").val(formatNumber(total_contract));
    $(".total_contract_product span").text(formatNumber(total_contract_product));
    $(".total_contract_product input").val(formatNumber(total_contract_product));
    if (total_contract_product != total_product_old) {
        // var params = JSON.parse('<?php echo json_encode($this->discounts_params); ?>');
        params['contract_value_min'] = total_contract_product;
        load_action('#gift', '/xadmin/api/checkGift/', params, false);
    }
}

function resetDiscounts() {
    $('.total_contract_discount input').val(0)
    $('tr.product_gif').remove()
}