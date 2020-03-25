<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Roche\LoginWithoutPass\Model;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\ValidationResultsInterfaceFactory;
use Magento\Customer\Helper\View as CustomerViewHelper;
use Magento\Customer\Model\Config\Share as ConfigShare;
use Magento\Customer\Model\Customer as CustomerModel;
use Magento\Customer\Model\Customer\CredentialsValidator;
use Magento\Customer\Model\Metadata\Validator;
use Magento\Eav\Model\Validator\Attribute\Backend;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObjectFactory as ObjectFactory;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;
use Magento\Framework\Encryption\Helper\Security;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\EmailNotConfirmedException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\ExpiredException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Exception\State\InvalidTransitionException;
use Magento\Framework\Exception\State\UserLockedException;
use Magento\Framework\Intl\DateTimeFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Math\Random;
use Magento\Framework\Phrase;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\Stdlib\StringUtils as StringHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface as PsrLogger;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Session\SaveHandlerInterface;
use Magento\Customer\Model\ResourceModel\Visitor\CollectionFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Customer\Model\AccountConfirmation;

class AccountManagement extends \Magento\Customer\Model\AccountManagement
{

    protected $customerRepository;
    protected $customerFactory;
    protected $authentication;
    protected $eventManager;

    public function __construct(
        CustomerFactory $customerFactory,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        Random $mathRandom,
        Validator $validator,
        ValidationResultsInterfaceFactory $validationResultsDataFactory,
        AddressRepositoryInterface $addressRepository,
        CustomerMetadataInterface $customerMetadataService,
        CustomerRegistry $customerRegistry,
        PsrLogger $logger,
        Encryptor $encryptor,
        ConfigShare $configShare,
        StringHelper $stringHelper,
        CustomerRepositoryInterface $customerRepository,
        ScopeConfigInterface $scopeConfig,
        TransportBuilder $transportBuilder,
        DataObjectProcessor $dataProcessor,
        Registry $registry,
        CustomerViewHelper $customerViewHelper,
        DateTime $dateTime,
        CustomerModel $customerModel,
        ObjectFactory $objectFactory,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        CredentialsValidator $credentialsValidator = null,
        DateTimeFactory $dateTimeFactory = null,
        AccountConfirmation $accountConfirmation = null,
        SessionManagerInterface $sessionManager = null,
        SaveHandlerInterface $saveHandler = null,
        CollectionFactory $visitorCollectionFactory = null,
        SearchCriteriaBuilder $searchCriteriaBuilder = null
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->eventManager = $eventManager;
        parent::__construct($customerFactory,$eventManager,$storeManager,$mathRandom,$validator,$validationResultsDataFactory,$addressRepository,$customerMetadataService,$customerRegistry,$logger,$encryptor,$configShare,$stringHelper,$customerRepository,$scopeConfig,$transportBuilder,$dataProcessor,$registry,$customerViewHelper,$dateTime,$customerModel,$objectFactory,$extensibleDataObjectConverter,$credentialsValidator,$dateTimeFactory,$accountConfirmation,$sessionManager,$saveHandler,$visitorCollectionFactory,$searchCriteriaBuilder);
    }


    public function createAccount(CustomerInterface $customer, $password = null, $redirectUrl = '')
    {
        if ($password == null) {
           
            $customerEmail = $customer->getEmail();
            try {
                
            } catch (InputException $e) {
                throw new LocalizedException(
                    __("The password can't be the same as the email address. Create a new password and try again.")
                );
            }
            //$hash = $this->createPasswordHash($password);
            $hash = null;
        } else {
            $hash = null;
        }
        return $this->createAccountWithPasswordHash($customer, $hash, $redirectUrl);
    }

     /**
     * @inheritdoc
     */
    public function authenticate($username, $password)
    {
        try {
            $customer = $this->customerRepository->get($username);
        } catch (NoSuchEntityException $e) {
            
            throw new InvalidEmailOrPasswordException(__('Invalid login or password.'));
        }

        $customerId = $customer->getId();
        if ($this->getAuthentication()->isLocked($customerId)) {
            throw new UserLockedException(__('The account is locked.'));
        }
        try {
            //$this->getAuthentication()->authenticate($customerId, $password);
        } catch (InvalidEmailOrPasswordException $e) {
            
            throw new InvalidEmailOrPasswordException(__('Invalid login or password.'));
        }
        if ($customer->getConfirmation() && $this->isConfirmationRequired($customer)) {
            throw new EmailNotConfirmedException(__("This account isn't confirmed. Verify and try again."));
        }

        $customerModel = $this->customerFactory->create()->updateData($customer);
        $this->eventManager->dispatch(
            'customer_customer_authenticated',
            ['model' => $customerModel, 'password' => $password]
        );

        $this->eventManager->dispatch('customer_data_object_login', ['customer' => $customer]);

        return $customer;
    }

        /**
     * Get authentication
     *
     * @return AuthenticationInterface
     */
    protected function getAuthentication()
    {
        if (!($this->authentication instanceof AuthenticationInterface)) {
            return \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Customer\Model\AuthenticationInterface::class
            );
        } else {
            return $this->authentication;
        }
    }

 
}
