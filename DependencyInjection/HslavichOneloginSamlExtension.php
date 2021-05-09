<?php

namespace Hslavich\OneloginSamlBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class HslavichOneloginSamlExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.php');

        $container->setParameter('hslavich_onelogin_saml.settings', $config);
        $this->loadIdentityProviders($config, $container);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function loadIdentityProviders(array $config, ContainerBuilder $container): void
    {
        foreach($config['idps'] as $id => $idpConfig) {
            $clientId = sprintf('onelogin_auth.%s', $id);
            $clientDef = new ChildDefinition(\OneLogin\Saml2\Auth::class);
            $authConfig = $config;
            unset($authConfig['idps']);
            $authConfig['idp'] = $idpConfig;
            $clientDef->replaceArgument(0, $authConfig);
            $clientDef->addTag(\OneLogin\Saml2\Auth::class);
            $container->setDefinition($clientId, $clientDef);
        }
    }
}
