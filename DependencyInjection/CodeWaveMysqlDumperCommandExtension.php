<?php

namespace CodeWave\MysqlDumperCommandBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CodeWaveMysqlDumperCommandExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('code_wave_mysql_dumper_command.date_template', $config['date_template']);
        $container->setParameter('code_wave_mysql_dumper_command.base_directory', $config['base_directory']);
        $container->setParameter('code_wave_mysql_dumper_command.compression_engine', $config['compression_engine']);

        if(isset($config['compression_level'])) {
          $container->setParameter('code_wave_mysql_dumper_command.compression_level',
            $config['compression_level']);
        }

        // Default values from manual pages
        if(! isset($config['compression_level'])) {
          if($config['compression_engine'] === 'bzip2') {
            $container->setParameter('code_wave_mysql_dumper_command.compression_level', "9");
          }

          if($config['compression_engine'] === 'gzip') {
            $container->setParameter('code_wave_mysql_dumper_command.compression_level', "6");
          }

          if($config['compression_engine'] === 'xz') {
            $container->setParameter('code_wave_mysql_dumper_command.compression_level', "6");
          }
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
