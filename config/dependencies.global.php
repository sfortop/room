<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */


use App\Action\Switcher;
use App\App;
use App\Entity\CorrectStorage;
use App\Gateway\CorrectStorageGateway;
use App\Gateway\PriceIntervalGateway;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Hydrator\ClassMethods;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Renderer\RendererInterface;
use Zend\View\Resolver\ResolverInterface;
use Zend\View\Resolver\TemplatePathStack;

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

// @todo create non-closure factories
return [
    'dependencies' => [
        'factories' => [
            //simple rendering part
            ResolverInterface::class => function (ContainerInterface $container) {
                $resolver = new TemplatePathStack();
                $resolver->addPath(dirname(__DIR__));
                return $resolver;
            },
            RendererInterface::class => function (ContainerInterface $container) {
                $renderer = new PhpRenderer();
                $renderer->setResolver($container->get(ResolverInterface::class));
                return $renderer;
            },

            // gateways
            PriceIntervalGateway::class => function (ContainerInterface $container) {
                //we can back to usage of generated hydrators after implementing some property naming strategy
                //due camelCase properties in our entities and underscored_properties at DB and htmls
//                $config = $container->get('config');
//                $hydratorClass = $config['hydrator']['classmap'][\App\Entity\PriceInterval::class];

                return new PriceIntervalGateway(
                    $container->get(\Zend\Db\Adapter\AdapterInterface::class),
                    new \Zend\Hydrator\ClassMethods(),
                    new \App\Entity\PriceInterval()
                );

            },
            CorrectStorageGateway::class => function(ContainerInterface $container) {
                return new CorrectStorageGateway(
                    $container->get(AdapterInterface::class),
                    new ClassMethods(),
                    new CorrectStorage()
                );
            },
            //actions api (application/json)
            \App\Action\Api\CreateOriginal::class => function (ContainerInterface $container) {
                //basically we should use data-mapper pattern here instead of hydrator
                //but for small application such decision looks like over-engineering
                //same solution will be used for other Api\Actions

                //we can back to usage of generated hydrators after implementing some property naming strategy
                //due camelCase properties in our entities and underscored_properties at DB and htmls
//                $config = $container->get('config');
//                $hydratorClass = $config['hydrator']['classmap'][\App\Entity\PriceInterval::class];

                return new \App\Action\Api\CreateOriginal(new \Zend\Hydrator\ClassMethods(), $container->get(PriceIntervalGateway::class));
            },

            //@todo extract initialization of routes to separate config file
            App::ROUTE_DISPATCHER => function () {
                return FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $routeCollector) {

                    // routes names has left mostly similar to path of templates
                    // if required it can be replaced by human readable url or whatever
                    $routeCollector->addRoute('GET', '/', Switcher::class);
                    $routeCollector->addRoute('GET', '/create/fail.phtml', \App\Action\CRUD\Create\Fail::class);
                    $routeCollector->addRoute('GET', '/create/success.phtml', \App\Action\CRUD\Create\Success::class);
                    $routeCollector->addRoute('GET', '/edit/fail.phtml', \App\Action\CRUD\Update\Fail::class);
                    $routeCollector->addRoute('GET', '/edit/success.phtml', \App\Action\CRUD\Update\Success::class);

                    $routeCollector->addRoute('GET', '/price-interval/create/form.phtml', \App\Action\CRUD\Create\FormOriginal::class);
                    $routeCollector->addRoute('POST', '/price-interval/create/', \App\Action\CRUD\Create\HandleOriginal::class);

                    $routeCollector->addRoute('GET', '/price-interval/read/list.phtml', \App\Action\CRUD\Read\Original::class);

                    $routeCollector->addRoute('GET', '/correct-storage/create/form.phtml', \App\Action\CRUD\Create\FormCorrect::class);
                    $routeCollector->addRoute('POST', '/correct-storage/create/', \App\Action\CRUD\Create\HandleCorrect::class);
                    $routeCollector->addRoute('GET', '/correct-storage/edit/{price-interval}', \App\Action\CRUD\Update\FormCorrect::class);
                    $routeCollector->addRoute('POST', '/correct-storage/edit/', \App\Action\CRUD\Update\HandleCorrect::class);
                    $routeCollector->addRoute('GET', '/correct-storage/read/list.phtml', \App\Action\CRUD\Read\Correct::class);
                    $routeCollector->addRoute('DELETE', '/correct-storage/delete/{id}', \App\Action\CRUD\Delete\HandleCorrect::class);

                    $routeCollector->addRoute('POST', '/api/correct-storage/create/', \App\Action\Api\CreateCorrect::class);
                    $routeCollector->addRoute('DELETE', '/api/correct-storage/delete/{id}', \App\Action\Api\DeleteCorrect::class);
                    $routeCollector->addRoute('PUT', '/api/correct-storage/update/', \App\Action\Api\UpdateCorrect::class);
                });
            },
        ]
    ]
];