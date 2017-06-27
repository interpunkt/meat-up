Hidden
======

Sometimes not all your properties should be visible to your users. Some of them may only be used by you to store same extra information. For these case the `Hidden` annoation can be used. 

Attribute definitions
---------------------

This annotation doesn't have any atrribute.

Usage examples
--------------

Let's say, that you have an entity with a property that will later be set not by the user but by one of your JavaScript funtions:

```php
...
    /**
     * @ORM\Column(type="string")
     */
    private $willBeSetByJavaScript;
...
```

Just like this the generated form would show this property and all the content that your JavaScript function would write into it. To avoid that tell the MeatUp commant to hide this field:

```php
...
use Ip\MeatUp\Mapping as MU;
...
    /**
     * @ORM\Column(type="string")
     * @MU\Hidden
     */
    private $willBeSetByJavaScript;
...
```

After executing the MeatUp command this field will be part of your form, but won't be visible by the browser.