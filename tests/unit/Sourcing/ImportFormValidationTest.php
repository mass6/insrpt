<?php

use Carbon\Carbon;
use Insight\Sourcing\SourcingRequest;

class ImportFormValidationTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $form;

    protected $imageMock;

    protected function _before()
    {
        $this->form = app()->make('Insight\Sourcing\Forms\ImportSourcingRequestForm');

        $this->imageMock = Mockery::mock(
            '\Symfony\Component\HttpFoundation\File\UploadedFile',
            [
                'getClientOriginalName'      => 'importfile1.xlsx',
                'getClientOriginalExtension' => 'xlsx',
                'getClientMimeType'         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    protected function _after()
    {
    }

    // tests

    /**
     *
     * @test
     */
    public function it_validates_an_import_form()
    {
        $formData = [
            'customer_id' => 3,
            'batch' => 'batch1',
            'received_on' => Carbon::today()->format('Y-m-d'),
        ];

        $this->imageMock->shouldReceive('getPath')->atLeast(1)->andReturn(__DIR__ . '../tests/_data/importfile.xlsx');
        $this->imageMock->shouldReceive('isValid')->atLeast(1)->andReturn(true);
        $this->imageMock->shouldReceive('guessExtension')->atLeast(1)->andReturn('xls');
        $this->imageMock->shouldReceive('getSize')->atLeast(1)->andReturn('35000'); // 35 kilobytes
        $formData['importfile'] = $this->imageMock;

        $result = $this->form->validate($formData);

        $this->assertTrue($result);

    }
    /**
     * @expectedException Laracasts\Validation\FormValidationException
     * @test
     */
    public function is_not_valid_with_empty_form_data()
    {
        $formData = [];
        $this->form->validate($formData);
    }


}