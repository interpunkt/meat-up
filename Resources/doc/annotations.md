MeatUp annotations
==================

The MeatUp command tries to get as much information as possible from the standard Doctrine annotations to keep the additional code used. But some additional information has to be provided anyways, in order to use the full functionality of the MeatUp command.

| Annotation | Description |
| --- | --- |
| CKEditor | Uses the CKEditor for rendering this field |
| Hidden | Render this property as a hidden field |
| Ignore | Ignore this property |
| ManyToOneChoice | Defines which property / properties should be used for a ManyToOneRelation field |
| ManyToOneOrderBy | Define in which order a ManyToOne relation should be shown |
| [OnIndexPage](https://github.com/interpunkt/meat-up/blob/master/Resources/doc/on_index_page.md) | Show a property on the index page |