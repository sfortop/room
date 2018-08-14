<?php


use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

class Init extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {

        // that case doesn't cover very long intervals
        // left as an example
        $this->table('price_interval')
            ->addColumn('date_start', 'date')
            ->addColumn('date_end', 'date')
            ->addColumn('price', 'float')
            ->addColumn('mon', 'boolean', ['default' => false])
            ->addColumn('tue', 'boolean', ['default' => false])
            ->addColumn('wed', 'boolean', ['default' => false])
            ->addColumn('thu', 'boolean', ['default' => false])
            ->addColumn('fri', 'boolean', ['default' => false])
            ->addColumn('sat', 'boolean', ['default' => false])
            ->addColumn('sun', 'boolean', ['default' => false])
            ->addColumn('indexed1', Literal::from('DATE GENERATED ALWAYS AS (DATE_ADD(date_start, INTERVAL mon DAY))'))
            ->addColumn('indexed2', Literal::from('DATE GENERATED ALWAYS AS (DATE_ADD(date_start, INTERVAL tue DAY))'))
            ->addColumn('indexed3', Literal::from('DATE GENERATED ALWAYS AS (DATE_ADD(date_start, INTERVAL wed DAY))'))
            ->addColumn('indexed4', Literal::from('DATE GENERATED ALWAYS AS (DATE_ADD(date_start, INTERVAL thu DAY))'))
            ->addColumn('indexed5', Literal::from('DATE GENERATED ALWAYS AS (DATE_ADD(date_start, INTERVAL fri DAY))'))
            ->addColumn('indexed6', Literal::from('DATE GENERATED ALWAYS AS (DATE_ADD(date_start, INTERVAL sat DAY))'))
            ->addColumn('indexed7', Literal::from('DATE GENERATED ALWAYS AS (DATE_ADD(date_start, INTERVAL sun DAY))'))
            ->addIndex('indexed1', ['unique' => true])
            ->addIndex('indexed2', ['unique' => true])
            ->addIndex('indexed3', ['unique' => true])
            ->addIndex('indexed4', ['unique' => true])
            ->addIndex('indexed5', ['unique' => true])
            ->addIndex('indexed6', ['unique' => true])
            ->addIndex('indexed7', ['unique' => true])
            ->create()
        ;


        $this->table('correct_storage',['id' => false, 'primary_key' => 'date'])
            ->addColumn('date', 'date')
            ->addColumn('price', 'float')
            ->create()
        ;
    }

}
