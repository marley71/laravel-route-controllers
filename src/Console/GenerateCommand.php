<?php namespace Marley71\RouteControllers\Console;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Config;

class GenerateCommand extends Command
{
    protected $types = ['web','api','string'];
    protected $prefixes = ['get','post','put','delete','any','patch','options'];
    protected $type = null;
    protected $code = "";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route-controllers:generate 
                            {type : The type route generate web or api or string (with string print output code)} 
                            {--path= : path controller}
                            {--class= : class controller}';


    protected $description = "generate route from controllers and add in routes.web.php o routes.api.php";


    /*
     * Se il file di ambiente esiste già viene semplicemente sovrascirtto con i nuovi valori passati dal comando (update)
     */
    public function handle()
    {
        $this->type = $this->argument('type');
        $path = $this->option('path')?"Http/Controllers/".$this->option('path'):"Http/Controllers";
        $cls = $this->option('class');

        $filesPath = app_path($path);
        $classes = $this->_getControllerClasses($filesPath);
        //print_r($classes);

        if ($cls) {
            $classes = [$cls];
        }
        foreach ($classes as $controller) {
            $className = str_replace('.php','',$controller);
            $pathNameSpace = str_replace("/","\\",$path);
            $cName = '\\App\\' . $pathNameSpace . '\\'.studly_case($className);
            $methods =$this->getMethods($cName);
            $this->dumpRoute($cName,$methods);
        }

        return $this->code;

    }

    protected function getMethods($cName) {

        $methods = [];
        foreach ($this->prefixes as $p) {
            $methods[$p] = [];
        }


        $cObj = new $cName;


        $f = new \ReflectionClass($cName);
        $methods = array();
        foreach ($f->getMethods() as $m) {
            //echo $m->class . "\n";
            //echo "strip " . stripslashes($cName) . " con " . stripslashes($m->class) . "\n";
            if (stripslashes($cName) == stripslashes($m->class)) {
                //$methods[] = $m->name;
                $r = new \ReflectionMethod($cName,$m->name);

                foreach ($this->prefixes as $pre) {
                    if (strpos($m->name, $pre) === 0) {
                        $methods[$pre][] = [$m->name,$r->getParameters()];
                    }
                }

            }
        }
        return $methods;
    }

    protected function dumpRoute($className,$methods) {

        if ($this->type == 'string') {
            $contents = "";
        } else {
            $fileName = base_path($this->type=='web'?"routes/web.php":"routes/api.php");
            $contents = file_get_contents($fileName);
        }

        $tagBegin = "#Begin$className";
        $tagEnd = "#End$className";

        $tagBeginPos = strpos($contents,$tagBegin);
        $tagEndPos = strpos($contents,$tagEnd);
        if ( ($tagBeginPos === FALSE && $tagEndPos === FALSE) ||
            ($tagBeginPos !== FALSE && $tagEndPos !== FALSE) ) {
            $code = "$tagBegin\n";
            foreach ($methods as $prefix => $names) {
                foreach ($names as $name) {
                    //echo "$prefix name $name .\n";
                    //$route = kebab_case(substr($name,strlen($prefix)));
                    $route = $this->_getRouteString($name,$prefix);
                    $code .= "Route::$prefix(\"$route\",\"$className@" . $name[0] . "\");\n";
                }

            }

            $code .= $tagEnd."\n";

            if ($tagBeginPos === FALSE && $tagEndPos === FALSE) {
                $contents .= "\n" . $code;
            } else {
                if ($tagEndPos < $tagBeginPos)
                    throw new \Exception('Invalid tags found ' . $className);

                $contents = substr($contents,0,$tagBeginPos) . $code . substr($contents,$tagEndPos+strlen($tagEnd));
            }
            $this->code .= "\n" . $code . "\n";
            if ($this->type=="string")
                $this->info($code);
            else
                file_put_contents($fileName,$contents);

        } else {
            throw new \Exception('Invalid tags found ' . $className);
        }
    }

    private function _getRouteString($methodInfo,$prefix) {
        $route = kebab_case(substr($methodInfo[0],strlen($prefix)));
        foreach ($methodInfo[1] as $param) {
            $name = $param->getName();
            $route .= '/{'.$name;
            $route .= $param->isOptional()?'?}':'}';
        }
        return $route;
    }

    private function _getControllerClasses($path) {
        $controllers = [];
        foreach (new \DirectoryIterator($path) as $fileInfo) {
            if($fileInfo->isDot()) continue;
            $ext = strtolower($fileInfo->getExtension());
            if (in_array($ext, ['php']))
                $controllers[] = $fileInfo->getBasename();
            //echo $fileInfo->getFilename() . "\n";
        }
        return $controllers;
    }
}
