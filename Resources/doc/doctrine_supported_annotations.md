Supported Doctrine annotations
==============================

The MeatUp command relies heavily on [Doctrine](http://www.doctrine-project.org/) and its annotations to work. 

On this page you find a list of all the Doctrine annotations, that the MeatUp command takes into consideration when generating your CRUD-Controller.

Column
------

The [Column annotation](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/annotations-reference.html#annref-column) defines how your property is mapped in the database. The following attributes are considered by the MeatUp command:

| Name | Description |
| --- | --- |
| type | The doctrine type which defines the database and PHP type |
| nullable | Determines if NULL values are allowed |
| scale | The scale for numeric types |

### type

There are [many different types](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/basic-mapping.html#doctrine-mapping-types) which can be used for your database fields. In the following table you can see which types are currently supported by the MeatUp command and which [FormType](http://symfony.com/doc/current/reference/forms/types.html) will be used for them:

| Doctrine Type | FormType |
| --- | --- |
| boolean | [CheckboxType](http://symfony.com/doc/current/reference/forms/types/checkbox.html) |
| date | [DateType](http://symfony.com/doc/current/reference/forms/types/date.html) |
| dateTime | [DateTimeType](http://symfony.com/doc/current/reference/forms/types/datetime.html) |
| decimal | [NumberType](http://symfony.com/doc/current/reference/forms/types/number.html) |
| float | [NumberType](http://symfony.com/doc/current/reference/forms/types/number.html) |
| integer | [IntegerType](http://symfony.com/doc/current/reference/forms/types/integer.html) |
| string | [TextType](http://symfony.com/doc/current/reference/forms/types/text.html) |
| text | [TextareaType](http://symfony.com/doc/current/reference/forms/types/textarea.html) |

### nullable

The `ǹullable` attribute defines if a database column can be NULL.

If a property has the nullable attribute set to true, the MeatUp command will generate the corresponding FormType with the attribute `required` set to false. 

If nullable is not specified or set to false the corresponding FormType will be required and its label will have an Asterisk (*) at the end. 

### scale

The `scale` attribute defines the maximum number of digits which will be saved after the decimal point. Because of that it is only used when the `type` attribute is either `decimal` or `float`.

Id
---

The [Id annotation](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/annotations-reference.html#annref-id) marks which property will be used as primary key in the database. The MeatUp command assumes, that this value is always [generated automatically](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/annotations-reference.html#generatedvalue) and never set by the user. Because of that, this property will **always be ignored** when the FormTypes are generated. It is however not a problem to show it in the index view, by adding the [OnIndexPage annotation](on_index_page.md).

ManyToOne
---------

The [ManyToOne annotation](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/association-mapping.html#many-to-one-unidirectional) is used to establish relations between entities. The following attributes are considered by the MeatUp command:
 
| Name | Description |
| --- | --- |
| targetEntity | The name of the entity which will be used for the relation |

The MeatUp command will generate an generic [EntityType field](https://symfony.com/doc/current/reference/forms/types/entity.html) for this property. If you want to cutomise how this field is rendered you can use the following MeatUp annotations:

| Name | Description |
| --- | --- |
| [ManyToOneChoice](many_to_one_choice.md) | Changes the labels that are shown |
| [ManyToOneOrderBy](many_to_one_order_by.md) | Changes the order of the labels that are shown |