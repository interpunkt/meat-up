OnIndexPage
===========

If you have read the [Basic Usage documentation](https://github.com/interpunkt/meat-up/blob/master/Resources/doc/basic_usage.md) you already know about this annotation. If not, in short, this annotation is needed to tell the MeatUp command which properties of your entity should be shown in the tabular view of the index page. 

Attribute definitions
---------------------

| Name | Type |Description | Required |
| --- | --- | --- | --- |
| label | String | The label which will be rendered on the index page | no |
| filter | String | The name of the Twig filter which should be used | no |
| filterParameters | Array | A list of parameters for the Twig filter | depends on the Twig filter |

Usage examples
--------------

The most basic example for it looks like this:

```php
...
use Ip\MeatUp\Mapping as MU;
...
    /**
    * @ORM\Column(type="string")
    * @MU\OnIndexPage
    */
    private $title;
...
```

After executing the MeatUp command you will see a column with the label *Title*. The label is, by default, the name of the property with the **first** letter capitalized.

Setting a custom label
----------------------

In some occasions the default label will not be a good fit. Like in the following example:

```php
...
use Ip\MeatUp\Mapping as MU;
...
    /**
    * @ORM\Column(type="string")
    * @MU\OnIndexPage
    */
    private $notAGoodLabel;
...
```

The resulting label would be `NotAGoodLabel`, which is not easy to read. 

To change this you have to overwrite the default label you have to set the `label` attribute of the annotation:

```php
...
use Ip\MeatUp\Mapping as MU;
...
    /**
    * @ORM\Column(type="string")
    * @MU\OnIndexPage(label="A much better label")
    */
    private $notAGoodLabel;
...
```

As one would expect this will result in the label `A much better label`. The `label` attribute is a string and therefor can be set to be anything.

Applying Twig filters to a property
-----------------------------------

Not all of the properties of an entity can be displayed just like that by Twig. One of this examples is the following property:

```php
...
use Ip\MeatUp\Mapping as MU;
...
    /**
    * @ORM\Column(type="datetime")
    * @MU\OnIndexPage
    */
    private $date;
...
```

The MeatUp command will generate your CRUD controller without any error message. But once you have at least one record and you go to your index page you will get the following error message:

```
An exception has been thrown during the rendering of a template ("Catchable Fatal Error: Object of class DateTime could not be converted to string").
```

You get this error message, because Twig does not know how to display a DateTime object. To be able to do that you need to apply the date filter. To apply filters to the properties you have to use the `filter` and `filterParameters` attributes of the annotation. To show for example a DateTime object in the German date format you would need to do the following:

```php
...
use Ip\MeatUp\Mapping as MU;
...
    /**
    * @ORM\Column(type="datetime")
    * @MU\OnIndexPage(filter="date", filterParameters={"d.m.Y"})
    */
    private $date;
...
```

After executing the MeatUp command again, your index page will be rendered correctly. 

Valid values for the `filter` parameter are all the [Twig filters](https://twig.sensiolabs.org/doc/2.x/).

The `filterParameters` attribute has to be array in which you have to specify all the parameters the specific Twig filter needs. So if filterParameter is mandatory or not has to be looked up in the documentation of the filter that you want to apply.



