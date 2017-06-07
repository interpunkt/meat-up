Basic Usage
===========

This guide will show you who to generate a CRUD Controller from a simple Doctrine entity.

Here is a summary of what you will have to do:

* [Create a simple Doctrine entity](#step-1-create-a-simple-doctrine-entity)
* [Execute the MeatUp command](#step-2-execute-the-meatup-command)
* [Add OnIndex annotation](#step-3-add-onindex-annotation)

Step 1: Create a simple Doctrine entity
----------------------------------------

```php
<?php
// File: src/DevPro/adminBundle/Entity/Meatup.php

namespace DevPro\adminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Meatup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $title;
    
    /**
     * @ORM\Column(type="text")
     */
    private $content;
    
    ...
}
```

The example above is a very basic Doctrine entity. If you don't understand everything in there, you can find more information in the [Doctrine documentation](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/index.html).

Now you can use a Doctrine command to create the getter and setter methods for you:

```bash
$ php app/console doctrine:generate:entities DevProadminBundle:Meatup
```

Now the entity class is complete and you can create the table in the database:

```bash
$ php app/console doctrine:schema:update --force
```

Step 2: Execute the MeatUp command
----------------------------------

Now everything is ready for the MeatUp command to generate your CRUD controller with the the fully qualified class name as argument:

```bash
$ php app/console ip:meat-up "DevPro\adminBundle\Entity\Meatup"
```

After the command has successfully finished you can start your web server and check out the controller. If you don't know how you can start the built-in Web-Server have a look at the [Symfony documentation](http://symfony.com/doc/current/setup/built_in_web_server.html).

If your Web-server is running as localhost:8000 just enter the following URL in your browser:

```bash
http://localhost:8000/admin/meatup
```

You can now go on and create a new record by clicking on the button with the label 'Neuer Eintrag'. After creating the new record you will be redirected to the overview page. There you will see that there is a new record and that you can edit it with a click on the button 'Bearbeiten', but there is no column yet to indicate which record it is.

Step 3: Add OnIndex annotation
-------------------------------

Because there is no way for the MeatUp command to guess which properties of your entities you want to show on the overview page, you need to add the OnIndex annotation to tell the bundle that this property should be on it. Luckily we only need to add two lines to the entity above to do that.

First we need to import the MeatUp namespace and add an alias to it:

```php
...
use Doctrine\ORM\Mapping as ORM;
use Ip\MeatUp\Mapping as MU;
...
```

Now we add the OnIndex annotation to the title property:

```php
...
    /**
    * @ORM\Column(type="string")
    * @MU\OnIndexPage
    */
    private $title;
...
```

That's it! Now we execute the MeatUp command once more and after reloading the website the title will be shown on the overview page with the record we created before:

```bash
$ php app/console ip:meat-up "DevPro\adminBundle\Entity\Meatup"
```

You now know already how to use the MeatUp command to create simple CRUD controllers. For more advanced usages please refer to the [docs](index.md).