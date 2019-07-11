<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "listData".
 *
 * @property int $id
 * @property string $task
 */
class TblListData extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'listData';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task'], 'required'],
            [['task'], 'string', 'max' => 500],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['task']; 
        return $scenarios; 
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task' => 'Task',
        ];
    }
}
