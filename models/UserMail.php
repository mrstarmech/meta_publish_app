<?php

namespace app\models;


/**
 * This is the model class for table "user_mail".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $time
 *
 */
class UserMail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_mail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'string'],
            [['time'], 'int']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'time' => 'Time',
        ];
    }
}
