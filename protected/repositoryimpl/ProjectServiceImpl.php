<?php

class ProjectServiceImpl implements ProjectService
{

  public function getAllProjects()
  {
    $projects=new Project('search');
    return $projects;
  }

  public function getProjectById($id)
  {
    $project=Project::model()->findByPk($id);
    return $project;
  }
  
}