<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Traits\CreateAppView;
use App\Models\User;

class CreateAppViewTest extends TestCase
{
  use CreateAppView;

  const VIEW_NAME = 'training.app';

  protected function assertView($view, $name, $data)
  {
    $this->assertEquals($view->getName(), $name);
    $this->assertEquals($view->getData(), $data);
  }

  /**
   * Test if view is created with correct data
   *
   * @return void
   */
  public function testCreateAppView()
  {
    # Test 2
    $dataURI = '/training';
    $user = User::getTestUser();
    $view = $this->createAppView($dataURI, $user);

    $this->assertView($view, self::VIEW_NAME, [
      'trial' => false,
      'dataURI' => $dataURI,
      'keyboardLayout' => $user->preferences->keyboard,
      'setting' => [
        'assignment'  => $user->preferences->show_assignment,
        'divider'     => $user->preferences->show_divider,
        'keyboard'    => $user->preferences->show_keyboard,
      ],
    ]);

    # Test 2
    $dataURI = '/training';
    $locale = 'de';
    $data = [
      'trial' => true,
      'setting' => [
        'assignment'  => 'true',
        'divider'     => 'true',
        'keyboard'    => 'false',
      ],
    ];
    $view = $this->createAppView($dataURI, $locale, $data);

    $this->assertView($view, self::VIEW_NAME, array_merge([
      'dataURI' => $dataURI,
      'keyboardLayout' => 'de-de',
    ], $data));
  }
}
