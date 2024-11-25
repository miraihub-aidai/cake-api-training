<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\TagsInterface;
use App\Domain\UseCase\PostTags;
use Cake\TestSuite\TestCase;
use RuntimeException;

class PostTagsTest extends TestCase
{
    /**
     * @var array
     */
    protected array $newTagData = [
        'title' => 'New Tag',
        'created' => '2024-01-01 00:00:00',
        'modified' => '2024-01-01 00:00:00',
    ];

    protected array $savedTagData = [
        'id' => 1,
        'title' => 'New Tag',
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

        // postTagsメソッドが1回呼ばれることを期待
        $tagsServiceMock->expects($this->once())
            ->method('postTags')
            ->with($this->newTagData)
            ->willReturn($this->savedTagData);

        // UseCaseを作成
        $useCase = new PostTags($tagsServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke($this->newTagData);

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

        // postTagsメソッドが例外を投げることを期待
        $tagsServiceMock->expects($this->once())
            ->method('postTags')
            ->with($this->newTagData)
            ->willThrowException(new RuntimeException('Unable to save tag'));

        // UseCaseを作成
        $useCase = new PostTags($tagsServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to save tag');

        // UseCaseを実行
        $useCase->__invoke($this->newTagData);
    }
}
