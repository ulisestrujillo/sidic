<?php
class ProjectTest extends CDbTestCase
{
  public $fixtures = array(
    'project' => 'Project',
  );

  public static function setUpBeforeClass()
  {
    if(!extension_loaded('pdo') ||
    !extension_loaded('pdo_sqlite'))
    markTestSkipped('PDO and SQLite extensions are required.');
    $config=array(
      'basePath'=>dirname(__FILE__),
      'components'=>array(
        'db'=>array(
          'connectionString' => 'mysql:host=localhost;dbname=sidic',
          'emulatePrepare' => true,
          'username' => 'root',
          'password' => '',
          'charset' => 'utf8',
        ),
        'fixture'=>array(
          'class'=>'system.test.CDbFixtureManager',
        ),
      ),
    );
    Yii::app()->configure($config);

  }

  public function testApprove()
  {
    // insert a comment in pending status
    $project=new Project;
    $project->setAttributes(array(
                                  'code' => '10',
                                  'name' => 'Choose one book for free!',
                                  'address' => 'Choose one book for free!',
                                  'location' => 'Choose one book for free!',
                                  ),false);

    $this->assertTrue($project->save(false));

    // verify the comment is in pending status
    $project=Project::model()->findByPk($project->id);

    $this->assertTrue($project instanceof Project);
  }

}