<?php
echo "<?php\n\n";

echo "namespace MVC_light\Model;\n\n";
echo "
use \MVC_light\Model as Model,\n
    \t\MVC_light\Service as Service;\n";

echo "class Model_$name extends Model {\n";

echo "\tfunction get_$name() {\n";

echo "\t\treturn [\n";

echo "\t\t\t'template' => '$template'\n";

echo "\t\t];\n";

echo "\t}\n";

echo "}\n";