<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\TagsInterface;
use App\Domain\UseCase\PutTags;
use Cake\TestSuite\TestCase;
use RuntimeException;

class PutTagsTest extends TestCase
{
    /**
     * @var array
     */
    protected array $updatedTagData = [
        'title' => 'Updated Tag',
        'created' => '2024-01-01 00:00:00',
        'modified' => '2024-01-01 00:00:00',
    ];

    protected array $savedTagData = [
        'id' => 1,
        'title' => 'Updated Tag',
        'created' => '2024-01-01 00:00:00',
        'modified' => '2024-01-01 00:00:00',
    ];

    /**
     * Test invoke method
     *
     * @return void
     */
    public function testInvoke(): void
    {
        // TagsInterfaceのモックを作成
        /** @var TagsInterface&\PHPUnit\Framework\MockObject\MockObject $tagsServiceMock */
        $tagsServiceMock = $this->getMockBuilder(TagsInterface::class)
            ->getMock();

        // putTagsメソッドが1回呼ばれることを期待
        $tagsServiceMock->expects($this->once())
            ->method('putTags')
            ->with('1', $this->updatedTagData)
            ->willReturn($this->savedTagData);

        // UseCaseを作成
        $useCase = new PutTags($tagsServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke('1', $this->updatedTagData);

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEquals($this->savedTagData, $result);
    }

    /**
     * Test invoke method with service error
     *
     * @return void
     */
    public function testInvokeServiceError(): void
    {
        // TagsInterfaceのモックを作成
        /** @var TagsInterface&\PHPUnit\Framework\MockObject\MockObject $tagsServiceMock */
        $tagsServiceMock = $this->getMockBuilder(TagsInterface::class)
            ->getMock();

        // putTagsメソッドが例外を投げることを期待
        $tagsServiceMock->expects($this->once())
            ->method('putTags')
            ->with('1', $this->updatedTagData)
            ->willThrowException(new RuntimeException('Unable to update tag'));

        // UseCaseを作成
        $useCase = new PutTags($tagsServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to update tag');

        // UseCaseを実行
        $useCase->__invoke('1', $this->updatedTagData);
    }
}
