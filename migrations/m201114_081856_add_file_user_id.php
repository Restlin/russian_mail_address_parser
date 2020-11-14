<?php

use yii\db\Migration;
use app\models\File;

/**
 * Class m201114_081856_add_file_user_id
 */
class m201114_081856_add_file_user_id extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $files = File::find()->all();
        foreach ($files as $file) {
            $file->delete();
        }
        $this->addColumn('{{%file}}', 'user_id', $this->integer()->notNull()->comment('ID пользователя'));
        $this->addForeignKey('fk_file_user_id', '{{%file}}', ['user_id'], '"user"', ['id'], 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('{{%file}}', 'user_id');
    }

}
