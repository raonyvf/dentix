<?php

require_once __DIR__.'/stubs.php';
require_once __DIR__.'/../../app/Http/Controllers/Admin/ProfessionalController.php';

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Admin\ProfessionalController;

class ProfessionalControllerTest extends TestCase
{
    private function extract(array $data): array
    {
        $controller = new ProfessionalController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('extractProfessionalData');
        $method->setAccessible(true);

        return $method->invoke($controller, $data);
    }

    public function test_salary_without_thousands()
    {
        $result = $this->extract(['salario_fixo' => '1234,56']);
        $this->assertSame('1234.56', $result['salario_fixo']);
    }

    public function test_salary_with_thousands()
    {
        $result = $this->extract(['salario_fixo' => '1.234,56']);
        $this->assertSame('1234.56', $result['salario_fixo']);
    }

    public function test_salary_with_multiple_thousands()
    {
        $result = $this->extract(['salario_fixo' => '1.234.567,89']);
        $this->assertSame('1234567.89', $result['salario_fixo']);
    }
}

$test = new ProfessionalControllerTest();
$test->test_salary_without_thousands();
$test->test_salary_with_thousands();
$test->test_salary_with_multiple_thousands();
echo "Test passed\n";
