<?php
namespace ZendX\Functions;

class Gid {
    protected $_string;
    
    public function __construct($string = null) {
        $this->_string = $this->_string ? $this->_string : 'kjasdofguas0rtlkwjer0q8r9udg9082q40trusqoidufg99a8syerqy93rqwydfy872q8734r59uqwefg';
    }
    
    public function getId() {
        return @time() . str_shuffle(substr(str_shuffle($this->_string), 0, 12));
    }
//    public function getId() {
//        $timestamp = time();
//        $randomChars = $this->random(9); //
//        $randomId = $timestamp . $randomChars . mt_rand(100, 999); // Thêm một số ngẫu nhiên từ 100 đến 999
//        return $randomId;
//    }
    
    public function random($length = 10) {
        return substr(str_shuffle($this->_string), 0, $length);
    }
}