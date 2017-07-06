File upload with the VichUploaderBundle
=======================================

For handling the Uploading of files the MeatUp command relies on the [VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle).

On this page you find the documentation on how the MeatUp command is using this bundle.

Installation
------------

Please refer to the [official installation procedure](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/installation.md) to install the VichUploaderBundle.

Declare a entity as a VichUploadable
------------------------------------

Let's consider an entity which contains an [UploadableField](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/usage.md#step-2-link-the-upload-mapping-to-an-entity):

```php
...

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Product
{
...
    /**
     * @Vich\UploadableField(mapping="product_data", fileNameProperty="dataName")
     * @var File
     */
    private $dataFile;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $dataName;
...
```

The MeatUp command will **ignore** any property which is defined as `fileNameProperty`. Only the property with the `Vich\UploadableField` annotation will be considered and will generate a [VichFileType](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/form/vich_file_type.md) with the following parameters:

| Parameter | Value |
| --- | --- |
| allow_delete | true |
| download_link | true |

These parameters are hard coded and you have to change them after the FormType has been generated if they don't fit your requirements.

