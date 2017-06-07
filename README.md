MeatUp Bundle
============

The MeatUp Bundle is a Symfony bundle that provides a command for generating a CRUD controller based on a Doctrine Entity. It uses the Symfony Skeleton Application from inter-Punkt as a starting basis. The bundle will generate the following files for you:

* A FormType based on a Doctrine Entity and some of MeatUps annotations
* A Controller for the CRUD operations
* A view file for a tabular presentation of all records
* View files for creating and updating records

Requirements
============

* [Symfony 2.8.* Skeleton Application](https://github.com/interpunkt/ip-symfony-skeleton)
* [Symfony Framework Bundle](https://github.com/symfony/symfony)
* [Doctrine Annotations](http://docs.doctrine-project.org/projects/doctrine-common/en/latest/reference/annotations.html)
* [Symfony Filesystem](https://symfony.com/doc/current/components/filesystem.html)
* [VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle)

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require ip/meat-up
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Ip\MeatUp\MeatUpBundle(),
        );

        // ...
    }

    // ...
}
```

Usage
=====

For the usage documentation see:

[Resources/doc/index.md](Resources/doc/index.md)

License
=======

See the bundled [LICENSE](LICENSE) file.