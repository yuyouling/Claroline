<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Claroline\CoreBundle\Library\Transfert\ConfigurationBuilders;

use Claroline\CoreBundle\Library\Transfert\Importer;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ToolsImporter extends Importer implements ConfigurationInterface
{
    public function  getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('tools');
        $this->addToolsSection($rootNode);

        return $treeBuilder;
    }

    public function addToolsSection($rootNode)
    {
        $rootNode
            ->prototype('array')
                ->children()
                    ->arrayNode('tool')
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('translation')->end()
                            ->variableNode('data')->end()
                            ->arrayNode('import')
                                ->children()
                                    ->scalarNode('path')->end()
                                ->end()
                            ->end()
                            ->arrayNode('roles')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('name')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Validate the workspace properties.
     *
     * @param array $data
     */
    public function validate(array $data)
    {
        $processor = new Processor();
        self::setData($data);
        $processor->processConfiguration($this, $data);

//        foreach ($data['tools'] as $tool) {
//            $importer = $this->getImporterByName($tool['name']);
//            $importer->validate($tool);
//        }
        //then validate each tools
    }

    private function validateTools($tool)
    {
        $toolImporter = null;
        $toolImporter = $this->getImporterByName($tool['name']);

        if (isset ($tool['tool']['config']) && $toolImporter) {
            $ds = DIRECTORY_SEPARATOR;
            $filepath = $this->getRootPath() . $ds . $tool['tool']['config'];
            //@todo error handling if path doesn't exists
            $tooldata =  Yaml::parse(file_get_contents($filepath));
            $toolImporter->validate($tooldata);
        }

        if (isset($tool['tool']['data']) && $toolImporter) {
            $tooldata = $tool['tool']['data'];
            $toolImporter->validate($tooldata);
        }
    }

    private static function setData($data)
    {
        self::$data = $data;
    }

    private static function getData()
    {
        return self::$data;
    }

    public function getName()
    {
        return 'tools_importer';
    }
}