ManyToOneChoice
===============

By default a [EntityType](http://symfony.com/doc/current/reference/forms/types/entity.html) field is rendered as a dropdown menu with it's items display by calling the `__toString()` method. With the ManyToOneChoice annotation you can change this to any number of properties.

Attribute definitions
---------------------

| Name | Type |Description | Required |
| --- | --- | --- | --- |
| labels | Array | A list of the properties that should be displayed | yes |

Usage examples
--------------

Let's say that you have an entity `Person` which has the first and the last name of a person:

```php
...
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     */
    private $firstName;

    /**
     * @ORM\Column(type="string")
     */
    protected $lastName;
...
```

To add this entity as a ManyToOne relation to another entity and show the first names in the dropdown menu, you would need to do this:

```php
...
use Ip\MeatUp\Mapping as MU;
...
    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @MU\ManyToOneChoice(labels={"firstName"})
     */
    private $person;

...
```
If the first name is not enough and you also want to show last name, just extend the array:

```php
...
use Ip\MeatUp\Mapping as MU;
...
    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @MU\ManyToOneChoice(labels={"firstName", "lastName"})
     */
    private $person;

...
```

The properties will be shown with **a space character** in between them. 

If you want to change the order of the labels as well, please refer to the [ManyToOneOrderBy annotation documentation](many_to_one_order_by.md).