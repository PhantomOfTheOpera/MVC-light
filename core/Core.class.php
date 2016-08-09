<?php
/**
 * Created by PhpStorm.
 * User: killer
 * Date: 05/08/16
 * Time: 20:37
 */

class Core
{
    /**
     * @var array - here goes SERVER URI after explode
     */
    public $request = [];

    /**
     * @var array - here stores info about Controller. On creation, is overwritten by Controller object
     */
    public $controller = ['name' => 'Main'];

    /**
     * @var array - here is stored info about action
     */
    public $action = ['name' => 'index'];

    /**
     * @var array - here stores info about Model. On creation, is overwritten by Model object
     */
    public $model = [];

    /**
     * @var array - here stores info about DB. On creation, is overwritten by DB object
     */
    public $db = [];

    /**
     * @var Twig_Environment object
     */
    public $twig;


    function __construct() {
        session_start();
        $this->form_names();
        $this->form_pathes();
        try {
            $this->initialize();
        } catch(Exception $e) {
            $this->error404();
        }
        $this->run();
    }

    /**
     * initializes Core::$twig
     * @return Twig_Environment
     */
    private function twig_start() {
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(ROOT.TEMPLATES_DIR);
        $twig = new Twig_Environment($loader,
            array(
                'cache' => TWIG_CACHE_DIR
            )
        );
        return $twig;
    }

    /**
     * initializes Core::$request, 'name' in  Core::$controller, Core::$db,
     *      Core::$model, Core::$action
     */
    private function form_names() {
        /*
        Important: we store whole "after explode" array in Core::$request,
            but here are used only two items
        */
        $this->request = explode('/', $_SERVER['REQUEST_URI']);
        if ( !empty($this->request[1]) )
            $this->controller['name'] = $this->request[1];
        if ( !empty($this->request[2]) )
            $this->action['name'] = $this->request[2];
        $this->model['name'] = 'Model_'.$this->controller['name'];
        $this->db['name'] = 'DB_'.$this->controller['name'];
        $this->controller['name'] = 'Controller_'.$this->controller['name'];
        $this->action['name'] = 'action_'.$this->action['name'];
    }

    /**
     * initializes 'file' and 'path' Core::$controller, Core::$db,
     *      Core::$model
     */
    private function form_pathes() {
        /* firstly generate file names */
        $this->model['file'] = strtolower($this->model['name']).'.php';
        $this->db['file'] = strtolower($this->db['name']).'.php';
        $this->controller['file'] = strtolower($this->controller['name']).'.php';
    }

    /**
     * initializes Core::$controller, Core::$db,
     *      Core::$model, Core::$twig
     * @throws Exception - 404 case exception
     */
    private function initialize() {
        if (file_exists(ROOT.CONTROLLERS_DIR.$this->controller['file']))
            include ROOT.CONTROLLERS_DIR.$this->controller['file'];
        else
            throw new Exception('404');
        $this->twig = $this->twig_start();
        if (file_exists(ROOT.DATABASE_DIR.$this->db['path'])) {
            include ROOT.DATABASE_DIR.$this->db['path'];
            $this->db = new $this->db['name'];
        }
        if (file_exists(ROOT.MODELS_DIR.$this->model['path'])) {
            include ROOT.MODELS_DIR.$this->model['path'];
            $this->model = new $this->model['name']($this->db);
        }
        /* we pass to controller Core object */
        $this->controller = new $this->controller['name']($this);
    }

    /**
     * starts controller's method
     */
    private function run() {
        $action = $this->action['name'];
        if(method_exists($this->controller, $action))
            $this->controller->$action();
        $this->controller->action_index();
    }

    /**
     * 404
     */
    public function error404() {
        /* no controller was included before this case */
        include ROOT.CONTROLLERS_DIR."controller_404.php";
        /* same for model */
        $this->model = new Model();
        $controller = new Controller_404($this);
        $controller->action_index();
        exit(0);
    }

    function __destruct() {
        session_write_close();
    }
}

$core = new Core();