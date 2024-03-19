<?php
namespace Admin\Filter;

use Zend\InputFilter\InputFilter;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

class Contract extends InputFilter {
	
	public function __construct($options = null){
	    $dbAdapter     = GlobalAdapterFeature::getStaticAdapter();
	    $optionId      = $options['id'];
	    $optionData    = $options['data'];
	    $optionRoute   = $options['route'];
	    
		// Phone
//		$this->add(array(
//			'name'		=> 'phone',
//			'required'	=> true,
//			'validators'	=> array(
//				array(
//					'name'		=> 'NotEmpty',
//				    'options'	=> array(
//				        'messages'	=> array(
//				            \Zend\Validator\NotEmpty::IS_EMPTY => 'Giá trị này không được để trống'
//				        )
//				    ),
//					'break_chain_on_failure'	=> true
//				),
//			    array(
//			        'name'		=> 'Regex',
//			        'options'	=> array(
//			            'pattern'   => '/^([0]{1})([0-9]{9,10})+$/',
//			            'messages'	=> array(
//			                \Zend\Validator\Regex::NOT_MATCH => 'Không đúng định dạng số điện thoại'
//			            )
//			        ),
//			        'break_chain_on_failure'	=> true
//			    )
//			)
//		));
		
		// Name
		$this->add(array(
			'name'		=> 'name',
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

        // Tỉnh thành
        $this->add(array(
            'name'		=> 'location_city_id',
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

        // Quận huyện
        $this->add(array(
            'name'		=> 'location_district_id',
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

        // Địa chỉ
        $this->add(array(
            'name'		=> 'address',
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

        $required_bill_type = true;
		$required_paid_number = false;
		$required_bill_bank_id = false;
		$required_paid_price = false;
		$required_bill_paid_type = false;

		 if (!empty($optionData['bill_type_id']) && $optionData['bill_type_id'] != 'cod') {
		 	$required_paid_price = true;
		 	$required_bill_type = true;
		 	$required_bill_paid_type = true;
		 	if($optionData['bill_type_id'] == 'chuyen-khoan') {
		 		$required_bill_bank_id = true;
		 	}
		 }
		 
		// Ngân hàng
		$this->add(array(
		    'name'		=> 'bill_bank_id',
		    'required'	=> $required_bill_bank_id,
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
		    )
		));

		// Hình thức thanh toán
		$this->add(array(
		    'name'		=> 'bill_type_id',
		    'required'	=> $required_bill_type,
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
		    )
		));

		// Ngày thu
		$this->add(array(
		    'name'		=> 'bill_date',
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
		    )
		));

		// Số phiêu thu
		$this->add(array(
		    'name'		=> 'bill_paid_number',
//		    'required'	=> $required_paid_number,
		    'required'	=> false,
//		    'validators'	=> array(
//		        array(
//		            'name'		=> 'NotEmpty',
//		            'options'	=> array(
//		                'messages'	=> array(
//		                    \Zend\Validator\NotEmpty::IS_EMPTY => 'Giá trị này không được để trống'
//		                )
//		            ),
//		            'break_chain_on_failure'	=> true
//		        ),
//		    )
		));

		// Tiền thu
		$this->add(array(
		    'name'		=> 'bill_paid_price',
		    'required'	=> $required_paid_price,
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

		// Tiền thu
		$this->add(array(
		    'name'		=> 'bill_paid_type_id',
		    'required'	=> $required_bill_paid_type,
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