<?php echo "<?php\n\n"; ?>

namespace MVC_light\Model;
use \MVC_light\Model as Model,
    \MVC_light\Service as Service;

class Model_<?php echo $name; ?> extends Model {

    function get_<?php echo $name; ?>() {

        return [
            'template' => '<?php echo $template; ?>'
        ];

    }

}