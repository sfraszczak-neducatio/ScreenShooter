<?php

namespace Neducatio\ScreenShooter\Tests;

use Neducatio\ScreenShooter\Shooter;
use Neducatio\ScreenShooter\Tests\WebDriver\WebDriverSessionDummy;
use org\bovigo\vfs\vfsStream;

/**
 * Shooter tests
 */
class ShooterShould extends \PHPUnit_Framework_TestCase
{
  private $fileSystem;
  
  /**
   * @test
   */
  public function beInstanceOfInterface()
  {
    $wdSession = new WebDriverSessionDummy();
    
    $this->assertInstanceOf('Neducatio\ScreenShooter\ShooterInterface', new Shooter($wdSession));
  }
  
  /**
   * @test
   */
  public function throwExceptionWhenInjectedWebDriverSessionCanNotMakeScreenshoot()
  {
    $this->setExpectedException('\InvalidArgumentException', 'Web driver session is not able to make screenshots.');
    
    $webDriverSession = new \stdClass();
    
    new Shooter($webDriverSession);
  }
  
  /**
   * @test
   */
  public function returnTrueWhenScreenshotIsSameAsExpected()
  {
    $wdSession = new WebDriverSessionDummy();
    $shooter = new Shooter($wdSession);
    
    $this->assertTrue($shooter->compare('vfs://Screenshots/first.png'));
  }
  
  /**
   * @test
   */
  public function returnFalseWhenScreenshotIsNotSameAsExpected()
  {
    $wdSession = new WebDriverSessionDummy();
    $shooter = new Shooter($wdSession);
    
    $this->assertFalse($shooter->compare('vfs://Screenshots/second.png'));
  }
  
  /**
   * @test
   */
  public function throwExceptionWhenExpectedScreenshotDoesNotExist()
  {
    $this->setExpectedException('\Neducatio\ScreenShooter\Exception\ScreenshotNotFoundException', 'Expected screenshot does not exist.');

    $wdSession = new WebDriverSessionDummy();
    $shooter = new Shooter($wdSession);
    
    $shooter->compare('vfs://Screenshots/third.png');
  }
  
  /**
   * @test
   */
  public function saveCurrentScreenshot()
  {
    $wdSession = new WebDriverSessionDummy();
    $shooter = new Shooter($wdSession);
    
    $shooter->save('vfs://Screenshots/third.png');
    
    $saved = base64_encode(file_get_contents('vfs://Screenshots/third.png'));
    $this->assertSame('c2NyZWVuc2hvdGNvbnRlbnQ=', $saved);
  }
  
  public function setUp()
  {
    $this->fileSystem = vfsStream::setup('Screenshots');
    $structure = vfsStream::create(array(
        'first.png' => 'screenshotcontent',
        'second.png' => 'othercontent'
    ));
    
    $this->fileSystem->addChild($structure);
  }
}
