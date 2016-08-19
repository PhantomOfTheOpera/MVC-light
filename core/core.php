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
            $this->run();
        } catch(Exception $e) {
            $this->error404();
        }
    }

    function __destruct() {
        session_write_close();
    }


    /**
     * initializes Core::$twig
     * @return Twig_Environment
     */
    private function twig_start() {
        // TODO: pass this to composer
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem(__DIR__.'/../application/views');
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
            but here are used only two first items
        */
        $this->request = explode('/', $_SERVER['REQUEST_URI']);
        if ( !empty($this->request[1]) ) {
            $this->controller['name'] = $this->request[1];
        }
        if ( !empty($this->request[2]) ) {
            $this->action['name'] = $this->request[2];
        }
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
        /* then generate pathes */
        $this->model['path'] = 'application/models/'.$this->model['file'];
        $this->db['path'] = 'application/controllers/database/'.$this->db['file'];
        $this->controller['path'] = "application/controllers/".$this->controller['file'];
    }

    /**
     * initializes Core::$controller, Core::$db,
     *      Core::$model, Core::$twig
     * @throws Exception - 404 case exception
     */
    private function initialize() {

        $this->twig = $this->twig_start();

        if (file_exists($this->db['path'])) {
            include $this->db['path'];
            $this->db = new $this->db['name'];
        }

        if (file_exists($this->model['path'])) {
            include $this->model['path'];
            $this->model = new $this->model['name']($this->db);
        }

        if (file_exists($this->controller['path']))
            include $this->controller['path'];
        else
            throw new Exception('404');
        /* we pass to controller Core object */
        $this->controller = new $this->controller['name']($this);
    }

    /**
     * starts controller's method or throws Exception
     * @throws Exception
     */
    private function run() {
        // that's action name received from url
        $action = $this->action['name'];
        // if it exists in controller object - call it
        if(method_exists($this->controller, $action))
            $this->controller->$action();
        // if not - call action_index() - maybe it will rule action on it's own
        else if ($this->controller->action_index() === false)
            throw new Exception('404');
        // if action_index() returns false - it means that it doesn't do anything itself. That's 404
    }

    /**
     * 404
     */
    public function error404() {
        /*
         * for some reasons, clear previous controller and model
         */
        $this->request[1] = '404';
        $this->controller = NULL;
        $this->model =  NULL;
        if (!class_exists('Controller'))
            require_once ROOT."controller.php";
        /* same for model */
        $this->model = new Model();
        $controller = new Controller($this);
        $controller->action_index();
        exit(0);
    }


}

$core = new Core();