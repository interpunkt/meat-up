CKEditor
========

Sometimes a simple text field is not enough. If you want to give your users the possibility to enter bold or cursive text or many more formattings, [IvoryCKEditorBundle](http://symfony.com/doc/master/bundles/IvoryCKEditorBundle/index.html) is exactly what you need. 

To be able to use it with the MeatUp command, you have first to [install](http://symfony.com/doc/master/bundles/IvoryCKEditorBundle/installation.html) it and afterwards to define *at least one* [configuration](http://symfony.com/doc/master/bundles/IvoryCKEditorBundle/usage/config.html). Once that is done, you are ready to use MeatUps `CKEditor` annotation.
 

Attribute definitions
---------------------

| Name | Type |Description | Required |
| --- | --- | --- | --- |
| config | String | The name of the CKEditor configuration | no |

Usage examples
--------------

By default the `CKEditor` annotation uses the [default configuration](http://symfony.com/doc/master/bundles/IvoryCKEditorBundle/usage/config.html#define-default-configuration) of the [IvoryCKEditorBundle](http://symfony.com/doc/master/bundles/IvoryCKEditorBundle/index.html). So to use this one you don't need the `config` attribute:

```php
...
use Ip\MeatUp\Mapping as MU;
...
    /**
     * @ORM\Column(type="text")
     * @MU\CKEditor
     */
    private $content; 
```

If you want to use any other configuration, than the default one. You simply have to define it in the `config` attribute. For a configuration with the name `notDefault`, you have to write it like this:

```php
...
use Ip\MeatUp\Mapping as MU;
...
    /**
     * @ORM\Column(type="text")
     * @MU\CKEditor(config="notDefault")
     */
    private $content; 
```