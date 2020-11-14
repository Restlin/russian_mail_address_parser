<?php

use yii\db\Migration;
use yii\db\ColumnSchemaBuilder;
use yii\base\NotSupportedException;

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
            'date_start' => $this->timestampWithTimezone()->comment('Дата начала парсинга'),
            'date_end' => $this->timestampWithTimezone()->comment('Дата завершения парсинга'),
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

    /**
     * @param int|null $precision
     * @return ColumnSchemaBuilder
     * @throws NotSupportedException
     */
    private function timestampWithTimezone(int $precision = null): ColumnSchemaBuilder
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('timestamp with time zone', $precision)->defaultExpression('NULL');
    }

}
