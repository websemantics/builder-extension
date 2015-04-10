#PyroEntity

An Entity is a representation of an Object Type which may correspond with a Stream,.. for example, a Person, a Company or an Animal can all be generated as Pyro Entities

#{entity_name} Entity

{description}

#Install

(1) Move the Http folder to your module's corresponding Http folder.

(2) Add the following lines to your Module's ServiceProvider: {module_name}ModuleServiceProvider.


    /**
     * The class bindings.
     *
     * @var array
     */
    protected $bindings = [
        '{vendor_name}\{module_name}Module\{entity_name}\{entity_name}Model' => '{vendor_name}\{module_name}Module\{entity_name}\{entity_name}Model'
    ];

    /**
     * The singleton bindings.
     *
     * @var array
     */
    protected $singletons = [
        '{vendor_name}\{module_name}Module\{entity_name}\Contract\{entity_name}RepositoryInterface' => '{vendor_name}\{module_name}Module\{entity_name}\{entity_name}Repository',
    ];


    /**
     * The addon routes.
     *
     * @var array
     */
    protected $routes = [
        'admin/{module_name_lower}/{entity_name_lower_plural}'            => '{vendor_name}\{module_name}Module\Http\Controller\Admin\{entity_name_plural}Controller@index',
        'admin/{module_name_lower}/{entity_name_lower_plural}/create'     => '{vendor_name}\{module_name}Module\Http\Controller\Admin\{entity_name_plural}Controller@create',
        'admin/{module_name_lower}/{entity_name_lower_plural}/edit/{id}'  => '{vendor_name}\{module_name}Module\Http\Controller\Admin\{entity_name_plural}Controller@edit',
    ];

(3) Add the following code to the Module's sections in {module_name}Module.php

    /**
     * The module sections.
     *
     * @var array
     */
    protected $sections = [
        '{entity_name_lower_plural}'  => [
            'buttons' => [
                'create'
            ]
        ]
    ];

(4) Update your language file, at for example, 'resources/lang/en/addon.php'

    return [
        'section'     => [
            '{entity_name_lower_plural}' => '{entity_name_plural}'
        ]
    ];

(5) Finally, you can relocate the Plugin and the Seeder classes anywhere in your application.

{generatedBy}