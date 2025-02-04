<?php

/**
 * Created on Thu Mar 16 2023
 * @author : Nicolas RENAULT <nrenault@tangkoko.com>
 * @copyright (c) 2023 Tangkoko
 **/


namespace Tangkoko\CustomerAttributesManagement\Block\Form\Attributes;

use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Block\Widget\AbstractWidget;
use Magento\Customer\Helper\Address as AddressHelper;
use Magento\Customer\Model\Options;
use Magento\Framework\View\Element\Template\Context;


/**
 * Widget for showing customer name.
 *
 * @method CustomerInterface getObject()
 * @method Name setObject(CustomerInterface $customer)
 *
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Name extends AbstractWidget
{
    /**
     * @var AddressMetadataInterface
     */
    protected $addressMetadata;

    /**
     * @var Options
     */
    protected $options;

    /**
     * @param Context $context
     * @param AddressHelper $addressHelper
     * @param CustomerMetadataInterface $customerMetadata
     * @param Options $options
     * @param AddressMetadataInterface $addressMetadata
     * @param array $data
     */
    public function __construct(
        Context $context,
        AddressHelper $addressHelper,
        CustomerMetadataInterface $customerMetadata,
        Options $options,
        AddressMetadataInterface $addressMetadata,
        array $data = []
    ) {
        $this->options = $options;
        parent::__construct($context, $addressHelper, $customerMetadata, $data);
        $this->addressMetadata = $addressMetadata;
        $this->_isScopePrivate = true;
    }

    /**
     * @inheritdoc
     */
    public function _construct()
    {
        parent::_construct();

        // default template location
        $this->setTemplate('Tangkoko_CustomerAttributesManagement::attributes/name.phtml');
    }

    /**
     * Can show config value
     *
     * @param string $key
     * @return bool
     */
    protected function _showConfig($key)
    {
        return (bool)$this->getConfig($key);
    }

    /**
     * Can show prefix
     *
     * @return bool
     */
    public function showPrefix()
    {
        return $this->_isAttributeVisible('prefix');
    }

    /**
     * Define if prefix attribute is required
     *
     * @return bool
     */
    public function isPrefixRequired()
    {
        return $this->_isAttributeRequired('prefix');
    }

    /**
     * Retrieve name prefix drop-down options
     *
     * @return array|bool
     */
    public function getPrefixOptions()
    {
        $prefixOptions = $this->options->getNamePrefixOptions();

        if ($this->getObject() && !empty($prefixOptions)) {
            $prefixOption = $this->getObject()->getPrefix();
            $oldPrefix = $this->escapeHtml(trim($prefixOption ?? ''));
            if ($prefixOption !== null && !isset($prefixOptions[$oldPrefix]) && !isset($prefixOptions[$prefixOption])) {
                $prefixOptions[$oldPrefix] = $oldPrefix;
            }
        }
        return $prefixOptions;
    }

    /**
     * Define if middle name attribute can be shown
     *
     * @return bool
     */
    public function showMiddlename()
    {
        return $this->_isAttributeVisible('middlename');
    }

    /**
     * Define if middlename attribute is required
     *
     * @return bool
     */
    public function isMiddlenameRequired()
    {
        return $this->_isAttributeRequired('middlename');
    }

    /**
     * Define if suffix attribute can be shown
     *
     * @return bool
     */
    public function showSuffix()
    {
        return $this->_isAttributeVisible('suffix');
    }

    /**
     * Define if suffix attribute is required
     *
     * @return bool
     */
    public function isSuffixRequired()
    {
        return $this->_isAttributeRequired('suffix');
    }

    /**
     * Retrieve name suffix drop-down options
     *
     * @return array|bool
     */
    public function getSuffixOptions()
    {
        $suffixOptions = $this->options->getNameSuffixOptions();
        if ($this->getObject() && !empty($suffixOptions)) {
            $suffixOption = $this->getObject()->getSuffix();
            $oldSuffix = $this->escapeHtml(trim($suffixOption ?? ''));
            if ($suffixOption !== null && !isset($suffixOptions[$oldSuffix]) && !isset($suffixOptions[$suffixOption])) {
                $suffixOptions[$oldSuffix] = $oldSuffix;
            }
        }
        return $suffixOptions;
    }

    /**
     * Class name getter
     *
     * @return string
     */
    public function getClassName()
    {
        if (!$this->hasData('class_name')) {
            $this->setData('class_name', 'customer-name');
        }
        return $this->getData('class_name');
    }

    /**
     * Container class name getter
     *
     * @return string
     */
    public function getContainerClassName()
    {
        $class = $this->getClassName();
        $class .= $this->showPrefix() ? '-prefix' : '';
        $class .= $this->showMiddlename() ? '-middlename' : '';
        $class .= $this->showSuffix() ? '-suffix' : '';
        return $class;
    }

    /**
     * @inheritdoc
     */
    protected function _getAttribute($attributeCode)
    {
        return $this->getData($attributeCode);
    }



    /**
     * Retrieve store attribute label
     *
     * @param string $attributeCode
     * @return string
     */
    public function getStoreLabel($attributeCode)
    {
        $attribute = $this->_getAttribute($attributeCode);
        return $attribute ? __($attribute->getStoreLabel()) : '';
    }

    /**
     * Get string with frontend validation classes for attribute
     *
     * @param string $attributeCode
     * @return string
     */
    public function getAttributeValidationClass($attributeCode)
    {
        $attributeMetadata = $this->_getAttribute($attributeCode);
        return $attributeMetadata ? $attributeMetadata->getFrontendClass() : '';
    }

    /**
     * Get string with frontend validation classes for attribute
     *
     * @param string $attributeCode
     * @return string
     */
    public function getAttributeValidationRules($attributeCode)
    {
        return $this->getViewModel()->getValidationRules($this->_getAttribute($attributeCode));
    }

    /**
     * Check if attribute is required
     *
     * @param string $attributeCode
     * @return bool
     */
    private function _isAttributeRequired($attributeCode)
    {
        $attributeMetadata = $this->_getAttribute($attributeCode);
        return $attributeMetadata ? (bool)$attributeMetadata->getIsRequired() : false;
    }

    /**
     * Check if attribute is visible
     *
     * @param string $attributeCode
     * @return bool
     */
    private function _isAttributeVisible($attributeCode)
    {
        $attributeMetadata = $this->_getAttribute($attributeCode);
        return $attributeMetadata ? (bool)$attributeMetadata->getIsVisible() : false;
    }
}
