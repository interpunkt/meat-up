Installation
============

### Require

[Symfony 2.8.* Skeleton Application](https://github.com/interpunkt/ip-symfony-skeleton)

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require daniellehrner/meat-up
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

            new DL\MeatUp\MeatUpBundle(),
        );

        // ...
    }

    // ...
}
```

Step 3: Usage
-------------------------

To create the FormType, Controller and the views execute the following console command with the fully qualified class name of the entity:

```console
$ php app/console dl:meat-up "DevPro\adminBundle\Entity\example"
```