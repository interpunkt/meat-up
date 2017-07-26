CroppableRatio
==============

By default the cropping box that is [built-in in the IP Symfony Skeleton](https://github.com/benblub/symfony-skeleton/wiki/Croppable) has no fixed aspect ratio. With the `CroppapleRatio` annotation you can set one.

Attribute definitions
---------------------

| Name | Type |Description | Required |
| --- | --- | --- | --- |
| aspectRatio | String | The aspect ration normally defined as division or float | yes |

Usage examples
--------------

Let's take the example from the [Croppable annotation](croppable_image_upload.md). In order to set a fixed aspect ration all you have to do is add the annotation to the property with the `Croppable` annotation:

```php
// ...

use DevPro\adminBundle\Annotation\Croppable;
use Ip\MeatUp\Mapping as MU;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

// ...

    /**
     * @Vich\UploadableField(mapping="upload_image", fileNameProperty="imageName")
     * @Croppable(
     *     height = "imageHeight",
     *     width = "imageWidth",
     *     x = "imageX",
     *     y = "imageY"
     * )
     * @MU\CroppableRatio(aspectRatio="3/2")
     * @var File
     */
    private $imageFile;
    
```

The MeatUp command will after that add the aspect ratio to the FormType for you.

If you want to learn more about the `Croppable` annotation please refer to [its documentation](croppable_image_upload.md).