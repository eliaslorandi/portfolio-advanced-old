<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property string $tech_stack
 * @property string $description
 * @property string|null $start_date
 * @property string|null $end_date
 *
 * @property ProjectImage[] $images
 * @property Testimonial[] $testimonials
 */
class Project extends \yii\db\ActiveRecord
{

    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'tech_stack', 'description'], 'required'],
            [['tech_stack', 'description'], 'string'],
            [['start_date', 'end_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'tech_stack' => Yii::t('app', 'Tech Stack'),
            'description' => Yii::t('app', 'Description'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
        ];
    }

    /**
     * Gets query for [[ProjectImages]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(ProjectImage::class, ['project_id' => 'id']);
    }

    /**
     * Gets query for [[Testimonials]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getTestimonials()
    {
        return $this->hasMany(Testimonial::class, ['project_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
    }

    public function saveImage() //4 etapas: criar um registo na tabela file, criar um registo na tabela project_image com id, 
    {                           //salvar a imagem no disco, agrupar estas 3 etapas numa transação
        Yii::$app->db->transaction(function ($db) {
            /**
             * @var $db \yii\db\Connection //para ajudar a autocomplementar
             */
            $file = new File();
            $file->name = uniqid(true) . '.' . $this->imageFile->extension; //para não haver ficheiros com o mesmo nome
            $file->base_url = Yii::$app->urlManager->createAbsoluteUrl(Yii::$app->params['uploads']['projects']);
            $file->mime_type = mime_content_type($this->imageFile->tempName);
            $file->save();

            //criar registro de imagem do projeto na tabela project_image
            $projectImage = new ProjectImage();
            $projectImage->project_id = $this->id;
            $projectImage->file_id = $file->id; //define o id
            $projectImage->save();

            //if para caso der erro no upload da imagem
            if (!$this->imageFile->saveAs(Yii::$app->params['uploads']['projects'] . '/' . $file->name)) { //substituido por um unico caminho em params.php('uploads/projects/' . $file->name)){
                $db->transaction->rollBack();
            }
        });
    }

    public function hasImages() //verifica se o projeto tem imagens
    {
        return count($this->images) > 0;
    }
}
