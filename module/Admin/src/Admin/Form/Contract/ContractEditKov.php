<?php
namespace Admin\Form\Contract;
use \Zend\Form\Form as Form;

class ContractEditKov extends Form {
	
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

		
		// Ghi chú sales
		$this->add(array(
		    'name'			=> 'sale_note',
		    'type'			=> 'Text',
		    'attributes'	=> array(
				'class'		  => 'form-control',
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
		        'value_options'	=> \ZendX\Functions\CreateArray::create($sm->get('Admin\Model\DocumentTable')->listItem(array( "where" => array( "code" => "production-type" )), array('task' => 'cache')), array('key' => 'id', 'value' => 'name')),
		    ),
		));
	}
}