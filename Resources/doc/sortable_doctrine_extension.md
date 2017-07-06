Sortable entities with the StofDoctrineExtensionsBundle
======================================================

For handling the sorting of the records of your entities the MeatUp command relies on the [StofDoctrineExtensionsBundle](http://symfony.com/doc/current/bundles/StofDoctrineExtensionsBundle/index.html).

On this page you find the documentation on how the MeatUp command is using this bundle.

Installation
-----------

First install the bundle with the following composer command:

```shell
$ composer require stof/doctrine-extensions-bundle
```

After that enable the bundle:

```php
// app/AppKernel.php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        );

        // ...
    }

    // ...
}
```

Finally enable the sortable extension:

```yml
# app/config/config.yml

stof_doctrine_extensions:
    default_locale: "%locale%"
    orm:
        default:
            sortable: true
```

`default` refers to your Doctrine entity manager. If you have more than one and you want to include the sortable extension in those entity managers as well, then you have include their names as well in the config file.

How to make an entity sortable
-----------------------------

In order to make your entity sortable you have to register the [SortableRepository](https://github.com/Atlantic18/DoctrineExtensions/blob/master/lib/Gedmo/Sortable/Entity/Repository/SortableRepository.php) first:
 
```php
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Gedmo\Sortable\Entity\Repository\SortableRepository")
 */
class meatup
{
// ...
```

The next step is to add the `SortablePosition` annotation to **one** of the properties of your entity:


```php
/**
     * @Gedmo\SortablePosition()
     * @ORM\Column(type="integer")
     */
    private $position;
```

That's it! Now you are already set to generate your sortable entity. 

When an entity is sortable the MeatUp command will add the `movePositionUpAction` and the `movePositionDownAction` functions to your controller. By calling these actions the entity will be moved up or down in the sort order. 

Additionally **two extra buttons** for moving the entities up or down will be generated next to the `edit` button in the index twig file. 
