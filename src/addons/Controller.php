<?php
// ------------------------------------------------------------------------
// |@Author       : Jarmin <jarmin@ladmin.cn>
// |@----------------------------------------------------------------------
// |@Date         : 2021-08-06 19:53:39
// |@----------------------------------------------------------------------
// |@LastEditTime : 2021-08-09 09:53:13
// |@----------------------------------------------------------------------
// |@LastEditors  : Jarmin <jarmin@ladmin.cn>
// |@----------------------------------------------------------------------
// |@Description  : 
// |@----------------------------------------------------------------------
// |@FilePath     : Controller.php
// |@----------------------------------------------------------------------
// |@Copyright (c) 2021 http://www.ladmin.cn   All rights reserved. 
// ------------------------------------------------------------------------
declare(strict_types=1);

namespace think\addons;

use think\facade\View;
use think\helper\Str;

class Controller extends \think\admin\Controller
{
    /**
     * 当前应用类库命名空间
     * @var string
     */
    protected $namespace = 'addons';

    /**
     * 控制器初始化
     */
    protected function initialize()
    {
        $addons_view_path = $this->app->addons->getAddonsPath() . $this->getName() . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR;
        View::config(['view_path'=> $addons_view_path]);
    }

    /**
     * 获取插件标识
     * @return mixed|null
     */
    protected function getName()
    {
        $class = get_class($this);
        list(, $name, ) = explode('\\', $class);
        $this->request->addon = $name;
        return $name;
    }
    
    /**
     * 解析应用类的类名
     * @access public
     * @param string $layer 层名 controller model ...
     * @param string $name  类名
     * @return string
     */
    public function parseClass(string $layer, string $name): string
    {
        $name  = str_replace(['/', '.'], '\\', $name);
        $array = explode('\\', $name);
        $class = Str::studly(array_pop($array));
        $path  = $array ? implode('\\', $array) . '\\' : '';
        return $this->namespace . '\\'. $this->getName() .'\\' . $layer . '\\' . $path . $class;
    }

    /**
     * 重写获取器 模型层实例获取
     */
    public function __get($name)
    {
        if (str_prefix($name, 'model')) {
            $class = $this->parseClass('model', str_replace('model','', $name));
        } else {
            throw new \think\exception\HttpException(404, '模型层需引用前缀:model');
        }
        return invoke($class);
    }
}