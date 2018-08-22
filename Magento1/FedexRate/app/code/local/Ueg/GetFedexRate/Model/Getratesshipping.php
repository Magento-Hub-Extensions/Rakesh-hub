<?php
class Ueg_GetFedexRate_Model_Getratesshipping
{
        protected $_weight;
        protected $_zip;
        public function _createRateSoapClient()

        {
           $wsdlBasePath = Mage::getModuleDir('etc', 'Mage_Usa')  . DS . 'wsdl' . DS . 'FedEx' . DS;
           $rateServiceWsdl = $wsdlBasePath . 'RateService_v10.wsdl';
           return $this->_createSoapClient($rateServiceWsdl);
        }

        public function _createSoapClient($wsdl, $trace = false)
        {
            $client = new SoapClient($wsdl, array('trace' => $trace));
            $client->__setLocation('https://ws.fedex.com:443/web-services');
            return $client;
        }

        public function getCustomRates()
        {
            $result = Mage::getModel('shipping/rate_result');
            $allowedMethods = explode(',', Mage::getStoreConfig('carriers/fedex/allowed_methods'));
            if (in_array('SMART_POST', $allowedMethods)) {
                $response = $this->doRatesRequest('SMART_POST');
                $preparedSmartpost = Mage::getModel('usa/shipping_carrier_fedex')->_prepareRateResponse($response);
                $result->append($preparedSmartpost);
            }
            $response = $this->doRatesRequest('general');
            $preparedGeneral = Mage::getModel('usa/shipping_carrier_fedex')->_prepareRateResponse($response);// need to public function
            if ($result->getError() && $preparedGeneral->getError()) {
                return $result->getError();
            }
            $result->append($preparedGeneral);
            $newResult = (array)$preparedGeneral;
            $data = array();
            
            foreach($newResult as &$_newResult)
            {                
                if(count($_newResult) > 0){
                    $data = array();
                    for ($i=0; $i < sizeof($_newResult); $i++) { 
                        $allValue = $_newResult[$i]->getData();
                        $data[$allValue['method_title']]=$allValue['price'];
                    }
                    break;
                }                
            }

           return $data;
           
        }

        protected function setWeight($weight)
        {
           $this->_weight = $weight; 
           
        }
        protected function setZip($zio)
        {
            $this->_zip = $zio; 
            
        }
        protected function getWeight()
        {
            return $this->_weight;
        }
        protected function getZip()
        {
            return $this->_zip;
        }

        public function getExtimateRate($weight, $zip)
        {
            $this->setWeight($weight);
            $this->setZip($zip);
            return $this->getCustomRates();           
        }


        public function doRatesRequest($purpose)
        {
                $ratesRequest = $this->formRateRequest($purpose);
                $requestString = serialize($ratesRequest);
                $response = null;
                if ($response === null) {
                    try {
                        $client = $this->_createRateSoapClient();
                        $response = $client->getRates($ratesRequest);
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
                else {
                        $response = unserialize($response);
                        $debugData['result'] = $response;
                }
                return $response;
        }

         public function formRateRequest($purpose)
            {
                    //$r = $this->_rawRequest;
                    $ratesRequest = array(
                        'WebAuthenticationDetail' => array(
                            'UserCredential' => array(
                                'Key'      => Mage::getStoreConfig('carriers/fedex/key'),
                                'Password' => Mage::getStoreConfig('carriers/fedex/password')
                            )
                        ),
                        'ClientDetail' => array(
                            'AccountNumber' => Mage::getStoreConfig('carriers/fedex/account'),
                            'MeterNumber'   => Mage::getStoreConfig('carriers/fedex/meter_number')
                        ),
                        'Version' => $this->getVersionInfo(),
                        'RequestedShipment' => array(
                            'DropoffType'   =>  Mage::getStoreConfig('carriers/fedex/dropoff'),
                            'ShipTimestamp' => date('c'),
                            'PackagingType' =>  Mage::getStoreConfig('carriers/fedex/packaging'),
                            'TotalInsuredValue' => array(
                                'Amount'  =>  0,
                                'Currency' => 'USD'
                            ),
                            'Shipper' => array(
                                'Address' => array(
                                    'PostalCode'  => '90034',
                                    'CountryCode' => 'US'
                                )
                            ),
                            'Recipient' => array(
                                'Address' => array(
                                    'PostalCode'  => $this->getZip(),
                                    'CountryCode' => 'US',
                                    'Residential' => (bool)Mage::getStoreConfig('carriers/fedex/residence_delivery')
                                )
                            ),
                            'ShippingChargesPayment' => array(
                                'PaymentType' => 'SENDER',
                                'Payor' => array(
                                    'AccountNumber' => Mage::getStoreConfig('carriers/fedex/account'),
                                    'CountryCode'   => 'US'
                                )
                            ),
                            'CustomsClearanceDetail' => array(
                                'CustomsValue' => array(
                                    'Amount' => 200,
                                    'Currency' => 'USD'
                                )
                            ),
                            'RateRequestTypes' => 'LIST',
                            'PackageCount'     => '1',
                            'PackageDetail'    => 'INDIVIDUAL_PACKAGES',
                            'RequestedPackageLineItems' => array(
                                '0' => array(
                                    'Weight' => array(
                                        'Value' => $this->getWeight(),
                                        'Units' => 'LB'
                                    ),
                                    'GroupPackageCount' => 1,
                                )
                            )
                        )
                    );

                    if ($purpose == 'general') {
                        $ratesRequest['RequestedShipment']['RequestedPackageLineItems'][0]['InsuredValue'] = array(
                            'Amount'  => 0,
                            'Currency' => 'USD'
                        );
                    } else if ($purpose == 'SMART_POST') {
                        $ratesRequest['RequestedShipment']['ServiceType'] = 'SMART_POST';
                        $ratesRequest['RequestedShipment']['SmartPostDetail'] = array(
                            'Indicia' => ((float)Mage::getStoreConfig('carriers/fedex/max_package_weight') >= 1) ? 'PARCEL_SELECT' : 'PRESORTED_STANDARD',
                            'HubId' => Mage::getStoreConfig('carriers/fedex/smartpost_hubid')
                        );
                    }

                    return $ratesRequest;
            }

           public function getVersionInfo()
            {
                return array(
                    'ServiceId'    => 'crs',
                    'Major'        => '10',
                    'Intermediate' => '0',
                    'Minor'        => '0'
                );
            }

}