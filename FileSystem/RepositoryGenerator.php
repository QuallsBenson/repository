<?php namespace Designplug\Repository\FileSystem;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class RepositoryGenerator{

  protected $generationPath,
            $generationTemplatePath,
            $repositoryName,
            $repositoryNamespace,
            $repositoryInitializerName,
            $repositoryInitializerNamespace,
            $modelName,
            $modelNamespace,
            $filesystem,
            $templateParser;


  public function __construct(){

      $this->filesystem = new Filesystem;

  }

  public function generate(array $options = array()){

    if(!empty($options))
      $this->setOptions($options);

    $param = $this->getDefaultTemplateParam($options);

    $this->generateModel($param);
    $this->generateRepository($param);
    $this->generateRepositoryInitializer($param);

  }

  protected function createFile($file, $template, array $param = array()){

    $content = $this->parseTemplate($template, $param);
    $path    = $this->getGenerationPath() .DIRECTORY_SEPARATOR . $file;

    if(!$this->filesystem->exists($path))
      $this->filesystem->dumpFile( $path , $content );

  }

  protected function getTemplateParser(){

    if(!isset($this->templateParser)){

      $dir                   = $this->getGenerationPath() .DIRECTORY_SEPARATOR .$this->getGenerationTemplatePath();
      $loader                = new \Twig_Loader_Filesystem($dir);
      $this->templateParser  = new \Twig_Environment($loader);

    }

    return $this->templateParser;

  }

  protected function parseTemplate($template, array $param = array()){

    return $this->getTemplateParser()->render($template.'.php.twig', $param);

  }

  protected function createDirectory($dir){

    $ds  = DIRECTORY_SEPARATOR;
    $dir = rtrim($this->getGenerationPath(), '/\\') .$ds .ltrim($dir, '/\\');
    $dir = str_replace( array('/','\\'), $ds, rtrim($dir, '/\\') );

    if(!$this->filesystem->exists($dir))
      $this->filesystem->mkdir($dir);

    return $dir;

  }

  protected function generateModel(array $param = array()){

    return $this->generateFile('Model', $param);

  }

  protected function generateRepository(array $param = array()){

      return $this->generateFile('Repository', $param);

  }

  protected function generateRepositoryInitializer(array $param = array()){

      return $this->generateFile('RepositoryInitializer', $param);

  }

  protected function generateFile($type, array $param = array()){

    $type = ucfirst($type);
    $this->createDirectory( $this->{'get' .$type .'Namespace'}() );
    $file =  $this->{'get'.$type.'Namespace'}() .DIRECTORY_SEPARATOR .$this->{'get' .ucfirst($type) .'Name' }() .'.php';
    return   $this->createFile($file, $type, $param);

  }



  public function setOptions(array $options){

    $this->setGenerationPath(@$options['generationPath'])
         ->setGenerationTemplatePath(@$options['templatePath'])
         ->setModelName(@$options['modelName'] ?: $options['name'])
         ->setModelNamespace(@$options['modelNamespace'])
         ->setRepositoryName(@$options['repositoryName'] ?: $options['name'])
         ->setRepositoryNamespace(@$options['repositoryNamespace'])
         ->setRepositoryInitializerName(@$options['repositoryName'] ?: $options['name'])
         ->setRepositoryInitializerNamespace(@$options['repositoryInitializerNamespace']);
  }

  public function getDefaultTemplateParam(array $param = array()){

    $param['modelName']                 = $this->getModelName();
    $param['repositoryName']            = $this->getRepositoryName();
    $param['repositoryInitializerName'] = $this->getRepositoryInitializerName();

    return $param;
  }

  public function setGenerationPath($path){

    $this->generationPath = rtrim($path, '/\\') ?: getcwd();

    return $this;

  }

  public function getGenerationPath(){

    return $this->generationPath ?: getcwd();

  }

  public function setGenerationTemplatePath($path){

    $this->generationTemplatePath = rtrim($path, '/\\');

    return $this;

  }

  public function getGenerationTemplatePath(){

    return $this->generationTemplatePath;

  }

  public function setRepositoryName($name){

    if(!$this->isValidObjectName($name))
      throw $this->createInvalidNameException('Repository', $name);

    //append Repository to give name if not already
    $name = $this->addSuffix($name, 'Repository');

    $this->repositoryName = ucfirst($name);

    return $this;

  }

  public function getRepositoryName(){

    return $this->repositoryName;

  }

  public function setRepositoryNamespace($namespace){

    $this->repositoryNamespace = rtrim($namespace, '\\') ?: '';

    return $this;

  }

  public function getRepositoryNamespace(){

    return $this->repositoryNamespace ?: "";

  }

  public function setModelName($name){

    if(!$this->isValidObjectName($name))
      throw $this->createInvalidNameException('Model/Entity', $name);

    $this->modelName = ucfirst($name);

    return $this;

  }

  public function getModelName(){

    return $this->modelName;

  }

  public function setModelNamespace($namespace){

    $this->modelNamespace = rtrim($namespace, '\\') ?: '';

    return $this;

  }

  public function getModelNamespace(){

    return $this->modelNamespace;

  }

  public function setRepositoryInitializerName($name){

    if(!$this->isValidObjectName($name))
      throw $this->createInvalidNameException('RepositoryInitializer', $name);

    $name = $this->addSuffix($name, 'RepositoryInitializer');

    $this->repositoryInitializerName = ucfirst($name);

    return $this;

  }

  public function getRepositoryInitializerName(){

    return $this->repositoryInitializerName;

  }

  public function setRepositoryInitializerNamespace($namespace){

    $this->repositoryInitializerNamespace = rtrim($namespace, '\\');

    return $this;

  }

  public function getRepositoryInitializerNamespace(){

    return $this->repositoryInitializerNamespace;

  }

  public function addSuffix($name, $suffix){

    if(strpos($name, $suffix, strlen($name) - 1) !== 0){
      $name = $name .$suffix;
    }

    return $name;

  }

  protected function isValidObjectName($name){

    return !!preg_match('/^[a-z0-9\-\_]+$/i',$name);

  }

  protected function createInvalidNameException($property, $name){

    return new \InvalidArgumentException($property .' name: ' .$name .'
                                         is invalid, must use valid variable name');

  }



}
