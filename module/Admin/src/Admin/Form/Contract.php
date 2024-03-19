<?php
namespace Admin\Form;
use \Zend\Form\Form as Form;

class Contract extends Form {
	
	public function __construct($sm){
		parent::__construct();
		
		// FORM Attribute
		$this->setAttributes(array(
			'action'	    => '',
			'method'	    => 'POST',
			'class'		    => 'horizontal-form',
			'role'		    => 'form',
			'name'		    => 'adminForm',
			'id'		    => 'adminForm',
		));
		
		// Id
		$this->add(array(
		    'name'			=> 'id',
		    'type'			=> 'Hidden',
		));
		
		// Phone
		$this->add(array(
		    'name'			=> 'phone',
		    'type'			=> 'Text',
		    'attributes'	=> array(
		        'class'		=> 'form-control mask_phone',
                'disabled'  => 'disabled',
		    ),
		));

		// Mã cũ
		$this->add(array(
		    'name'			=> 'code_old',
		    'type'			=> 'Text',
		    'attributes'	=> array(
		        'class'		=> 'form-control',
		    ),
		));

		// tên ng nhận
		$this->add(array(
		    'name'			=> 'name_received',
		    'type'			=> 'Text',
		    'attributes'	=> array(
				'class'		=> 'form-control',
		    ),
		));

		// sđt ng nhận
		$this->add(array(
		    'name'			=> 'phone_received',
		    'type'			=> 'Text',
		    'attributes'	=> array(
				'class'		=> 'form-control',
		    ),
		));

		// Địa chỉ nhận hàng
		$this->add(array(
		    'name'			=> 'address_received',
		    'type'			=> 'Text',
		    'attributes'	=> array(
				'class'		=> 'form-control',
		    ),
		));
		
		// Name
		$this->add(array(
			'name'			=> 'name',
			'type'			=> 'Text',
			'attributes'	=> array(
				'class'		=> 'form-control',
			),
		));

		// Tên xe - Năm sản xuất
		$this->add(array(
			'name'			=> 'product_name',
			'type'			=> 'Text',
			'attributes'	=> array(
				'class'		=> 'form-control',
			),
		));
		
		// Tỉnh thành
		$this->add(array(
		    'name'			=> 'location_city_id',
		    'type'			=> 'Select',
		    'attributes'	=> array(
		        'class'		=> 'form-control select2 select2_basic',
		    ),
		    'options'		=> array(
		        'empty_option'	=> '- Chọn -',
		        'disable_inarray_validator' => true,
		        'value_options'	=> \ZendX\Functions\CreateArray::create($sm->getServiceLocator()->get('Admin\Model\LocationsTable')->listItem(array('level' => 1), array('task' => 'cache')), array('key' => 'code', 'value' => 'name')),
		    ),
		));
		
		// Quận huyện
		$this->add(array(
		    'name'			=> 'location_district_id',
		    'type'			=> 'Text',
		    'attributes'	=> array(
		        'class'		          => 'form-control select2 select2_advance',
		        'value'               => '',
		        'data-table'          => TABLE_LOCATIONS,
		        'data-id'             => 'code',
		        'data-text'           => 'name',
		        'data-parent'         => '',
		        'data-parent-field'   => 'parent',
				'data-parent-name'    => 'location_city_id',
		    ),
		));
		
		// Địa chỉ
		$this->add(array(
		    'name'			=> 'address',
		    'type'			=> 'Text',
		    'attributes'	=> array(
		        'class'		=> 'form-control',
		    )
		));
		
		// Ngày đơn hàng
		$this->add(array(
		    'name'			=> 'date',
		    'type'			=> 'Text',
		    'attributes'	=> array(
		        'class'		    => 'form-control date-picker not-push',
		        'placeholder'	=> 'dd/mm/yyyy',
		        'value'         => date('d/m/Y')
		    )
		));

		// Tiền đặt cọc
		$this->add(array(
		    'name'			=> 'price_deposits',
		    'type'			=> 'Text',
		    'attributes'	=> array(
				'class'		  => 'form-control mask_currency',
		    )
		));
		
		// Thành tiền
		$this->add(array(
		    'name'			=> 'price_total',
		    'type'			=> 'Text',
		    'attributes'	=> array(
		        'class'		  => 'form-control text-danger mask_currency',
		        'value'       => 0,
		        'data-value'  => 0,
		        'readonly'    => 'readonly',
		    )
		));
		
		// Ghi chú sales
		$this->add(array(
		    'name'			=> 'sale_note',
		    'type'			=> 'Text',
		    'attributes'	=> array(
				'class'		  => 'form-control',
		    )
		));

		// Vận chuyển
//		$this->add(array(
//		    'name'			=> 'transport_id',
//		    'type'			=> 'Select',
//		    'attributes'	=> array(
//				'class'		=> 'form-control select2 select2_basic',
//		    ),
//		    'options'		=> array(
//		        'empty_option'	=> '- Chọn -',
//		        'disable_inarray_validator' => true,
//		        'value_options'	=> \ZendX\Functions\CreateArray::create($sm->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array( "where" => array( "code" => "transport" )), array('task' => 'cache')), array('key' => 'id', 'value' => 'name')),
//		    ),
//		));

		// Bảo hành
		$this->add(array(
		    'name'			=> 'status_guarantee_id',
		    'type'			=> 'Checkbox',
		    'options'		=> array(
				'label_attributes' => array(
		            'class'		=> 'checkbox-inline',
				),
			)
		));

		// Loại đơn sản xuất
		$this->add(array(
		    'name'			=> 'production_type_id',
		    'type'			=> 'Select',
		    'attributes'	=> array(
				'class'		=> 'form-control select2 select2_basic',
		    ),
		    'options'		=> array(
		        'empty_option'	=> '- Chọn -',
		        'disable_inarray_validator' => true,
		        'value_options'	=> \ZendX\Functions\CreateArray::create($sm->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array( "where" => array( "code" => "production-type" )), array('task' => 'cache')), array('key' => 'id', 'value' => 'name')),
		    ),
		));

		// Ngày hóa đơn
		$this->add(array(
		    'name'			=> 'bill_date',
		    'type'			=> 'Text',
		    'attributes'	=> array(
		        'class'		    => 'form-control date-picker',
		        'placeholder'	=> 'dd/mm/yyyy',
		        'value'         => date('d/m/Y'),
		        'data-value'    => date('d/m/Y'),
		    )
		));
		
		// Hình thức thanh toán
		$this->add(array(
		    'name'			=> 'bill_type_id',
		    'type'			=> 'Select',
		    'attributes'	=> array(
		        'class'		=> 'form-control select2 select2_basic',
		    ),
		    'options'		=> array(
				'empty_option'	=> '- Chọn -',
				'disable_inarray_validator' => true,
		        'value_options'	=> \ZendX\Functions\CreateArray::create($sm->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array( "table" => "document", "where" => array( "code" => "sale-bill-type" ), "order" => array("ordering" => "ASC", "created" => "ASC", "name" => "ASC"), "view"  => array( "key" => "id", "value" => "name", "sprintf" => "%s" ) ), array('task' => 'cache')), array('key' => 'alias', 'value' => 'name')),
		    )
		));
		
		// Ngân hàng
		$this->add(array(
		    'name'			=> 'bill_bank_id',
		    'type'			=> 'Select',
		    'attributes'	=> array(
				'class'		=> 'form-control select2 select2_basic',
		    ),
		    'options'		=> array(
		        'empty_option'	=> '- Chọn -',
		        'disable_inarray_validator' => true,
		        'value_options'	=> \ZendX\Functions\CreateArray::create($sm->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array( "table" => "document", "where" => array( "code" => "bill-bank" ), "order" => array("ordering" => "ASC", "created" => "ASC", "name" => "ASC"), "view"  => array( "key" => "id", "value" => "name", "sprintf" => "%s" ) ), array('task' => 'cache')), array('key' => 'alias', 'value' => 'name')),
		    )
		));
		
		// Số phiếu thu
		$this->add(array(
		    'name'			=> 'bill_paid_number',
		    'type'			=> 'Text',
		    'attributes'	=> array(
		        'class'		=> 'form-control mask_bill',
		    )
		));
		
		// Số tiền thu
		$this->add(array(
		    'name'			=> 'bill_paid_price',
		    'type'			=> 'Text',
		    'attributes'	=> array(
		        'class'		=> 'form-control mask_currency',
		    )
		));
		// Phân loại phiếu thu
		$bill_paid_type_id = \ZendX\Functions\CreateArray::create($sm->getServiceLocator()->get('Admin\Model\DocumentTable')->listItem(array( "table" => "document", "where" => array( "code" => "bill-type-paid" ), "order" => array("ordering" => "ASC", "created" => "ASC", "name" => "ASC"), "view"  => array( "key" => "id", "value" => "name", "sprintf" => "%s" ) ), array('task' => 'cache')), array('key' => 'alias', 'value' => 'name'));
		unset($bill_paid_type_id['nop-them']);
		unset($bill_paid_type_id['phu-phi']);
		$this->add(array(
		    'name'			=> 'bill_paid_type_id',
		    'type'			=> 'Select',
		    'attributes'	=> array(
		        'class'		=> 'form-control select2 select2_basic',
		    ),
		    'options'		=> array(
                'empty_option'	=> '- Chọn -',
				'value_options'	=> $bill_paid_type_id
		    )
		));
	}
}