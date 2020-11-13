<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%file}}`.
 */
class m201113_174141_create_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey()->comment('ID Файла'),
            'name' => $this->string()->notNull()->comment('Наименование файла'),
            'mime' => $this->string()->notNull()->comment('MIME тип'),
            'size' => $this->bigInteger()->notNull()->defaultValue(0)->comment('Размер'),
            'status' => $this->integer()->notNull()->defaultValue(0)->comment('Статус обработки файла'),
        ]);

        if (!file_exists('files')) {
            mkdir('files');
            chmod('files', 0777);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%file}}');
    }
}
