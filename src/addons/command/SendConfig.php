<?php
// -----------------------------------------------------------------------
// |@Author       : Jarmin <jarmin@ladmin.cn>
// |@----------------------------------------------------------------------
// |@Date         : 2021-08-01 11:23:21
// |@----------------------------------------------------------------------
// |@LastEditTime : 2021-08-05 12:30:34
// |@----------------------------------------------------------------------
// |@LastEditors  : Jarmin <jarmin@ladmin.cn>
// |@----------------------------------------------------------------------
// |@Description  : 
// |@----------------------------------------------------------------------
// |@FilePath     : SendConfig.php
// |@----------------------------------------------------------------------
// |@Copyright (c) 2021 http://www.ladmin.cn   All rights reserved. 
// -----------------------------------------------------------------------
namespace think\addons\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Env;

class SendConfig extends Command
{

    public function configure()
    {
        $this->setName('addons:config')->setDescription('send config to config folder');
    }
    
    public function execute(Input $input, Output $output)
    {
        //获取默认配置文件
        $content = file_get_contents(dirname(dirname(__DIR__)) . '/Config.php');
        $configPath = config_path() . '/';
        $configFile = $configPath . 'addons.php';
        //判断目录是否存在
        if (!file_exists($configPath)) {
            mkdir($configPath, 0755, true);
        }
        //判断文件是否存在
        if (is_file($configFile)) {
            throw new \InvalidArgumentException(sprintf('The config file "%s" already exists', $configFile));
        }
        if (false === file_put_contents($configFile, $content)) {
            throw new \RuntimeException(sprintf('The config file "%s" could not be written to "%s"', $configFile,$configPath));
        }
        $output->writeln('create addons config ok');
    }

}