<?php return array(
    'root' => array(
        'name' => 'bus-management-system/arangodb-migration',
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'reference' => null,
        'type' => 'project',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'bus-management-system/arangodb-migration' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'reference' => null,
            'type' => 'project',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'triagens/arangodb' => array(
            'pretty_version' => 'v3.8.0',
            'version' => '3.8.0.0',
            'reference' => '5104c4e2803d8b7fab97a0c80a3abe3f3ff3253e',
            'type' => 'library',
            'install_path' => __DIR__ . '/../triagens/arangodb',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
