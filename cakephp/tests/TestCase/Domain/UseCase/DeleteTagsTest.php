<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\TagsInterface;
use App\Domain\UseCase\DeleteTags;
use Cake\TestSuite\TestCase;
use RuntimeException;

class DeleteTagsTest extends TestCase
{
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

        // deleteTagsメソッドが1回呼ばれることを期待
        $tagsServiceMock->expects($this->once())
            ->method('deleteTags')
            ->with('1')
            ->willReturn(true);

        // UseCaseを作成
        $useCase = new DeleteTags($tagsServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke('1');

        // 結果を検証
        $this->assertTrue($result);
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

        // deleteTagsメソッドが例外を投げることを期待
        $tagsServiceMock->expects($this->once())
            ->method('deleteTags')
            ->with('1')
            ->willThrowException(new RuntimeException('Unable to delete tag'));

        // UseCaseを作成
        $useCase = new DeleteTags($tagsServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to delete tag');

        // UseCaseを実行
        $useCase->__invoke('1');
    }
}
