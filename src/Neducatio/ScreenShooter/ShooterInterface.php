<?php

namespace Neducatio\ScreenShooter;

/**
 * Shooter interface
 */
interface ShooterInterface
{
  /**
   * Compare current screenshot with given
   *
   * @param string $expectedScreenshotPath
   */
  public function compare($expectedScreenshotPath);

  /**
   * Save current screenshot
   *
   * @param string $screenshotPath
   */
  public function save($screenshotPath);
}
