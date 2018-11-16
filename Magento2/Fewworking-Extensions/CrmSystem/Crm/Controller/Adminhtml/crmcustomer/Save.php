<?php

namespace Ueg\Crm\Controller\Adminhtml\crmcustomer;

error_reporting(E_ALL);
ini_set("display_errors", 1);

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Ueg\Crm\Model\Customer\Attribute\Source\CustomCustomerAssignedToOptions;
use Magento\Directory\Model\CountryFactory;
use Ueg\Crm\Model\Customer\Attribute\Source as CustomerAttributeSource;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Ueg\Crm\Model\CrmcuslogFactory;
use \Magento\Customer\Model\CustomerFactory;
use Magento\Directory\Model\CountryInformationAcquirer;
use Ueg\Crm\Helper\Data as CrmHelper;

class Save extends \Magento\Backend\App\Action {

    /**
     * @var PageFactory
     */
    protected $resultPagee;
    protected $_customerRepositoryInterface;
    protected $addressRepository;
    protected $addressFactory;
    protected $resourceConnection;
    protected $customeroptions15201797334;
    protected $customeroptions15242422063;
    protected $customeroptions15242422064;
    protected $customeroptions15242422065;
    protected $customeroptions15242422066;
    protected $countryFactory;
    protected $authSession;
    protected $crmcuslogFactory;
    protected $customerFactory;
    protected $countryInformationAcquirer;
    protected $crmHelper;

    // protected $addressDataFactory;
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
    Context $context, PageFactory $resultPageFactory, \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface, \Magento\Customer\Api\AddressRepositoryInterface $addressRepository, \Magento\Customer\Api\Data\AddressInterface $addressDataFactory, \Magento\Customer\Model\AddressFactory $AddressFactory, \Magento\Framework\App\ResourceConnection $ResourceConnection, CustomCustomerAssignedToOptions $CustomCustomerAssignedToOptions, CountryFactory $CountryFactory, CustomerAttributeSource\Customeroptions15242422063 $Customeroptions15242422063, CustomerAttributeSource\Customeroptions15242422064 $Customeroptions15242422064, CustomerAttributeSource\Customeroptions15242422065 $Customeroptions15242422065, CustomerAttributeSource\Customeroptions15242422066 $Customeroptions15242422066, AuthSession $authSession, CrmcuslogFactory $crmcuslogFactory, CustomerFactory $customerFactory, CountryInformationAcquirer $CountryInformationAcquirer, CrmHelper $crmHelper
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->addressRepository = $addressRepository;
        $this->addressDataFactory = $addressDataFactory;
        $this->addressFactory = $AddressFactory;
        $this->resourceConnection = $ResourceConnection;

        $this->customeroptions15201797334 = $CustomCustomerAssignedToOptions;
        $this->customeroptions15242422063 = $Customeroptions15242422064;
        $this->customeroptions15242422064 = $Customeroptions15242422064;
        $this->customeroptions15242422065 = $Customeroptions15242422065;
        $this->customeroptions15242422066 = $Customeroptions15242422066;

        $this->countryFactory = $CountryFactory;
        $this->authSession = $authSession;
        $this->crmcuslogFactory = $crmcuslogFactory;
        $this->customerFactory = $customerFactory;
        $this->countryInformationAcquirer = $CountryInformationAcquirer;
        $this->crmHelper = $crmHelper;
    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute() {

        $params = $this->getRequest()->getParams();
        //echo "<pre>"; print_r($params); echo "</pre>"; exit;
        if (isset($params['account'])) {
            $id = $params['account']['id'];

            $data = array();
            $account = $params['account'];

            if (isset($account['firstname'])) {
                $data['firstname'] = $account['firstname'];
            }
            if (isset($account['lastname'])) {
                $data['lastname'] = $account['lastname'];
            }
            if (isset($account['email'])) {
                $data['email'] = $account['email'];
            } else {
//                      $customerObj = Mage::getModel("customer/customer")->load($id);
                $customerObj = $this->_customerRepositoryInterface->getById($id);
                if ($customerObj->getId()) {
                    $data['email'] = $customerObj->getEmail();
                }
            }
            if (isset($account['ebay_id'])) {
                $data['ebay_id'] = $account['ebay_id'];
            }
            if (isset($account['client_code'])) {
                $data['client_code'] = $account['client_code'];
            }
            if (isset($account['hot_client'])) {
                $data['hot_client'] = $account['hot_client'];
            }
            if (isset($account['assigned_to'])) {
                $data['assigned_to'] = $account['assigned_to'];
            }
            if (isset($account['preferred_contact_time'])) {
                $data['preferred_contact_time'] = $account['preferred_contact_time'];
            }
            if (isset($account['preferred_contact_method'])) {
                $data['preferred_contact_method'] = $account['preferred_contact_method'];
            }
            if (isset($account['source_sale'])) {
                $data['source_sale'] = $account['source_sale'];
            }
            if (isset($account['preferred_payment'])) {
                $data['preferred_payment'] = $account['preferred_payment'];
            }
            if (isset($account['admin_only'])) {
                $data['admin_only'] = $account['admin_only'];
            }
            if (isset($account['customer_notes'])) {
                $data['customer_notes'] = $account['customer_notes'];
            }
            if (isset($account['last_contacted'])) {
                $data['last_contacted'] = $account['last_contacted'];
            }
            if (isset($account['last_communication'])) {
                $data['last_communication'] = $account['last_communication'];
            }

            //echo "<pre>"; print_r($data); echo "</pre>";
//                $customer = Mage::getModel("customer/customer");
            $customer = $this->_customerRepositoryInterface->getById($id);
            if ($customer->getId()) {
                try {
                    foreach ($data as $key => $singleCustomerData) {

                        if ($key == 'firstname' || $key == 'lastname' || $key == 'email') {
                            $customer->setData($key, $singleCustomerData);
                        } elseif ($key == 'company') {
                            echo "$key => $singleCustomerData <br/>";
                            exit;
                            $customer->setCustomAttribute('customer_company', $singleCustomerData);
                        } else {
                            $customer->setCustomAttribute($key, $singleCustomerData);
                        }
                    }


                    $this->_customerRepositoryInterface->save($customer);
                    $this->messageManager->addSuccess(__('Customer Info Saved.'));
                } catch (\Exception $exc) {
                    $this->messageManager->addError(__($exc->getMessage()));
                    $this->_redirect("*/*/view", array('id' => $id));
                    return;
                }
            } else {
                $this->messageManager->addError(__('Invalid Customer Info'));
                $this->_redirect("*/*/view", array('id' => $id));
                return;
            }



            // segment
            if (isset($account['mailing_list'])) {
                $resource = $this->resourceConnection;
                $writeConnection = $resource->getConnection();
                $subscriberTable = $resource->getTableName('aw_advancednewsletter_subscriber');
                $segment = implode(',', $account['mailing_list']);
                $query = "UPDATE $subscriberTable SET segments_codes = '$segment' WHERE customer_id = $id";
                $writeConnection->query($query);
            }


            // address
            if (isset($account['address'])) {

                foreach ($account['address'] as $addressType) {
                    foreach ($addressType as $addressId => $addressData) {
//                                $address = Mage::getModel( 'customer/address' )->load( $addressId );
                        $address = $this->addressFactory->create()->load($addressId);
                        //echo "<pre>"; print_r($address->getData()); echo "</pre>"; exit;
                        /* $address->setId($addressId)
                          ->setCustomerId($id)
                          ->setSaveInAddressBook('1'); */
                        foreach ($addressData as $code => $value) {
                            $address->setData($code, $value);
                        }
                        try {
                            $address->save();
                        } catch (Exception $exc) {
                            $this->messageManager->addError(__($exc->getMessage()));
                            $this->_redirect("*/*/view", array('id' => $id));
                            return;
                        }
                    }
                }
            }

            //exit;

            $logData = array();
            if (isset($account['firstname'])) {
                $logData['First name'] = $account['firstname'];
            }
            if (isset($account['lastname'])) {
                $logData['Last name'] = $account['lastname'];
            }

//                $options = Mage::getModel('crm/eav_entity_attribute_source_customeroptions15242422066')->getOptionArray();
            $options = $this->customeroptions15242422066->getOptionArray();
            if (isset($account['address']) && isset($account['address']['primary'])) {
                $primary = array_shift($account['address']['primary']);
                if (isset($primary['telephone'])) {
                    $logData['Phone'] = $primary['telephone'];
                }
                if (isset($primary['phone_type'])) {
                    $logData['Phone Type'] = $options[$primary['phone_type']];
                }
                if (isset($primary['company'])) {
                    $logData['Company'] = $primary['company'];
                }
            }
            if (isset($account['address']) && isset($account['address']['additional'])) {
                $additional = array_shift($account['address']['additional']);
                if (isset($additional['telephone'])) {
                    $logData['Additional Phone'] = $additional['telephone'];
                }
                if (isset($additional['phone_type'])) {
                    $logData['Additional Phone Type'] = $options[$additional['phone_type']];
                }
            }

            if (isset($account['email'])) {
                $logData['Email'] = $account['email'];
            }
            if (isset($account['ebay_id'])) {
                $logData['eBay ID'] = $account['ebay_id'];
            }
            if (isset($account['client_code'])) {
                $logData['Client Code'] = $account['client_code'];
            }
            if (isset($account['hot_client'])) {
                $logData['Hot Client'] = ($account['hot_client'] == 1) ? "Yes" : "No";
            }

//                $options = Mage::getModel('crm/eav_entity_attribute_source_customeroptions15201797334')->getOptionArray();
            $options = $this->customeroptions15201797334->getOptionArray();
            if (isset($account['assigned_to'])) {
                $assignedTo = array();
                foreach ($account['assigned_to'] as $_aId) {
                    $assignedTo[] = $options[$_aId];
                }
                $logData['Assigned To'] = implode(', ', $assignedTo);
            }
            if (isset($account['preferred_contact_time'])) {
                $logData['Preferred Contact Time'] = $account['preferred_contact_time'];
            }

            $options = $this->customeroptions15242422063->getOptionArray();
            if (isset($account['preferred_contact_method'])) {
                $logData['Preferred Contact Method'] = $options[$account['preferred_contact_method']];
            }

            $options = $this->customeroptions15242422064->getOptionArray();
            if (isset($account['source_sale'])) {
                $logData['Source of Sale'] = $options[$account['source_sale']];
            }

            $options = $this->customeroptions15242422065->getOptionArray();
            if (isset($account['preferred_payment'])) {
                $logData['Preferred Payment Method'] = $options[$account['preferred_payment']];
            }

            if (isset($account['admin_only'])) {
                $logData['Administrator Only'] = ($account['admin_only'] == 1) ? "Yes" : "No";
            }
            if (isset($account['mailing_list'])) {
                $logData['Mailing List'] = implode(', ', $account['mailing_list']);
            }
            if (isset($account['customer_notes'])) {
                $logData['Notes'] = $account['customer_notes'];
            }

//                $regionCollection = Mage::getModel('directory/region_api')->items("US");
            $regionCollection = $this->countryFactory->create()->loadByCode("us")->getRegions();
            $regions = array();
            foreach ($regionCollection as $_region) {
//                $key = $_region['region_id'];
//                $regions[$key] = $_region['name'];
                $key = $_region->getData('region_id');
                $regions[$key] = $_region->getData('name');
            }


            if (isset($account['address']) && isset($account['address']['billing'])) {
                $billing = array_shift($account['address']['billing']);
                $bill_street = isset($billing['street']) ? $billing['street'] . "\n" : '';
                $bill_city = isset($billing['city']) ? $billing['city'] . "\n" : '';
//                $bill_region_id = isset($regions[$billing['region_id']]) ? $regions[$billing['region_id']] . "," : '';
                $bill_region_id = '';
                if (isset($billing['region_id'])) {
                    $bill_region_id = $regions[$billing['region_id']] . ",";
                }

                $bill_postcode = isset($billing['postcode']) ? $billing['postcode'] : '';

                $logData['Billing Address'] = $bill_street . $bill_city . $bill_region_id . $bill_postcode;
            }
            if (isset($account['address']) && isset($account['address']['shipping'])) {
                $shipping = array_shift($account['address']['shipping']);
                $ship_street = isset($shipping['street']) ? $shipping['street'] . "\n" : '';
                $ship_city = isset($shipping['city']) ? $shipping['city'] . "\n" : '';
                $ship_region_id = '';

                if (isset($shipping['region_id'])) {
                    $ship_region_id = $regions[$shipping['region_id']] . ",";
                }

                $ship_postcode = isset($shipping['postcode']) ? $shipping['postcode'] : '';

                $logData['Shipping Address'] = $ship_street . $ship_city . $ship_region_id . $ship_postcode;
            }

            if (isset($account['last_contacted'])) {
                $logData['Last Contact'] = $account['last_contacted'];
            }
            if (isset($account['last_communication'])) {
                $logData['Last Communication'] = $account['last_communication'];
            }

            $updated_data = "";
            foreach ($logData as $key => $item) {
                $updated_data .= "<strong>$key:</strong> $item \n";
            }
            $user = $this->authSession;
            $userId = $user->getUser()->getUserId();
            $log = array(
                'customer_id' => $id,
                'user_id' => $userId,
                'event_name' => "Account Updated",
                'updated_data' => $updated_data,
                'created_at' => $this->crmHelper->now(),
            );
//                $comment = Mage::getModel("crm/crmcuslog");
            $comment = $this->crmcuslogFactory->create();
            $comment->setData($log)->save();


            $this->_redirect("*/*/view", array('id' => $id));
        }
    }

}
