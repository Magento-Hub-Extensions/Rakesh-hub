<?php

namespace Ueg\Crm\Block\Adminhtml\Crmadminacl\Edit\Tab;

/**
 * Crmadminacl edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Ueg\Crm\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Ueg\Crm\Model\Status $status,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_status = $status;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Ueg\Crm\Model\BlogPosts */
        $model = $this->_coreRegistry->registry('crmadminacl');
        $id = $this->getRequest()->getParam('id');
        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);

        if ($model->getId()) {
            // $fieldset->addField('crmacl_id', 'hidden', ['name' => 'crmacl_id']);
           
        }
         $fieldset->addField('id', 'hidden', ['name' => 'id']);
		 $fieldset->addField('role_id', 'hidden', ['name' => 'role_id']);

        $fieldset->addField('account_overview_read', 'multiselect', array(
                    'label'     => __('Account Overview Read'),
                    'values'   => self::getValueArray1(),
                    'name' => 'account_overview_read',
                ));
                $fieldset->addField('account_overview_write', 'multiselect', array(
                    'label'     => __('Account Overview Write'),
                    'values'   => self::getValueArray1(),
                    'name' => 'account_overview_write',
                ));
                $fieldset->addField('inventory_read', 'multiselect', array(
                    'label'     => __('Inventory Read'),
                    'values'   => self::getValueArray2(),
                    'name' => 'inventory_read',
                ));
                $fieldset->addField('account_view', 'select', array(
                    'label'     => __('Account View'),
                    'values'   => array(
                        array(
                            'value' => 0,
                            'label' => "No"
                        ),
                        array(
                            'value' => 1,
                            'label' => "Yes"
                        )
                    ),
                    'name' => 'account_view',
                ));
                $fieldset->addField('account_logview', 'select', array(
                    'label'     => __('Account Log View'),
                    'values'   => array(
                        array(
                            'value' => 0,
                            'label' => "No"
                        ),
                        array(
                            'value' => 1,
                            'label' => "Yes"
                        )
                    ),
                    'name' => 'account_logview',
                ));
                $fieldset->addField('order_street_view', 'select', array(
                    'label'     => __('Order Address Street View'),
                    'values'   => array(
                        array(
                            'value' => 0,
                            'label' => "No"
                        ),
                        array(
                            'value' => 1,
                            'label' => "Yes"
                        )
                    ),
                    'name' => 'order_street_view',
                ));
                $fieldset->addField('sales_log_delete', 'select', array(
                    'label'     => __('Sales Log Delete'),
                    'values'   => array(
                        array(
                            'value' => 0,
                            'label' => "No"
                        ),
                        array(
                            'value' => 1,
                            'label' => "Yes"
                        )
                    ),
                    'name' => 'sales_log_delete',
                ));
					

        if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
        }

        // print_r($model->getData());

        $form->setValues($model->getData());

        $data_=array('id'=>$id);    
        // $form->setValues($data_);

        $this->setForm($form);
		
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Item Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Item Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
    
    public function getTargetOptionArray(){
    	return array(
    				'_self' => "Self",
					'_blank' => "New Page",
    				);
    }

     public function getOptionArray1()
    {
        $data_array=array();
        $data_array['all']='all';
        $data_array['first_name']='first_name';
        $data_array['last_name']='last_name';
        $data_array['phone']='phone';
        $data_array['phone_type']='phone_type';
        $data_array['company']='company';
        $data_array['email']='email';
        $data_array['additional_phone']='additional_phone';
        $data_array['additional_phone_type']='additionalphone_type';
        $data_array['ebay_id']='ebay_id';
        $data_array['client_code']='client_code';
        $data_array['hot_client']='hot_client';
        $data_array['assigned_to']='assigned_to';
        $data_array['notes']='notes';
        $data_array['billing_street']='billing_street';
        $data_array['billing_city']='billing_city';
        $data_array['billing_state']='billing_state';
        $data_array['billing_zip']='billing_zip';
        $data_array['shipping_street']='shipping_street';
        $data_array['shipping_city']='shipping_city';
        $data_array['shipping_state']='shipping_state';
        $data_array['shipping_zip']='shipping_zip';
        $data_array['date_modified']='date_modified';
        $data_array['date_created']='date_created';
        $data_array['admin_only']='admin_only';
        $data_array['contact_time']='contact_time';
        $data_array['contact_method']='contact_method';
        $data_array['source_sale']='source_sale';
        $data_array['payment_method']='payment_method';
        $data_array['mailing_list']='mailing_list';
        $data_array['last_contact']='last_contact';
        $data_array['last_communication']='last_communication';
        return($data_array);
    }
     public function getValueArray1()
    {
        $data_array=array();
        foreach(self::getOptionArray1() as $k=>$v){
            $data_array[]=array('value'=>$k,'label'=>$v);
        }
        return($data_array);

    }

     public function getOptionArray2()
    {
        $data_array=array();
        $data_array['all']='all';
        $data_array['public_title']='public_title';
        $data_array['sku']='sku';
        $data_array['population']='population';
        $data_array['pricing_method']='pricing_method';
        $data_array['free_shipping']='free_shipping';
        $data_array['amex_disc']='amex_disc';
        $data_array['restockable']='restockable';
        $data_array['buyback']='buyback';
        $data_array['buyback_method']='buyback_method';
        $data_array['msg_buyback']='msg_buyback';
        $data_array['greysheet_bid']='greysheet_bid';
        $data_array['greysheet_ask']='greysheet_ask';
        $data_array['pcgs_price']='pcgs_price';
        $data_array['pcgs_next']='pcgs_next';
        $data_array['numismedia_price']='numismedia_price';
        $data_array['numismedia_next']='numismedia_next';
        $data_array['sellon_msg']='sellon_msg';
        $data_array['msg_price']='msg_price';
        $data_array['sellon_ebay']='sellon_ebay';
        $data_array['ebay_price']='ebay_price';
        $data_array['sellon_cce']='sellon_cce';
        $data_array['cce_retail']='cce_retail';
        $data_array['sellon_amazon']='sellon_amazon';
        $data_array['amazon_price']='amazon_price';
        $data_array['on_hold']='on_hold';
        $data_array['ready']='ready';
        $data_array['status']='status';
        $data_array['visibility']='visibility';
        $data_array['cost']='cost';
        $data_array['view_on_ebay']='view_on_ebay';
        $data_array['generic_pricing_method']='generic_pricing_method';
        $data_array['generic_base_sku']='generic_base_sku';
        $data_array['generic_premium']='generic_premium';
        return($data_array);
    }
     public function getValueArray2()
    {
        $data_array=array();
        foreach(self::getOptionArray2() as $k=>$v){
            $data_array[]=array('value'=>$k,'label'=>$v);
        }
        return($data_array);

    }

}
