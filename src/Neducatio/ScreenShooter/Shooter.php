<?php

namespace Neducatio\ScreenShooter;

use Belt\Belt;

/**
 * Base shooter class
 */
class Shooter implements ShooterInterface
{
  protected $lastScreenshot = null;
  protected $wdSession;
  
  /**
   * Constructor
   *
   * @param object $webDriverSession
   * 
   * @throws \InvalidArgumentException
   */
  public function __construct($webDriverSession)
  {
    if (!Belt::contains(Belt::methods($webDriverSession), 'screenshot')) {
      throw new \InvalidArgumentException('Web driver session is not able to make screenshots.');
    }
    
    $this->wdSession = $webDriverSession;
  }

  /**
   * Compare current screenshot with given
   *
   * @param string $expectedScreenshotPath
   */
  public function compare($expectedScreenshotPath)
  {
    if (!is_file($expectedScreenshotPath)) {
      throw new Exception\ScreenshotNotFoundException('Expected screenshot does not exist.');
    }
    
    $this->lastScreenshot = $this->wdSession->screenshot();
    $expected = base64_encode(file_get_contents($expectedScreenshotPath));

    return Belt::isEqual($this->lastScreenshot, $expected);
  }
  
  /**
   * Save current screenshot
   *
   * @param string $screenshotPath
   */
  public function save($screenshotPath)
  {
    $this->lastScreenshot = $this->wdSession->screenshot();
    file_put_contents($screenshotPath, base64_decode($this->lastScreenshot));
  }
}
