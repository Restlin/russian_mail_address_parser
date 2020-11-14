<?php

use yii\db\Migration;

/**
 * Class m201114_123003_add_tokens
 */
class m201114_123003_add_tokens extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('token', [
            'id' => $this->primaryKey(),
            'value' => $this->string()->notNull()->comment('Значение')
        ]);
        $tokens = $this->actionLoad();
        foreach ($tokens as $token) {
            $this->insert('token', ['value' => $token]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('token');
    }

    protected function actionLoad(): array {
        $file = fopen('./migrations/data/cities.csv', 'r');
        $arr = [];
        while ($row = fgetcsv($file)) {
            $arr[] = $row[0];
            $arr[] = $row[2];
            if ($row[4]) {
                $arr[] = $row[4];
            }
            $arr[] = $row[6];
        }
        $arr = array_unique($arr);
        fclose($file);
        return $arr;
    }

}
