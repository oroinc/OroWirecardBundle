<?php

namespace Oro\Bundle\WirecardBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroWirecardBundleInstaller implements Installation
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** update integration transport table */
        $this->updateOroIntegrationTransportTable($schema);

        /** Tables generation **/
        $this->createOroWirecardSeamlessCreditCardLblTable($schema);
        $this->createOroWirecardSeamlessCreditCardShLblTable($schema);
        $this->createOroWirecardSeamlessPayPalLblTable($schema);
        $this->createOroWirecardSeamlessPayPalShLblTable($schema);
        $this->createOroWirecardSeamlessSepaLblTable($schema);
        $this->createOroWirecardSeamlessSepaShLblTable($schema);

        /** Foreign keys generation **/
        $this->addOroWirecardSeamlessCreditCardLblForeignKeys($schema);
        $this->addOroWirecardSeamlessCreditCardShLblForeignKeys($schema);
        $this->addOroWirecardSeamlessPayPalLblForeignKeys($schema);
        $this->addOroWirecardSeamlessPayPalShLblForeignKeys($schema);
        $this->addOroWirecardSeamlessSepaLblForeignKeys($schema);
        $this->addOroWirecardSeamlessSepaShLblForeignKeys($schema);
    }

    /**
     * @param Schema $schema
     */
    public function updateOroIntegrationTransportTable(Schema $schema)
    {
        $table = $schema->getTable('oro_integration_transport');
        $table->addColumn('oro_wcs_customer_id', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('oro_wcs_shop_id', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('oro_wcs_secret', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('oro_wcs_test_mode', 'boolean', ['default' => '0', 'notnull' => false]);
    }

    /**
     * Create oro_wcs_credit_card_lbl table
     *
     * @param Schema $schema
     */
    protected function createOroWirecardSeamlessCreditCardLblTable(Schema $schema)
    {
        $table = $schema->createTable('oro_wcs_credit_card_lbl');
        $table->addColumn('transport_id', 'integer', []);
        $table->addColumn('localized_value_id', 'integer', []);
        $table->setPrimaryKey(['transport_id', 'localized_value_id']);
        $table->addUniqueIndex(['localized_value_id'], 'UNIQ_37219116EB576E89');
    }

    /**
     * Create oro_wcs_credit_card_sh_lbl table
     *
     * @param Schema $schema
     */
    protected function createOroWirecardSeamlessCreditCardShLblTable(Schema $schema)
    {
        $table = $schema->createTable('oro_wcs_credit_card_sh_lbl');
        $table->addColumn('transport_id', 'integer', []);
        $table->addColumn('localized_value_id', 'integer', []);
        $table->setPrimaryKey(['transport_id', 'localized_value_id']);
        $table->addUniqueIndex(['localized_value_id'], 'UNIQ_CB754450EB576E89');
    }

    /**
     * Create oro_wcs_paypal_lbl table
     *
     * @param Schema $schema
     */
    protected function createOroWirecardSeamlessPayPalLblTable(Schema $schema)
    {
        $table = $schema->createTable('oro_wcs_paypal_lbl');
        $table->addColumn('transport_id', 'integer', []);
        $table->addColumn('localized_value_id', 'integer', []);
        $table->setPrimaryKey(['transport_id', 'localized_value_id']);
        $table->addUniqueIndex(['localized_value_id'], 'UNIQ_6890DDC0EB576E89');
    }

    /**
     * Create oro_wcs_paypal_sh_lbl table
     *
     * @param Schema $schema
     */
    protected function createOroWirecardSeamlessPayPalShLblTable(Schema $schema)
    {
        $table = $schema->createTable('oro_wcs_paypal_sh_lbl');
        $table->addColumn('transport_id', 'integer', []);
        $table->addColumn('localized_value_id', 'integer', []);
        $table->setPrimaryKey(['transport_id', 'localized_value_id']);
        $table->addUniqueIndex(['localized_value_id'], 'UNIQ_A2DB8E9EEB576E89');
    }

    /**
     * Create oro_wcs_sepa_lbl table
     *
     * @param Schema $schema
     */
    protected function createOroWirecardSeamlessSepaLblTable(Schema $schema)
    {
        $table = $schema->createTable('oro_wcs_sepa_lbl');
        $table->addColumn('transport_id', 'integer', []);
        $table->addColumn('localized_value_id', 'integer', []);
        $table->setPrimaryKey(['transport_id', 'localized_value_id']);
        $table->addUniqueIndex(['localized_value_id'], 'UNIQ_C3E98F4BEB576E89');
    }

    /**
     * Create oro_wcs_sepa_sh_lbl table
     *
     * @param Schema $schema
     */
    protected function createOroWirecardSeamlessSepaShLblTable(Schema $schema)
    {
        $table = $schema->createTable('oro_wcs_sepa_sh_lbl');
        $table->addColumn('transport_id', 'integer', []);
        $table->addColumn('localized_value_id', 'integer', []);
        $table->setPrimaryKey(['transport_id', 'localized_value_id']);
        $table->addUniqueIndex(['localized_value_id'], 'UNIQ_EEEF8C1AEB576E89');
    }

    /**
     * Add oro_wcs_credit_card_lbl foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroWirecardSeamlessCreditCardLblForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_wcs_credit_card_lbl');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_transport'),
            ['transport_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_fallback_localization_val'),
            ['localized_value_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }

    /**
     * Add oro_wcs_credit_card_sh_lbl foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroWirecardSeamlessCreditCardShLblForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_wcs_credit_card_sh_lbl');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_transport'),
            ['transport_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_fallback_localization_val'),
            ['localized_value_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }

    /**
     * Add oro_wcs_paypal_lbl foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroWirecardSeamlessPayPalLblForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_wcs_paypal_lbl');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_transport'),
            ['transport_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_fallback_localization_val'),
            ['localized_value_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }

    /**
     * Add oro_wcs_paypal_sh_lbl foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroWirecardSeamlessPayPalShLblForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_wcs_paypal_sh_lbl');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_transport'),
            ['transport_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_fallback_localization_val'),
            ['localized_value_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }

    /**
     * Add oro_wcs_sepa_lbl foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroWirecardSeamlessSepaLblForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_wcs_sepa_lbl');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_transport'),
            ['transport_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_fallback_localization_val'),
            ['localized_value_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }

    /**
     * Add oro_wcs_sepa_sh_lbl foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOroWirecardSeamlessSepaShLblForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_wcs_sepa_sh_lbl');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_transport'),
            ['transport_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_fallback_localization_val'),
            ['localized_value_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }
}
