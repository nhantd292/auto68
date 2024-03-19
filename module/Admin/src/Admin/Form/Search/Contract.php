<?php
namespace Admin\Form\Search;
use \Zend\Form\Form as Form;

class Contract extends Form{ 
    
	public function __construct($sm, $params = null){
	    $action   = $params['action'];
	    $ssFilter = $params['ssFilter'];
		parent::__construct();
		
		$userInfo = new \ZendX\System\UserInfo();
		$userInfo = $userInfo->getUserInfo();
		
		// FORM Attribute
		$this->setAttributes(array(
			'action'	=> '',
			'method'	=> 'POST',
			'class'		=> 'horizontal-form',
			'role'		=> 'form',
			'name'		=> 'adminForm',
			'id'		=> 'adminForm',
		));
		
		// Keyword
		$this->add(array(
		    'name'			=> 'filter_keyword',
		    'type'			=> 'Text',
		    'attributes'	=> array(
		        'placeholder'   => 'Từ khóa',
		        'class'			=> 'form-control input-sm',
		        'id'			=> 'filter_keyword',
		    ),
		));

		// Bắt đầu từ ngày
		$this->add(array(
		    'name'			=> 'filter_date_begin',
		    'type'			=> 'Text',
		    'attributes'	=> array(
		        'class'			=> 'form-control date-picker',
		        'placeholder'	=> 'Từ ngày',
		        'autocomplete'  => 'off'
		    )
		));
		
		// Ngày kết thúc
		$this->add(array(
		    'name'			=> 'filter_date_end',
		    'type'			=> 'Text',
		    'attributes'	=> array(
		        'class'			=> 'form-control date-picker',
		        'placeholder'	=> 'Đến ngày',
		        'autocomplete'  => 'off'
		    )
		));
		
		// Phân loại ngày tìm kiếm
		$this->add(array(
		    'name'			=> 'filter_date_type',
		    'type'			=> 'Select',
		    'attributes'	=> array(
		        'class'		=> 'form-control select2 select2_basic',
		        'value'     => 'date'
		    ),
		    'options'		=> array(
                'value_options'	=> array('date' => 'Ngày lên đơn', 'production_date' => 'Ngày sản xuất'),
		    )
		));
		
		// Màu thảm
		$this->add(array(
		    'name'			=> 'filter_carpet_color',
		    'type'			=> 'Select',
		    'attributes'	=> array(
		        'class'		=> 'form-control select2 select2_basic',
		    ),
		    'options'		=> array(
		        'empty_option'	=> '- Màu thảm -',
		        'value_options'	=> \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\CarpetColorTable')->listItem(null, array('task' => 'list-all')), array('key' => 'id', 'value' => 'name')),
		    )
		));

		// Màu rối
		$this->add(array(
		    'name'			=> 'filter_tangled_color',
		    'type'			=> 'Select',
		    'attributes'	=> array(
		        'class'		=> 'form-control select2 select2_basic',
		    ),
		    'options'		=> array(
		        'empty_option'	=> '- Màu rối -',
		        'value_options'	=> \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\TangledColorTable')->listItem(null, array('task' => 'list-all')), array('key' => 'id', 'value' => 'name')),
		    )
		));

        // loại sản phẩm
        $this->add(array(
            'name'			=> 'filter_flooring',
            'type'			=> 'Select',
            'attributes'	=> array(
                'class'		=> 'form-control select2 select2_basic',
            ),
            'options'		=> array(
                'empty_option'	=> '- Loại sản phẩm -',
                'value_options'	=> \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'flooring')), array('task' => 'cache')), array('key' => 'id', 'value' => 'name')),
            )
        ));

		// Cơ sở
		$this->add(array(
		    'name'			=> 'filter_sale_branch',
		    'type'			=> 'Select',
		    'attributes'	=> array(
		        'class'		=> 'form-control select2 select2_basic',
		    ),
		    'options'		=> array(
		        'empty_option'	=> '- Cơ sở kinh doanh -',
		        'value_options'	=> \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'sale-branch')), array('task' => 'cache')), array('key' => 'id', 'value' => 'name')),
		    )
		));
		
		// Đội nhóm
		$sale_group_id = $userInfo['sale_group_id'];
		$sale_group_ids = !empty($userInfo['sale_group_ids']) ? explode(',', $userInfo['sale_group_ids']) : null;
		$group = $sm->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'lists-group')), array('task' => 'cache'));
		$group_data = array();
		if(!empty($ssFilter['filter_sale_branch'])) {
			foreach ($group AS $key => $val) {
				if($val['document_id'] == $ssFilter['filter_sale_branch']) {
			        $group_data[] = $val;
				}
			}
		}
		$this->add(array(
		    'name'			=> 'filter_sale_group',
		    'type'			=> 'Select',
		    'attributes'	=> array(
		        'class'		=> 'form-control select2 select2_basic',
		    ),
		    'options'		=> array(
		        'empty_option'	=> '- Đội nhóm -',
		        'value_options'	=> \ZendX\Functions\CreateArray::createSelect($group_data, array('key' => 'id', 'value' => 'name,content', 'symbol' => ' - '))
		    )
		));

        $user_care	= \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\UserTable')->listItem(array('company_department_id' => 'care'), array('task' => 'list-user-department')), array('key' => 'id', 'value' => 'name'));
        $user_sales	= \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\UserTable')->listItem(array('company_department_id' => 'sales'), array('task' => 'list-user-department')), array('key' => 'id', 'value' => 'name'));
        $user_data = array_merge($user_sales, $user_care);

		$this->add(array(
		    'name'			=> 'filter_user',
		    'type'			=> 'Select',
		    'attributes'	=> array(
		        'class'		=> 'form-control select2 select2_basic',
		    ),
		    'options'		=> array(
		        'empty_option'	=> '- Nhân viên -',
		        'value_options'	=> $user_data,
		    )
		));
		
		// Phân loại sản phẩm
		$this->add(array(
		    'name'			=> 'filter_product_type',
		    'type'			=> 'Select',
		    'attributes'	=> array(
		        'class'		=> 'form-control select2 select2_basic',
		    ),
		    'options'		=> array(
		        'empty_option'	=> '- Loại sản phẩm -',
		        'value_options'	=> \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'product-type')), array('task' => 'cache')), array('key' => 'alias', 'value' => 'name')),
		    )
		));
		
		// Sản phẩm
		$product = $sm->get('Admin\Model\ProductTable')->listItem(null, array('task' => 'cache'));
		$filter_product = array();
		foreach ($product AS $key => $val) {
		    if(!empty($ssFilter['filter_product_type'])) {
		        if($val['type'] == $ssFilter['filter_product_type']) {
        		    $filter_product[$val['id']] = $val['name'];
		        }
		    } else {
    		    $filter_product[$val['id']] = $val['name'];
		    }
		}
		$this->add(array(
			'name'			=> 'filter_product',
			'type'			=> 'Select',
			'attributes'	=> array(
				'class'		=> 'form-control select2 select2_basic',
			),
			'options'		=> array(
				'empty_option'	=> '- Sản phẩm -',
				'value_options'	=> \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\KovProductsTable')->listItem(null, array('task' => 'cache'), array('task' => 'cache')), array('key' => 'id', 'value' => 'fullName')),
			)
		));

		
		// Lớp học
		$edu_class = $sm->get('Admin\Model\EduClassTable')->listItem(null, array('task' => 'cache'));
		$edu_class_data = array();
		    foreach ($edu_class AS $key => $val) {
		        $edu_class_data[$val['id']] = $val['name'] .' ('. $val['student_total'] .'/'. $val['student_max'] .')';
		    }

		// Mã vận đơn
		$this->add(array(
		    'name'			=> 'filter_bill_code',
		    'type'			=> 'Select',
		    'attributes'	=> array(
		        'class'		=> 'form-control select2 select2_basic',
		    ),
		    'options'		=> array(
		        'empty_option'	=> '- Mã vận đơn -',
		        'value_options'	=> array('1' => 'Chưa có', '2' => 'Đã có'),
		    )
		));

        // Shipper
        $this->add(array(
            'name'			=> 'filter_shipper_id',
            'type'			=> 'Select',
            'attributes'	=> array(
                'class'		=> 'form-control select2 select2_basic',
            ),
            'options'		=> array(
                'empty_option'	=> '- Nhân viên giao hàng -',
                'value_options'	=> \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\UserTable')->listItem(null, array('task' => 'list-positons-care')), array('key' => 'id', 'value' => 'name')),
            )
        ));

        // Shipper
        $this->add(array(
            'name'			=> 'filter_technical_id',
            'type'			=> 'Select',
            'attributes'	=> array(
                'class'		=> 'form-control select2 select2_basic',
            ),
            'options'		=> array(
                'empty_option'	=> '- Thợ kỹ thuật -',
                'value_options'	=> \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'technical')), array('task' => 'cache')), array('key' => 'id', 'value' => 'name')))
        ));

        // Shipper
        $this->add(array(
            'name'			=> 'filter_tailors_id',
            'type'			=> 'Select',
            'attributes'	=> array(
                'class'		=> 'form-control select2 select2_basic',
            ),
            'options'		=> array(
                'empty_option'	=> '- Thợ may -',
                'value_options'	=> \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\DocumentTable')->listItem(array('where' => array('code' => 'tailors')), array('task' => 'cache')), array('key' => 'id', 'value' => 'name')))
        ));



        // Bộ phận
        $this->add(array(
            'name'			=> 'filter_status_type',
            'type'			=> 'Select',
            'attributes'	=> array(
                'class'		=> 'form-control select2 select2_basic',
            ),
            'options'		=> array(
                'empty_option'	=> '- Bộ phận -',
                'disable_inarray_validator' => true,
                'value_options'	=> array('production_department_type' => 'Sản xuất', 'status_check_id' => 'Giục đơn', 'status_acounting_id' => 'Kế toán', ),
            ),
        ));

        $list_status = [];
        if($ssFilter['filter_status_type'] == 'production_department_type'){
            $list_status = \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\DocumentTable')->listItem(array( "where" => array( "code" => "production-department" )), array('task' => 'cache')), array('key' => 'alias', 'value' => 'name'));
        }
        if($ssFilter['filter_status_type'] == 'status_check_id'){
            $list_status = \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\DocumentTable')->listItem(array( "where" => array( "code" => "status-check" )), array('task' => 'cache')), array('key' => 'alias', 'value' => 'name'));
        }
        if($ssFilter['filter_status_type'] == 'status_acounting_id'){
            $list_status = \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\DocumentTable')->listItem(array( "where" => array( "code" => "status-acounting" )), array('task' => 'cache')), array('key' => 'alias', 'value' => 'name'));
        }

        // Trạng thái theo bộ phận
        $this->add(array(
            'name'			=> 'filter_status',
            'type'			=> 'Select',
            'attributes'	=> array(
                'class'		=> 'form-control select2 select2_basic',
            ),
            'options'		=> array(
                'empty_option'	=> '- Trạng thái -',
                'disable_inarray_validator' => true,
                'value_options'	=> $list_status,
            ),
        ));

        $list_contract_type = \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\DocumentTable')->listItem(array( "where" => array( "code" => "production-type" )), array('task' => 'cache')), array('key' => 'id', 'value' => 'name'));
        // Phân loại đơn hàng
        $this->add(array(
            'name' => 'filter_contract_type',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control select2 select2_basic',
            ),
            'options' => array(
                'empty_option'	=> '- Loại đơn -',
                'value_options' => $list_contract_type,
            )
        ));

        // Phân loại đơn hàng Thường - Bảo hành
        $this->add(array(
            'name' => 'filter_status_guarantee_id',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control select2 select2_basic',
            ),
            'options' => array(
                'value_options' => array('' => 'Bảo hành', '0' => 'Đơn Thường', '1' => 'Đơn bảo hành'),
            )
        ));


        // đơn đã xuất kho
        $this->add(array(
            'name' => 'filter_status_shipped',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control select2 select2_basic',
            ),
            'options' => array(
                'value_options' => array('' => 'Xác nhận xuất kho', '0' => 'Chưa xuất kho', '1' => 'Đã xuất kho'),
            )
        ));

        // Phân loại đơn hàng Thường - Bảo hành
        $this->add(array(
            'name' => 'filter_status_store',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control select2 select2_basic',
            ),
            'options' => array(
                'value_options' => array('' => 'Loại đơn', '0' => 'Đơn Thường', '1' => 'Đơn cửa hàng'),
            )
        ));
        $this->add(array(
            'name' => 'filter_status_lock',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control select2 select2_basic',
            ),
            'options' => array(
                'value_options' => array('' => 'Trạng thái khóa', '0' => 'Chưa khóa', '1' => 'Đã khóa'),
            )
        ));

        // Đơn trùng
        $this->add(array(
            'name' => 'filter_coincider',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'form-control select2 select2_basic',
            ),
            'options' => array(
                'value_options' => array('' => 'Trùng đơn', 'yes' => 'Đơn hàng trùng', 'no' => 'Đơn hàng không trùng'),
            )
        ));
		
		// Submit
		$this->add(array(
		    'name'			=> 'filter_submit',
		    'type'			=> 'Submit',
		    'attributes'	=> array(
		        'value'     => 'Tìm',
		        'class'		=> 'btn btn-sm green',
		    ),
		));
		
		// Xóa
		$this->add(array(
		    'name'			=> 'filter_reset',
		    'type'			=> 'Submit',
		    'attributes'	=> array(
		        'value'     => 'Xóa',
		        'class'		=> 'btn btn-sm red',
		    ),
		));
		
		// Action
		$this->add(array(
			'name'			=> 'filter_action',
			'type'			=> 'Hidden',
		));
		
		// Order
		$this->add(array(
		    'name'			=> 'order',
		    'type'			=> 'Hidden',
		));
		
		// Order By
		$this->add(array(
		    'name'			=> 'order_by',
		    'type'			=> 'Hidden',
		));



        // action new
        $this->add(array(
            'name'			=> 'action_new',
            'type'			=> 'Hidden',
            'attributes'	=> array(
                'value'   => 'new',
            ),
        ));
        // action index
        $this->add(array(
            'name'			=> 'action_index',
            'type'			=> 'Hidden',
            'attributes'	=> array(
                'value'   => 'index',
            ),
        ));
	}
}