<?php
namespace Admin\Filter;

use Zend\InputFilter\InputFilter;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

class ContractEditKov extends InputFilter {
	
	public function __construct($options = null){
	    $dbAdapter     = GlobalAdapterFeature::getStaticAdapter();
	    $optionId      = $options['id'];
	    $optionData    = $options['data'];
	    $optionRoute   = $options['route'];


        // Tên người nhận
        $this->add(array(
            'name'		=> 'name_received',
            'required'	=> true,
            'validators'	=> array(
                array(
                    'name'		=> 'NotEmpty',
                    'options'	=> array(
                        'messages'	=> array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Giá trị này không được để trống'
                        )
                    ),
                    'break_chain_on_failure'	=> true
                )
            )
        ));

        // Số điện thoại người nhận
        $this->add(array(
            'name'		=> 'phone_received',
            'required'	=> true,
            'validators'	=> array(
                array(
                    'name'		=> 'NotEmpty',
                    'options'	=> array(
                        'messages'	=> array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Giá trị này không được để trống'
                        )
                    ),
                    'break_chain_on_failure'	=> true
                )
            )
        ));

        // Địa chỉ người nhận
        $this->add(array(
            'name'		=> 'address_received',
            'required'	=> true,
            'validators'	=> array(
                array(
                    'name'		=> 'NotEmpty',
                    'options'	=> array(
                        'messages'	=> array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Giá trị này không được để trống'
                        )
                    ),
                    'break_chain_on_failure'	=> true
                )
            )
        ));
		
		// Ngày đơn hàng
		$this->add(array(
	        'name'		=> 'date',
	        'required'	=> true,
	        'validators'	=> array(
	            array(
	                'name'		=> 'NotEmpty',
	                'options'	=> array(
	                    'messages'	=> array(
	                        \Zend\Validator\NotEmpty::IS_EMPTY => 'Giá trị này không được để trống'
	                    )
	                ),
	                'break_chain_on_failure'	=> true
	            ),
	            array(
	                'name'		=> 'Regex',
	                'options'	=> array(
	                    'pattern'   => '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})+$/',
	                    'messages'	=> array(
	                        \Zend\Validator\Regex::NOT_MATCH => 'Không đúng định dạng ngày tháng dd/mm/yyyy'
	                    )
	                ),
	                'break_chain_on_failure'	=> true
	            )
	        )
	    ));

		// Loại đơn sản xuất
		$this->add(array(
		    'name'		=> 'production_type_id',
		    'required'	=> true,
		    'validators'	=> array(
		        array(
		            'name'		=> 'NotEmpty',
		            'options'	=> array(
		                'messages'	=> array(
		                    \Zend\Validator\NotEmpty::IS_EMPTY => 'Giá trị này không được để trống'
		                )
		            ),
		            'break_chain_on_failure'	=> true
		        )
		    )
		));
	}
}