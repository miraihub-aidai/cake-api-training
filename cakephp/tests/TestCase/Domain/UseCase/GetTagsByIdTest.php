<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\TagsInterface;
use App\Domain\UseCase\GetTagsById;
use Cake\TestSuite\TestCase;
use RuntimeException;

class GetTagsByIdTest extends TestCase
{
    /**
     * @var array
     */
    protected array $mockedTag = [
        'id' => 1,
        'title' => 'Test Tag',
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

        // getTagsByIdメソッドが1回呼ばれることを期待
        $tagsServiceMock->expects($this->once())
            ->method('getTagsById')
            ->with('1')
            ->willReturn($this->mockedTag);

        // UseCaseを作成
        $useCase = new GetTagsById($tagsServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke('1');

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEquals($this->mockedTag, $result);
    }

    /**
     * Test invoke method with non-existent tag
     *
     * @return void
     */
    public function testInvokeWithNonExistentTag(): void
    {
        // TagsInterfaceのモックを作成
        /** @var TagsInterface&\PHPUnit\Framework\MockObject\MockObject $tagsServiceMock */
        $tagsServiceMock = $this->getMockBuilder(TagsInterface::class)
            ->getMock();

        // getTagsByIdメソッドが1回呼ばれ、nullを返すことを期待
        $tagsServiceMock->expects($this->once())
            ->method('getTagsById')
            ->with('999')
            ->willReturn(null);

        // UseCaseを作成
        $useCase = new GetTagsById($tagsServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke('999');

        // 結果を検証
        $this->assertNull($result);
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

        // getTagsByIdメソッドが例外を投げることを期待
        $tagsServiceMock->expects($this->once())
            ->method('getTagsById')
            ->with('1')
            ->willThrowException(new RuntimeException('Service error occurred'));

        // UseCaseを作成
        $useCase = new GetTagsById($tagsServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Service error occurred');

        // UseCaseを実行
        $useCase->__invoke('1');
    }
}
