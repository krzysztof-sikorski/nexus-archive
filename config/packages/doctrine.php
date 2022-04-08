<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;
use Symfony\Config\FrameworkConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (
    DoctrineConfig $doctrineConfig,
    FrameworkConfig $frameworkConfig,
    ContainerConfigurator $containerConfigurator
) {
    $defaultConnectionName = 'default';
    $cachePoolNameDoctrineSystemCache = 'doctrine.system_cache_pool';
    $cachePoolNameDoctrineResultCache = 'doctrine.result_cache_pool';

    $dbalConfig = $doctrineConfig->dbal();
    $dbalConfig->defaultConnection(value: $defaultConnectionName);

    $defaultConnectionConfig = $dbalConfig->connection(name: $defaultConnectionName);
    $defaultConnectionConfig->url(value: env(name: 'DATABASE_URL')->resolve());

    $ormConfig = $doctrineConfig->orm();
    $ormConfig->autoGenerateProxyClasses(value: true);

    $entityManagerConfig = $ormConfig->entityManager(name: $defaultConnectionName);
    $entityManagerConfig->namingStrategy(value: 'doctrine.orm.naming_strategy.underscore_number_aware');
    $entityManagerConfig->autoMapping(value: true);

    $mappingConfig = $entityManagerConfig->mapping(name: 'App');
    $mappingConfig->dir(value: '%kernel.project_dir%/src/Entity');
    $mappingConfig->prefix('App\Entity');
    $mappingConfig->alias(value: 'App');
    $mappingConfig->isBundle(value: false);

    if ('prod' === $containerConfigurator->env()) {
        $ormConfig->autoGenerateProxyClasses(value: false);

        $queryCacheDriverConfig = $entityManagerConfig->queryCacheDriver();
        $queryCacheDriverConfig->type(value: 'pool');
        $queryCacheDriverConfig->pool($cachePoolNameDoctrineSystemCache);

        $resultCacheDriverConfig = $entityManagerConfig->resultCacheDriver();
        $resultCacheDriverConfig->type(value: 'pool');
        $resultCacheDriverConfig->pool($cachePoolNameDoctrineResultCache);

        $cacheConfig = $frameworkConfig->cache();
        $cacheConfig->pool(name: $cachePoolNameDoctrineResultCache)->adapters(['cache.app']);
        $cacheConfig->pool(name: $cachePoolNameDoctrineSystemCache)->adapters(['cache.system']);
    }

    if ('test' === $containerConfigurator->env()) {
        // "TEST_TOKEN" is typically set by ParaTest
        $defaultConnectionConfig->dbnameSuffix('_test' . env(name: 'TEST_TOKEN')->default(''));
    }
};
