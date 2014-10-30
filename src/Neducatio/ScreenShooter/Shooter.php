<?php

namespace Neducatio\ScreenShooter;

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
    $this->setWebDriverSession($webDriverSession);
  }

  /**
   * Compare current screenshot with given
   *
   * @param string $expectedScreenshotPath
   * 
   * @return boolean
   */
  public function compare($expectedScreenshotPath)
  {
    if (!is_file($expectedScreenshotPath)) {
      throw new Exception\ScreenshotNotFoundException('Expected screenshot does not exist.');
    }

    $this->lastScreenshot = $this->wdSession->screenshot();
    $expected = base64_encode(file_get_contents($expectedScreenshotPath));

    return $expected === $this->lastScreenshot;
  }

  /**
   * Asserts if current screenshot is same as expected
   *
   * @param string $expectedScreenshotPath
   *
   * @throws Exception\DifferentScreenshotException
   */
  public function assertScreenshot($expectedScreenshotPath)
  {
    if (!$this->compare($expectedScreenshotPath)) {
      throw new Exception\DifferentScreenshotException('Current screenshot is not same as expected.');
    }
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

  /**
   * Set web driver session
   *
   * @param object $webDriverSession
   *
   * @throws \InvalidArgumentException
   */
  public function setWebDriverSession($webDriverSession)
  {
    if (!is_callable(array($webDriverSession, 'screenshot'))) {
      throw new \InvalidArgumentException('Web driver session is not able to make screenshots.');
    }

    $this->wdSession = $webDriverSession;
  }
}
