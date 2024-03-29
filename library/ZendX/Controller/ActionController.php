<?php

namespace ZendX\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Controller\PluginManager;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\Http\Cookies;

class ActionController extends AbstractActionController {
    protected $_settings;
    protected $_userInfo;
    protected $_urlController;
    protected $_viewModel;
    protected $_params;
    protected $_table;
    protected $_form;
    protected $kiotviet_token;
    protected $_options = array(
        'tableName', 'formName'
    );
    protected $_paginator = array(
        'itemCountPerPage'	=> 50,
        'pageRange'			=> 5,
        'options'           => array(10, 20, 50, 100, 200, 500, 1000, 2000, 5000)
    );
    
    public function setPluginManager(PluginManager $plugins) {
        $this->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onInit'), 100);
        $this->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispath'));
        parent::setPluginManager($plugins);
    }
    
    public function onInit(MvcEvent $e) {
        // Set token kiotviet.
        if(isset($_COOKIE['kiotviet_token'])){
            $this->kiotviet_token = $_COOKIE['kiotviet_token'];
        } else {
            $token = $this->get_kiotviet_token(CLIENT_ID, CLIENT_SECRET);
            setcookie('kiotviet_token', $token,time()+43200);
            $this->kiotviet_token = $token;
        }

        // Lấy thông tin setting
        $this->_settings = $this->getServiceLocator()->get('Admin\Model\SettingTable')->listItem(array('code' => 'General'), array('task' => 'cache-by-code'));
        $this->_params['settings'] = $this->_settings;
        
        // Thông tin tài khoản đăng nhập
        $this->_userInfo = new \ZendX\System\UserInfo();
        
        // Lấy thông tin config
        $config = $this->getServiceLocator()->get('config');
        
        // Get Module - Controller - Action
        $routeMatch = $e->getRouteMatch();
        $controllerArray = explode('\\', $routeMatch->getParam('controller'));
        
        // Truyền một phần tử ra ngoài view
        $this->_params['module']        = strtolower(preg_replace('/\B([A-Z])/', '-$1', $controllerArray[0]));
        $this->_params['controller']    = strtolower(preg_replace('/\B([A-Z])/', '-$1', $controllerArray[2]));
        $this->_params['action']        = $routeMatch->getParam('action');
        
        // Lấy thông tin route
        $route = $this->params()->fromRoute();
        $route['routeName'] = $routeMatch->getMatchedRouteName();
        $this->_params['route'] = $route;
        
        // Thiết lập link controller
        $this->_urlController = $this->_params['module'] . '/' . $this->_params['controller'];
        
        // Thiết lập layout cho Controller
        $this->layout($config['module_layouts'][$controllerArray[0]]);
        
        // Thiết lập các tham số của template
        $template = explode('/',  $config['module_layouts'][$controllerArray[0]]);
        $this->_params['template'] = array(
            'pathTheme'             => PATH_TEMPLATE . '/'. $template[1],
            'pathThemeTemplate'     => PATH_TEMPLATE . '/'. $template[1] .'/template',
            'pathImg'               => PATH_TEMPLATE .'/'. $template[1] .'/img',
            'pathCss' 	            => PATH_TEMPLATE .'/'. $template[1] .'/css',
            'pathJs' 	            => PATH_TEMPLATE .'/'. $template[1] .'/js',
            'pathPlugin'	        => PATH_TEMPLATE .'/'. $template[1] .'/plugins',
            'pathHtml'	            => PATH_TEMPLATE .'/'. $template[1] .'/html',
        	'urlTheme'              => URL_TEMPLATE .'/'. $template[1],
        	'urlThemeTemplate'      => URL_TEMPLATE .'/'. $template[1] .'/template',
        	'urlImg'                => URL_TEMPLATE .'/'. $template[1] .'/img',
        	'urlCss' 	            => URL_TEMPLATE .'/'. $template[1] .'/css',
        	'urlJs' 	            => URL_TEMPLATE .'/'. $template[1] .'/js',
        	'urlPlugin'	            => URL_TEMPLATE .'/'. $template[1] .'/plugins',
        	'urlHtml'	            => URL_TEMPLATE .'/'. $template[1] .'/html',
        );
        
        // Kiểm tra quyền đăng nhập
        if($this->_params['action'] != 'login' && $this->_params['action'] != 'logout' && $this->_params['controller'] != 'notice' && $this->_params['controller'] != 'api' && $this->_params['module'] != 'api') {
            $loggedStatus = $this->identity() ? true : false;

            // Check user có bị thay đổi quyền không.
            $curent_user = $this->getServiceLocator()->get('Admin\Model\UserTable')->getItem(array('id' => $this->_userInfo->getUserInfo('id')));
            if($curent_user['flag'] != 0 || $curent_user['status'] == 0){
                return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'user', 'action' => 'logout'));
            }
            
            if($loggedStatus == false) {
                return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'user', 'action' => 'login'));
            } else {
                $userInfo = new \ZendX\System\UserInfo();
                $permission_list = $userInfo->getPermissionListInfo();
                
                if(empty($permission_list['privileges'])) {
                    return $this->redirect()->toRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'no-access'));
                } else {
                    if($permission_list['privileges'] != 'full') {
                        $aclObj = new \ZendX\System\Acl($permission_list['role'], $permission_list['privileges']);
                        
                        if($aclObj->isAllowed($this->_params) == false) {
                            $urlNoAccess = $this->url()->fromRoute('routeAdmin/default', array('controller' => 'notice', 'action' => 'no-access'));
                            $response = $this->getResponse();
                            $response->getHeaders()->addHeaderLine('Location', $urlNoAccess);
                            $response->setStatusCode(302);
                            $response->sendHeaders();
                        
                            $this->getEvent()->stopPropagation();
                            return $response;
                        }
                    }
                }
            }
        }
        
        // Khai báo Adapter mặc định cho từng module
        $zendConfig = $this->getServiceLocator()->get('Config');
        $module_adapter = $zendConfig['module_adapter'][$controllerArray[0]] ? $zendConfig['module_adapter'][$controllerArray[0]] : 'dbConfig';
        GlobalAdapterFeature::setStaticAdapter($this->getServiceLocator()->get($module_adapter));
        
        // Gọi đến function chạy đầu tiên
        $this->init();
    }
    
    public function onDispath(MvcEvent $e) {
        // Truyền tất cả params ra ngoài layout
        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        $viewModel->arrParams = $this->_params;
        $viewModel->settings = $this->_settings;
    }
    
    public function init() {
    }
    
    public function getTable() {
        if(empty($this->_table)) {
            $this->_table = $this->getServiceLocator()->get($this->_options['tableName']);
        }
    
        return $this->_table;
    }
    
    public function getForm() {
        if(empty($this->_form)) {
            $this->_form = $this->getServiceLocator()->get('FormElementManager')->get($this->_options['formName']);
        }
    
        return $this->_form;
    }
    
    public function setLayout($layout) {
        $this->_params['layout'] = $layout;
    }
    
    public function goRoute($actionInfo = null) {
        $actionInfo['controller'] = !empty($actionInfo['controller']) ? $actionInfo['controller'] : $this->_params['controller'];
        $actionInfo['action'] = !empty($actionInfo['action']) ? $actionInfo['action'] : 'index';
        $actionInfo['route'] = !empty($actionInfo['route']) ? $actionInfo['route'] : $this->_params['route']['routeName'];
        
        $paramRoute = array('controller' => $actionInfo['controller'], 'action' => $actionInfo['action']);
        if(!empty($actionInfo['id'])) {
            $paramRoute['id'] = $actionInfo['id'];
        }
        return $this->redirect()->toRoute($actionInfo['route'], $paramRoute);
    }
    
    public function goUrl($url = null) {
        return $this->redirect()->toUrl($url);
    }
    
    public function getInfoSystem() {
        $manager        = $this->getServiceLocator()->get('ModuleManager');
        $modules        = $manager->getLoadedModules();
        $loadedModules          = array_keys($modules);
        $skipModulesList        = array('api');
        $skipActionsList        = array('notFoundAction', 'getMethodFromAction');
        $skipControllersList    = array('notice', 'nested', 'api');
        
        $arrAction = array();
        
        $wordFirst = new \ZendX\Functions\WordFirst();
        foreach ($loadedModules as $loadedModule) {
            $moduleClass = '\\' .$loadedModule . '\Module';
            $moduleObject = new $moduleClass;
            $config = $moduleObject->getConfig();
        
            $controllers = $config['controllers']['invokables'];
            foreach ($controllers as $key => $moduleClass) {
                $tmpArray = get_class_methods($moduleClass);
                $controllerActions = array();
                if(!empty($tmpArray)) {
                    foreach ($tmpArray as $action) {
                        if (substr($action, strlen($action)-6) === 'Action' && !in_array($action, $skipActionsList)) {
                            $controllerActions[] = substr($wordFirst->lowerFirstUpper($action), 0, -7);
                        }
                    }
                }
        
                $module     = $wordFirst->lowerFirstUpper($loadedModule);
                $controller = explode('\\Controller\\', $moduleClass);
                $controller = $wordFirst->lowerFirstUpper(substr($controller[1], 0, -10));
                $action     = $controllerActions;
        
                if (!in_array($module, $skipModulesList) && !in_array($controller, $skipControllersList)) {
                    $arrAction[$module][$controller] = $action;
                }
            }
    	}
        
        return $arrAction;
    }
    
    public function statusAction() {
        if($this->getRequest()->isXmlHttpRequest()) {
            $this->getTable()->changeStatus($this->_params, array('task' => 'change-status'));
        } else {
            $this->goRoute();
        }
    
        return $this->response;
    }
    
    public function deleteAction() {
        if($this->getRequest()->isPost()) {
            if(!empty($this->_params['data']['cid'])) {
                $result = $this->getTable()->deleteItem($this->_params, array('task' => 'delete-item'));
                $message = 'Xóa '. $result .' phần tử thành công';
                $this->flashMessenger()->addMessage($message);
            }
        }
    
        $this->goRoute();
    }
    
    public function orderingAction() {
        if($this->getRequest()->isPost()) {
            if(!empty($this->_params['data']['cid']) && !empty($this->_params['data']['ordering'])) {
                $result = $this->getTable()->changeOrdering($this->_params, array('task' => 'change-ordering'));
                $message = 'Sắp xếp '. $result .' phần tử thành công';
                $this->flashMessenger()->addMessage($message);
            }
        }
    
        $this->goRoute();
    }

    public function get_kiotviet_token($client_id, $secret_key)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://id.kiotviet.vn/connect/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "scopes=PublicApi.Access&grant_type=client_credentials&client_id=" . $client_id . "&client_secret=" . $secret_key,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            $response = json_decode($response, true);
            if (isset($response['access_token'])) {
                return $response['token_type'] . " " . $response['access_token'];
            }
            return false;
        }
    }

    public function kiotviet_call($retailer, $token, $api_endpoint, $query = array(), $method = 'GET')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://public.kiotapi.com" . $api_endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
            if (is_array($query)) {
                $query = json_encode($query);
            }
            curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        }
        $header = array(
            "authorization: " . $token,
            "cache-control: no-cache",
            "content-type: application/json",
            "retailer: " . $retailer
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return $response;
        }
    }

    public function postJson($json)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://webhook.site/3043df2f-05e7-4430-876a-28070f7ff8d3");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        $header = array(
            "cache-control: no-cache",
            "content-type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            return $response;
        }
    }

    public function addNew($array){
        foreach($array as $key => $value){
            $array[$key] = $this->changeText($array[$key]);
            if(count($value['children']) > 0){
                $array[$key]['children'] = $this->addNew($array[$key]['children']);
            }
        }
        return $array;
    }

    public function changeText($arr){
        if(isset($arr['parentId']) && $arr['hasChild']){
            $arr['categoryName'] = '-  '.$arr['categoryName'];
        }
        elseif (isset($arr['parentId']) && !$arr['hasChild']){
            $arr['categoryName'] = '- - - '.$arr['categoryName'];
        }
        return $arr;
    }

    public function getNameCat($array, &$result){
        foreach($array as $key => $value){
            $result[$value['categoryId']] = $value['categoryName'];
            if(count($value['children']) > 0){
                $this->getNameCat($array[$key]['children'], $result);
            }
        }
        return $result;
    }
}