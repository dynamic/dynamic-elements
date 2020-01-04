<?php

namespace Dynamic\Elements\ORM;

use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

class ElementContentDataExtension extends DataExtension
{
    /**
     * @var array
     */
    private static $has_one = [
        'Image' => Image::class,
        'ElementLink' => Link::class,
    ];

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->replaceField(
            'ElementLinkID',
            LinkField::create('ElementLinkID')
                ->setTitle('Link')
                ->setDescription('Optional. Add a call to action link.')
        );

        $fields->insertBefore(
            'HTML',
            $fields->dataFieldByName('ElementLinkID')
        );

        $image = $fields->dataFieldByName('Image')
                ->setDescription('Optional. Display an image with this content.')
                ->setFolderName('Uploads/Elements/Content');
        $fields->insertBefore($image, 'HTML');
    }
}
