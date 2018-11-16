<?php

namespace Ueg\Crm\Model;

class Crminvoice extends \Magento\Framework\Model\AbstractModel {

    protected $_resource;
    private $logger;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('Ueg\Crm\Model\ResourceModel\Crminvoice');
    }

    public function importOrderFromMagento() {
        try {

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $this->_resource = $objectManager->create('Magento\Framework\App\ResourceConnection');
            $resource = $this->_resource;


            $this->logger = $objectManager->create('Psr\Log\LoggerInterface');
            $readConnection = $resource->getConnection();
            $writeConnection = $resource->getConnection();

            $order = $resource->getTableName('sales_order');
            $orderItem = $resource->getTableName('sales_order_item');
            $shipment = $resource->getTableName('sales_shipment');
            $shipmentTrack = $resource->getTableName('sales_shipment_track');
            $shipmentAddress = $resource->getTableName('sales_order_address');
            $crmInvoice = $resource->getTableName('ueg_crm_invoice');
            $crmInvoiceItem = $resource->getTableName('ueg_crm_invoice_item');

            $maxSql = "SELECT MAX(magento_order_id) AS max_id FROM $crmInvoice";
            $maxId = $readConnection->fetchOne($maxSql);
            //echo "Max Id: ". $maxId;
            if (!isset($maxId) || empty($maxId)) {
                $maxId = 0;
            }

            $importSql = "INSERT INTO $crmInvoice (magento_customer_id, customer_name, customer_email, magento_order_id, order_number, total_qty, subtotal, tax_amount, shipping_amount, discount_amount, grand_total, state, status, created_at, rep, shipping_method)
				SELECT customer_id, CONCAT(customer_firstname, ' ', customer_lastname), customer_email, entity_id, increment_id, total_qty_ordered, subtotal, tax_amount, shipping_amount, discount_amount, grand_total, state, status, created_at, order_rep, shipping_description
				FROM $order
				WHERE entity_id > $maxId";
            //echo $importSql;
            $writeConnection->query($importSql);

            /* $updateSql = "UPDATE invoice
              SET
              invoice.ship_date = shipment.created_at,
              invoice.tracking_id = track.number,
              invoice.billing_firstname = address.firstname,
              invoice.billing_lastname = address.lastname,
              invoice.billing_street = address.street,
              invoice.billing_city = address.city,
              invoice.billing_region = address.region,
              invoice.billing_postcode = address.postcode
              FROM
              $crmInvoice as invoice
              INNER JOIN $shipment AS shipment ON invoice.magento_order_id = shipment.order_id
              INNER JOIN $shipmentAddress AS address ON invoice.magento_order_id = address.parent_id AND address.address_type = 'billing'
              LEFT JOIN $shipmentTrack AS track ON shipment.entity_id = track.parent_id
              ";
              echo $updateSql;
              $writeConnection->query( $updateSql ); */

            $updateSql = "UPDATE $crmInvoice 
							INNER JOIN $shipment ON $crmInvoice.magento_order_id = $shipment.order_id
						SET
							$crmInvoice.magento_shipping_id = $shipment.entity_id,
							$crmInvoice.ship_date = $shipment.created_at
						";
            //echo $updateSql;
            $writeConnection->query($updateSql);

            $updateSql = "UPDATE $crmInvoice 
							INNER JOIN $shipmentAddress ON $crmInvoice.magento_order_id = $shipmentAddress.parent_id AND $shipmentAddress.address_type = 'billing'
						SET
							$crmInvoice.billing_firstname = $shipmentAddress.firstname,
							$crmInvoice.billing_lastname = $shipmentAddress.lastname,
							$crmInvoice.billing_street = $shipmentAddress.street,
							$crmInvoice.billing_city = $shipmentAddress.city,
							$crmInvoice.billing_region = $shipmentAddress.region,
							$crmInvoice.billing_postcode = $shipmentAddress.postcode
						";
            //echo $updateSql;
            $writeConnection->query($updateSql);

            $updateSql = "UPDATE $crmInvoice 
							LEFT JOIN $shipmentTrack ON $crmInvoice.magento_shipping_id = $shipmentTrack.parent_id
						SET
							$crmInvoice.tracking_id = $shipmentTrack.number
						";
            //echo $updateSql;
            $writeConnection->query($updateSql);

            $maxItemSql = "SELECT MAX(magento_order_id) AS max_id FROM $crmInvoiceItem";
            $maxItemId = $readConnection->fetchOne($maxItemSql);
            //echo "Max Id: ". $maxItemId;
            if (!isset($maxItemId) || empty($maxItemId)) {
                $maxItemId = 0;
            }
            $importItemSql = "INSERT INTO $crmInvoiceItem (parent_id, magento_order_id, item_sku, item_name, qty, unit_price, total)
				SELECT (SELECT invoice_id FROM $crmInvoice WHERE magento_order_id = order_id), order_id, sku, name, qty_ordered, price, row_total
				FROM $orderItem
				WHERE order_id > $maxItemId";
            //echo $importItemSql;
            $writeConnection->query($importItemSql);
        } catch (\Exception $e) {
            //$this->logger->info('Exception: ' . $e->getTraceAsString());
        }
    }

}

?>