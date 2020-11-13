<?php

use yii\db\Migration;

/**
 * Class m201113_193940_create_row
 */
class m201113_193940_create_row extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%row}}', [
            'id' => $this->primaryKey()->comment('ИД строки'),
            'file_id' => $this->integer()->notNull()->comment('ИД файла'),
            'content' => $this->text()->notNull()->comment('Содержимое строки'),
            'address_base' => $this->text()->Null()->comment('Базовый адрес'),
            'address_new' => $this->text()->Null()->comment('Адрес после обработки'),
            'status' => $this->integer()->notNull()->defaultValue(0)->comment('Статус обработки'),
        ]);
        $this->addForeignKey('row_file_id_fk', 'row', 'file_id', 'file', 'id');
        $this->createIndex('row_file_id_idx', 'row', 'file_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%row}}');
    }    
}
