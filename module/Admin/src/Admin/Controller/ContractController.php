<?php

namespace Admin\Controller;

use ZendX\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Form\FormInterface;

class ContractController extends ActionController {

    public function init() {

        // Thiết lập options
        $this->_options['tableName'] = 'Admin\Model\ContractTable';
        $this->_options['formName'] = 'formAdminContract';

        // Thiết lập session filter
        $action = str_replace('-', '_', $this->_params['action']);
        $ssFilter = new Container(__CLASS__. $action);

        $this->_params['ssFilter']['order_by']              = !empty($ssFilter->order_by) ? $ssFilter->order_by : 'date';
        $this->_params['ssFilter']['order']                 = !empty($ssFilter->order) ? $ssFilter->order : 'DESC';
        $this->_params['ssFilter']['filter_keyword']        = $ssFilter->filter_keyword;
        $this->_params['ssFilter']['filter_date_begin']     = $ssFilter->filter_date_begin;
        $this->_params['ssFilter']['filter_date_end']       = $ssFilter->filter_date_end;
        $this->_params['ssFilter']['filter_date_type']      = $ssFilter->filter_date_type;
        $this->_params['ssFilter']['filter_sale_branch']    = $ssFilter->filter_sale_branch;
        $this->_params['ssFilter']['filter_sale_group']     = $ssFilter->filter_sale_group;
        $this->_params['ssFilter']['filter_user']           = $ssFilter->filter_user;
        $this->_params['ssFilter']['filter_debt']           = $ssFilter->filter_debt;
        $this->_params['ssFilter']['filter_product_type'] 	= $ssFilter->filter_product_type;
        $this->_params['ssFilter']['filter_product'] 	    = $ssFilter->filter_product;
        $this->_params['ssFilter']['filter_bill_code']      = $ssFilter->filter_bill_code;
        $this->_params['ssFilter']['filter_status_type']    = $ssFilter->filter_status_type;
        $this->_params['ssFilter']['filter_status']         = $ssFilter->filter_status;
        $this->_params['ssFilter']['filter_shipper_id']     = $ssFilter->filter_shipper_id;
        $this->_params['ssFilter']['filter_contract_type']  = $ssFilter->filter_contract_type;
        $this->_params['ssFilter']['filter_hidden']         = $ssFilter->filter_hidden;
        $this->_params['ssFilter']['filter_carpet_color']   = $ssFilter->filter_carpet_color;
        $this->_params['ssFilter']['filter_tangled_color']  = $ssFilter->filter_tangled_color;
        $this->_params['ssFilter']['filter_flooring']       = $ssFilter->filter_flooring;
        $this->_params['ssFilter']['filter_coincider']      = $ssFilter->filter_coincider;
        $this->_params['ssFilter']['filter_status_store']   = $ssFilter->filter_status_store;
        $this->_params['ssFilter']['filter_status_lock']    = $ssFilter->filter_status_lock;

        // Thiết lập lại thông số phân trang
        $this->_paginator['itemCountPerPage'] = !empty($ssFilter->pagination_option) ? $ssFilter->pagination_option : $this->_paginator['itemCountPerPage'];
        $this->_paginator['currentPageNumber'] = $this->params()->fromRoute('page', 1);
        $this->_params['paginator'] = $this->_paginator;

        // Lấy dữ liệu post của form
        $this->_params['data'] = array_merge($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());

        // Truyển dữ dữ liệu ra ngoài view
        $this->_viewModel['params'] = $this->_params;
        $this->_viewModel['curent_user']  = $this->_userInfo->getUserInfo();
//        $this->_viewModel['encode_phone'] = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'encode-phone')), array('task' => 'list-all')), array('key' => 'content', 'value' => 'status'));
    }

    // Tìm kiếm
    public function filterAction() {
        if($this->getRequest()->isPost()) {
            $data = $this->_params['data'];

            $action = !empty($this->getRequest()->getPost('filter_action')) ? str_replace('-', '_', $this->getRequest()->getPost('filter_action')) : 'index';
            $ssFilter	= new Container(__CLASS__ . $action);

            $ssFilter->pagination_option        = intval($data['pagination_option']);
            $ssFilter->order_by                 = $data['order_by'];
            $ssFilter->order                    = $data['order'];
            $ssFilter->filter_keyword           = $data['filter_keyword'];
            $ssFilter->filter_date_begin        = $data['filter_date_begin'];
            $ssFilter->filter_date_end          = $data['filter_date_end'];
            $ssFilter->filter_date_type         = $data['filter_date_type'];
            $ssFilter->filter_debt 	            = $data['filter_debt'];
            $ssFilter->filter_product 	        = $data['filter_product'];
            $ssFilter->filter_bill_code         = $data['filter_bill_code'];
            $ssFilter->filter_status_type       = $data['filter_status_type'];
            $ssFilter->filter_status            = $data['filter_status'];
            $ssFilter->filter_contract_type     = $data['filter_contract_type'];
            $ssFilter->filter_shipper_id        = $data['filter_shipper_id'];
            $ssFilter->filter_user              = $data['filter_user'];
            $ssFilter->filter_action            = $data['filter_action'];
            $ssFilter->filter_hidden            = $data['filter_hidden'];
            $ssFilter->filter_carpet_color      = $data['filter_carpet_color'];
            $ssFilter->filter_tangled_color     = $data['filter_tangled_color'];
            $ssFilter->filter_flooring          = $data['filter_flooring'];
            $ssFilter->filter_coincider 	    = $data['filter_coincider'];
            $ssFilter->filter_status_store      = $data['filter_status_store'];
            $ssFilter->filter_status_lock       = $data['filter_status_lock'];

            if($data['filter_product_type'] != $ssFilter->filter_product_type) {
                $ssFilter->filter_product_type 	= $data['filter_product_type'];
                $ssFilter->filter_product = '';
            }

            $ssFilter->filter_sale_group = $data['filter_sale_group'];
            if(!empty($data['filter_sale_branch'])) {
                if($ssFilter->filter_sale_branch != $data['filter_sale_branch']) {
                    $ssFilter->filter_sale_group = null;
                    $ssFilter->filter_sale_branch = $data['filter_sale_branch'];
                }
            } else {
                $ssFilter->filter_sale_group = null;
                $ssFilter->filter_sale_branch = $data['filter_sale_branch'];
            }

            if($ssFilter['filter_date_type'] == 'date_debt') {
                if(empty($ssFilter->filter_date_begin)) {
                    $ssFilter->filter_date_begin = date('01/m/Y');
                    $ssFilter->filter_date_end = date('t/m/Y');
                }
            }

            if(empty($data['filter_status_type'])){
                $ssFilter->filter_status = null;
            }
        }
        $action = str_replace('_', '-', $this->getRequest()->getPost('filter_action'));
        $this->goRoute(['action' => $action]);
    }

    // Danh sách
    public function indexAction() {
        $ssFilter = new Container(__CLASS__.'index');
        // Phân quyền view
        $curent_user = $this->_userInfo->getUserInfo();
        $permission_ids = explode(',', $curent_user['permission_ids']);
        if(!in_array(SYSTEM, $permission_ids) && !in_array(ADMIN, $permission_ids) && !in_array(MANAGER, $permission_ids)){
            if(in_array(GDCN, $permission_ids) || in_array(SALEADMIN, $permission_ids)){
                $this->_params['ssFilter']['filter_sale_branch'] = $curent_user['sale_branch_id'];
                $ssFilter->filter_sale_branch = $curent_user['sale_branch_id'];
            }
            elseif (in_array(GROUP_SALES_LEADER, $permission_ids)){
                $this->_params['ssFilter']['filter_sale_branch'] = $curent_user['sale_branch_id'];
                $this->_params['ssFilter']['filter_sale_group'] = $curent_user['sale_group_id'];
                $ssFilter->filter_sale_branch = $curent_user['sale_branch_id'];
                $ssFilter->filter_sale_group = $curent_user['filter_sale_group'];
            }
            else{
                $this->_params['ssFilter']['filter_user'] = $curent_user['id'];
            }
        }

        $myForm	= new \Admin\Form\Search\Contract($this->getServiceLocator(), $this->_params);
        $myForm->setData($this->_params['ssFilter']);

        $items = $this->getTable()->listItem($this->_params, array('task' => 'list-item'));
        if( $this->_params['ssFilter']['filter_date_type'] == 'date_debt') {
            $count = $this->getTable()->listItem($this->_params, array('task' => 'list-item', 'paginator' => false))->count();
        } else {
            $count = $this->getTable()->countItem($this->_params, array('task' => 'list-item'));;
        }

        $userInfo = new \ZendX\System\UserInfo();
        $this->_viewModel['myForm']	                = $myForm;
        $this->_viewModel['items']                  = $items;
        $this->_viewModel['count']                  = $count;
        $this->_viewModel['user']                   = $this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['bill']                   = $this->getServiceLocator()->get('Admin\Model\BillTable')->listItem(null, array('task' => 'by-contract'));
        $this->_viewModel['bill_type']              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'bill-type')), array('task' => 'cache'));
        $this->_viewModel['sale_group']             = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache'));
        $this->_viewModel['sale_branch']            = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache'));
        $this->_viewModel['transport']              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'transport')), array('task' => 'cache'));
        $this->_viewModel['carpet_color']           = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['tangled_color']          = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['flooring']               = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        $this->_viewModel['production_department']  = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'production-department')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['product']                = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));
        $this->_viewModel['kovProduct']             = $this->getServiceLocator()->get('Admin\Model\KovProductsTable')->listItem(null, array('task' => 'cache'));


        $this->_viewModel['status_product']         = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'production-department')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['status_check']           = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'status-check')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['status_accounting']      = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'status-acounting')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['status_sales']           = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'status')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['caption']                = 'Đơn hàng - Danh sách';

        return new ViewModel($this->_viewModel);
    }

    // Danh sách
    public function lostAction() {
        $action = str_replace('-', '_', $this->_params['action']);
        $ssFilter = new Container(__CLASS__.$action);
        // Phân quyền view
        $curent_user = $this->_userInfo->getUserInfo();
        $permission_ids = explode(',', $curent_user['permission_ids']);
        if(!in_array(SYSTEM, $permission_ids) && !in_array(ADMIN, $permission_ids) && !in_array(MANAGER, $permission_ids)){
            if(in_array(GDCN, $permission_ids) || in_array(SALEADMIN, $permission_ids)){
                $this->_params['ssFilter']['filter_sale_branch'] = $curent_user['sale_branch_id'];
                $ssFilter->filter_sale_branch = $curent_user['sale_branch_id'];
            }
            elseif (in_array(GROUP_SALES_LEADER, $permission_ids)){
                $this->_params['ssFilter']['filter_sale_branch'] = $curent_user['sale_branch_id'];
                $this->_params['ssFilter']['filter_sale_group'] = $curent_user['sale_group_id'];
                $ssFilter->filter_sale_branch = $curent_user['sale_branch_id'];
                $ssFilter->filter_sale_group = $curent_user['filter_sale_group'];
            }
            else{
                $this->_params['ssFilter']['filter_user'] = $curent_user['id'];
            }
        }
        $myForm	= new \Admin\Form\Search\Contract($this->getServiceLocator(), $this->_params);
        $myForm->setData($this->_params['ssFilter']);

        $items = $this->getTable()->listItem($this->_params, array('task' => 'list-item-lost'));
        $count = $this->getTable()->countItem($this->_params, array('task' => 'list-item-lost'));

        $userInfo = new \ZendX\System\UserInfo();
        $this->_viewModel['myForm']	                = $myForm;
        $this->_viewModel['items']                  = $items;
        $this->_viewModel['count']                  = $count;
        $this->_viewModel['user']                   = $this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['bill']                   = $this->getServiceLocator()->get('Admin\Model\BillTable')->listItem(null, array('task' => 'by-contract'));
        $this->_viewModel['bill_type']              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'bill-type')), array('task' => 'cache'));
        $this->_viewModel['sale_group']             = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache'));
        $this->_viewModel['sale_branch']            = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache'));
        $this->_viewModel['transport']              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'transport')), array('task' => 'cache'));
        $this->_viewModel['carpet_color']           = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['tangled_color']          = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['flooring']               = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        $this->_viewModel['production_department']  = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'production-department')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['product']                = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));
        $this->_viewModel['kovProduct']             = $this->getServiceLocator()->get('Admin\Model\KovProductsTable')->listItem(null, array('task' => 'cache'));

        $this->_viewModel['status_product']         = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'production-department')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['status_check']           = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'status-check')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['status_accounting']      = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'status-acounting')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['status_sales']           = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'status')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['caption']                = 'Đơn hàng - Danh sách';

        return new ViewModel($this->_viewModel);
    }

    // Danh sách đơn hàng có sẵn
    public function warehouseAction() {
        $ssFilter = new Container(__CLASS__.'warehouse');
        $myForm	= new \Admin\Form\Search\Contract($this->getServiceLocator(), $this->_params);

        $myForm->setData($this->_params['ssFilter']);
        $this->_params['ssFilter']['filter_hidden'] = 0;

        $items = $this->getTable()->listItem($this->_params, array('task' => 'list-item-warehouse'));
        $count = $this->getTable()->countItem($this->_params, array('task' => 'list-item-warehouse'));

        $userInfo                                   = new \ZendX\System\UserInfo();
        $this->_viewModel['myForm']	                = $myForm;
        $this->_viewModel['items']                  = $items;
        $this->_viewModel['count']                  = $count;
        $this->_viewModel['user']                   = $this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['bill']                   = $this->getServiceLocator()->get('Admin\Model\BillTable')->listItem(null, array('task' => 'by-contract'));
        $this->_viewModel['bill_type']              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'bill-type')), array('task' => 'cache'));
        $this->_viewModel['sale_group']             = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache'));
        $this->_viewModel['sale_branch']            = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache'));
        $this->_viewModel['transport']              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'transport')), array('task' => 'cache'));
        $this->_viewModel['carpet_color']           = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['tangled_color']          = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['flooring']               = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        $this->_viewModel['production_department']  = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'production-department')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['product']                = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));
        $this->_viewModel['caption']                = 'Danh sách hàng có sẵn';

        return new ViewModel($this->_viewModel);
    }

    // Danh sách đơn hàng có sẵn đã ẩn
    public function warehouseHiddenAction() {
        $ssFilter = new Container(__CLASS__.'warehouse');
        $myForm	= new \Admin\Form\Search\Contract($this->getServiceLocator(), $this->_params);

        $myForm->setData($this->_params['ssFilter']);
        $this->_params['ssFilter']['filter_hidden'] = 1;



        $items = $this->getTable()->listItem($this->_params, array('task' => 'list-item-warehouse'));
        $count = $this->getTable()->countItem($this->_params, array('task' => 'list-item-warehouse'));

        $userInfo                                   = new \ZendX\System\UserInfo();
        $this->_viewModel['myForm']	                = $myForm;
        $this->_viewModel['items']                  = $items;
        $this->_viewModel['count']                  = $count;
        $this->_viewModel['user']                   = $this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['bill']                   = $this->getServiceLocator()->get('Admin\Model\BillTable')->listItem(null, array('task' => 'by-contract'));
        $this->_viewModel['bill_type']              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'bill-type')), array('task' => 'cache'));
        $this->_viewModel['sale_group']             = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache'));
        $this->_viewModel['sale_branch']            = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache'));
        $this->_viewModel['transport']              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'transport')), array('task' => 'cache'));
        $this->_viewModel['carpet_color']           = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['tangled_color']          = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['flooring']               = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        $this->_viewModel['production_department']  = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'production-department')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['product']                = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));
        $this->_viewModel['caption']                = 'Danh sách hàng có sẵn đã ẩn';

        return new ViewModel($this->_viewModel);
    }

    // Thêm mới đơn hàng
    public function addAction() {
        // Sale nào tạo đơn: sale cửa hàng(1) hay telesale(null)
        $numberFormat = new \ZendX\Functions\Number();
        $status_store = !empty($this->params('code')) ? 1 : 0;

        $contact_item = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $this->params('id')));
        $sales_manager = $this->getServiceLocator()->get('Admin\Model\UserTable')->getItem(array('id' => $contact_item['user_id']));

        $myForm = $this->getForm();
        if($this->getRequest()->isPost()){
            $myForm->setInputFilter(new \Admin\Filter\Contract(array('data' => $this->_params['data'], 'route' => $this->_params['route'])));
            $this->_params['data']['phone'] = $contact_item['phone'];
            $this->_params['data']['status_store'] = $status_store;
            $myForm->setData($this->_params['data']);
            $controlAction = $this->_params['data']['control-action'];
            if(!empty($contact_item)){
                if($myForm->isValid()){
                    $contract_product = $this->_params['data']['contract_product'];
                    $check_emty_data = true;// kiểm tra thông tin sản phẩm của đơn hàng đã đầy đủ chưa
                    $check_order = true; // kiểm tra đơn hàng có sản phẩm có gia bán nhỏ hơn giá niêm yết không.
                    for ($i = 0; $i < count($contract_product['product_id']) - 1; $i++ ){
                        if(
                            trim($contract_product['product_id'][$i]) == "" ||
                            trim($contract_product['product_name'][$i]) == "" ||
                            trim($contract_product['carpet_color_id'][$i]) == "" ||
                            trim($contract_product['tangled_color_id'][$i]) == "" ||
                            trim($contract_product['flooring_id'][$i]) == "" ||
                            trim($contract_product['price'][$i]) == "" ||
                            trim($contract_product['vat'][$i] == "")
                        )$check_emty_data = false;

                        // kiểm tra đơn hàng có sản phẩm nào bán giá nhỏ hơn giá niêm yết không.
                        $listed_price = $numberFormat->formatToData(trim($contract_product['listed_price'][$i]));
                        $pr_price = $numberFormat->formatToData(trim($contract_product['price'][$i]));
                        if($pr_price < $listed_price){
                            $check_order = false;
                        }
                    }

                    if($check_emty_data){
                        $this->_params['item'] = $contact_item;
                        $this->_params['sales_manager'] = $sales_manager;
                        $contact_same_phones = $this->getServiceLocator()->get('Admin\Model\ContactTable')->listItem(['ssFilter'=>['filter_keyword'=>$contact_item['phone']]],['task'=>'search'])->toArray();
                        // Lấy ra đơn hàng chưa thành công cuối cùng của khách hàng đang lên đơn
                        $contract_coincider = count($contact_same_phones) ? $this->getTable()->listItem(array('ssFilter' => array('contact_id' => array_column($contact_same_phones,'id'), 'date' => $this->_params['data']['date'])), array('task' => 'contract-coincider')) : null;
                        if(!empty($contract_coincider)){
                            $this->_params['data']['coincider_status']  = 'yes';
                            $this->_params['data']['coincider_code']    = $contract_coincider['code'];
                        }

                        $result = $this->getTable()->saveItem($this->_params, array('task' => 'add-item'));
                        $this->flashMessenger()->addMessage('Dữ liệu đã được cập nhật thành công');

                        // Tạo thông báo khi có sản phẩm bán sai giá niêm yết
                        if($check_order == false){
                            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $result));
                            $user_notifi_branch = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(['data' => ['status' => 1, 'notifi' => 'branch', 'sale_branch_id' => $contract['sale_branch_id']]], array('task' => 'list-all')) , array('key' => 'id', 'value' => 'id'));
                            $user_notifi_all    = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(['data' => ['status' => 1, 'notifi' => 'all']], array('task' => 'list-all')) , array('key' => 'id', 'value' => 'id'));
                            $users_notifi       = array_merge(array_values($user_notifi_branch), array_values($user_notifi_all));

                            if(count($users_notifi > 0)){
                                $data_notifi['data'] = array(
                                    'content' => "Đơn hàng ".$contract['code']." có sản phẩm bán giá nhỏ hơn giá niêm yết",
                                    'link' => $contract['id'],
                                );
                                $notifi_id = $this->getServiceLocator()->get('Admin\Model\NotifiTable')->saveItem($data_notifi, array('task' => 'add-item'));
                                if($notifi_id){
                                    foreach($users_notifi as $uid){
                                        $data_notifi_user['data'] = array(
                                            'user_id' => $uid,
                                            'notifi_id' => $notifi_id,
                                        );
                                        $this->getServiceLocator()->get('Admin\Model\NotifiUserTable')->saveItem($data_notifi_user, array('task' => 'add-item'));
                                    }
                                }
                            }
                        }
                        if($controlAction == 'save-new') {
                            $this->goRoute(array('action' => 'add'));
                        } else if($controlAction == 'save') {
//                            $this->goRoute(array('action' => 'view', 'id' => $result));
                            $this->goRoute();
                        } else {
                            $this->goRoute();
                        }
                    }
                    else{
                        $this->_viewModel['check_product_id'] = 'Cần nhập đầy đủ thông tin của sản phẩm';
                        $this->_viewModel['productList'] = $this->_params['data']['contract_product'];
                    }
                }
                else {
                    $this->_viewModel['productList']  = $this->_params['data']['contract_product'];
                    $this->_viewModel['contactPhone'] = $contact_item['phone'];
                }
            }
            else{
                $this->_viewModel['check_contact'] = 'Liên hệ khách hàng chưa có trên hệ thống, vui lòng tạo liên hệ trước khi lên đơn';
                $this->_viewModel['productList']   = $this->_params['data']['contract_product'];
            }
        }
        else{
            $this->_viewModel['contactPhone']   = $contact_item['phone'];
            $this->_viewModel['contactId']      = $contact_item['id'];
        }

        $this->_viewModel['combos']         = $this->getServiceLocator()->get('Admin\Model\ComboProductTable')->listItem(array('ssFilter' => ['filter_status' => 1]), array('task' => 'list-all'));
        $this->_viewModel['product']        = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));
        $this->_viewModel['type_of_carpet'] = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'type-of-carpet')), array('task' => 'cache'));
        $this->_viewModel['carpet_color']   = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['tangled_color']  = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['row_seats']      = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'row-seats')), array('task' => 'cache'));
        $this->_viewModel['flooring']       = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        $this->_viewModel['myForm']	        = $myForm;
        $this->_viewModel['caption']        = 'Đơn hàng - Thêm mới';
        return new ViewModel($this->_viewModel);
    }

    // Thêm mới đơn hàng theo sản phẩm mới
    public function addKovAction() {
//        $return = $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/branches');
//        echo "<pre>";
//        print_r(json_decode($return));
//        echo "</pre>";
//        exit;

        // Sale nào tạo đơn: sale cửa hàng(1) hay telesale(null)
        $numberFormat = new \ZendX\Functions\Number();
        $status_store = !empty($this->params('code')) ? 1 : 0;

        $contact_item = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $this->params('id')));
        $sales_manager = $this->getServiceLocator()->get('Admin\Model\UserTable')->getItem(array('id' => $contact_item['user_id']));

        $myForm = $this->getForm();
        if($this->getRequest()->isPost()){
            $myForm->setInputFilter(new \Admin\Filter\Contract(array('data' => $this->_params['data'], 'route' => $this->_params['route'])));
            $this->_params['data']['phone'] = $contact_item['phone'];
            $this->_params['data']['status_store'] = $status_store;
            $myForm->setData($this->_params['data']);
            $controlAction = $this->_params['data']['control-action'];

            $productList = $this->_params['data']['contract_product'];
            $productList['unit_type'] = array_values($productList['unit_type']);
            if(!empty($contact_item)){
                if($myForm->isValid()){
                    $contract_product = $this->_params['data']['contract_product'];
                    $check_emty_data = true;// kiểm tra thông tin sản phẩm của đơn hàng đã đầy đủ chưa
                    $check_order = true; // kiểm tra đơn hàng có sản phẩm có gia bán nhỏ hơn giá niêm yết không.
                    $check_number_product_return = true;
                    $product_return_false = [];

                    // Lấy ra đơn hàng chưa thành công cuối cùng của khách hàng đang lên đơn
                    $contact_same_phones = $this->getServiceLocator()->get('Admin\Model\ContactTable')->listItem(['ssFilter'=>['filter_keyword'=>$contact_item['phone']]],['task'=>'search'])->toArray();
                    // Lấy ra đơn hàng chưa thành công cuối cùng của khách hàng đang lên đơn
                    $contract_coincider = count($contact_same_phones) ? $this->getTable()->listItem(array('ssFilter' => array('contact_id' => array_column($contact_same_phones,'id'), 'date' => $this->_params['data']['date'])), array('task' => 'contract-coincider')) : null;
                    if(!empty($contract_coincider)){
                        $this->_params['data']['coincider_status']  = 'yes';
                        $this->_params['data']['coincider_code']    = $contract_coincider['code'];
                    }

                    for ($i = 0; $i < count($contract_product['product_id']); $i++ ){
                        if(
                            trim($contract_product['product_id'][$i]) == "" ||
                            trim($contract_product['price'][$i]) == "" ||
                            trim($contract_product['product_name'][$i]) == ""||
                            trim($contract_product['numbers'][$i]) == ""
                        )$check_emty_data = false;

                        // kiểm tra đơn hàng có sản phẩm nào bán giá nhỏ hơn giá niêm yết không.
                        $listed_price = $numberFormat->formatToData(trim($contract_product['listed_price'][$i]));
                        $pr_price = $numberFormat->formatToData(trim($contract_product['price'][$i]));
                        if($pr_price < $listed_price){
                            $check_order = false;
                        }
                        if(!empty($contract_product['product_return_id'][$i])){
                            $return_id = $contract_product['product_return_id'][$i];
                            $product_return_choice = $this->getServiceLocator()->get('Admin\Model\ProductReturnTable')->getItem(array('id' => $return_id));
                            if($contract_product['numbers'][$i] > $product_return_choice->quantity){
                                $check_number_product_return = false;
                                $product_return_false[] = $contract_product['full_name'][$i];
                            }
                        }
                    }

                    if($check_emty_data){
                        if($check_number_product_return){
                            $this->_params['item'] = $contact_item;
                            $this->_params['sales_manager'] = $sales_manager;
                            $contact_same_phones = $this->getServiceLocator()->get('Admin\Model\ContactTable')->listItem(['ssFilter'=>['filter_keyword'=>$contact_item['phone']]],['task'=>'search'])->toArray();
                            // Lấy ra đơn hàng chưa thành công cuối cùng của khách hàng đang lên đơn
                            $contract_coincider = count($contact_same_phones) ? $this->getTable()->listItem(array('ssFilter' => array('contact_id' => array_column($contact_same_phones,'id'), 'date' => $this->_params['data']['date'])), array('task' => 'contract-coincider')) : null;
                            if(!empty($contract_coincider)){
                                $this->_params['data']['coincider_status']  = 'yes';
                                $this->_params['data']['coincider_code']    = $contract_coincider['code'];
                            }

                            // TẠO ĐƠN HÀNG LÊN API
                            $result_kov = $this->createOrderKov($this->_params['data']);
//                            echo "<pre>";
//                            print_r($result_kov);
//                            echo "</pre>";
//                            exit;
                            if((int)$result_kov['id']){
                                $this->_params['data']['id_kov']  = $result_kov['id'];

                                $result = $this->getTable()->saveItem($this->_params, array('task' => 'add-kov-item'));
                                $this->flashMessenger()->addMessage('Dữ liệu đã được cập nhật thành công');

                                // cập nhật mã đơn hàng trên crm lên ghi chú đơn hàng kov
                                $contract_new = $this->getTable()->getItem(array('id' => $result));
                                $order_data['description'] = $this->_params['data']['sale_note'].'(Đơn hàng đẩy từ CRM '.$contract_new['code'].')';
                                $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/orders/'.$contract_new['id_kov'], $order_data, 'PUT');

                                // Tạo thông báo khi có sản phẩm bán sai giá niêm yết
                                if($check_order == false){
                                    $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $result));
                                    $user_notifi_branch = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(['data' => ['status' => 1, 'notifi' => 'branch', 'sale_branch_id' => $contract['sale_branch_id']]], array('task' => 'list-all')) , array('key' => 'id', 'value' => 'id'));
                                    $user_notifi_all    = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(['data' => ['status' => 1, 'notifi' => 'all']], array('task' => 'list-all')) , array('key' => 'id', 'value' => 'id'));
                                    $users_notifi       = array_merge(array_values($user_notifi_branch), array_values($user_notifi_all));

                                    if(count($users_notifi > 0)){
                                        $data_notifi['data'] = array(
                                            'content' => "Đơn hàng ".$contract['code']." có sản phẩm bán giá nhỏ hơn giá niêm yết",
                                            'link' => $contract['id'],
                                        );
                                        $notifi_id = $this->getServiceLocator()->get('Admin\Model\NotifiTable')->saveItem($data_notifi, array('task' => 'add-item'));
                                        if($notifi_id){
                                            foreach($users_notifi as $uid){
                                                $data_notifi_user['data'] = array(
                                                    'user_id' => $uid,
                                                    'notifi_id' => $notifi_id,
                                                );
                                                $this->getServiceLocator()->get('Admin\Model\NotifiUserTable')->saveItem($data_notifi_user, array('task' => 'add-item'));
                                            }
                                        }
                                    }
                                }
                                if($controlAction == 'save-new') {
                                    $this->goRoute(array('action' => 'add'));
                                } else if($controlAction == 'save') {
                                    $this->goRoute();
                                } else {
                                    $this->goRoute();
                                }
                            }
                            else{
                                $mesage = $result_kov['responseStatus']['message'];
                                $this->_viewModel['check_product_id'] = 'Đồng bộ đơn hàng lên hệ thống Kiotviet thất bại: '.$mesage;
                                $this->_viewModel['productList'] = $this->_params['data']['contract_product'];
                            }
                        }
                        else{
                            $this->_viewModel['check_product_id'] = 'Số lượng sản phẩm '.implode(',', $product_return_false).' không đủ trong kho hàng có sẵn';
                            $this->_viewModel['productList'] = $this->_params['data']['contract_product'];
                            $this->_viewModel['total_contract_vat'] = $this->_params['data']['total_contract_vat'];
                        }
                    }
                    else{
                        $this->_viewModel['check_product_id'] = 'Cần nhập đầy đủ thông tin của sản phẩm';
                        $this->_viewModel['productList'] = $this->_params['data']['contract_product'];
                        $this->_viewModel['total_contract_vat'] = $this->_params['data']['total_contract_vat'];
                    }
                }
                else {
                    $this->_viewModel['productList']  = $productList;
                    $this->_viewModel['total_contract_vat']  = $this->_params['data']['total_contract_vat'];
                    $this->_viewModel['contactPhone'] = $contact_item['phone'];
                }
            }
            else{
                $this->_viewModel['check_contact'] = 'Liên hệ khách hàng chưa có trên hệ thống, vui lòng tạo liên hệ trước khi lên đơn';
                $this->_viewModel['productList']   = $productList;
                $this->_viewModel['total_contract_vat']   = $this->_params['data']['total_contract_vat'];
            }
        }
        else{
            $this->_viewModel['contactPhone']   = $contact_item['phone'];
            $this->_viewModel['contactId']      = $contact_item['id'];
        }

        $categories = $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/categories?pageSize=100&hierachicalData=true');
        $categories = json_decode($categories, true)['data'];
        $this->_viewModel['categories'] = $this->getNameCat($this->addNew($categories), $result);

        $discounts_params = array(
            'range_branch'  => $this->_userInfo->getUserInfo('kov_branch_id'),
            'range_contact' => $contact_item['contact_group'],
            'range_sales'   => $this->_userInfo->getUserInfo('id'),
        );
        $this->_viewModel['discounts_params']	= $discounts_params;
//        $this->_viewModel['kovDiscounts']       = $this->getServiceLocator()->get('Admin\Model\KovDiscountsTable')->listItem($discounts_params, array('task' => 'list-check')); // Danh sách khuyến mãi có thể được áp dụng

        $this->_viewModel['type_of_carpet'] = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'type-of-carpet')), array('task' => 'cache'));
        $this->_viewModel['carpet_color']   = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['tangled_color']  = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['row_seats']      = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'row-seats')), array('task' => 'cache'));
        $this->_viewModel['flooring']       = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        $this->_viewModel['myForm']	        = $myForm;
        $this->_viewModel['caption']        = 'Đơn hàng - Thêm mới';
        return new ViewModel($this->_viewModel);
    }

    // Sửa Đơn hàng
    public function editKovAction() {
//        $result = $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/invoices/code/HD000316');
//        $result = json_decode($result, true);
//        echo "<pre>";
//        print_r($result);
//        echo "</pre>";
//        exit;

        $dateFormat = new \ZendX\Functions\Date();
        $numberFormat = new \ZendX\Functions\Number();

        $myForm = new \Admin\Form\Contract\ContractEditKov($this->getServiceLocator(), $this->_params);
        if(!empty($this->params('id'))) {
            $id = $this->params('id');
            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $id));
            $contract_options = !empty($contract['options']) ? unserialize($contract['options']) : array();
            $contract['date'] = $dateFormat->formatToView($contract['date']);
            $contract = array_merge($contract, $contract_options);

            $contact = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $contract['contact_id']));
            $myForm->setData($contract);
            $this->_viewModel['contract']           = $contract;
            $this->_viewModel['contact']            = $contact;
            $this->_viewModel['option_product']     = $contract_options['product'];
            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'not-found'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'not-found'));
        }

        // nếu đon hàng đã có trạng thái sản xuất thì không thể sửa
        $curent_user = $this->_userInfo->getUserInfo();
        $permission_ids = explode(',', $curent_user['permission_ids']);
        if(!in_array(SYSTEM, $permission_ids) && !in_array(ADMIN, $permission_ids) && !empty($contract['production_department_type'])){
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'not-found'));
        }

        if($this->getRequest()->isPost()){
            $myForm->setInputFilter(new \Admin\Filter\ContractEditKov(array('data' => $this->_params['data'], 'route' => $this->_params['route'])));
            $myForm->setData($this->_params['data']);
            $controlAction = $this->_params['data']['control-action'];
            if($myForm->isValid()){
                $this->_params['item'] = $contract;
                $this->_params['contact'] = $contact;

                $contract_product = $this->_params['data']['contract_product'];
                $check_emty_data = true;// kiểm tra thông tin sản phẩm của đơn hàng đã đầy đủ chưa
                $check_order = true; // kiểm tra đơn hàng có sản phẩm có gia bán nhỏ hơn giá niêm yết không.

                for ($i = 0; $i < count($contract_product['product_id']); $i++ ){
                    if(
                        trim($contract_product['product_id'][$i]) == "" ||
                        trim($contract_product['price'][$i]) == "" ||
                        trim($contract_product['product_name'][$i]) == ""||
                        trim($contract_product['numbers'][$i]) == ""
                    )$check_emty_data = false;

                    // kiểm tra đơn hàng có sản phẩm nào bán giá nhỏ hơn giá niêm yết không.
                    $listed_price = $numberFormat->formatToData(trim($contract_product['listed_price'][$i]));
                    $pr_price = $numberFormat->formatToData(trim($contract_product['price'][$i]));
                    if($pr_price < $listed_price){
                        $check_order = false;
                    }
                }

                if($check_emty_data){
                    $result_kov = $this->createOrderKov($this->_params['data'], 'PUT');

                    if((int)$result_kov['id']) {
                        $result = $this->getTable()->saveItem($this->_params, array('task' => 'edit-kov-item'));
                        $this->flashMessenger()->addMessage('Dữ liệu đã được cập nhật thành công');
                        // cập nhật mã đơn hàng trên crm lên ghi chú đơn hàng kov
                        $contract_new = $this->getTable()->getItem(array('id' => $result));
                        $order_data['description'] = $this->_params['data']['sale_note'].'(Đơn hàng đẩy từ CRM '.$contract_new['code'].')';
                        $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/orders/'.$contract_new['id_kov'], $order_data, 'PUT');

                        //                  Tạo thông báo khi có sản phẩm bán sai giá niêm yết
                        if ($check_order == false) {
                            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $result));
                            $user_notifi_branch = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(['data' => ['status' => 1, 'notifi' => 'branch', 'sale_branch_id' => $contract['sale_branch_id']]], array('task' => 'list-all')), array('key' => 'id', 'value' => 'id'));
                            $user_notifi_all = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(['data' => ['status' => 1, 'notifi' => 'all']], array('task' => 'list-all')), array('key' => 'id', 'value' => 'id'));
                            $users_notifi = array_merge(array_values($user_notifi_branch), array_values($user_notifi_all));

                            if (count($users_notifi > 0)) {
                                $data_notifi['data'] = array(
                                    'content' => "Đơn hàng " . $contract['code'] . " có sản phẩm bán giá nhỏ hơn giá niêm yết",
                                    'link' => $contract['id'],
                                );
                                $notifi_id = $this->getServiceLocator()->get('Admin\Model\NotifiTable')->saveItem($data_notifi, array('task' => 'add-item'));
                                if ($notifi_id) {
                                    foreach ($users_notifi as $uid) {
                                        $data_notifi_user['data'] = array(
                                            'user_id' => $uid,
                                            'notifi_id' => $notifi_id,
                                        );
                                        $this->getServiceLocator()->get('Admin\Model\NotifiUserTable')->saveItem($data_notifi_user, array('task' => 'add-item'));
                                    }
                                }
                            }
                        }
                        if ($controlAction == 'save-new') {
                            $this->goRoute(array('action' => 'add'));
                        } else if ($controlAction == 'save') {
                            $this->goRoute();
                        } else {
                            $this->goRoute();
                        }
                    }
                    else{
                        $mesage = $result_kov['responseStatus']['message'];
                        $this->_viewModel['check_product_id'] = 'Đồng bộ đơn hàng lên hệ thống Kiotviet thất bại: '.$mesage;
                        $this->_viewModel['productList'] = $this->_params['data']['contract_product'];
                    }
                }
                else{
                    $this->_viewModel['check_product_id'] = 'Cần nhập đầy đủ thông tin của sản phẩm';
                    $this->_viewModel['productList'] = $this->_params['data']['contract_product'];
                    $this->_viewModel['total_contract_vat'] = $this->_params['data']['total_contract_vat'];
                }
            }
            else {
                $this->_viewModel['productList']  = $this->_params['data']['contract_product'];
                $this->_viewModel['total_contract_vat']  = $this->_params['data']['total_contract_vat'];
                $this->_viewModel['contactPhone'] = $contact_item['phone'];
            }
        }

        $categories = $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/categories?pageSize=100&hierachicalData=true');
        $categories = json_decode($categories, true)['data'];
        $this->_viewModel['categories'] = $this->getNameCat($this->addNew($categories), $result);

        $discounts_params = array(
            'range_branch'  => $this->_userInfo->getUserInfo('kov_branch_id'),
            'range_contact' => $contact_item['contact_group'],
            'range_sales'   => $this->_userInfo->getUserInfo('id'),
        );
        $this->_viewModel['discounts_params']	= $discounts_params;
        $this->_viewModel['myForm']	        = $myForm;
        $this->_viewModel['caption']        = 'Đơn hàng - Sửa';
        return new ViewModel($this->_viewModel);
    }

    // Sửa Đơn hàng
//    public function editKov2Action() {
//        $dateFormat = new \ZendX\Functions\Date();
//        $numberFormat = new \ZendX\Functions\Number();
//
//        $myForm = new \Admin\Form\Contract\ContractEditKov($this->getServiceLocator(), $this->_params);
//        if(!empty($this->params('id'))) {
//            $id = $this->params('id');
//            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $id));
//            $contract_options = !empty($contract['options']) ? unserialize($contract['options']) : array();
//            $contract['date'] = $dateFormat->formatToView($contract['date']);
//            $contract = array_merge($contract, $contract_options);
//
//            $contact = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $contract['contact_id']));
//            $myForm->setData($contract);
//            $this->_viewModel['contract']           = $contract;
//            $this->_viewModel['contact']            = $contact;
//            $this->_viewModel['option_product']     = $contract_options['product'];
//            if($contract['lock']){
//                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'not-found'));
//            }
//        } else {
//            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'not-found'));
//        }
//
//        // nếu đon hàng đã có trạng thái sản xuất thì không thể sửa
//        $curent_user = $this->_userInfo->getUserInfo();
//        $permission_ids = explode(',', $curent_user['permission_ids']);
//        if(!in_array(SYSTEM, $permission_ids) && !in_array(ADMIN, $permission_ids) && !empty($contract['production_department_type'])){
//            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'not-found'));
//        }
//
//        if($this->getRequest()->isPost()){
//            $myForm->setInputFilter(new \Admin\Filter\ContractEditKov(array('data' => $this->_params['data'], 'route' => $this->_params['route'])));
//            $myForm->setData($this->_params['data']);
//            $controlAction = $this->_params['data']['control-action'];
//            if($myForm->isValid()){
//                $this->_params['item'] = $contract;
//                $this->_params['contact'] = $contact;
//
//                $contract_product = $this->_params['data']['contract_product'];
//                $check_emty_data = true;// kiểm tra thông tin sản phẩm của đơn hàng đã đầy đủ chưa
//                $check_order = true; // kiểm tra đơn hàng có sản phẩm có gia bán nhỏ hơn giá niêm yết không.
//
//                for ($i = 0; $i < count($contract_product['product_id']); $i++ ){
//                    if(
//                        trim($contract_product['product_id'][$i]) == "" ||
//                        trim($contract_product['price'][$i]) == "" ||
//                        trim($contract_product['product_name'][$i]) == ""||
//                        trim($contract_product['numbers'][$i]) == ""
//                    )$check_emty_data = false;
//
//                    // kiểm tra đơn hàng có sản phẩm nào bán giá nhỏ hơn giá niêm yết không.
//                    $listed_price = $numberFormat->formatToData(trim($contract_product['listed_price'][$i]));
//                    $pr_price = $numberFormat->formatToData(trim($contract_product['price'][$i]));
//                    if($pr_price < $listed_price){
//                        $check_order = false;
//                    }
//                }
//
//                if($check_emty_data){
//                    $result_kov = $this->createOrderKov2($this->_params['data']);
//
//                    if((int)$result_kov['id']) {
//                        $this->_params['data']['id_kov']  = $result_kov['id'];
//                        $result = $this->getTable()->saveItem($this->_params, array('task' => 'edit-kov-item'));
//                        $this->flashMessenger()->addMessage('Dữ liệu đã được cập nhật thành công');
//                        // cập nhật mã đơn hàng trên crm lên ghi chú đơn hàng kov
//                        $contract_new = $this->getTable()->getItem(array('id' => $result));
//                        $order_data['description'] = $this->_params['data']['sale_note'].'(Đơn hàng đẩy từ CRM '.$contract_new['code'].')';
//                        $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/orders/'.$result_kov['id'], $order_data, 'PUT');
//
//                        if ($controlAction == 'save-new') {
//                            $this->goRoute(array('action' => 'add'));
//                        } else if ($controlAction == 'save') {
//                            $this->goRoute();
//                        } else {
//                            $this->goRoute();
//                        }
//                    }
//                    else{
//                        $mesage = $result_kov['responseStatus']['message'];
//                        $this->_viewModel['check_product_id'] = 'Đồng bộ đơn hàng lên hệ thống Kiotviet thất bại: '.$mesage;
//                        $this->_viewModel['productList'] = $this->_params['data']['contract_product'];
//                    }
//                }
//                else{
//                    $this->_viewModel['check_product_id'] = 'Cần nhập đầy đủ thông tin của sản phẩm';
//                    $this->_viewModel['productList'] = $this->_params['data']['contract_product'];
//                    $this->_viewModel['total_contract_vat'] = $this->_params['data']['total_contract_vat'];
//                }
//            }
//            else {
//                $this->_viewModel['productList']  = $this->_params['data']['contract_product'];
//                $this->_viewModel['total_contract_vat']  = $this->_params['data']['total_contract_vat'];
//                $this->_viewModel['contactPhone'] = $contact_item['phone'];
//            }
//        }
//
//        $categories = $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/categories?pageSize=100&hierachicalData=true');
//        $categories = json_decode($categories, true)['data'];
//        $this->_viewModel['categories'] = $this->getNameCat($this->addNew($categories), $result);
//
//        $discounts_params = array(
//            'range_branch'  => $this->_userInfo->getUserInfo('kov_branch_id'),
//            'range_contact' => $contact_item['contact_group'],
//            'range_sales'   => $this->_userInfo->getUserInfo('id'),
//        );
//        $this->_viewModel['discounts_params']	= $discounts_params;
//        $this->_viewModel['myForm']	        = $myForm;
//        $this->_viewModel['caption']        = 'Đơn hàng - Sửa';
//        return new ViewModel($this->_viewModel);
//    }
//
//    public function createOrderKov2($params, $method = 'POST'){
//        $contract_if = $this->getTable()->getItem(['id' => $params['id']]);
//
//        $numberFormat = new \ZendX\Functions\Number();
//        $branch_user = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('id' => $contract_if['sale_branch_id']));
//        $customer_code = $branch_user['alias'];
//        $customer = [];
//        if($customer_code){
//            $cus = $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/customers/code/'.$customer_code); // Lấy user tương ứng chi nhánh trên kiotviet
//            if($cus){
//                $cus = json_decode($cus);
//                $customer['id'] = $cus->id;
//                $customer['code'] = $cus->code;
//                $customer['name'] = $cus->name;
//                $customer['gender'] = $cus->gender;
//                $customer['birthDate'] = $cus->birthDate;
//                $customer['contactNumber'] = $cus->contactNumber;
//                $customer['address'] = $cus->address;
//                $customer['email'] = $cus->email;
//                $customer['comments'] = $cus->comments;
//            }
//        }
//        // Lấy thông tin chi tiết đơn hàng
//        $contract_product = $params['contract_product'];
//        $contract_product['unit_type'] = array_values($contract_product['unit_type']);
//        for($i = 0; $i < count($contract_product['product_id']); $i++){
//            if(!empty($contract_product['product_id'][$i])) {
//                $product_add[$i]['productId'] = $contract_product['product_id'][$i];
//                $product_add[$i]['productCode'] = $contract_product['code'][$i];
//                $product_add[$i]['productName'] = $contract_product['full_name'][$i];
//                $product_add[$i]['quantity'] = $contract_product['numbers'][$i];
//                $product_add[$i]['price'] = $numberFormat->formatToData($contract_product['price'][$i]);
//                $product_add[$i]['note'] = '';
//            }
//        }
//        $surchages = array(
//            array(
//                'code'  => 'Thuship',
//                'price' => $numberFormat->formatToData($params['fee_other']),
//            ),
//            array(
//                'code'  => 'VAT',
//                'price' => $numberFormat->formatToData($params['total_contract_vat']),
//            ),
//        );
//
//        $order_data['branchId']         = $this->_userInfo->getUserInfo('kov_branch_id');
//        $order_data['description']      = $params['sale_note'] .'(Đơn hàng đẩy từ CRM)';
//        $order_data['orderDetails']     = $product_add;
//        $order_data['discount']         = $numberFormat->formatToData($params['total_contract_discount']);
//        $order_data['surchages']        = $surchages;
//        $order_data['customer']         = $customer;
//
//        if($method == 'POST'){
//            if(!empty($params['bill_type_id']) && $params['bill_type_id'] != 'cod'){
//                if($numberFormat->formatToData($params['bill_paid_price']) > 0){
//                    $order_data['totalPayment'] = $numberFormat->formatToData($params['bill_paid_price']);
//                    // $order_data['makeInvoice'] = true; // có tạo hóa đơn cùng đơn hàng không
//                    $order_data['method'] = 'Cash';
//                    if($params['bill_type_id'] == 'chuyen-khoan'){
//                        $order_data['accountId'] = BANK_TECHCOMBANK;
//                        $order_data['method'] = 'Transfer';
//                    }
//                }
//            }
//        }
//
//        $order_id = '';
//        if($method == 'PUT'){
//            $contract = $this->getTable()->getItem(['id' => $params['id']]);
//            $order_id = '/'.$contract['id_kov'];
//        }
//
//        $invoi_data['branchId'] = $this->_userInfo->getUserInfo('kov_branch_id');
//        $invoi_data['invoiceDetails'] = $product_add;
//
////        echo "<pre>";
////        print_r($order_data);
////        echo "</pre>";
////        exit;
//
//        $result_kov = $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/orders'.$order_id, $order_data, $method);
//
//        return json_decode($result_kov, true);
//    }

    public function createOrderKov($params, $method = 'POST'){
        $numberFormat = new \ZendX\Functions\Number();
        $branch_user = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('id' => $this->_userInfo->getUserInfo('sale_branch_id')));
        $customer_code = $branch_user['alias'];
        $customer = [];
        if($customer_code){
            $cus = $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/customers/code/'.$customer_code); // Lấy user tương ứng chi nhánh trên kiotviet
            if($cus){
                $cus = json_decode($cus);
                $customer['id'] = $cus->id;
                $customer['code'] = $cus->code;
                $customer['name'] = $cus->name;
                $customer['gender'] = $cus->gender;
                $customer['birthDate'] = $cus->birthDate;
                $customer['contactNumber'] = $cus->contactNumber;
                $customer['address'] = $cus->address;
                $customer['email'] = $cus->email;
                $customer['comments'] = $cus->comments;
            }
        }
        // Lấy thông tin chi tiết đơn hàng
        $contract_product = $params['contract_product'];
        $contract_product['unit_type'] = array_values($contract_product['unit_type']);
        for($i = 0; $i < count($contract_product['product_id']); $i++){
            if(!empty($contract_product['product_id'][$i])) {
//                $discount_value = 0;
//                if($contract_product['unit_type'][$i] == 1)
//                    $discount_value = $numberFormat->formatToData($contract_product['discount_value'][$i]);
//                else if($contract_product['unit_type'][$i] == 2)
//                    $discount_value = $numberFormat->formatToData($contract_product['listed_price'][$i]) * $numberFormat->formatToData($contract_product['discount_value'][$i]) / 100;
//
//                $listed_price = $numberFormat->formatToData($contract_product['listed_price'][$i]);
//                $price = $numberFormat->formatToData($contract_product['price'][$i]);
//                if($price > $listed_price){
//                    $discount_value = $listed_price - $price;
//                }

                $product_add[$i]['productId'] = $contract_product['product_id'][$i];
                $product_add[$i]['productCode'] = $contract_product['code'][$i];
                $product_add[$i]['productName'] = $contract_product['full_name'][$i];
                $product_add[$i]['quantity'] = $contract_product['numbers'][$i];

//                $product_add[$i]['price'] = $numberFormat->formatToData($contract_product['listed_price'][$i]);
//                $product_add[$i]['discount'] = $discount_value;
//                $product_add[$i]['discountRatio'] = $contract_product['unit_type'][$i] == 2 ? $contract_product['discount_value'][$i] : 0 ;

                $product_add[$i]['price'] = $numberFormat->formatToData($contract_product['price'][$i]);

                $product_add[$i]['note'] = '';
            }
        }
        $surchages = array(
            array(
                'code'  => 'Thuship',
                'price' => $numberFormat->formatToData($params['fee_other']),
            ),
            array(
                'code'  => 'VAT',
                'price' => $numberFormat->formatToData($params['total_contract_vat']),
            ),
        );

        $order_data['branchId']         = $this->_userInfo->getUserInfo('kov_branch_id');
        $order_data['description']      = $params['sale_note'] .'(Đơn hàng đẩy từ CRM)';
        $order_data['orderDetails']     = $product_add;
        $order_data['discount']         = $numberFormat->formatToData($params['total_contract_discount']);
        $order_data['surchages']        = $surchages;
        $order_data['customer']         = $customer;
        $order_data['orderDelivery']    = array('deliveryCode' => '');

        if($method == 'POST'){
            if(!empty($params['bill_type_id']) && $params['bill_type_id'] != 'cod'){
                if($numberFormat->formatToData($params['bill_paid_price']) > 0){
                    $order_data['totalPayment'] = $numberFormat->formatToData($params['bill_paid_price']);
                    // $order_data['makeInvoice'] = true; // có tạo hóa đơn cùng đơn hàng không
                    $order_data['method'] = 'Cash';
                    if($params['bill_type_id'] == 'chuyen-khoan'){
                        $order_data['accountId'] = BANK_TECHCOMBANK;
                        $order_data['method'] = 'Transfer';
                    }
                }
            }
        }

        $order_id = '';
        if($method == 'PUT'){
            $contract = $this->getTable()->getItem(['id' => $params['id']]);
            $order_id = '/'.$contract['id_kov'];
        }

        $invoi_data['branchId'] = $this->_userInfo->getUserInfo('kov_branch_id');
        $invoi_data['invoiceDetails'] = $product_add;

        $result_kov = $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/orders'.$order_id, $order_data, $method);

        return json_decode($result_kov, true);
    }

    // Lên đơn hàng từ cửa hàng
//    public function addStoreAction()
//    {
//        $myForm = new \Admin\Form\Contract\AddStore($this->getServiceLocator(), $this->_params);
//        $myForm->setInputFilter(new \Admin\Filter\Contract\AddStore($this->_params));
//        $this->_viewModel['caption'] = 'Lên đơn hàng';
//        $this->_viewModel['myForm']  = $myForm;
//        $viewModel                   = new ViewModel($this->_viewModel);
//
//        if ($this->getRequest()->isPost()) {
//            $myForm->setData($this->_params['data']);
//            if ($myForm->isValid()) {
//                $contactPhone = $this->_params['data']['phone'];
//                $contacts = $this->getServiceLocator()->get('Admin\Model\ContactTable')->listItem(array('data' => array('phone' => $contactPhone)), array('task' => 'by-item'));
//                $viewModel->setVariable('contacts', $contacts);
//                $viewModel->setVariable('user',$this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache')));
//                $viewModel->setVariable('product_group', $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'product-group')), array('task' => 'cache')));
//                $viewModel->setVariable('sale_history_action', $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-history-action')), array('task' => 'cache')));
//                $viewModel->setVariable('sale_history_result', $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-history-result')), array('task' => 'cache')));
//                $viewModel->setVariable('sale_history_type', $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-history-type')), array('task' => 'cache')));
//                $viewModel->setVariable('sale_group', $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache')));
//                $viewModel->setVariable('sale_branch', $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache')));
//            }
//        }
//
//        return $viewModel;
//    }

    // Xem chi tiết Đơn hàng
    public function viewAction() {
        if(!empty($this->_params['data']['id'])) {
            $item = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $notifi = $this->getServiceLocator()->get('Admin\Model\NotifiTable')->getItem(array('link' => $this->_params['data']['id']), array('task' => 'link'));
        if(!empty($notifi)){
            $notifi_user = $this->getServiceLocator()->get('Admin\Model\NotifiUserTable')->getItem(array('user_id' => $this->_userInfo->getUserInfo('id'), 'notifi_id' => $notifi->id), array('task' => 'notifi'));
            if(!empty($notifi_user)){
                $this->getServiceLocator()->get('Admin\Model\NotifiUserTable')->changeStatus(['data' => array("cid" => [$notifi_user->id], "status" => 0)], array('task' => 'change-status'));
            }
        }

        $this->_viewModel['item']                       = $item;
        $this->_viewModel['contact']                    = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $item['contact_id']));
        $this->_viewModel['user']                       = $this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['sale_group']                 = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache'));
        $this->_viewModel['sale_branch']                = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache'));
        $this->_viewModel['sale_history_action']        = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-history-action')), array('task' => 'cache'));
        $this->_viewModel['sale_history_result']        = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-history-result')), array('task' => 'cache'));
        $this->_viewModel['sale_source_group']          = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-source-group')), array('task' => 'cache'));
        $this->_viewModel['sale_source_known']          = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-source-known')), array('task' => 'cache'));
        $this->_viewModel['sale_contact_type']          = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-contact-type')), array('task' => 'cache-alias'));
        $this->_viewModel['sale_contact_subject']       = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-contact-subject')), array('task' => 'cache-alias'));
        $this->_viewModel['sale_lost']                  = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-lost')), array('task' => 'cache'));
        $this->_viewModel['location_city']              = $this->getServiceLocator()->get('Admin\Model\LocationsTable')->listItem(array('level' => 1), array('task' => 'cache'));
        $this->_viewModel['location_district']          = $this->getServiceLocator()->get('Admin\Model\LocationsTable')->listItem(array('level' => 2), array('task' => 'cache'));
        $this->_viewModel['sex']                        = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sex')), array('task' => 'cache-alias'));
        $this->_viewModel['bill']                       = $this->getServiceLocator()->get('Admin\Model\BillTable')->listItem(null, array('task' => 'by-contract'));
        $this->_viewModel['bill_type']                  = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'bill-type')), array('task' => 'cache'));
        $this->_viewModel['transport']                  = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'transport')), array('task' => 'cache'));
        $this->_viewModel['type_of_carpet']             = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'type-of-carpet')), array('task' => 'cache'));
        $this->_viewModel['carpet_color']               = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['tangled_color']              = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['row_seats']                  = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'row-seats')), array('task' => 'cache'));
        $this->_viewModel['flooring']                   = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        $this->_viewModel['production_department']      = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'production-department')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['status']                     = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'status')), array('task' => 'cache'));
        $this->_viewModel['production_type']            = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'production-type')), array('task' => 'cache')), array('key' => 'id', 'value' => 'object'));
        $this->_viewModel['kovProduct']                 = $this->getServiceLocator()->get('Admin\Model\KovProductsTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['caption']                    = 'Xem chi tiết đơn hàng';
        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Xem nhanh Đơn hàng
    public function quickViewAction() {
        if(!empty($this->_params['data']['id'])) {
            $item = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
        } else {
            //return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['item']                       = $item;
        $this->_viewModel['contact']                    = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $item['contact_id']));
        $this->_viewModel['user']                       = $this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['sale_group']                 = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache'));
        $this->_viewModel['sale_branch']                = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache'));
        $this->_viewModel['sale_history_action']        = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-history-action')), array('task' => 'cache'));
        $this->_viewModel['sale_history_result']        = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-history-result')), array('task' => 'cache'));
        $this->_viewModel['sale_source_group']          = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-source-group')), array('task' => 'cache'));
        $this->_viewModel['sale_contact_type']          = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-contact-type')), array('task' => 'cache-alias'));
        $this->_viewModel['sale_contact_subject']       = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-contact-subject')), array('task' => 'cache-alias'));
        $this->_viewModel['sale_lost']                  = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-lost')), array('task' => 'cache'));
        $this->_viewModel['location_city']              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'location-city')), array('task' => 'cache'));
        $this->_viewModel['location_district']          = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'location-district')), array('task' => 'cache'));
        $this->_viewModel['product_interest']           = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'product-interest')), array('task' => 'cache'));
        $this->_viewModel['product']                    = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));
        $this->_viewModel['sex']                        = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sex')), array('task' => 'cache-alias'));
        $this->_viewModel['caption']                    = 'Xem chi tiết Đơn hàng';

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    public function quickProductAction() {
        if(!empty($this->_params['data']['id'])) {
            $item = $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/products/'.$this->_params['data']['id']);
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }
        $product = json_decode($item, true);
        $inventories = $product['inventories'];
        // echo "<pre>";
        // print_r($inventories);
        // echo "<pre>";
        // exit;
        if(!empty($inventories)){
            $this->_viewModel['inventories'] = $inventories;
        }

        $this->_viewModel['caption'] = 'Chi tiết sản phẩm';
        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Sửa Đơn hàng
    public function editAction() {
        $dateFormat = new \ZendX\Functions\Date();
        $numberFormat = new \ZendX\Functions\Number();
        $myForm = new \Admin\Form\Contract\Edit($this->getServiceLocator(), $this->_params);


        if(!empty($this->_params['data']['id'])) {
            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
            $contract_options = !empty($contract['options']) ? unserialize($contract['options']) : array();
            $contract = array_merge($contract, $contract_options);
            $contract['date'] = $dateFormat->formatToView($contract['date']);
            $contact = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $contract['contact_id']));
            $myForm->setData($contract);

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            if($this->_params['data']['modal'] == 'success') {
                $myForm->setInputFilter(new \Admin\Filter\Contract\Edit($this->_params));
                $myForm->setData($this->_params['data']);

                if($myForm->isValid()){
                    $this->_params['item'] = $contract;
                    $this->_params['contact'] = $contact;

                    $contract_product = $this->_params['data']['contract_product'];
                    $check_emty_data = true;// kiểm tra thông tin sản phẩm của đơn hàng đã đầy đủ chưa
                    $check_order = true; // kiểm tra đơn hàng có sản phẩm có gia bán nhỏ hơn giá niêm yết không.

                    for ($i = 0; $i <= count($contract_product['product_id']) - 1; $i++ ){
                        if(
                            trim($contract_product['product_id'][$i]) == "" ||
                            trim($contract_product['product_name'][$i]) == "" ||
                            trim($contract_product['carpet_color_id'][$i]) == "" ||
                            trim($contract_product['tangled_color_id'][$i]) == "" ||
                            trim($contract_product['flooring_id'][$i]) == "" ||
                            trim($contract_product['price'][$i]) == "" ||
                            trim($contract_product['vat'][$i] == "")
                        )$check_emty_data = false;

                        // kiểm tra đơn hàng có sản phẩm nào bán giá nhỏ hơn giá niêm yết không.
                        $listed_price = $numberFormat->formatToData(trim($contract_product['listed_price'][$i]));
                        $pr_price = $numberFormat->formatToData(trim($contract_product['price'][$i]));
                        if($pr_price < $listed_price){
                            $check_order = false;
                        }
                    }

                    // Tạo thông báo khi có sản phẩm bán sai giá niêm yết
                    if($check_order == false){
                        $notifi = $this->getServiceLocator()->get('Admin\Model\NotifiTable')->getItem(array('link' => $this->_params['data']['id']), array('task' => 'link'));
                        if(!empty($notifi)){
                            $notifi_user = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\NotifiUserTable')->listItem(array('ssFilter' => array('filter_status' => 1, 'filter_notifi_id' => $notifi->id)), array('task' => 'list-all')), array('key' => 'id', 'value' => 'id'));
                            if(!empty($notifi_user)){
                                $this->getServiceLocator()->get('Admin\Model\NotifiUserTable')->changeStatus(['data' => array("cid" => array_values($notifi_user), "status" => 1)], array('task' => 'change-status'));
                            }
                        }
                        else{
                            $user_notifi_branch = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(['data' => ['status' => 1, 'notifi' => 'branch', 'sale_branch_id' => $contract['sale_branch_id']]], array('task' => 'list-all')) , array('key' => 'id', 'value' => 'id'));
                            $user_notifi_all    = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(['data' => ['status' => 1, 'notifi' => 'all']], array('task' => 'list-all')) , array('key' => 'id', 'value' => 'id'));
                            $users_notifi       = array_merge(array_values($user_notifi_branch), array_values($user_notifi_all));

                            if(count($users_notifi > 0)){
                                $data_notifi['data'] = array(
                                    'content' => "Đơn hàng ".$contract['code']." có sản phẩm bán giá nhỏ hơn giá niêm yết",
                                    'link' => $contract['id'],
                                );
                                $notifi_id = $this->getServiceLocator()->get('Admin\Model\NotifiTable')->saveItem($data_notifi, array('task' => 'add-item'));
                                if($notifi_id){
                                    foreach($users_notifi as $uid){
                                        $data_notifi_user['data'] = array(
                                            'user_id' => $uid,
                                            'notifi_id' => $notifi_id,
                                        );
                                        $this->getServiceLocator()->get('Admin\Model\NotifiUserTable')->saveItem($data_notifi_user, array('task' => 'add-item'));
                                    }
                                }
                            }
                        }
                    }

                    if($check_emty_data){
                        $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem($this->_params, array('task' => 'edit-item'));
                        $this->flashMessenger()->addMessage('Cập nhật dữ liệu thành công');
                        echo 'success';
                        return $this->response;
                    }
                    else{
                        $this->_viewModel['check_product_id'] = 'Cần nhập đầy đủ thông tin của sản phẩm';
                    }
                }
            } else {
                $myForm->setData($this->_params['data']);
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']         = $myForm;
        $this->_viewModel['contract']       = $contract;
        $this->_viewModel['contact']        = $contact;
        $this->_viewModel['product_type']   = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));

        $this->_viewModel['carpet_color']   = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['tangled_color']  = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['row_seats']      = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'row-seats')), array('task' => 'cache'));
        $this->_viewModel['flooring']       = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        $this->_viewModel['caption']        = 'Sửa Đơn hàng';

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Sửa Tên xe - năm sản xuất trong Đơn hàng có sẵn
    public function editWarehouseAction() {
        $myForm = new \Admin\Form\Contract\Edit($this->getServiceLocator(), $this->_params);

        if(!empty($this->_params['data']['id'])) {
            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
            $contract_options = !empty($contract['options']) ? unserialize($contract['options']) : array();
            $contract = array_merge($contract, $contract_options);
            $myForm->setData($contract);

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            if($this->_params['data']['modal'] == 'success') {
                $myForm->setData($this->_params['data']);
                $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem($this->_params, array('task' => 'edit-warehouse'));
                $this->flashMessenger()->addMessage('Cập nhật dữ liệu thành công');
                echo 'success';
                return $this->response;
            } else {
                $myForm->setData($this->_params['data']);
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']         = $myForm;
        $this->_viewModel['contract']       = $contract;
        $this->_viewModel['product_type']   = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));
        $this->_viewModel['carpet_color']   = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['tangled_color']  = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['flooring']       = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        $this->_viewModel['caption']        = 'Sửa tên xe - năm sản xuất';

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    // Xóa sản phẩm trong Đơn hàng có sẵn
    public function delProductWarehouseAction() {
        $myForm = new \Admin\Form\Contract\Edit($this->getServiceLocator(), $this->_params);

        if(!empty($this->_params['data']['id'])) {
            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
            $contract_options = !empty($contract['options']) ? unserialize($contract['options']) : array();
            $contract = array_merge($contract, $contract_options);
            $myForm->setData($contract);

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            if($this->_params['data']['modal'] == 'success') {
                $myForm->setData($this->_params['data']);
                $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem($this->_params, array('task' => 'del-product-warehouse'));
                $this->flashMessenger()->addMessage('Cập nhật dữ liệu thành công');
                echo 'success';
                return $this->response;
            } else {
                $myForm->setData($this->_params['data']);
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']         = $myForm;
        $this->_viewModel['contract']       = $contract;
        $this->_viewModel['product_type']   = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));
        $this->_viewModel['carpet_color']   = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['tangled_color']  = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['flooring']       = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        $this->_viewModel['caption']        = 'Xóa sản phẩm - Đơn hàng có sẵn';

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    // Sửa sản phẩm
    public function editProductAction() {
        $dateFormat = new \ZendX\Functions\Date();
        $numberFormat = new \ZendX\Functions\Number();
        $myForm = new \Admin\Form\Contract\EditProduct($this->getServiceLocator(), $this->_params);

        if(!empty($this->_params['data']['id'])) {
            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
            $contact = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $contract['contact_id']));

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            if($this->_params['data']['modal'] == 'success') {
                $myForm->setInputFilter(new \Admin\Filter\Contract\EditProduct($this->_params));
                $myForm->setData($this->_params['data']);

                if($myForm->isValid()){
                    $this->_params['data'] = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    $this->_params['item'] = $contract;
                    $this->_params['contact'] = $contact;

                    // Tính lại giá tiền khi thay đổi sản phẩm
                    $price = $numberFormat->formatToNumber($this->_params['data']['price']);
                    $price_promotion = 0;
                    $price_promotion_percent = $contract['price_promotion_percent'];
                    $price_promotion_price = $contract['price_promotion_price'];
                    $price_paid = $contract['price_paid'];
                    $price_accrued = $contract['price_accrued'];

                    if(!empty($contract['price_promotion_percent'])) {
                        $price_promotion = $contract['price_promotion_percent'] / 100 * $price;
                    }
                    if(!empty($contract['price_promotion_price'])) {
                        $price_promotion = $price_promotion + $contract['price_promotion_price'];
                    }

                    $price_total = $price - $price_promotion;
                    $price_owed = $price_total - $price_paid + $price_accrued;

                    $this->_params['data']['price'] = $price;
                    $this->_params['data']['price_promotion'] = $price_promotion;
                    $this->_params['data']['price_promotion_percent'] = $price_promotion_percent;
                    $this->_params['data']['price_promotion_price'] = $price_promotion_price;
                    $this->_params['data']['price_total'] = $price_total;
                    $this->_params['data']['price_owed'] = $price_owed;

                    $result = $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem($this->_params, array('task' => 'edit-item'));

                    $this->flashMessenger()->addMessage('Cập nhật dữ liệu thành công');
                    echo 'success';
                    return $this->response;
                }
            } else {
                $myForm->setData($this->_params['data']);
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']     = $myForm;
        $this->_viewModel['contract']   = $contract;
        $this->_viewModel['caption']    = 'Sửa sản phẩm';

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Sửa ưu đãi
    public function editPromotionAction() {
        $dateFormat = new \ZendX\Functions\Date();
        $numberFormat = new \ZendX\Functions\Number();
        $myForm = new \Admin\Form\Contract\EditPromotion($this->getServiceLocator(), $this->_params);

        if(!empty($this->_params['data']['id'])) {
            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
            $contract_options = !empty($contract['options']) ? unserialize($contract['options']) : array();
            $contract = array_merge($contract, $contract_options);
            $contract['date'] = $dateFormat->formatToView($contract['date']);
            $contact = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $contract['contact_id']));
            $myForm->setData($contract);

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            if($this->_params['data']['modal'] == 'success') {
                $myForm->setInputFilter(new \Admin\Filter\Contract\EditPromotion($this->_params));
                $myForm->setData($this->_params['data']);

                if($myForm->isValid()){
                    $this->_params['data'] = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    $this->_params['item'] = $contract;
                    $this->_params['contact'] = $contact;

                    // Tính lại giá tiền khi thay đổi sản phẩm
                    $price = $numberFormat->formatToNumber($this->_params['data']['price']);
                    $price_promotion = 0;
                    $price_promotion_percent = $numberFormat->formatToNumber($this->_params['data']['price_promotion_percent']);
                    $price_promotion_price = $numberFormat->formatToNumber($this->_params['data']['price_promotion_price']);
                    $price_paid = $contract['price_paid'];
                    $price_accrued = $contract['price_accrued'];

                    if(!empty($this->_params['data']['price_promotion_percent'])) {
                        $price_promotion = $numberFormat->formatToNumber($this->_params['data']['price_promotion_percent']) / 100 * $price;
                    }
                    if(!empty($this->_params['data']['price_promotion_price'])) {
                        $price_promotion = $price_promotion + $numberFormat->formatToNumber($this->_params['data']['price_promotion_price']);
                    }

                    $price_total = $price - $price_promotion;
                    $price_owed = $price_total - $price_paid + $price_accrued;

                    $this->_params['data']['price'] = $price;
                    $this->_params['data']['price_promotion'] = $price_promotion;
                    $this->_params['data']['price_promotion_percent'] = $price_promotion_percent;
                    $this->_params['data']['price_promotion_price'] = $price_promotion_price;
                    $this->_params['data']['price_total'] = $price_total;
                    $this->_params['data']['price_owed'] = $price_owed;

                    $result = $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem($this->_params, array('task' => 'edit-item'));

                    $this->flashMessenger()->addMessage('Cập nhật dữ liệu thành công');
                    echo 'success';
                    return $this->response;
                }
            } else {
                $myForm->setData($this->_params['data']);
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']     = $myForm;
        $this->_viewModel['contract']   = $contract;
        $this->_viewModel['caption']    = 'Sửa ưu đãi';

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Sửa ghi chú
    public function editNoteAction() {
        $myForm = new \Admin\Form\Contract\EditNote($this->getServiceLocator(), $this->_params);

        if(!empty($this->_params['data']['id'])) {
            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
            $contract_options = !empty($contract['options']) ? unserialize($contract['options']) : array();
            $myForm->setData($contract_options);

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            if($this->_params['data']['modal'] == 'success') {
                $myForm->setInputFilter(new \Admin\Filter\Contract\EditNote($this->_params));
                $myForm->setData($this->_params['data']);

                if($myForm->isValid()){
                    $this->_params['data'] = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    $this->_params['item'] = $contract;

                    $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem($this->_params, array('task' => 'update-note'));

                    $this->flashMessenger()->addMessage('Cập nhật dữ liệu thành công');
                    echo 'success';
                    return $this->response;
                }
            } else {
                $myForm->setData($this->_params['data']);
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']     = $myForm;
        $this->_viewModel['contract']   = $contract;
        $this->_viewModel['caption']    = 'Sửa ghi chú';

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Khôi phục đơn hàng đã xóa
    public function restoreAction() {
        $myForm = new \Admin\Form\Contract\EditNote($this->getServiceLocator(), $this->_params);

        if(!empty($this->_params['data']['id'])) {
            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            if($this->_params['data']['modal'] == 'success') {
                $myForm->setInputFilter(new \Admin\Filter\Contract\EditNote($this->_params));
                $myForm->setData($this->_params['data']);

                if($myForm->isValid()){
                    $this->_params['data'] = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    $this->_params['item'] = $contract;

                    $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem($this->_params, array('task' => 'show-delete'));

                    $this->flashMessenger()->addMessage('Khôi phục đơn hàng thành công');
                    echo 'success';
                    return $this->response;
                }
            } else {
                $myForm->setData($this->_params['data']);
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']     = $myForm;
        $this->_viewModel['contract']   = $contract;
        $this->_viewModel['caption']    = 'Khôi phục đơn hàng đã xóa';

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // cập nhật trạng thái sale (với những đơn chưa có trạng thái của các bộ phận khác mới được cập nhật).
    public function editStatusAction() {
        $myForm = new \Admin\Form\Contract\EditStatus($this->getServiceLocator(), $this->_params);

        if(!empty($this->_params['data']['id'])) {
            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
            $myForm->setData($contract);

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            if($this->_params['data']['modal'] == 'success') {
                $myForm->setData($this->_params['data']);

                if($myForm->isValid()){
                    $this->_params['data'] = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    if(empty($contract['production_department_type']) && empty($contract['status_check_id']) && empty($contract['status_acounting_id'])){
                        // Hủy đơn hàng trên Kiotviet.
                        if($this->_params['data']['status_id'] == HUY_SALES){
                            $this->kiotviet_call(RETAILER, $this->kiotviet_token, '/orders/' . $contract['id_kov'], null, 'DELETE' );
                        }

                        $this->getTable()->updateItem($this->_params, array('task' => 'update-status'));
                        $this->flashMessenger()->addMessage('Cập nhật dữ liệu thành công');
                    }
                    else{
                        $this->flashMessenger()->addMessage('Đơn hàng đã có trạng thái của các bộ phận không thể cập nhật');
                    }

                    echo 'success';
                    return $this->response;
                }
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']     = $myForm;
        $this->_viewModel['contract']   = $contract;
        $this->_viewModel['caption']    = 'Sửa trạng thái';

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Xóa Đơn hàng
    public function deleteAction() {
        $item = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->params('id')));

        if($item['lock']){
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock'));
        }

        if(empty($item)) {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        if($this->getRequest()->isPost()) {
            // Xóa hoa đồng
            $this->_params['item'] = $item;
            // Xóa đơn hàng
//            $contract_delete = $this->getTable()->deleteItem($this->_params, array('task' => 'delete-item'));
            // Xóa tạm thời
            $contract_delete = $this->getTable()->deleteItem($this->_params, array('task' => 'delete-hidden'));

            $this->flashMessenger()->addMessage('Xóa Đơn hàng thành công');

            $this->goRoute();
        }


        $this->_viewModel['item']               = $item;
        $this->_viewModel['contact']            = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $item['contact_id']));
        $this->_viewModel['user']               = $this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['sale_group']         = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache'));
        $this->_viewModel['sale_branch']        = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache'));
        $this->_viewModel['sale_source_group']  = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-source-group')), array('task' => 'cache'));
        $this->_viewModel['sex']                = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sex')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['product']            = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));
        $this->_viewModel['caption']            = 'Đơn hàng - Xóa';
        return new ViewModel($this->_viewModel);
    }

    // Xóa Đơn hàng có sẵn
    public function deleteWarehouseAction() {
        $item = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->params('id')));

        if($item['lock']){
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock'));
        }

        if(empty($item)) {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        if($this->getRequest()->isPost()) {
            // Xóa hoa đồng
            $this->_params['item'] = $item;
            // Xóa đơn hàng
//            $this->getTable()->deleteItem($this->_params, array('task' => 'delete-item'));
            // Xóa tạm thời
            $this->getTable()->deleteItem($this->_params, array('task' => 'delete-hidden'));
            $this->flashMessenger()->addMessage('Xóa Đơn hàng có sẵn thành công');

            $this->goRoute(['action' => 'warehouse']);
        }


        $this->_viewModel['item']               = $item;
        $this->_viewModel['contact']            = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $item['contact_id']));
        $this->_viewModel['user']               = $this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['sale_group']         = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache'));
        $this->_viewModel['sale_branch']        = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache'));
        $this->_viewModel['sale_source_group']  = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-source-group')), array('task' => 'cache'));
        $this->_viewModel['sex']                = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sex')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['product']            = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));
        $this->_viewModel['caption']            = 'Đơn hàng có sẵn - Xóa';
        return new ViewModel($this->_viewModel);
    }

    // Sửa thông tin khách hàng
    public function contactEditAction() {
        $dateFormat = new \ZendX\Functions\Date();
        $myForm = new \Admin\Form\Contract\Contact($this->getServiceLocator(), $this->_params);

        if(!empty($this->_params['data']['id'])) {
            $contact = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $this->_params['data']['id']));
            $contact_options = !empty($contact['options']) ? unserialize($contact['options']) : array();
            $contact = array_merge($contact, $contact_options);
            $contact['birthday'] = !empty($contact['birthday']) ? $dateFormat->formatToView($contact['birthday']) : null;

            $myForm->setData($contact);

        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            if($this->_params['data']['modal'] == 'success') {
                $myForm->setInputFilter(new \Admin\Filter\Contract\Contact($this->_params));
                $myForm->setData($this->_params['data']);

                if($myForm->isValid()){
                    $this->_params['data'] = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    $this->_params['item'] = $contact;

                    $result = $this->getServiceLocator()->get('Admin\Model\ContactTable')->saveItem($this->_params, array('task' => 'edit-item'));

                    $this->flashMessenger()->addMessage('Cập nhật dữ liệu thành công');
                    echo 'success';
                    return $this->response;
                }
            } else {
                $myForm->setData($this->_params['data']);
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']             = $myForm;
        $this->_viewModel['item']               = $contact;
        $this->_viewModel['location_district']  = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'location-district')), array('task' => 'cache'));
        $this->_viewModel['caption']            = 'Cập nhật thông tin khách hàng';

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Thêm hóa đơn
    public function billAddAction() {
        if(!empty($this->_params['data']['id'])) {
            $contract   = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
            $contact    = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $contract['contact_id']));
            $contact['birthday_year'] = !empty($contact['birthday_year']) ? $contact['birthday_year'] : null;

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            $myForm = new \Admin\Form\Contract\Bill($this->getServiceLocator());
            $myForm->setData($this->_params['data']);

            if($this->_params['data']['modal'] == 'success') {
                $myForm->setInputFilter(new \Admin\Filter\Contract\Bill(array('data' => $this->_params['data'], 'contract' => $contract, 'contact' => $contact)));
                if($myForm->isValid()){
                    $this->_params['data']      = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    $this->_params['contract']  = $contract;
                    $this->_params['contact']   = $contact;

                    if(!empty($this->_params['data']['paid_price']) || !empty($this->_params['data']['accrued_price'])) {
                        // Thêm hóa đơn
                        $this->_params['data']['paid_type_id'] = ['data']['type'];
                        $this->getServiceLocator()->get('Admin\Model\BillTable')->saveItem($this->_params, array('task' => 'add-item'));

                        // Cập nhật lại thông tin thanh toán Đơn hàng
                        $number = new \ZendX\Functions\Number();

                        $price_paid     = $contract['price_paid'] + $number->formatToNumber($this->_params['data']['paid_price']);
                        $price_accrued  = $contract['price_accrued'] + $number->formatToNumber($this->_params['data']['accrued_price']);
                        $price_owed     = $contract['price_total'] - $price_paid + $price_accrued;

                        $arrContract = array();
                        $arrContract['id'] = $contract['id'];
                        $arrContract['price_paid'] = $price_paid;
                        $arrContract['price_accrued'] = $price_accrued;
                        $arrContract['price_owed'] = $price_owed;

                        $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem(array('data' => $arrContract), array('task' => 'update-bill-add'));
                    }

                    $this->flashMessenger()->addMessage('Thêm hóa đơn thành công');
                    echo 'success';
                    return $this->response;
                }
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']     = $myForm;
        $this->_viewModel['caption']    = 'Thêm hóa đơn';
        $this->_viewModel['contract']   = $contract;
        $this->_viewModel['contact']    = $contact;

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Sửa hóa đơn
    public function billEditAction() {
        $dateFormat = new \ZendX\Functions\Date();

        if(!empty($this->_params['data']['id'])) {
            $item       = $this->getServiceLocator()->get('Admin\Model\BillTable')->getItem(array('id' => $this->_params['data']['id']));
            $contract   = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $item['contract_id']));
            $contact    = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $contract['contact_id']));

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            $myForm = new \Admin\Form\Contract\BillEdit($this->getServiceLocator());
            $myForm->setInputFilter(new \Admin\Filter\Contract\BillEdit(array('data' => $this->_params['data'], 'item' => $item)));
            if ($item['type'] == 'Thu') {
                $caption = 'Sửa phiếu thu';
                $message = 'Sửa phiếu thu thành công';
            } elseif ($item['type'] == 'Chi') {
                $caption = 'Sửa phiếu chi';
                $message = 'Sửa phiếu chi thành công';
            }

            if($this->_params['data']['modal'] == 'success') {
                $myForm->setData($this->_params['data']);
                if($myForm->isValid()){
                    $this->_params['data']      = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    $this->_params['contract']  = $contract;
                    $this->_params['contact']   = $contact;
                    $this->_params['item']      = $item;

                    $result = $this->getServiceLocator()->get('Admin\Model\BillTable')->saveItem($this->_params, array('task' => 'contract-edit-item'));

                    $this->flashMessenger()->addMessage($message);
                    echo 'success';
                    return $this->response;
                }
            } else {
                $item_options = !empty($item['options']) ? unserialize($item['options']) : array();
                $item = array_merge($item, $item_options);
                $item['date'] = $dateFormat->formatToView($item['date']);

                $myForm->setData($item);
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']     = $myForm;
        $this->_viewModel['caption']    = $caption;
        $this->_viewModel['item']       = $item;
        $this->_viewModel['contract']   = $contract;

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Xóa hóa đơn
    public function billDeleteAction() {
        if(!empty($this->_params['data']['id'])) {
            $item       = $this->getServiceLocator()->get('Admin\Model\BillTable')->getItem(array('id' => $this->_params['data']['id']));
            $contract   = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $item['contract_id']));
            $contact    = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $contract['contact_id']));

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            $myForm = new \Admin\Form\Contract\BillDelete($this->getServiceLocator());
            $myForm->setInputFilter(new \Admin\Filter\Contract\BillDelete($this->_params));
            $myForm->setData($item);

            if($this->_params['data']['modal'] == 'success') {
                $myForm->setData($this->_params['data']);
                if($myForm->isValid()){
                    $this->_params['data']      = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    $this->_params['contract']  = $contract;
                    $this->_params['contact']   = $contact;
                    $this->_params['item']      = $item;

                    $result = $this->getServiceLocator()->get('Admin\Model\BillTable')->deleteItem($this->_params, array('task' => 'contract-delete-item'));

                    $this->flashMessenger()->addMessage('Xóa hóa đơn thành công');
                    echo 'success';
                    return $this->response;
                }
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']         = $myForm;
        $this->_viewModel['caption']        = 'Xóa hóa đơn';
        $this->_viewModel['item']           = $item;
        $this->_viewModel['contract']       = $contract;
        $this->_viewModel['bill_type']      = array('paid' => 'Thu', 'accrued' => 'Chi', 'surcharge' => 'Phụ phí');
        $this->_viewModel['paid_type']      = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array( "table" => "document", "where" => array( "code" => "bill-type-paid" ), "order" => array("ordering" => "ASC", "created" => "ASC", "name" => "ASC"), "view"  => array( "key" => "id", "value" => "name", "sprintf" => "%s" ) ), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['accrued_type']   = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array( "table" => "document", "where" => array( "code" => "bill-type-accrued" ), "order" => array("ordering" => "ASC", "created" => "ASC", "name" => "ASC"), "view"  => array( "key" => "id", "value" => "name", "sprintf" => "%s" ) ), array('task' => 'cache'));
        $this->_viewModel['surcharge_type'] = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array( "table" => "document", "where" => array( "code" => "bill-type-surcharge" ), "order" => array("ordering" => "ASC", "created" => "ASC", "name" => "ASC"), "view"  => array( "key" => "id", "value" => "name", "sprintf" => "%s" ) ), array('task' => 'cache'));

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Thêm vật phẩm
    public function matterAddAction() {
        if(!empty($this->_params['data']['id'])) {
            $items      = $this->getServiceLocator()->get('Admin\Model\MatterTable')->listItem(array('data' => array('contract_id' => $this->_params['data']['id'])), array('task' => 'list-ajax'));
            $contract   = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
            $contact    = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $contract['contact_id']));
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            $myForm = new \Admin\Form\Contract\Matter($this->getServiceLocator());
            $myForm->setInputFilter(new \Admin\Filter\Contract\Matter(array('data' => $this->_params['data'], 'route' => $this->_params['route'], 'contract' => $contract, 'contact' => $contact)));
            $myForm->setData($this->_params['data']);

            if($this->_params['data']['modal'] == 'success') {
                if($myForm->isValid()){
                    $this->_params['data']      = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    $this->_params['contract']  = $contract;
                    $this->_params['contact']   = $contact;
                    // Cập nhật vật phẩm vào Đơn hàng
                    $update = $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem($this->_params, array('task' => 'add-matter'));

                    // Thêm vật phẩm vào danh sách
                    $result = $this->getServiceLocator()->get('Admin\Model\MatterTable')->saveItem($this->_params, array('task' => 'add-item'));

                    $this->flashMessenger()->addMessage('Thêm vật phẩm thành công');
                    echo 'success';
                    return $this->response;
                }
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']     = $myForm;
        $this->_viewModel['caption']    = 'Thêm vật phẩm';
        $this->_viewModel['items']      = $items;
        $this->_viewModel['contract']   = $contract;
        $this->_viewModel['contact']    = $contact;

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Xóa vật phẩm
    public function matterDeleteAction() {
        if(!empty($this->_params['data']['id'])) {
            $item       = $this->getServiceLocator()->get('Admin\Model\MatterTable')->getItem(array('id' => $this->_params['data']['id']));
            $contract   = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $item['contract_id']));
            $contact    = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $contract['contact_id']));
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            $myForm = new \Admin\Form\Contract\MatterDelete($this->getServiceLocator());
            $myForm->setInputFilter(new \Admin\Filter\Contract\MatterDelete($this->_params));

            if($this->_params['data']['modal'] == 'success') {
                $myForm->setData($this->_params['data']);
                if($myForm->isValid()){
                    $this->_params['data']      = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    $this->_params['contract']  = $contract;
                    $this->_params['contact']   = $contact;
                    $this->_params['item']      = $item;

                    // Cập nhật vật phẩm vào Đơn hàng
                    $update = $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem($this->_params, array('task' => 'delete-matter'));

                    // Xóa khỏi danh sách
                    $result = $this->getServiceLocator()->get('Admin\Model\MatterTable')->deleteItem($this->_params, array('task' => 'contract-delete-item'));

                    $this->flashMessenger()->addMessage('Xóa vật phẩm thành công');
                    echo 'success';
                    return $this->response;
                }
            } else {
                $myForm->setData($item);
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']         = $myForm;
        $this->_viewModel['caption']        = 'Xóa vật phẩm';
        $this->_viewModel['item']           = $item;
        $this->_viewModel['matter']         = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'matter')), array('task' => 'cache'));

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Chuyển người quản lý
    public function changeUserAction(){

        if($this->getRequest()->isXmlHttpRequest()) {
            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']), null);
            $contact = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $contract['contact_id']), null);

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }

            if(!empty($contract)) {
                if($this->getRequest()->isPost()){
                    $bill = $this->getServiceLocator()->get('Admin\Model\BillTable')->listItem(array('data' => array('contract_id' => $contract['id'])), array('task' => 'list-all'));

                    $this->_params['user'] = $this->getServiceLocator()->get('Admin\Model\UserTable')->getItem(array('id' => $this->_params['data']['user_id']));
                    $this->_params['contract'] = $contract;
                    $this->_params['contact'] = $contact;
                    $this->_params['bill'] = $bill;

                    $result = $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem($this->_params, array('task' => 'change-user'));
                }
            }

            return $this->response;
        } else {
            if($this->getRequest()->isPost()){
                $myForm = new \Admin\Form\Contract\ChangeUser($this->getServiceLocator(), $this->_params);

                if($this->getRequest()->isPost()){
                    $items = $this->getServiceLocator()->get('Admin\Model\ContractTable')->listItem(array('ids' => $this->_params['data']['cid']), array('task' => 'list-item-multi'));
                }

                $this->_viewModel['myForm']	                = $myForm;
                $this->_viewModel['caption']                = 'Đơn hàng - Chuyển quyền quản lý';
                $this->_viewModel['items']                  = $items;
                $this->_viewModel['user']                   = $this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache'));
                $this->_viewModel['sale_group']             = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache'));
                $this->_viewModel['sale_branch']            = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache'));
                $this->_viewModel['product']                = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));
            } else {
                return $this->redirect()->toRoute('routeAdmin/default', array('controller' => $this->_params['controller'], 'action' => 'index'));
            }
        }

        return new ViewModel($this->_viewModel);
    }

    // Chuyển nhượng
    public function transferAction() {
        $dateFormat = new \ZendX\Functions\Date();
        $myForm = new \Admin\Form\Contract\Transfer($this->getServiceLocator(), $this->_params);

        if(!empty($this->_params['data']['id'])) {
            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            if($this->_params['data']['modal'] == 'success') {
                $myForm->setInputFilter(new \Admin\Filter\Contract\Transfer($this->_params));
                $myForm->setData($this->_params['data']);

                if($myForm->isValid()){
                    $this->_params['data'] = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    $this->_params['contract'] = $contract;

                    $result = $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem($this->_params, array('task' => 'transfer'));

                    $this->flashMessenger()->addMessage('Cập nhật dữ liệu thành công');
                    echo 'success';
                    return $this->response;
                }
            } else {
                $myForm->setData($this->_params['data']);
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']             = $myForm;
        $this->_viewModel['location_district']  = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'location-district')), array('task' => 'cache'));
        $this->_viewModel['caption']            = 'Chuyển nhượng Đơn hàng';

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    public function printMultiAction() {
        $items      = $this->getServiceLocator()->get('Admin\Model\ContractTable')->listItem(array('ids' => $this->_params['data']['cid']), array('task' => 'list-print-multi'));
        $items      = $items->current();
        $contact      = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('id' => $items['contact_id']), null);

        if(empty($items)) {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        $this->_viewModel['items']                      = $items;
        $this->_viewModel['contact']                    = $contact;
        $this->_viewModel['user']                       = $this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['bill']                       = $this->getServiceLocator()->get('Admin\Model\BillTable')->listItem(null, array('task' => 'by-contract'));
        $this->_viewModel['bill_type']                  = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-bill-type')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['transport']                  = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'transport')), array('task' => 'cache'));
        $this->_viewModel['type_of_carpet']             = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'type-of-carpet')), array('task' => 'cache'));
        $this->_viewModel['carpet_color']               = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'carpet-color')), array('task' => 'cache'));
        $this->_viewModel['tangled_color']              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'tangled-color')), array('task' => 'cache'));
        $this->_viewModel['row_seats']                  = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'row-seats')), array('task' => 'cache'));
        $this->_viewModel['flooring']                   = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        $this->_viewModel['production_department']      = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'production-department')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['product_type']               = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'product-type')), array('task' => 'cache'));
        $this->_viewModel['status']                     = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'status')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    public function importAction()
    {
        $myForm = new \Admin\Form\Contract\Import($this->getServiceLocator(), $this->_params);
        $myForm->setInputFilter(new \Admin\Filter\Contract\Import($this->_params));
        $this->_viewModel['caption'] = 'Cập nhật mã vận đơn';
        $this->_viewModel['myForm']  = $myForm;
        $viewModel                   = new ViewModel($this->_viewModel);

        if ($this->getRequest()->isXmlHttpRequest()) {
            if ($this->getRequest()->isPost()) {
                //Check liên hệ
                $itemByCode = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('code' => $this->_params['data']['code']), array('task' => 'by-code'));
                $itemByBillCode = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('bill_code' => $this->_params['data']['bill_code']), array('task' => 'by-bill-code'));
                $this->_params['data']['id'] = $itemByCode['id'];
                if (empty($itemByCode) || $itemByCode['lock'] == 1) {
                    echo 'Không tồn tại hoặc đã khóa';
                } else {
                    if (!empty($itemByBillCode) AND $itemByBillCode['code'] != $this->_params['data']['code']) {
                        echo 'Mã vận đơn đã tồn tại';
                    } else {
                        $contact = $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem($this->_params, array('task' => 'import-update'));
                        echo 'Hoàn thành';
                    }
                }

                return $this->response;
            }
        }
        else {
            if ($this->getRequest()->isPost()) {
                $myForm->setData($this->_params['data']);
                if ($myForm->isValid()) {
                    if (!empty($this->_params['data']['file_import']['tmp_name'])) {
                        $upload      = new \ZendX\File\Upload();
                        $file_import = $upload->uploadFile('file_import', PATH_FILES . '/import/', array());
                    }
                    $viewModel->setVariable('file_import', $file_import);
                    $viewModel->setVariable('import', true);

                    require_once PATH_VENDOR . '/Excel/PHPExcel/IOFactory.php';
                    $objPHPExcel = \PHPExcel_IOFactory::load(PATH_FILES . '/import/' . $file_import);

                    $sheetData = $objPHPExcel->getActiveSheet(1)->toArray(null, true, true, true);
                    $viewModel->setVariable('sheetData', $sheetData);
                }
            }
        }

        return $viewModel;
    }

    // Nhập đơn hàng từ hệ thống cũ
    public function inputAction()
    {
        $date = new \ZendX\Functions\Date();
        $myForm = new \Admin\Form\Contract\Input($this->getServiceLocator(), $this->_params);
        $myForm->setInputFilter(new \Admin\Filter\Contract\Input($this->_params));
        $this->_viewModel['caption'] = 'Import đơn hàng';
        $this->_viewModel['myForm']  = $myForm;
        $viewModel                   = new ViewModel($this->_viewModel);

        if ($this->getRequest()->isXmlHttpRequest()) {
            if ($this->getRequest()->isPost()) {
                // Check mã đơn hàng
                $contract = $this->getTable()->getItem(array('code' => $this->_params['data']['code']), array('task' => 'by-code'));
                if(!empty($contract)){
                    echo 'Đã tồn tại mã đơn hàng';
                }
                else{
                    $bill_code = $this->getTable()->getItem(array('bill_code' => $this->_params['data']['bill_code']), array('task' => 'by-bill-code'));
                    if(!empty($bill_code)){
                        echo 'Đã tồn tại mã vận đơn';
                    }
                    else{
                        $check_date = true;
                        if($check_date && !empty($this->_params['data']['date']))
                            $check_date = $date->check_date_format_to_data($this->_params['data']['date']);
                        if($check_date && !empty($this->_params['data']['guarantee_date']))
                            $check_date = $date->check_date_format_to_data($this->_params['data']['guarantee_date']);

                        if($check_date == true){
                            $contact = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('phone' => $this->_params['data']['phone']), array('task' => 'by-phone'));
                            if(empty($contact)){
                                echo 'Khách hàng không tồn tại';
                            }
                            else{
                                $this->_params['data']['contact_id'] = $contact['id'];

                                $user_mkt = $this->getServiceLocator()->get('Admin\Model\UserTable')->getItem(array('code' => $this->_params['data']['marketer_id']), array('task' => 'by-code'));
                                $this->_params['data']['marketer_id'] = !empty($user_mkt) ? $user_mkt['id'] : '';

                                $user_sales = $this->getServiceLocator()->get('Admin\Model\UserTable')->getItem(array('code' => $this->_params['data']['user_id']), array('task' => 'by-code'));
                                $this->_params['data']['user_id'] = !empty($user_sales) ? $user_sales['id'] : '';

                                $product_group = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['product_group_id'], 'code' => 'product-group'), array('task' => 'by-custom-name'));
                                $this->_params['data']['product_group_id'] = !empty($product_group) ? $product_group['id'] : '';

                                $status_product = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['production_department_type'], 'code' => 'production-department'), array('task' => 'by-custom-name'));
                                $this->_params['data']['production_department_type'] = !empty($status_product) ? $status_product['alias'] : '';

                                $status_check = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['status_check_id'], 'code' => 'status-check'), array('task' => 'by-custom-name'));
                                $this->_params['data']['status_check_id'] = !empty($status_check) ? $status_check['alias'] : '';

                                $status_accounting = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['status_acounting_id'], 'code' => 'status-acounting'), array('task' => 'by-custom-name'));
                                $this->_params['data']['status_acounting_id'] = !empty($status_accounting) ? $status_accounting['alias'] : '';

                                $production_type = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['production_type_id'], 'code' => 'production-type'), array('task' => 'by-custom-name'));
                                $this->_params['data']['production_type_id'] = !empty($production_type) ? $production_type['id'] : '';

                                $shipper = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['shipper_id'], 'code' => 'shipper'), array('task' => 'by-custom-name'));
                                $this->_params['data']['shipper_id'] = !empty($shipper) ? $shipper['id'] : '';

                                $sale_branch = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['sale_branch_id'], 'code' => 'sale-branch'), array('task' => 'by-custom-name'));
                                $this->_params['data']['sale_branch_id'] = !empty($sale_branch) ? $sale_branch['id'] : '';

                                $sale_group = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['sale_group_id'], 'code' => 'lists-group'), array('task' => 'by-custom-name'));
                                $this->_params['data']['sale_group_id'] = !empty($sale_group) ? $sale_group['id'] : '';

                                $contact_type = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['contact_type'], 'code' => 'sale-contact-type'), array('task' => 'by-custom-name'));
                                $this->_params['data']['contact_type'] = !empty($contact_type) ? $contact_type['alias'] : '';

                                $product = $this->getServiceLocator()->get('Admin\Model\ProductTable')->getItem(array('name' => $this->_params['data']['product']), array('task' => 'by-name'));
                                $this->_params['data']['product_id'] = !empty($product) ? $product['id'] : '';
                                $this->_params['data']['product_alias'] = !empty($product) ? $product['code'] : '';

                                $carpet = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->getItem(array('name' => $this->_params['data']['carpet_color_id']), array('task' => 'by-name'));
                                $this->_params['data']['carpet_color_id'] = !empty($carpet) ? $carpet['id'] : '';

                                $tangled = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->getItem(array('name' => $this->_params['data']['tangled_color_id']), array('task' => 'by-name'));
                                $this->_params['data']['tangled_color_id'] = !empty($tangled) ? $tangled['id'] : '';

                                $flooring = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['flooring_id'], 'code' => 'flooring'), array('task' => 'by-custom-name'));
                                $this->_params['data']['flooring_id'] = !empty($flooring) ? $flooring['id'] : '';

                                $transport = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['transport_id'], 'code' => 'transport'), array('task' => 'by-custom-name'));
                                $this->_params['data']['transport_id'] = !empty($transport) ? $transport['id'] : '';

                                $this->getServiceLocator()->get('Admin\Model\ContractTable')->importItem($this->_params, array('task' => 'import-contract-old'));
                                echo "Hoàn thành";
                            }
                        }
                        else{
                            echo "Không đúng định dạng ngày tháng";
                        }
                    }
                }
                return $this->response;
            }
        } else {
            if ($this->getRequest()->isPost()) {
                $myForm->setData($this->_params['data']);
                if ($myForm->isValid()) {
                    if (!empty($this->_params['data']['file_import']['tmp_name'])) {
                        $upload      = new \ZendX\File\Upload();
                        $file_import = $upload->uploadFile('file_import', PATH_FILES . '/import/', array());
                    }
                    $viewModel->setVariable('file_import', $file_import);
                    $viewModel->setVariable('import', true);

                    require_once PATH_VENDOR . '/Excel/PHPExcel/IOFactory.php';
                    $objPHPExcel = \PHPExcel_IOFactory::load(PATH_FILES . '/import/' . $file_import);

                    $sheetData = $objPHPExcel->getActiveSheet(1)->toArray(null, true, true, true);
                    $viewModel->setVariable('sheetData', $sheetData);
                }
            }
        }

        return $viewModel;
    }

    // Nhập đơn hàng có sẵn trong kho
    public function inputWarehouseAction()
    {
        $date = new \ZendX\Functions\Date();
        $myForm = new \Admin\Form\Contract\Input($this->getServiceLocator(), $this->_params);
        $myForm->setInputFilter(new \Admin\Filter\Contract\Input($this->_params));
        $this->_viewModel['caption'] = 'Import đơn hàng có sẵn';
        $this->_viewModel['myForm']  = $myForm;
        $viewModel                   = new ViewModel($this->_viewModel);

        if ($this->getRequest()->isXmlHttpRequest()) {
            if ($this->getRequest()->isPost()) {
                if (empty($this->_params['data']['product_name'])) echo 'Sản phẩm không được để trống';
                else {
                    $check_date = $date->check_date_format_to_data($this->_params['data']['date']);

                    if($check_date == true){
                        $sale_branch = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['sale_branch_id'], 'code' => 'sale-branch'), array('task' => 'by-custom-name'));
                        if(!empty($sale_branch)){
                            $this->_params['data']['sale_branch_id'] =  $sale_branch['id'];

                            $contact = $this->getServiceLocator()->get('Admin\Model\ContactTable')->getItem(array('phone' => $this->_params['data']['phone']), array('task' => 'by-phone'));
                            $this->_params['data']['contact_id'] = !empty($contact) ? $contact['id'] : '';

                            $production_type = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['production_type_id'], 'code' => 'production-type'), array('task' => 'by-custom-name'));
                            $this->_params['data']['production_type_id'] = !empty($production_type) ? $production_type['id'] : '';

                            $product = $this->getServiceLocator()->get('Admin\Model\ProductTable')->getItem(array('name' => $this->_params['data']['product']), array('task' => 'by-name'));
                            $this->_params['data']['product_id'] = !empty($product) ? $product['id'] : '';
                            $this->_params['data']['product_alias'] = !empty($product) ? $product['code'] : '';

                            $carpet = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->getItem(array('name' => $this->_params['data']['carpet_color_id']), array('task' => 'by-name'));
                            $this->_params['data']['carpet_color_id'] = !empty($carpet) ? $carpet['id'] : '';

                            $tangled = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->getItem(array('name' => $this->_params['data']['tangled_color_id']), array('task' => 'by-name'));
                            $this->_params['data']['tangled_color_id'] = !empty($tangled) ? $tangled['id'] : '';

                            $flooring = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->getItem(array('name' => $this->_params['data']['flooring_id'], 'code' => 'flooring'), array('task' => 'by-custom-name'));
                            $this->_params['data']['flooring_id'] = !empty($flooring) ? $flooring['id'] : '';

                            $this->_params['data']['status_acounting_id'] = STATUS_CONTRACT_ACOUNTING_RETURN;

                            $this->getServiceLocator()->get('Admin\Model\ContractTable')->importItem($this->_params, array('task' => 'import-contract-warehouse'));
                            echo "Hoàn thành";
                        }
                        else{
                            echo "Cơ sở không tồn tại";
                        }
                    }
                    else{
                        echo "Không đúng định dạng ngày tháng";
                    }
                }

                return $this->response;
            }
        }
        else {
            if ($this->getRequest()->isPost()) {
                $myForm->setData($this->_params['data']);
                if ($myForm->isValid()) {
                    if (!empty($this->_params['data']['file_import']['tmp_name'])) {
                        $upload      = new \ZendX\File\Upload();
                        $file_import = $upload->uploadFile('file_import', PATH_FILES . '/import/', array());
                    }
                    $viewModel->setVariable('file_import', $file_import);
                    $viewModel->setVariable('import', true);

                    require_once PATH_VENDOR . '/Excel/PHPExcel/IOFactory.php';
                    $objPHPExcel = \PHPExcel_IOFactory::load(PATH_FILES . '/import/' . $file_import);

                    $sheetData = $objPHPExcel->getActiveSheet(1)->toArray(null, true, true, true);
                    $viewModel->setVariable('sheetData', $sheetData);
                }
            }
        }

        return $viewModel;
    }

    public function exportAction() {
        $dateFormat             = new \ZendX\Functions\Date();
        $items                  = $this->getTable()->listItem(array('ids' => $this->_params['data']['cid']), array('task' => 'list-item-export'));
//        $items                  = $this->getTable()->listItem($this->_params, array('task' => 'list-item', 'paginator' => false));

        $user                   = $this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache'));
        $sale_group             = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache'));
        $sale_branch            = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache'));
        $contract_type          = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-contract-type')), array('task' => 'cache'));
        $sale_history_action    = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-history-action')), array('task' => 'cache'));
        $sale_history_result    = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-history-result')), array('task' => 'cache'));
        $transport              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'transport')), array('task' => 'cache'));
        $location_city          = $this->getServiceLocator()->get('Admin\Model\LocationsTable')->listItem(array('level' => 1), array('task' => 'cache'));
        $location_district      = $this->getServiceLocator()->get('Admin\Model\LocationsTable')->listItem(array('level' => 2), array('task' => 'cache'));

        $status_product         = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'production-department')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $status_check           = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'status-check')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $status_accounting      = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'status-acounting')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $shipper                = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'shipper')), array('task' => 'cache')), array('key' => 'id', 'value' => 'object'));
        $products               = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));

        $carpet_color = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
        $tangled_color          = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
        $flooring               = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        //Include PHPExcel
        require_once PATH_VENDOR . '/Excel/PHPExcel.php';

        // Config
        $config = array(
            'sheetData' => 0,
            'headRow' => 1,
            'startRow' => 2,
            'startColumn' => 0,
        );

        // Column
        $arrColumn = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ');

        // Data Export
        $arrData = array(
            array('field' => 'stt', 'title' => 'STT'),
            array('field' => 'bill_code', 'title' => 'MÃ VẬN ĐƠN'),
            array('field' => 'date','type'=>'date', 'title' => 'NGÀY','format'=>'d/m/Y H:i:s'),
            array('field' => 'code', 'title' => 'MÃ ĐƠN HÀNG'),
            array('field' => 'user_id', 'title' => 'Nhân viên', 'type' => 'data_source', 'data_source' => $user),
            array(
                'field' =>'product',
                'title' => 'SỐ LƯỢNG + NỘI DUNG',
                'type' => 'custom_serialize',
                'data_custom_field' => 'options',
            ),
            array('field' => 'sale_note', 'title' => 'GHI CHÚ SALES', 'type' => 'options', 'option_field' => 'sale_note'),
            array('field' => 'shipper_id', 'title' => 'NV GIAO HÀNG', 'type' => 'data_source', 'data_source' => $shipper),
            array('field' => 'name', 'title' => 'TÊN NGƯỜI NHẬN'),
            array('field' => 'weight', 'title' => 'CÂN NẶNG'),
            array('field' => 'address', 'title' => 'ĐỊA CHỈ'),
            array('field' => 'phone', 'title' => 'SỐ ĐIỆN THOẠI'),
            array('field' => 'price_total', 'title' => 'TỔNG TIỀN'),
            array('field' => 'price_listed','type'=>'listed-price', 'title' => 'GIÁ NIÊM YẾT'),
            array('field' => 'transport_id', 'title' => 'DỊCH VỤ VẬN CHUYỂN', 'type' => 'data_source', 'data_source' => $transport),
            array('field' => 'note_order', 'title' => 'GHI CHÚ ĐỐI VỚI CÁC ĐƠN HÀNG ĐẦU NHẬN TT'),
            array('field' => 'view_product', 'title' => 'CHO KHÁCH XEM HÀNG'),
        );

        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator($this->_userInfo->getUserInfo('name'))
            ->setLastModifiedBy($this->_userInfo->getUserInfo('username'))
            ->setTitle("Don_kinh_doanh_".date('d-m-Y'));

        // Dữ liệu tiêu đề cột
        $startColumn = $config['startColumn'];
        foreach ($arrData AS $key => $data) {
            $objPHPExcel->setActiveSheetIndex($config['sheetData'])->setCellValue($arrColumn[$startColumn] . $config['headRow'], $data['title']);
            $objPHPExcel->getActiveSheet()->getStyle($arrColumn[$startColumn] . $config['headRow'])->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension($arrColumn[$startColumn])->setAutoSize(true);
            $startColumn++;
        }

        // Dữ liệu data
        $startRow = $config['startRow'];
        $i = 1;

        foreach ($items AS $item) {
            $item['stt'] = $i;
            $options = unserialize($item['options']);
            $contact_options = unserialize($item['contact_options']);

            $item['name'] = !empty($options['contract_received']['name']) ? $options['contract_received']['name'] : $item['contact_name'];
            $item['address'] = !empty($options['contract_received']['address']) ? $options['contract_received']['address'] : $contact_options['address'];
            $item['phone'] = !empty($options['contract_received']['phone']) ? $options['contract_received']['phone'] : $item['contact_phone'];
            $item['product_name'] = $options['product_name'];
            $startColumn = $config['startColumn'];
            foreach ($arrData AS $key => $data) {
                switch ($data['type']) {
                    case 'date':
                        $formatDate = $data['format'] ? $data['format'] : 'd/m/Y';
                        $value = '\''.date($formatDate,strtotime($item[$data['field']]));// $dateFormat->formatToView($item[$data['field']], $formatDate);
                        break;
                    case 'data_source':
                        $field = $data['data_source_field'] ? $data['data_source_field'] : 'name';
                        $value = $data['data_source'][$item[$data['field']]][$field];
                        break;
                    case 'listed-price':
                        $value = array_sum(array_column($options['product'],'listed_price'));
                        break;
                    case 'data_serialize':
                        $data_serialize = $item[$data['data_serialize_field']] ? unserialize($item[$data['data_serialize_field']]) : array();
                        $value = $data_serialize[$data['field']];

                        if(!empty($data['data_source'])) {
                            $field = $data['data_source_field'] ? $data['data_source_field'] : 'name';
                            $value = $data['data_source'][$data_serialize[$data['field']]][$field];
                        }
                        if(!empty($data['data_date_format'])) {
                            $value = $dateFormat->formatToView($data_serialize[$data['field']], $data['data_date_format']);
                        }
                        break;
                    case 'options':
                        $value = $options[$data['option_field']];
                        break;
                    case 'custom_serialize':
                        $value = '';
                        $data_custom = $item[$data['data_custom_field']] ? unserialize($item[$data['data_custom_field']]) : array();
                        $key = 0;
                        foreach ($data_custom[$data['field']] AS $key_f => $value_f) {
                            $infos = [$carpet_color[$value_f['carpet_color']]['name']?:'Không Làm',$tangled_color[$value_f['tangled_color']]['name']?:'Không Làm',$flooring[$value_f['flooring_id']]['name']?:'Không Làm'];
                            $value .= (++$key).'. '.$products[$value_f['product_id']]->name.' x '.($value_f['numbers']?:1).' '.$value_f['product_name']. ', '.PHP_EOL.implode(', ',$infos) .PHP_EOL;
                        }
                        break;
                    default:
                        $value = $item[$data['field']];
                }
                $objPHPExcel->setActiveSheetIndex($config['sheetData'])->setCellValue($arrColumn[$startColumn] . $startRow, $value);
                $objPHPExcel->setActiveSheetIndex($config['sheetData'])->getStyle($arrColumn[$startColumn] . $startRow)->getAlignment()->setWrapText(true);
                $startColumn++;
            }
            $startRow++;
            $i++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.'Don_kinh_doanh_'.date('d-m-Y').'.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;

        return $this->response;
    }
    public function exportOldAction() {
        $dateFormat             = new \ZendX\Functions\Date();
        $items                  = $this->getTable()->listItem(array('data'=>array('ids'=>$this->_params['data']['cid'])), array('task' => 'list-by-id'));
        $user                   = $this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache'));
        $sale_group             = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache'));
        $sale_branch            = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache'));
        $contract_type          = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-contract-type')), array('task' => 'cache'));
        $sale_history_action    = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-history-action')), array('task' => 'cache'));
        $sale_history_result    = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-history-result')), array('task' => 'cache'));
        $transport              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'transport')), array('task' => 'cache'));
        $location_city          = $this->getServiceLocator()->get('Admin\Model\LocationsTable')->listItem(array('level' => 1), array('task' => 'cache'));
        $location_district      = $this->getServiceLocator()->get('Admin\Model\LocationsTable')->listItem(array('level' => 2), array('task' => 'cache'));
        $shipper                = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'shipper')), array('task' => 'cache'));
        $carpet_color           = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
        $tangled_color          = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
        $flooring               = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        $product                = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));

        //Include PHPExcel
        require_once PATH_VENDOR . '/Excel/PHPExcel.php';

        // Config
        $config = array(
            'sheetData' => 0,
            'headRow' => 1,
            'startRow' => 2,
            'startColumn' => 0,
        );

        // Column
        $arrColumn = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ');

        // Data Export
        $arrData = array(
            array('field' => 'stt', 'title' => 'STT'),
            array('field' => 'bill_code', 'title' => 'MÃ VẬN ĐƠN'),
            array('field' => 'code', 'title' => 'MÃ ĐƠN HÀNG'),
            array('field' => 'date','type'=>'date', 'title' => 'NGÀY'),
            array('field' => 'user_id', 'title' => 'NHÂN VIÊN', 'type' => 'data_source', 'data_source' => $user),
            // array('field' => 'product_name', 'title' => 'SỐ LƯỢNG + NỘI DUNG'),
            array('field' => 'name', 'title' => 'TÊN NGƯỜI NHẬN'),
            array('field' => 'phone', 'title' => 'SỐ ĐIỆN THOẠI'),
            array('field' => 'address', 'title' => 'ĐỊA CHỈ'),
            array('field' => 'price_total', 'title' => 'TỔNG TIỀN'),
            array('field' => 'product_id', 'title' => 'SẢN PHẨM'),
            array('field' => 'product_name', 'title' => 'TÊN XE - NĂM SẢN XUẤT'),
            array('field' => 'carpet_color', 'title' => 'MÀU SẮC THẢM'),
            array('field' => 'tangled_color', 'title' => 'MÀU RỐI'),
            array('field' => 'flooring', 'title' => 'LOẠI SẢN PHẨM'),

            // array('field' => 'transport_id', 'title' => 'DỊCH VỤ VẬN CHUYỂN', 'type' => 'data_source', 'data_source' => $transport),
            // array('field' => 'note_order', 'title' => 'GHI CHÚ ĐỐI VỚI CÁC ĐƠN HÀNG ĐẦU NHẬN TT'),
            array('field' => 'sale_note', 'title' => 'GHI CHÚ SALES'),
            array('field' => 'shipper_id', 'title' => 'NV GIAO HÀNG', 'type' => 'data_source', 'data_source' => $shipper),
        );

        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator($this->_userInfo->getUserInfo('name'))
            ->setLastModifiedBy($this->_userInfo->getUserInfo('username'))
            ->setTitle("Export");

        // Dữ liệu tiêu đề cột
        $startColumn = $config['startColumn'];
        foreach ($arrData AS $key => $data) {
            $objPHPExcel->setActiveSheetIndex($config['sheetData'])->setCellValue($arrColumn[$startColumn] . $config['headRow'], $data['title']);
            $objPHPExcel->getActiveSheet()->getStyle($arrColumn[$startColumn] . $config['headRow'])->getFont()->setBold(true);
            $startColumn++;
        }

        // Dữ liệu data
        $startRow = $config['startRow'];
        $i = 1;
        foreach ($items AS $item) {
            $item['stt'] = $i;
            $options = unserialize($item['options']);
            $item['name'] = $options['contract_received']['name'];
            $item['address'] = $options['contract_received']['address'];
            $item['phone'] = $options['contract_received']['phone'];
            $item['sale_note'] = $options['sale_note'];
            $index_product = -1;
            foreach($options['product'] as $k=>$v) {
                $index_product += 1;
                $item['product_id'] = $product[$v['product_id']]['name'];
                $item['product_name'] = $v['product_name'];
                $item['carpet_color'] = $carpet_color[$v['carpet_color_id']]['name'];
                $item['tangled_color'] = $tangled_color[$v['tangled_color_id']]['name'];
                $item['flooring'] = $flooring[$v['flooring_id']]['name'];
                $row_product = $startRow + $index_product;
                $objPHPExcel->setActiveSheetIndex($config['sheetData'])->setCellValue($arrColumn[9] . $row_product, $item['product_id']);
                $objPHPExcel->setActiveSheetIndex($config['sheetData'])->setCellValue($arrColumn[10] . $row_product, $item['product_name']);
                $objPHPExcel->setActiveSheetIndex($config['sheetData'])->setCellValue($arrColumn[11] . $row_product, $item['carpet_color']);
                $objPHPExcel->setActiveSheetIndex($config['sheetData'])->setCellValue($arrColumn[12] . $row_product, $item['tangled_color']);
                $objPHPExcel->setActiveSheetIndex($config['sheetData'])->setCellValue($arrColumn[13] . $row_product, $item['flooring']);
            }
            $startColumn = $config['startColumn'];
            foreach ($arrData AS $key => $data) {
                switch ($data['type']) {
                    case 'date':
                        $formatDate = $data['format'] ? $data['format'] : 'd/m/Y';
                        $value = $dateFormat->formatToView($item[$data['field']], $formatDate);
                        break;
                    case 'data_source':
                        $field = $data['data_source_field'] ? $data['data_source_field'] : 'name';
                        $value = $data['data_source'][$item[$data['field']]][$field];
                        break;
                    case 'data_serialize':
                        $data_serialize = $item[$data['data_serialize_field']] ? unserialize($item[$data['data_serialize_field']]) : array();
                        $value = $data_serialize[$data['field']];

                        if(!empty($data['data_source'])) {
                            $field = $data['data_source_field'] ? $data['data_source_field'] : 'name';
                            $value = $data['data_source'][$data_serialize[$data['field']]][$field];
                        }
                        if(!empty($data['data_date_format'])) {
                            $value = $dateFormat->formatToView($data_serialize[$data['field']], $data['data_date_format']);
                        }
                        break;
                    default:
                        if ($data['field'] == 'phone') $item[$data['field']] = '\''.$item[$data['field']];
                        $value = $item[$data['field']];
                }
                if (!in_array($data['field'],['product_id','product_name','carpet_color','tangled_color','flooring']))
                    $objPHPExcel->setActiveSheetIndex($config['sheetData'])->setCellValue($arrColumn[$startColumn] . $startRow, $value);
                $startColumn++;
            }
            $startRow+=(1+(count($options['product'])-1));
            $i++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Export.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;

        return $this->response;
    }

    // Cập nhật giá vốn của của đơn hàng có sẵn - sử dụng khi cần cập nhật đồng loạt các đơn hàng có trên hệ thống
    public function updateCapitalAction() {
        $contracts = $this->getTable()->listItem(null, array('task' => 'list-all'));

        foreach ($contracts as $contract){
            $options = !empty($contract['options']) ? unserialize($contract['options']) : array();
            if(count($options['product'])){
                $options_update = array();

                foreach ($options['product'] as $key_p => $product){
                    if(!empty($product['stock'])){
                        $contract_stock = $this->getTable()->getItem(array('code' => $product['stock']), array('task' => 'by-code'));
                        $stock_sale_branch_id           = $contract_stock['sale_branch_id'];

                        $product_stock_id               = $product['product_id'];
                        $product_stock_carpet_color_id  = $product['carpet_color_id'];
                        $product_stock_tangled_color_id = $product['tangled_color_id'];
                        $product_stock_flooring_id_id   = $product['flooring_id'];

                        $stock_options = !empty($contract_stock['options']) ? unserialize($contract_stock['options']) : array();
                        foreach ($stock_options['product'] as $p){
                            if($p['product_id'] == $product_stock_id && $p['carpet_color_id'] == $product_stock_carpet_color_id && $p['tangled_color_id'] == $product_stock_tangled_color_id && $p['flooring_id'] == $product_stock_flooring_id_id){
                                if($stock_sale_branch_id == $contract['sale_branch_id']){
                                    $options_update[$key_p]['total_production'] = 0;
                                }
                                else{
                                    $options_update[$key_p]['total_production'] = $p['total_production'] / 2;
                                }
                            }
                        }
                    }
                    else{
                        $options_update[$key_p]['total_production'] = $product['total_production'];
                    }
                }
                if(!empty($options_update)){
                    $this->getTable()->saveItem(array('data' => array('id' => $contract['id'], 'options_update' => $options_update)), array('task' => 'update-capital'));
                }
            }
        }

        return $this->response;
    }

    // Cập nhật key_id cho sản phẩm của tất cả các đơn - sử dụng một lần khi cập nhật dữ liệu mới.
    public function updateKeyIdProductAction() {
        $list_all_create = $this->getTable()->listItem(null, array('task' => 'list-all'));
        foreach ($list_all_create as $key => $value){
            $this->_params['item'] = $value;
            $this->_params['data']['id'] = $value['id'];
            $this->getTable()->saveItem($this->_params , array('task' => 'create-key-id'));
        }

        $list_all_update = $this->getTable()->listItem(null, array('task' => 'list-all'));
        foreach ($list_all_update as $key => $value){
            $this->_params['item'] = $value;
            $this->_params['data']['id'] = $value['id'];
            $this->getTable()->saveItem($this->_params , array('task' => 'update-key-id'));
        }
        return $this->response;
    }

    // Cập nhật giá vốn mặc định cho sản phẩm của tất cả các đơn - sử dụng một lần khi cập nhật dữ liệu mới.
    public function updateCapitalDefaultAction() {
        $contracts = $this->getTable()->listItem(null, array('task' => 'list-all'));
        foreach ($contracts as $keys => $values){
            $options  = unserialize($values['options']);
            $products = $options['product'];
            if(!empty($products)){
                $carpetColor  = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
                $tangledColor = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
                foreach ($products as $key => $value){
                    if(!empty($value['stock'])){
                        $contract_stock = $this->getTable()->getItem(array('code' => $value['stock']), array('task' => 'by-code'));
                        $stock_sale_branch_id           = $contract_stock['sale_branch_id'];

                        $product_stock_id               = $value['product_id'];
                        $product_stock_carpet_color_id  = $value['carpet_color_id'];
                        $product_stock_tangled_color_id = $value['tangled_color_id'];
                        $product_stock_flooring_id_id   = $value['flooring_id'];

                        $stock_options = !empty($contract_stock['options']) ? unserialize($contract_stock['options']) : array();
                        foreach ($stock_options['product'] as $p){
                            if($p['product_id'] == $product_stock_id && $p['carpet_color_id'] == $product_stock_carpet_color_id && $p['tangled_color_id'] == $product_stock_tangled_color_id && $p['flooring_id'] == $product_stock_flooring_id_id){
                                if($stock_sale_branch_id == $values['sale_branch_id']){
                                    $products[$key]['capital_default'] = 0;
                                }
                                else{
                                    $products[$key]['capital_default'] = $p['capital_default'] / 2;
                                }
                            }
                        }
                    }
//                    else{
//                        $parentCarpet  = $carpetColor[$value['carpet_color_id']]['parent'];
//                        $parentTangled = $tangledColor[$value['tangled_color_id']]['parent'];
//
//                        $price_capital_default = $this->getServiceLocator()->get('Admin\Model\ProductListedTable')
//                            ->getItem(array(
//                                'data' => array(
//                                    'product_id' => $value['product_id'],
//                                    'group_carpet_color_id' => $parentCarpet,
//                                    'group_tangled_color_id' => $parentTangled,
//                                    'flooring_id' => $value['flooring_id'],
//                                    'type' => 'default',
//                                )
//                            ), array('task' => 'by-ajax'));
//                        $capital_default = $price_capital_default['price'] ? $price_capital_default['price'] : 0;
//                        $products[$key]['capital_default'] = $capital_default;
//                    }
                }
            }
            $options['product'] = $products;
            $this->_params['data']['id']        = $values['id'];
            $this->_params['data']['options']   = serialize($options);

            $this->getTable()->saveItem($this->_params , array('task' => 'update-capital-default'));
        }
        return $this->response;
    }

    // Cập nhật tổng số lượng sản phẩm của đơn hàng.
    public function updateTotalNumberProductAction() {
        $contracts = $this->getTable()->listItem(null, array('task' => 'list-all'));
        foreach ($contracts as $keys => $contract){
            $this->getTable()->saveItem(array('data' => $contract['id']) , array('task' => 'update-number-product-total'));
        }
        return $this->response;
    }

    public function hiddenAction() {
        $result = $this->getTable()->saveItem($this->_params, array('task' => 'hidden'));
        $this->flashMessenger()->addMessage('Ẩn '.$result.' đơn hàng thành công');
        $this->goRoute(['action' => 'warehouse']);
    }

    public function showAction() {
        $result = $this->getTable()->saveItem($this->_params, array('task' => 'show'));
        $this->flashMessenger()->addMessage('Hiển thị '.$result.' đơn hàng thành công');
        $this->goRoute(['action' => 'warehouse-hidden']);
    }

    // Sửa trạng thái thủ công - chỉ dành cho admin
    public function editStatusAccountAction() {
        $myForm = new \Admin\Form\ContractOwed\EditStatus($this->getServiceLocator(), $this->_params);

        if(!empty($this->_params['data']['id'])) {
            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
            $myForm->setData($contract);

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            if($this->_params['data']['modal'] == 'success') {
                $myForm->setData($this->_params['data']);

                if($myForm->isValid()){
                    $this->_params['data'] = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    $result = $this->getTable()->updateItem($this->_params, array('task' => 'update-status'));

                    $this->flashMessenger()->addMessage('Cập nhật dữ liệu thành công');
                    echo 'success';
                    return $this->response;
                }
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']     = $myForm;
        $this->_viewModel['contract']   = $contract;
        $this->_viewModel['caption']    = 'Sửa trạng thái';

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    // Khóa đơn hàng
    public function lockAction() {
        $result = $this->getTable()->saveItem($this->_params, array('task' => 'lock'));
        $this->flashMessenger()->addMessage('Khóa '.$result.' đơn hàng thành công');
        $this->goRoute();
    }

    // Mở khóa đơn hàng
    public function unlockAction() {
        $result = $this->getTable()->saveItem($this->_params, array('task' => 'unlock'));
        $this->flashMessenger()->addMessage('Mở khóa '.$result.' đơn hàng thành công');
        $this->goRoute();
    }

    public function giftAction() {
        $myForm = new \Admin\Form\Contract\EditNote($this->getServiceLocator(), $this->_params);

        if(!empty($this->_params['data']['id'])) {
            $contract = $this->getServiceLocator()->get('Admin\Model\ContractTable')->getItem(array('id' => $this->_params['data']['id']));
            $contract_options = !empty($contract['options']) ? unserialize($contract['options']) : array();
            $myForm->setData($contract_options);

            if($contract['lock']){
                return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'lock', 'type' => 'modal'));
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/type', array('controller' => 'notice', 'action' => 'not-found', 'type' => 'modal'));
        }

        if($this->getRequest()->isPost()){
            if($this->_params['data']['modal'] == 'success') {
                $myForm->setInputFilter(new \Admin\Filter\Contract\EditNote($this->_params));
                $myForm->setData($this->_params['data']);

                if($myForm->isValid()){
                    $this->_params['data'] = $myForm->getData(FormInterface::VALUES_AS_ARRAY);
                    $this->_params['item'] = $contract;

                    $this->getServiceLocator()->get('Admin\Model\ContractTable')->saveItem($this->_params, array('task' => 'update-note'));

                    $this->flashMessenger()->addMessage('Cập nhật dữ liệu thành công');
                    echo 'success';
                    return $this->response;
                }
            } else {
                $myForm->setData($this->_params['data']);
            }
        } else {
            return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'not-found'));
        }

        $this->_viewModel['myForm']     = $myForm;
        $this->_viewModel['contract']   = $contract;
        $this->_viewModel['caption']    = 'Sửa ghi chú';

        $viewModel = new ViewModel($this->_viewModel);
        $viewModel->setTerminal(true);

        return $viewModel;
    }

    public function searchAction() {
        $myForm	= new \Admin\Form\Search\Contract($this->getServiceLocator(), $this->_params);
        $myForm->setData($this->_params['ssFilter']);

        if($this->_params['ssFilter']['filter_keyword']){
            $items = $this->getTable()->listItem($this->_params, array('task' => 'list-item'));
            $count = $this->getTable()->countItem($this->_params, array('task' => 'list-item'));
        }

        $userInfo = new \ZendX\System\UserInfo();
        $this->_viewModel['myForm']	                = $myForm;
        $this->_viewModel['items']                  = $items;
        $this->_viewModel['count']                  = $count;
        $this->_viewModel['user']                   = $this->getServiceLocator()->get('Admin\Model\UserTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['bill']                   = $this->getServiceLocator()->get('Admin\Model\BillTable')->listItem(null, array('task' => 'by-contract'));
        $this->_viewModel['bill_type']              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'bill-type')), array('task' => 'cache'));
        $this->_viewModel['sale_group']             = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache'));
        $this->_viewModel['sale_branch']            = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache'));
        $this->_viewModel['transport']              = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'transport')), array('task' => 'cache'));
        $this->_viewModel['carpet_color']           = $this->getServiceLocator()->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['tangled_color']          = $this->getServiceLocator()->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['flooring']               = $this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache'));
        $this->_viewModel['production_department']  = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'production-department')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['product']                = $this->getServiceLocator()->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache-active'));
        $this->_viewModel['kovProduct']             = $this->getServiceLocator()->get('Admin\Model\KovProductsTable')->listItem(null, array('task' => 'cache'));
        $this->_viewModel['status_product']         = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'production-department')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['status_check']           = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'status-check')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['status_accounting']      = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'status-acounting')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['status_sales']           = \ZendX\Functions\CreateArray::create($this->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'status')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'object'));
        $this->_viewModel['caption']                = 'Tìm kiếm đơn hàng';

        return new ViewModel($this->_viewModel);
    }
}


