Image upload with cropping with the IP Symfony Skeleton
=======================================================

The [IP Symfony Skeleton](https://github.com/interpunkt/ip-symfony-skeleton) comes with a built in way to upload images and crop them. The functionality is described [in its wiki](https://github.com/benblub/symfony-skeleton/wiki/Croppable).

It consists of two steps:

* Including the `Croppable` annotation to the entity
* Adding the corresponding values to the `attr` parameter of the FormType

As always the MeatUp command will generate the FormType for you, so you don't need to worry about that. But you still have to write the entity. For that just follow the following example:

```php
// ...

use DevPro\adminBundle\Annotation\Croppable;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

// ...

/**
 * @ORM\Entity()
 * @Vich\Uploadable
*/
class skeleton
{

// ...

    /**
     * @Vich\UploadableField(mapping="upload_image", fileNameProperty="imageName")
     * @Croppable(
     *     height = "imageHeight",
     *     width = "imageWidth",
     *     x = "imageX",
     *     y = "imageY"
     * )
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $imageHeight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $imageWidth;

    private $imageX;

    private $imageY;
    
// ...
    
```

If you want to have a fixed aspect ratio for the crop box please check out the [CroppableRatio annotation](croppable_aspect_ratio.md).