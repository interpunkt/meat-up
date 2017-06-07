Ignore
======

Sometimes you don't want to include all the properties of your entity in the FormType. For these case the `Ignore` annoation can be used.

Attribute definitions
---------------------

This annotation doesn't have any atrribute.

Usage examples
--------------

A typical property which in many cases can be ignored in the FormType is a date field which is set when the object is created:

```php
...

    public function __construct()
    {
        $this->date = new \DateTime();
    }
    
    /**
     * @ORM\Column(type="date")
     */
    private $date;
...
```

This property is set in the constructor and should not be changed by the user, you can tell the MeatUp command to ignore it:

```php
...
use Ip\MeatUp\Mapping as MU;
...
    public function __construct()
    {
        $this->date = new \DateTime();
    }
    
    /**
     * @ORM\Column(type="date")
     * @MU\Ignore
     */
    private $date;
...
```