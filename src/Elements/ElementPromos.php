<?php

namespace Dynamic\Elements\Elements;

use DNADesign\Elemental\Models\BaseElement;
use Dynamic\Elements\Model\PromoObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Security\Permission;
use SilverStripe\Security\PermissionProvider;
use SilverStripe\Security\Security;
use Symbiote\GridFieldExtensions\GridFieldAddExistingSearchButton;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

/**
 * Class PromosElement.
 */
class ElementPromos extends BaseElement implements PermissionProvider
{
    /**
     * @var string
     */
    private static $icon = 'font-icon-block-banner';

    /**
     * @return string
     */
    private static $singular_name = 'Promos Element';

    /**
     * @return string
     */
    private static $plural_name = 'Promos Elements';

    /**
     * @var string
     */
    private static $table_name = 'ElementPromos';

    /**
     * @var array
     */
    private static $styles = array();

    /**
     * @var array
     */
    private static $db = [
        'Content' => DBHTMLText::class,
    ];

    /**
     * @var array
     */
    private static $many_many = array(
        'Promos' => PromoObject::class,
    );

    /**
     * @var array
     */
    private static $many_many_extraFields = array(
        'Promos' => array(
            'SortOrder' => 'Int',
        ),
    );

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->dataFieldByName('Content')
                ->setRows(8)
            ;

            if ($this->ID) {
                $promoField = $fields->dataFieldByName('Promos');
                $config = $promoField->getConfig();
                $config->addComponent(new GridFieldOrderableRows('SortOrder'));
                $config->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
                $config->addComponent(new GridFieldAddExistingSearchButton());

                $fields->addFieldsToTab('Root.Promos', array(
                    $promoField,
                ));
            }
        });

        return parent::getCMSFields();
    }

    /**
     * @return mixed
     */
    public function getPromoList()
    {
        return $this->Promos()->sort('SortOrder');
    }

    /**
     * @return DBHTMLText
     */
    public function ElementSummary()
    {
        return DBField::create_field('HTMLText', $this->Content)->Summary(20);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__.'.BlockType', 'Promos');
    }

    /**
     * @return array
     */
    public function providePermissions()
    {
        return array(
            'EDIT_PROMOS_ELEMENT' => array(
                'name' => _t(
                    'ElementPromos.EDIT_PROMOS_ELEMENT_PERMISSION',
                    'Manage Promos Elements'
                ),
                'category' => _t(
                    'Permissions.PERMISSIONS_PROMOS_ELEMENT',
                    'Elements'
                ),
                'help' => _t(
                    'ElementPromos.EDIT_PERMISSION_PROMOS_ELEMENT',
                    'Ability to edit Promos Elements.'
                ),
                'sort' => 400,
            ),
        );
    }

    /**
     * Set permissions, allow all users to access by default.
     * Override in descendant classes, or use PermissionProvider.
     */

    /**
     * @param null $member
     *
     * @return bool
     */
    public function canCreate($member = null, $context = [])
    {
        if (!$member) {
            $member = Security::getCurrentUser();
        }

        $extended = $this->extendedCan('canCreate', $member);
        if ($extended !== null) {
            return $extended;
        }

        return Permission::checkMember($member, 'EDIT_PROMOS_ELEMENT_PERMISSION', 'any');
    }

    /**
     * @param null $member
     *
     * @return bool
     */
    public function canView($member = null, $context = [])
    {
        return true;
    }

    /**
     * @param null $member
     *
     * @return bool
     */
    public function canEdit($member = null, $context = [])
    {
        if (!$member) {
            $member = Security::getCurrentUser();
        }

        $extended = $this->extendedCan('canEdit', $member);
        if ($extended !== null) {
            return $extended;
        }

        return Permission::checkMember($member, 'EDIT_PROMOS_ELEMENT_PERMISSION', 'any');
    }

    /**
     * @param null $member
     *
     * @return bool
     */
    public function canDelete($member = null, $context = [])
    {
        if (!$member) {
            $member = Security::getCurrentUser();
        }

        $extended = $this->extendedCan('canDelete', $member);
        if ($extended !== null) {
            return $extended;
        }

        return Permission::checkMember($member, 'EDIT_PROMOS_ELEMENT_PERMISSION', 'any');
    }
}
