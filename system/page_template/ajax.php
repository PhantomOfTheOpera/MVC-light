<?php echo "<?php\n\n"; ?>

namespace MVC_light;

class Ajax_<?php echo $name; ?> extends Ajax {

    function action() {
        $this->message['state'] = 'success';
        $this->code = 200;
    }

}